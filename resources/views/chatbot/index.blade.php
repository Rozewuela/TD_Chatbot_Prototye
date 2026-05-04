{{--
    ============================================================
    LMLinga – Chatbot Module
    View: resources/views/chatbot/index.blade.php
    Description: Main chatbot page. Static UI only.
                 No backend logic or database calls.
    ============================================================
--}}
@extends('layouts.app')

@section('content')

{{-- ========================================================
     CHATBOT PAGE WRAPPER
     Two-column layout: sidebar (left) + chat area (right)
======================================================== --}}
<div class="chatbot-wrapper">

    {{-- ====================================================
         LEFT SIDEBAR
         Contains: logo, user profile, nav, logout button
    ==================================================== --}}
    <aside class="sidebar" id="sidebar">

        {{-- Sidebar toggle close (mobile) --}}
        <button class="sidebar-close-btn d-lg-none" id="sidebarCloseBtn" aria-label="Close sidebar">
            <i class="bi bi-x-lg"></i>
        </button>

        {{-- Logo / Branding --}}
        <div class="sidebar-brand">
            <div class="brand-icon">
                <img class ="brand-icon" src="{{ asset('images/logo.png') }}" alt="logo">
            </div>
            <span class="brand-name">LMLinga</span>
        </div>

        {{-- Divider --}}
        <hr class="sidebar-divider" />

        {{-- User Profile --}}
        <div class="sidebar-profile">
            <div class="profile-avatar">
                <span>JD</span>
            </div>
            <div class="profile-info">
                {{-- In a real app: {{ Auth::user()->name }} --}}
                <p class="profile-name">John Doe</p>
                <p class="profile-role">Household Head</p>
            </div>
        </div>

        {{-- Divider --}}
        <hr class="sidebar-divider" />

        {{-- Navigation Links --}}
        <nav class="sidebar-nav">
            <a href="#" class="nav-link-item active">
                <i class="bi bi-chat-dots-fill"></i>
                <span>Chatbot</span>
            </a>
            <a href="#" class="nav-link-item">
                <i class="bi bi-house-heart-fill"></i>
                <span>Household Info</span>
            </a>
            <a href="#" class="nav-link-item">
                <i class="bi bi-person-lines-fill"></i>
                <span>My Profile</span>
            </a>
            
        </nav>

        {{-- Spacer pushes logout to bottom --}}
        <div class="sidebar-spacer"></div>

        {{-- Logout Button --}}
        {{-- In a real app, this would be a form POST to /logout --}}
        <div class="sidebar-footer">
            <a href="#" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>
        </div>

    </aside>{{-- END sidebar --}}

    {{-- ====================================================
         MAIN CHAT AREA
         Contains: header, messages, input bar
    ==================================================== --}}
    <main class="chat-main">

        {{-- ------------------------------------------------
             CHAT HEADER
             LMLinga logo + greeting + language toggle
        ------------------------------------------------ --}}
        <header class="chat-header">

            {{-- Mobile hamburger button --}}
            <button class="hamburger-btn d-lg-none" id="hamburgerBtn" aria-label="Open sidebar">
                <i class="bi bi-list"></i>
            </button>

            {{-- Logo + Title --}}
            <div class="header-brand">
                <div class="header-logo">
                     <img class ="brand-icon" src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
                <div class="header-title-group">
                    <h1 class="header-title">LMLinga</h1>
                    <p class="header-subtitle">Smart Health Support Chatbot</p>
                </div>
            </div>

            {{-- Language Toggle Buttons --}}
            {{-- JS switches active state; future: sends lang param to backend --}}
            <div class="lang-toggle-group">
                <button class="lang-btn active" data-lang="en" aria-pressed="true">
                    🇺🇸 English
                </button>
                <button class="lang-btn" data-lang="tl" aria-pressed="false">
                    🇵🇭 Tagalog
                </button>
                <button class="lang-btn" data-lang="bi" aria-pressed="false">
                    🏡 Bikol-Iriga
                </button>
            </div>

        </header>{{-- END chat-header --}}

        {{-- ------------------------------------------------
             CHAT MESSAGES AREA
             Scrollable container for all chat bubbles
        ------------------------------------------------ --}}
        <section class="chat-messages" id="chatMessages" aria-live="polite" aria-label="Chat conversation">

            {{-- ---- BOT GREETING (initial message on page load) ---- --}}
            <div class="message-row bot-row">
                <div class="bot-avatar">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="message-bubble bot-bubble">
                    {{-- Greeting changes based on selected language (JS-controlled) --}}
                    <p class="greeting-text">
                        <strong id="greetingName">Hi, John Doe!</strong> 👋
                    </p>
                    <p id="greetingSubtitle">
                        I'm your <em>Smart Health Support Chatbot</em>. I can help you with general health information.
                        Please choose your preferred language below or just type your question!
                    </p>
                    {{-- Language shortcut buttons inside the bubble --}}
                    <div class="bubble-lang-shortcuts mt-2">
                        <button class="bubble-lang-btn" data-lang="en">🇺🇸 English</button>
                        <button class="bubble-lang-btn" data-lang="tl">🇵🇭 Tagalog</button>
                        <button class="bubble-lang-btn" data-lang="bi">🏡 Bikol-Iriga</button>
                    </div>
                    <span class="message-time">{{ now()->format('h:i A') }}</span>
                </div>
            </div>

            {{-- ---- DISCLAIMER SYSTEM MESSAGE ---- --}}
            <div class="system-message">
                <i class="bi bi-info-circle-fill"></i>
                <span id="disclaimerText">
                    This chatbot is for health information and education only. It does <strong>not</strong> provide medical diagnosis. For emergencies, call your Barangay Health Center.
                </span>
            </div>

            {{-- ---- QUICK QUESTION CHIPS ---- --}}
            <div class="quick-chips" id="quickChips">
                <p class="chips-label" id="chipsLabel">Common health questions:</p>
                <div class="chips-row">
                    <button class="chip-btn" data-question="fever">🌡️ Fever</button>
                    <button class="chip-btn" data-question="cough">😷 Cough</button>
                    <button class="chip-btn" data-question="headache">🤕 Headache</button>
                    <button class="chip-btn" data-question="diarrhea">💊 Diarrhea</button>
                    <button class="chip-btn" data-question="hypertension">❤️ Hypertension</button>
                    <button class="chip-btn" data-question="dengue">🦟 Dengue</button>
                    <button class="chip-btn" data-question="vaccine">💉 Vaccines</button>
                </div>
            </div>

        </section>{{-- END chat-messages --}}

        {{-- ------------------------------------------------
             CHAT INPUT BAR
             Text input + send button
        ------------------------------------------------ --}}
        <footer class="chat-input-bar">

            <div class="input-inner">

                {{-- Attachment / emoji hint (future feature) --}}
                <button class="input-icon-btn" title="Attach" aria-label="Attach file (coming soon)" disabled>
                    <i class="bi bi-paperclip"></i>
                </button>

                {{-- Message input --}}
                <input
                    type="text"
                    id="messageInput"
                    class="message-input"
                    placeholder="Type your health question here…"
                    autocomplete="off"
                    aria-label="Type your message"
                    maxlength="300"
                />

                {{-- Send button --}}
                <button class="send-btn" id="sendBtn" aria-label="Send message">
                    <i class="bi bi-send-fill"></i>
                </button>

            </div>

            {{-- Character count & disclaimer --}}
            <p class="input-disclaimer" id="inputDisclaimer">
                <i class="bi bi-shield-check"></i>
                For health information only — not a substitute for professional medical advice.
            </p>

        </footer>{{-- END chat-input-bar --}}

    </main>{{-- END chat-main --}}

</div>{{-- END chatbot-wrapper --}}

{{-- Sidebar overlay (mobile backdrop) --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

@endsection

{{-- ============================================================
     PUSH SCRIPTS – chatbot.js is loaded at end of body
============================================================ --}}
@push('scripts')
<script src="{{ asset('js/chatbot.js') }}"></script>
@endpush
