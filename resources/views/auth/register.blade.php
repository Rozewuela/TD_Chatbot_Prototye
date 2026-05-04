@extends('layouts.app')

@section('body-class', 'auth-page')

@section('content')
<div class="auth-page">
    <div class="auth-card">

        {{-- ==================== LEFT PANEL: Branding ==================== --}}
        <div class="auth-left">

            {{-- Logo --}}
            <div class="auth-logo">
                <img src="{{ asset('img/logo.png') }}" class="landing-logo-img" alt="LMLinga">
                <span>LMLinga</span>
            </div>

            {{-- AI Illustration --}}
            <img src="{{ asset('img/ai.png') }}" class="register-ai-img float-animation" alt="AI Assistant">

            <p class="auth-caption">
                A multilingual chatbot for health<br/>information and education only.
            </p>

            {{-- Language toggle --}}
            <div class="auth-lang-btns">
                <button type="button" class="lm-lang-btn active" onclick="setLang(this)">English</button>
                <button type="button" class="lm-lang-btn" onclick="setLang(this)">Tagalog</button>
                <button type="button" class="lm-lang-btn" onclick="setLang(this)">Bikol - Iriga</button>
            </div>

        </div>
        {{-- ==================== END LEFT PANEL ==================== --}}

        {{-- ==================== RIGHT PANEL: Form ==================== --}}
        <div class="auth-right">

            {{-- Close button --}}
            <a href="{{ route('landing') }}" class="auth-close" aria-label="Back to landing">&times;</a>

            <div class="auth-heading">
                <div class="auth-head-icon">
                    <i class="bi bi-person-fill"></i>
                </div>
                <span>CREATE AN ACCOUNT</span>
            </div>

            {{-- Laravel Registration Form --}}
            <form method="POST" action="{{ route('register.submit') }}" id="registerForm" novalidate>
                @csrf

                {{-- First Name --}}
                <div class="lm-form-group">
                    <label for="first_name">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        placeholder="Enter your first name"
                        autocomplete="given-name"
                        class="@error('first_name') input-error @enderror"
                        required
                    >
                    @error('first_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Middle Name --}}
                <div class="lm-form-group">
                    <label for="middle_name">Middle Name</label>
                    <input
                        type="text"
                        id="middle_name"
                        name="middle_name"
                        value="{{ old('middle_name') }}"
                        placeholder="Enter your middle name"
                        autocomplete="additional-name"
                        class="@error('middle_name') input-error @enderror"
                    >
                    @error('middle_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div class="lm-form-group">
                    <label for="last_name">Last Name</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        placeholder="Enter your last name"
                        autocomplete="family-name"
                        class="@error('last_name') input-error @enderror"
                        required
                    >
                    @error('last_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Household No. --}}
                <div class="lm-form-group">
                    <label for="household_no">Household No.</label>
                    <input
                        type="text"
                        id="household_no"
                        name="household_no"
                        value="{{ old('household_no') }}"
                        placeholder="Enter your household number"
                        class="@error('household_no') input-error @enderror"
                        required
                    >
                    @error('household_no')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="lm-form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        autocomplete="new-password"
                        class="@error('password') input-error @enderror"
                        minlength="8"
                        required
                    >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="lm-form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button type="submit" class="lm-btn-submit">Register</button>

                <p class="auth-footer">
                    Already have an account?
                    <a href="{{ route('login') }}">Login</a>
                </p>
            </form>

        </div>
        {{-- ==================== END RIGHT PANEL ==================== --}}

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Language toggle UI
    function setLang(btn) {
        document.querySelectorAll('.lm-lang-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>
@endpush
