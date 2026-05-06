import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';

window.Pusher = Pusher;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

import './wilayah';
import './upload-handler';

window.scrollToBottom = function() {
    const el = document.getElementById('chat-container');
    if (el) el.scrollTop = el.scrollHeight;
};

const handleChatEvent = (e) => {
    const chatData = e.chat;

    if (e.is_delete) {
        document.getElementById(`message-${chatData.id}`)?.remove();
        return;
    }

    const chatContainer = document.getElementById('chat-container');
    if (!chatContainer || document.getElementById(`message-${chatData.id}`)) return;

    const isMe = chatData.id_pengirim == window.currentUserId;
    const isGlobal = chatData.id_penerima == 'GLOBAL';
    const fotoUrl = chatData.foto_chat ? (chatData.foto_chat.startsWith('http') ? chatData.foto_chat : `/storage/${chatData.foto_chat}`) : null;

    let bubble = '';

    if (isGlobal) {
        bubble = `
            <div id="message-${chatData.id}" class="flex justify-center mb-6 px-4 w-full">
                <div class="w-full max-w-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-4 shadow-sm relative overflow-hidden">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-amber-500 text-white p-1.5 rounded-lg">
                            <i class="fa-solid fa-bullhorn text-[10px]"></i>
                        </span>
                        <span class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Pusat Informasi</span>
                        <span class="text-[9px] font-bold text-amber-600 ml-auto">Baru saja</span>
                    </div>
                    ${fotoUrl ? `<img src="${fotoUrl}" class="rounded-xl mb-2 w-full max-h-60 object-cover border border-amber-200 shadow-sm cursor-pointer" onclick="window.open('${fotoUrl}', '_blank')">` : ''}
                    <p class="text-sm text-amber-900 font-semibold leading-relaxed">${chatData.pesan}</p>
                </div>
            </div>
        `;
    } else {
        bubble = `
            <div id="message-${chatData.id}" class="flex ${isMe ? 'justify-end' : 'justify-start'} mb-4 group px-4 w-full">
                <div class="relative ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-700 border border-slate-200 rounded-tl-none'} max-w-[85%] md:max-w-[70%] p-3 rounded-2xl shadow-sm">
                    ${fotoUrl ? `<img src="${fotoUrl}" class="rounded-lg mb-2 max-w-full h-auto cursor-pointer" onclick="window.open('${fotoUrl}', '_blank')">` : ''}
                    ${chatData.pesan ? `<p class="text-sm font-medium leading-relaxed">${chatData.pesan}</p>` : ''}
                    <div class="flex items-center justify-end gap-1.5 mt-2 ${isMe ? 'opacity-80' : 'text-slate-400'}">
                        <span class="text-[9px] font-bold">Baru saja</span>
                        ${isMe ? `<i class="fa-solid fa-check text-[10px]"></i>` : ''}
                    </div>
                    ${isMe ? `
                        <button onclick="deleteMessage('${chatData.id}')" class="absolute -left-10 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 p-2 text-red-400 hover:text-red-600 transition-all">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
    }

    chatContainer.insertAdjacentHTML('beforeend', bubble);
    window.scrollToBottom();
};

if (window.currentUserId) {
    window.Echo.private(`chat.${window.currentUserId}`)
        .listen('.MessageSent', (e) => {
            if (e.chat.id_penerima !== 'GLOBAL') {
                handleChatEvent(e);
            }
        });

    window.Echo.channel('chat.global')
        .listen('.MessageSent', (e) => {
            if (e.chat.id_penerima === 'GLOBAL') {
                handleChatEvent(e);
            }
        });
}

window.deleteMessage = function(chatId) {
    if (confirm('Hapus pesan?')) {
        axios.delete(`/chat/${chatId}`).then(res => {
            if (res.data.success) document.getElementById(`message-${chatId}`)?.remove();
        });
    }
};
