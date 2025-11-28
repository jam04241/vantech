<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // API method to get single employee for editing
    public function getEmployee($id)
    {
        $employee = Employee::find($id);
        return response()->json($employee);
    }

    public function store(EmployeeRequest $request)
    {
        try {
            Employee::create($request->validated());
            return redirect()->route('staff.record')->with('success', 'Employee added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding employee: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Request $request)
    {
        $employees = Employee::latest()->paginate(50); // 50 records per page
        return view('DASHBOARD.staff_record', compact('employees'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        try {
            $employee->update($request->validated());
            return redirect()->route('staff.record')->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }
}