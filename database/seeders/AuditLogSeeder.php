<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get admin user (usually ID 1)
        $admin = User::first();

        if (!$admin) {
            return; // No users to seed audit logs for
        }

        // Sample audit log data
        $auditLogs = [
            // LOGIN/LOGOUT Examples
            [
                'user_id' => $admin->id,
                'action' => 'LOGIN',
                'module' => 'Authentication',
                'description' => $admin->first_name . ' ' . $admin->last_name . ' logged in',
                'changes' => json_encode(['ip_address' => '192.168.1.100']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'LOGOUT',
                'module' => 'Authentication',
                'description' => $admin->first_name . ' ' . $admin->last_name . ' logged out (Session: 2h 30m)',
                'changes' => json_encode(['session_duration' => '2h 30m']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],

            // POS Examples
            [
                'user_id' => $admin->id,
                'action' => 'CREATE',
                'module' => 'POS',
                'description' => 'Sold 3 items to John Doe (Total: ₱2,500.00)',
                'changes' => json_encode(['items_count' => 3, 'total' => 2500.00, 'customer' => 'John Doe']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'CREATE',
                'module' => 'POS',
                'description' => 'Sold 2 items to Jane Smith (Total: ₱1,800.00)',
                'changes' => json_encode(['items_count' => 2, 'total' => 1800.00, 'customer' => 'Jane Smith']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],

            // Inventory Examples
            [
                'user_id' => $admin->id,
                'action' => 'CREATE',
                'module' => 'Inventory',
                'description' => 'Added new product: Samsung SSD 1TB (SKU: SSD-001)',
                'changes' => json_encode(['product_id' => 1, 'price' => 5500.00, 'stock' => 50]),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'module' => 'Inventory',
                'description' => 'Updated stock for Samsung SSD 1TB: 50 → 45 (Change: -5)',
                'changes' => json_encode(['product_id' => 1, 'old_stock' => 50, 'new_stock' => 45, 'change' => -5]),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'module' => 'Inventory',
                'description' => 'Updated price for Kingston RAM 16GB: ₱3,500.00 → ₱3,200.00',
                'changes' => json_encode(['product_id' => 2, 'old_price' => 3500.00, 'new_price' => 3200.00]),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'DELETE',
                'module' => 'Inventory',
                'description' => 'Deleted product: Old Graphics Card GTX 960',
                'changes' => json_encode(['product_id' => 3, 'product_name' => 'Old Graphics Card GTX 960']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],

            // Services Examples
            [
                'user_id' => $admin->id,
                'action' => 'CREATE',
                'module' => 'Services',
                'description' => 'Created service for Maria Garcia - Laptop Repair (Fee: ₱800.00)',
                'changes' => json_encode(['service_id' => 1, 'customer_id' => 5, 'service_fee' => 800.00]),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'module' => 'Services',
                'description' => 'Updated service status: Pending → Completed',
                'changes' => json_encode(['service_id' => 1, 'old_status' => 'Pending', 'new_status' => 'Completed']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],

            // Customer Examples
            [
                'user_id' => $admin->id,
                'action' => 'CREATE',
                'module' => 'Customer',
                'description' => 'Added new customer: Carlos Reyes (carlos.reyes@email.com)',
                'changes' => json_encode(['customer_id' => 10, 'email' => 'carlos.reyes@email.com', 'phone' => '09123456789']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'module' => 'Customer',
                'description' => 'Updated customer info: Jose Santos (Phone: 09987654321)',
                'changes' => json_encode(['customer_id' => 8, 'old_phone' => '09111111111', 'new_phone' => '09987654321']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],

            // Staff Examples
            [
                'user_id' => $admin->id,
                'action' => 'CREATE',
                'module' => 'Staff',
                'description' => 'Added new staff: Miguel Torres (Role: staff)',
                'changes' => json_encode(['user_id' => 5, 'role' => 'staff', 'email' => 'miguel.torres@email.com']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'module' => 'Staff',
                'description' => 'Updated staff role: staff → admin',
                'changes' => json_encode(['user_id' => 4, 'old_role' => 'staff', 'new_role' => 'admin']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'DELETE',
                'module' => 'Staff',
                'description' => 'Deleted staff: Ana Rodriguez (Former staff member)',
                'changes' => json_encode(['user_id' => 3, 'name' => 'Ana Rodriguez']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],

            // Admin Examples
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'module' => 'Admin',
                'description' => 'Updated system settings - Tax rate changed',
                'changes' => json_encode(['old_tax_rate' => '12%', 'new_tax_rate' => '15%']),
                'ip_address' => '192.168.1.100',
                'created_at' => now()->subDays(11),
                'updated_at' => now()->subDays(11),
            ],
        ];

        // Insert all audit logs
        foreach ($auditLogs as $log) {
            AuditLog::create($log);
        }

        $this->command->info('Audit log seeder completed with ' . count($auditLogs) . ' sample records!');
    }
}
