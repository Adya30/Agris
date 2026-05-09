@extends('layouts.agen')
@section('title', 'Chat - AGRIS')

@section('content')
<div class="bg-slate-100 h-[calc(100vh-64px)] overflow-hidden" id="chat-app" v-cloak>
    <div class="max-w-5xl mx-auto h-full flex flex-col bg-white shadow-2xl">
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-200 bg-white z-20">
            <div class="flex items-center gap-4">
                <img src="{{ $admin->fotoProfil ?? 'https://ui-avatars.com/api/?name=Admin&background=4f46e5&color=fff' }}" class="w-12 h-12 rounded-2xl object-cover">
                <h2 class="font-black text-slate-800 text-sm uppercase">Customer Service</h2>
            </div>
            <i class="fa-solid fa-comments text-slate-300 text-xl"></i>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6 bg-[#f8fafc]" id="chat-container">
            <div v-for="chat in chats" :key="chat.id" :class="chat.id_penerima == 'GLOBAL' ? 'flex justify-center' : (chat.id_pengirim == @js(Auth::id()) ? 'flex justify-end' : 'flex justify-start')">

                <div v-if="chat.id_penerima == 'GLOBAL'" class="w-full max-w-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-amber-500 text-white p-1.5 rounded-lg text-[10px]"><i class="fa-solid fa-bullhorn"></i></span>
                        <span class="text-[10px] font-black text-amber-700 uppercase">Pusat Informasi</span>
                        <span class="text-[9px] font-bold text-amber-600 ml-auto">@{{ formatTime(chat.waktu_chat) }}</span>
                    </div>
                    <p class="text-sm text-amber-900 font-semibold">@{{ chat.pesan }}</p>
                    <div v-if="chat.foto_chat" class="mt-3 rounded-xl overflow-hidden border border-amber-200 shadow-sm">
                        <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat" class="w-full max-h-60 object-cover">
                    </div>
                </div>

                <div v-else class="max-w-[85%] md:max-w-[70%] group flex items-end gap-2">
                    <div v-if="chat.id_pengirim == @js(Auth::id())">
                        <button @click.stop="toggleMenu(chat.id)" class="opacity-0 group-hover:opacity-100 p-2 text-slate-400"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        <div v-if="activeMenu === chat.id" class="absolute right-0 bottom-full mb-2 w-32 bg-white rounded-xl shadow-2xl z-50 py-1">
                            <button @click="deleteChat(chat.id)" class="w-full text-left px-4 py-2 text-[11px] font-bold text-red-600">HAPUS</button>
                        </div>
                    </div>
                    <div :class="chat.id_pengirim == @js(Auth::id()) ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-700 border rounded-tl-none'" class="px-4 py-3 rounded-3xl shadow-sm">
                        <div v-if="chat.foto_chat" class="mb-2 rounded-lg overflow-hidden">
                            <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat" class="max-h-64 w-full object-cover">
                        </div>
                        <p class="text-sm font-medium">@{{ chat.pesan }}</p>
                        <div class="flex justify-end gap-1.5 mt-2 text-[9px] font-bold">@{{ formatTime(chat.waktu_chat) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-slate-100">
            <div v-if="imagePreview" class="mb-4 flex items-center justify-between p-3 bg-indigo-50 rounded-2xl">
                <span class="text-xs font-bold text-indigo-800">@{{ selectedFile?.name }}</span>
                <button @click="cancelImage" class="text-red-500"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-[2rem]">
                <label class="w-12 h-12 flex flex-center text-slate-400 cursor-pointer">
                    <i class="fa-solid fa-paperclip text-xl ml-3 mt-3"></i>
                    <input type="file" @change="handleFileUpload" class="hidden" id="file-input-field">
                </label>
                <input type="text" v-model="newMessage" @keyup.enter="sendChat" placeholder="Tulis pesan..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm">
                <button @click="sendChat" class="bg-indigo-600 text-white w-12 h-12 rounded-full flex items-center justify-center"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    const { createApp, ref, onMounted, nextTick } = Vue;
    createApp({
        setup() {
            const chats = ref(@js($chats));
            const newMessage = ref('');
            const selectedFile = ref(null);
            const imagePreview = ref(false);
            const activeMenu = ref(null);

            const scrollToBottom = () => nextTick(() => {
                const el = document.getElementById('chat-container');
                if (el) el.scrollTop = el.scrollHeight;
            });

            const sendChat = () => {
                if (!newMessage.value && !selectedFile.value) return;
                const formData = new FormData();
                formData.append('id_penerima', @js($admin->id));
                formData.append('pesan', newMessage.value || '');
                if (selectedFile.value) formData.append('foto_chat', selectedFile.value);
                newMessage.value = '';
                cancelImage();
                axios.post('/chat', formData).then(res => {
                    if (!chats.value.some(c => c.id === res.data.id)) {
                        chats.value.push(res.data);
                        scrollToBottom();
                    }
                });
            };

            const handleFileUpload = (e) => {
                const file = e.target.files[0];
                if (file) { selectedFile.value = file; imagePreview.value = true; }
            };

            const cancelImage = () => {
                selectedFile.value = null; imagePreview.value = false;
                document.getElementById('file-input-field').value = '';
            };

            const deleteChat = (id) => {
                axios.delete(`/chat/${id}`).then(() => {
                    chats.value = chats.value.filter(c => c.id !== id);
                });
            };

            const formatTime = (t) => {
                if (!t) return 'Baru saja';
                const d = new Date(t);
                return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
            };

            const toggleMenu = (id) => activeMenu.value = activeMenu.value === id ? null : id;

            onMounted(() => {
                scrollToBottom();
                window.addEventListener('click', () => activeMenu.value = null);
                const checkEcho = setInterval(() => {
                    if (window.Echo) {
                        clearInterval(checkEcho);
                        window.Echo.private(`chat.${@js(Auth::id())}`).listen('.MessageSent', (e) => {
                            if (e.is_delete) {
                                chats.value = chats.value.filter(c => c.id !== e.chat.id);
                            } else if (!chats.value.some(c => c.id === e.chat.id)) {
                                chats.value.push(e.chat);
                                scrollToBottom();
                            }
                        });
                        window.Echo.channel('chat.global').listen('.MessageSent', (e) => {
                            if (!chats.value.some(c => c.id === e.chat.id)) {
                                chats.value.push(e.chat);
                                scrollToBottom();
                            }
                        });
                    }
                }, 500);
            });

            return { chats, newMessage, selectedFile, imagePreview, activeMenu, sendChat, handleFileUpload, cancelImage, deleteChat, formatTime, toggleMenu };
        }
    }).mount('#chat-app');
</script>
@endsection
