<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Inertia::render("AdminSide/Employees/Index", [
            "users" => User::paginate(9),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("AdminSide/Employees/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employeeCredentials = $request->validate([
            "first_name"=> "required|max:255|regex:/^[a-zA-Z\s]+$/",
            "last_name"=> "required|max:255|regex:/^[a-zA-Z\s]+$/",
            "email" => "required|lowercase|email|max:255",
            "phone_number" => "required|digits:11",
            "role" => "required|max:255",
            "status" => "required|max:255",
            "password" => "required|min:3|confirmed",
        ]);

        $employee = User::create($employeeCredentials);

        return redirect()->route("employees.index")
            ->with('message', 'Employee created successfully')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        return Inertia::render("AdminSide/Employees/Edit", [
            "employee" => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            "first_name" => "required|max:255|regex:/^[a-zA-Z\s]+$/",
            "last_name" => "required|max:255|regex:/^[a-zA-Z\s]+$/",
            "email" => "required|lowercase|email|max:255",
            "phone_number" => "required|digits:11",
            "role" => "required|max:255",
            "status" => "required|max:255",
            "password" => "nullable|min:3|confirmed",
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $employee->update($validated);

        return redirect()->route("employees.index")
            ->with('message', 'Employee updated successfully')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        try {
            // Prevent self-deletion
            if ($employee->id === Auth::id()) {
                return back()->withErrors(['error' => 'You cannot delete your own account.']);
            }

            // Delete the employee
            $employee->delete();

            return redirect()->route('employees.index')->with('message', 'Employee deleted successfully')->with('type', 'success');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error deleting employee: ' . $e->getMessage()]);
        }
    }
}
