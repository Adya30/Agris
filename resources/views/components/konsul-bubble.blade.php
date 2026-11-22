@php
    $chatbotUserId = Auth::id() ?? (session()->isStarted() ? session()->getId() : request()->ip());
    $chatbotDateKey = now()->toDateString();
    $hasUsedChatbotToday = Cache::has('chatbot_used_' . $chatbotUserId . '_' . $chatbotDateKey);
@endphp

<style>
    iframe[src*="chatbase.co"],
    iframe[id*="chatbase"],
    #chatbase-bubble-button,
    #chatbase-bubble-window {
        z-index: 300 !important;
    }

    /* Lock overlay styling: only the disabled comment column replica */
    .chatbase-quota-lock-overlay {
        position: absolute !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        height: 76px !important;
        background: #ffffff !important;
        border-top: 1px solid #f1f5f9 !important;
        border-radius: 0 0 16px 16px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 16px !important;
        box-sizing: border-box !important;
        z-index: 2147483647 !important;
        pointer-events: auto !important;
        cursor: not-allowed !important;
    }

    .chatbase-disabled-input-bar {
        width: 100% !important;
        height: 46px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9999px !important;
        background: #f8fafc !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0 10px 0 16px !important;
        box-sizing: border-box !important;
        cursor: not-allowed !important;
    }

    .chatbase-disabled-field {
        flex: 1 !important;
        border: none !important;
        background: transparent !important;
        font-size: 13px !important;
        color: #94a3b8 !important;
        outline: none !important;
        cursor: not-allowed !important;
        padding: 0 !important;
    }

    .chatbase-disabled-icons {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .chatbase-disabled-send-btn {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        background: #e2e8f0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #94a3b8 !important;
    }
</style>

<script>
(function(){
    const USER_ID = @json($chatbotUserId);
    const DATE_KEY = @json($chatbotDateKey);
    const RECORD_URL = @json(route('agen.chatbot.record'));
    const CSRF_TOKEN = @json(csrf_token());
    const STORAGE_KEY = 'chatbase_used_' + USER_ID + '_' + DATE_KEY;

    let isQuotaExceeded = @json($hasUsedChatbotToday) || (localStorage.getItem(STORAGE_KEY) === '1');

    function notifyQuotaExceeded() {
        if (!isQuotaExceeded) {
            isQuotaExceeded = true;
            try { localStorage.setItem(STORAGE_KEY, '1'); } catch(e) {}

            fetch(RECORD_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ user_id: USER_ID })
            }).catch(() => {});
        }
        applyEnforcement();
    }

    function createOrUpdateLockOverlay(windowEl) {
        if (!windowEl) return;

        let lockOverlay = windowEl.querySelector('.chatbase-quota-lock-overlay');
        if (!lockOverlay) {
            lockOverlay = document.createElement('div');
            lockOverlay.className = 'chatbase-quota-lock-overlay';
            lockOverlay.innerHTML = `
                <div class="chatbase-disabled-input-bar">
                    <input type="text" disabled placeholder="Batas pesan tercapai" class="chatbase-disabled-field" />
                    <div class="chatbase-disabled-icons">
                        <svg style="width:18px; height:18px; color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 02-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                        </svg>
                        <div class="chatbase-disabled-send-btn">
                            <svg style="width:14px; height:14px; color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        </div>
                    </div>
                </div>
            `;
            windowEl.appendChild(lockOverlay);
        }
    }

    function applyEnforcement() {
        const windowEl = document.getElementById('chatbase-bubble-window');
        if (windowEl && isQuotaExceeded) {
            createOrUpdateLockOverlay(windowEl);
        }
    }

    // Chatbase initial stub
    if (!window.chatbase || window.chatbase("getState") !== "initialized") {
        window.chatbase = (...args) => {
            if (!window.chatbase.q) { window.chatbase.q = []; }
            window.chatbase.q.push(args);
        };
        window.chatbase = new Proxy(window.chatbase, {
            get(target, prop) {
                if (prop === "q") { return target.q; }
                return (...args) => target(prop, ...args);
            }
        });
    }

    try {
        window.chatbase("addEventListener", "user-message", function(event) {
            notifyQuotaExceeded();
        });
    } catch(e) {}

    window.addEventListener("message", function(event) {
        if (!event.origin || !event.origin.includes("chatbase.co")) return;
        let data = event.data;
        if (typeof data === "string") {
            try { data = JSON.parse(data); } catch(e) {}
        }
        if (data && (
            data.type === "user-message" ||
            data.event === "user-message" ||
            data === "user-message"
        )) {
            notifyQuotaExceeded();
        }
    });

    const observer = new MutationObserver(function() {
        applyEnforcement();
    });

    const onLoad = function() {
        const script = document.createElement("script");
        script.src = "https://www.chatbase.co/embed.min.js";
        script.id = "Y2vILvNPd7OK5zbNrjNrA";
        script.domain = "www.chatbase.co";
        script.onload = function() {
            setTimeout(applyEnforcement, 500);
            setTimeout(applyEnforcement, 1500);
            setTimeout(applyEnforcement, 3000);
        };
        document.body.appendChild(script);

        observer.observe(document.body, { childList: true, subtree: true });
        setInterval(applyEnforcement, 1000);
    };

    if (document.readyState === "complete") {
        onLoad();
    } else {
        window.addEventListener("load", onLoad);
    }
})();
</script>
