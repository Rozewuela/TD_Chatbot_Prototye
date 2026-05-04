@extends('layouts.app')

@section('title', 'LMLinga – Smart Health Support For Every Resident')

@section('content')

<div class="landing-page">

    {{-- ===== NAVBAR ===== --}}
    <nav class="landing-nav">
        <div class="landing-logo">
<img src="{{ asset('img/logo.png') }}" class="landing-logo-img" alt="LMLinga">

            <span>LMLinga</span>
        </div>

    </nav>

    {{-- ===== HERO SECTION ===== --}}
    <main class="landing-hero">

        {{-- Left: Text Content --}}
        <div class="landing-hero-left">


            <h1 class="landing-title">
                Smart Health Support<br>For Every Resident
            </h1>

            <p class="landing-subtitle">
                A multilingual chatbot offering quick and accessible
                health information to inform and educate, not diagnose.
            </p>

            {{-- Language tags --}}
            <div class="hero-lang-tags">
                <span class="lang-tag">🇺🇸 English</span>
                <span class="lang-tag">🇵🇭 Tagalog</span>
                <span class="lang-tag">BI Bikol-Iriga</span>
            </div>

            {{-- CTA Buttons --}}
            <div class="landing-btns">
                <a href="{{ route('register') }}" class="lm-btn-primary">Register</a>
                <a href="{{ route('login') }}" class="lm-btn-outline">Login</a>
            </div>

        </div>

        {{-- Right: AI Illustration --}}
        <div class="landing-hero-right">
            <img src="{{ asset('img/ai.png') }}" alt="LMLinga AI Assistant" class="hero-ai-img float-animation">

        </div>


    </main>



</div>

@endsection
