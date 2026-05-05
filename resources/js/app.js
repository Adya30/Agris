import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// HANYA BOLEH ADA SATU INISIALISASI ECHO
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Hapus import './echo' jika isinya mendefinisikan window.Echo lagi
import './wilayah';
import './upload-handler';

window.scrollToBottom = function() {
    const el = document.getElementById('chat-container');
    if (el) {
        el.scrollTop = el.scrollHeight;
    }
};

if (window.currentUserId) {
    // Pastikan channel dan event name sesuai dengan di backend
    window.Echo.private(`chat.${window.currentUserId}`)
        .listen('.MessageSent', (e) => {
            console.log('Event diterima:', e); // Debugging: Cek di console browser

            const chatContainer = document.getElementById('chat-container');
            if (chatContainer) {
                const isMe = e.chat.id_pengirim == window.currentUserId;

                const bubble = `
                    <div class="flex ${isMe ? 'justify-end' : 'justify-start'} mb-4">
                        <div class="${isMe ? 'bg-indigo-600 text-white' : 'bg-white text-gray-800 border'} max-w-md p-3 rounded-lg shadow-sm">
                            ${e.chat.foto_chat ? `<img src="${e.chat.foto_chat}" class="rounded mb-2 max-w-full h-auto">` : ''}
                            ${e.chat.pesan ? `<p class="text-sm">${e.chat.pesan}</p>` : ''}
                            <span class="text-[10px] opacity-70 block mt-1 text-right">Baru saja</span>
                        </div>
                    </div>
                `;

                chatContainer.insertAdjacentHTML('beforeend', bubble);
                window.scrollToBottom();
            }
        });
}
