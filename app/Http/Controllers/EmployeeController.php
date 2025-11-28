<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('DASHBOARD.edit_employee_modal', compact('employee'));
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
        $employees = Employee::latest()->paginate(50);
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