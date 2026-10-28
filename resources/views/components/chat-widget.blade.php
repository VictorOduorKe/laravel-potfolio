<!-- Floating Gemini AI Assistant Launcher -->
<button id="chat-launcher" type="button" aria-label="Open AI Assistant Chat" class="chat-launcher">
    <span class="material-symbols-outlined" style="font-size: 28px;">smart_toy</span>
</button>

<!-- Chat Window Modal -->
<div id="chat-window" class="chat-window hidden" data-url="{{ route('chat') }}">
    <div class="chat-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981;"></div>
            <div>
                <h4 style="color: #ffffff; font-size: 14px; font-weight: 700; margin: 0;">AI Portfolio Assistant</h4>
                <span style="font-size: 11px; color: #b8c6e8;">Grounded in Live Portfolio Data</span>
            </div>
        </div>
        <button id="chat-close" type="button" style="background: transparent; color: #ffffff; padding: 4px; display: flex; align-items: center;" aria-label="Close Assistant">
            <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
        </button>
    </div>

    <!-- Message Stream -->
    <div id="chat-messages" class="chat-messages">
        <!-- Rendered dynamically by app.js -->
    </div>

    <!-- Input Form -->
    <form id="chat-form" class="chat-input-area">
        <input id="chat-input" type="text" placeholder="Ask about Victor's projects or tech stack..." autocomplete="off" maxlength="300" style="flex: 1; padding: 10px 14px; font-size: 13px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); outline: none;">
        <button type="submit" style="background: var(--primary); color: #ffffff; border-radius: var(--radius-sm); padding: 0 14px; display: flex; align-items: center; justify-content: center;" aria-label="Send Message">
            <span class="material-symbols-outlined" style="font-size: 18px;">send</span>
        </button>
    </form>
</div>
