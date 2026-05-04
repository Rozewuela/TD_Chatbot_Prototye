<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle registration form submission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'household_no' => 'required|string|max:255|unique:users,household_no',
            'password'     => 'required|string|min:8|confirmed',
        ], [
            'first_name.required'   => 'First name is required.',
            'last_name.required'    => 'Last name is required.',
            'household_no.required' => 'Household number is required.',
            'household_no.unique'   => 'This household number is already registered.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Passwords do not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // TODO: Create user and authenticate
        // For now, redirect to login page after successful validation
        return redirect()->route('login')->with('success', 'Account created successfully! Please log in.');
    }

    /**
     * Handle login form submission.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'household_no' => 'required|string',
            'password'     => 'required|string',
        ]);

        // TODO: Implement actual authentication
        // For now, redirect to chatbot
        return redirect()->route('chatbot');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        // TODO: Implement actual logout
        return redirect()->route('landing');
    }
}
