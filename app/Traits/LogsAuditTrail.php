<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait LogsAuditTrail
{
    /**
     * Log an audit trail entry
     */
    protected function logAudit($action, $module, $description, $changes = null, $request = null)
    {
        try {
            $user = Auth::user();
            $ipAddress = $request ? $request->ip() : request()->ip();

            $this->callStoredProcedure(
                'sp_insert_audit_log',
                [
                    $user->id,
                    $action,
                    $module,
                    $description,
                    $changes ? json_encode($changes) : json_encode([])
                ],
                $ipAddress
            );
        } catch (\Exception $e) {
            Log::error('Failed to log audit trail: ' . $e->getMessage());
        }
    }

    /**
     * Call stored procedure for audit logging (database-agnostic)
     */
    protected function callStoredProcedure($procedureName, $params, $ipAddress = null)
    {
        try {
            $user = Auth::user();
            $driver = DB::getDriverName();

            if ($driver === 'mysql') {
                $placeholders = "?,?,?,?,?";
                DB::statement("CALL $procedureName($placeholders)", $params);
            } elseif ($driver === 'sqlsrv') {
                $placeholders = "@param1,@param2,@param3,@param4,@param5";
                DB::statement("EXEC $procedureName $placeholders", $params);
            }
        } catch (\Exception $e) {
            // Fallback to Eloquent
            try {
                AuditLog::create([
                    'user_id' => $user->id ?? null,
                    'action' => $params[1] ?? 'Unknown',
                    'module' => $params[2] ?? 'System',
                    'description' => $params[3] ?? '',
                    'changes' => $params[4] ?? null,
                    'ip_address' => $ipAddress,
                ]);
            } catch (\Exception $fallbackError) {
                Log::error('Audit logging failed completely: ' . $fallbackError->getMessage());
            }
        }
    }

    /**
     * Log a create action
     */
    protected function logCreateAudit($action, $module, $description, $data = null, $request = null)
    {
        $changes = $data ? ['created_data' => $data] : null;
        $this->logAudit($action, $module, $description, $changes, $request);
    }

    /**
     * Log an update action
     */
    protected function logUpdateAudit($action, $module, $description, $oldData, $newData, $request = null)
    {
        $changes = [
            'old_data' => $oldData,
            'new_data' => $newData,
        ];
        $this->logAudit($action, $module, $description, $changes, $request);
    }

    /**
     * Log a POS sale action
     * Automatically calculates description from customer and items
     */
    protected function logSaleAudit($module, $customer, $totalQuantity, $totalPrice, $request = null)
    {
        // Get customer full name from relationship
        $customerName = $customer->first_name . ' ' . $customer->last_name;

        // Create description: "Sold {quantity} items to {customer_name} (Total: {total_price})"
        $description = "Sold {$totalQuantity} items to {$customerName} (Total: {$totalPrice})";

        // Create changes data with sale details
        $changes = [
            'customer_id' => $customer->id,
            'customer_name' => $customerName,
            'quantity' => $totalQuantity,
            'total_price' => $totalPrice
        ];

        // Log with 'Sold' action for POS module
        $this->logAudit('PURCHASE', $module, $description, $changes, $request);
    }

    /**
     * Log Add Service Type action
     * Format: "Added new service: {service_name} (Fee: ₱{fee})"
     */
    protected function logAddServiceTypeAudit($serviceName, $fee, $request = null)
    {
        $description = "Added new service: {$serviceName} (Fee: ₱" . number_format($fee, 2) . ")";
        $changes = [
            'service_name' => $serviceName,
            'fee' => $fee
        ];
        $this->logAudit('CREATE', 'Services', $description, $changes, $request);
    }

    /**
     * Log Add Service List action (Customer avails service)
     * Format: "Created service for {customer_name} - {service_name} (Fee: ₱{fee})"
     */
    protected function logAddServiceListAudit($customerName, $serviceName, $fee, $request = null)
    {
        $description = "Created service for {$customerName} - {$serviceName} (Fee: ₱" . number_format($fee, 2) . ")";
        $changes = [
            'customer_name' => $customerName,
            'service_name' => $serviceName,
            'fee' => $fee
        ];
        $this->logAudit('CREATE', 'Services', $description, $changes, $request);
    }

    /**
     * Log Update Service Name action
     * Format: "Updated service name: {old_name} -> {new_name}"
     */
    protected function logUpdateServiceNameAudit($oldName, $newName, $request = null)
    {
        $description = "Updated service name: {$oldName} -> {$newName}";
        $changes = [
            'old_name' => $oldName,
            'new_name' => $newName
        ];
        $this->logAudit('UPDATE', 'Services', $description, $changes, $request);
    }

    /**
     * Log Update Service Fee action
     * Format: "Updated {service_name} fee: ₱{old_fee} -> ₱{new_fee}"
     */
    protected function logUpdateServiceFeeAudit($serviceName, $oldFee, $newFee, $request = null)
    {
        $description = "Updated {$serviceName} fee: ₱" . number_format($oldFee, 2) . " -> ₱" . number_format($newFee, 2);
        $changes = [
            'service_name' => $serviceName,
            'old_fee' => $oldFee,
            'new_fee' => $newFee
        ];
        $this->logAudit('UPDATE', 'Services', $description, $changes, $request);
    }

    /**
     * Log Issue Acknowledgment Receipt action
     * Format: "Issued acknowledgment receipt for {customer_name} - {service_name}"
     */
    protected function logAcknowledgmentReceiptAudit($customerName, $serviceName, $request = null)
    {
        $description = "Issued acknowledgment receipt for {$customerName} - {$serviceName}";
        $changes = [
            'customer_name' => $customerName,
            'service_name' => $serviceName
        ];
        $this->logAudit('ACKNOWLEDGE', 'Services', $description, $changes, $request);
    }

    /**
     * Log Issue Service Receipt (Final) action
     * Format: "Issued service receipt for {customer_name} - {service_name} (Total Service Fee: ₱{total_amount})"
     */
    protected function logServiceReceiptAudit($customerName, $serviceName, $totalAmount, $request = null)
    {
        $description = "Issued service receipt for {$customerName} - {$serviceName} (Total Service Fee: ₱" . number_format($totalAmount, 2) . ")";
        $changes = [
            'customer_name' => $customerName,
            'service_name' => $serviceName,
            'total_amount' => $totalAmount
        ];
        $this->logAudit('COMPLETED SERVICE', 'Services', $description, $changes, $request);
    }
}
