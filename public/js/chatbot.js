/**
 * LMLinga – Barangay Health Center Chatbot
 * File: public/js/chatbot.js
 * Mode: Local AI (Ollama Mistral) via Laravel Proxy
 */

const state = {
    currentLang: 'en',
    isTyping: false
};

// DOM References
const chatMessages      = document.getElementById('chatMessages');
const messageInput      = document.getElementById('messageInput');
const sendBtn           = document.getElementById('sendBtn');
const langBtns          = document.querySelectorAll('.lang-btn');
const chipBtns          = document.querySelectorAll('.chip-btn');
const greetingSubtitle  = document.getElementById('greetingSubtitle');
const disclaimerText    = document.getElementById('disclaimerText');

// 1. UTILITY FUNCTIONS
function getTimestamp() {
    return new Date().toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit', hour12: true });
}

function scrollToBottom() {
    chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: 'smooth' });
}

function escapeHTML(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

// 2. CHAT BUBBLE RENDERERS
function appendUserMessage(text) {
    const row = document.createElement('div');
    row.classList.add('message-row', 'user-row');
    row.innerHTML = `
        <div class="message-bubble user-bubble">
            <p>${escapeHTML(text)}</p>
            <span class="message-time">${getTimestamp()}</span>
        </div>`;
    chatMessages.appendChild(row);
    scrollToBottom();
}

function appendBotMessage(text, isHTML = false) {
    const content = isHTML ? text : escapeHTML(text).replace(/\n/g, "<br>");

    const row = document.createElement('div');
    row.classList.add('message-row', 'bot-row');

    row.innerHTML = `
        <div class="bot-avatar"><i class="bi bi-robot"></i></div>
        <div class="message-bubble bot-bubble">
            <div>${content}</div>
            <span class="message-time">${getTimestamp()}</span>
        </div>`;
        
    chatMessages.appendChild(row);
    scrollToBottom();
}

function showTypingIndicator() {
    const row = document.createElement('div');
    row.classList.add('typing-row');
    row.id = 'typingIndicator';
    row.innerHTML = `
        <div class="bot-avatar"><i class="bi bi-robot"></i></div>
        <div class="typing-bubble">
            <span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>
        </div>`;
    chatMessages.appendChild(row);
    scrollToBottom();
}

function removeTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    if (indicator) indicator.remove();
}

// Ensure this function is called by your lang-btn click listeners
function updateLanguageState(lang) {
    state.currentLang = lang; 
    // Now, every time sendMessage() runs, it sends the correct 'lang'
}

async function sendMessage() {
    const text = messageInput.value.trim();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!text || state.isTyping) return;

    messageInput.value = '';
    appendUserMessage(text);

    state.isTyping = true;
    sendBtn.disabled = true;
    showTypingIndicator();

    try {
        const response = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            // Fix: We send state.currentLang so the backend knows which folder to open
            body: JSON.stringify({ message: text, lang: state.currentLang })
        });

        if (!response.ok) throw new Error('Server returned an error');

        const data = await response.json();
        removeTypingIndicator();
        
        if (data.reply && data.reply.trim() !== "") {
            appendBotMessage(data.reply);
            // Sync local state if backend returned a specific language
            if(data.current_lang) state.currentLang = data.current_lang;
        } else {
            appendBotMessage("<em>[Local AI returned an empty response]</em>. Please check if Mistral is loaded in Ollama.");
        }

    } catch (error) {
        console.error("Debugger Error:", error);
        removeTypingIndicator();
        appendBotMessage("Under development issue. Please try again later.");
    } finally {
        state.isTyping = false;
        sendBtn.disabled = false;
        messageInput.focus();
    }
}

// 4. EVENT LISTENERS
sendBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(); });

chipBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        messageInput.value = this.innerText;
        sendMessage();
    });
});

// 4. EVENT LISTENERS (Updated for Language Awareness)

// Main Header Buttons
// Update your language button listener
langBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const selectedLang = this.dataset.lang;
        updateLanguageState(selectedLang);

        langBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        let message;

        if (selectedLang === 'tl') {
            message = `Language set to <strong>${this.innerText}</strong>. Paano kita matutulungan?`;
        } 
        else if (selectedLang === 'bi') {
            message = `Language set to <strong>${this.innerText}</strong>. Paano ko ika matatabangan?`;
        } 
        else {
            message = `Language set to <strong>${this.innerText}</strong>. How can I help?`;
        }

        appendBotMessage(message, true);
    });
});

// Shortcut buttons inside the chat bubbles
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('bubble-lang-btn')) {
        const selectedLang = e.target.dataset.lang;
        updateLanguageState(selectedLang);
        
        // Sync the header buttons visually
        langBtns.forEach(b => {
            b.classList.toggle('active', b.dataset.lang === selectedLang);
        });

        appendBotMessage(
    `Language changed to <strong>${e.target.innerText}</strong>`,
    true
);
    }
});

// The "Brain" function that keeps the state in sync
function updateLanguageState(lang) {
    state.currentLang = lang;
    console.log("Language state updated to:", state.currentLang);
}