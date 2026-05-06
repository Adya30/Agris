@extends('layouts.agen')

@section('title', 'Chat - AGRIS')

@section('content')
<div class="bg-slate-100 h-[calc(100vh-64px)] overflow-hidden" id="chat-app" v-cloak>
    <div class="max-w-5xl mx-auto h-full flex flex-col bg-white shadow-2xl">
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-200 shrink-0 bg-white z-20">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ $admin->fotoProfil ?? 'https://ui-avatars.com/api/?name=Admin&background=4f46e5&color=fff' }}"
                         class="w-12 h-12 rounded-2xl object-cover border-2 border-indigo-100 shadow-sm">
                </div>
                <div>
                    <h2 class="font-black text-slate-800 text-sm tracking-tight uppercase">Customer Service</h2>
                </div>
            </div>
            <div class="text-slate-300">
                <i class="fa-solid fa-comments text-xl"></i>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6 bg-[#f8fafc] scrollbar-thin scrollbar-thumb-slate-200" id="chat-container">
            <div v-for="chat in chats" :key="chat.id"
                 :class="chat.id_penerima == 'GLOBAL' ? 'flex justify-center' : (chat.id_pengirim == @js(Auth::id()) ? 'flex justify-end' : 'flex justify-start')">

                <div v-if="chat.id_penerima == 'GLOBAL'" class="w-full max-w-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-4 shadow-sm relative overflow-hidden">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-amber-500 text-white p-1.5 rounded-lg text-[10px]"><i class="fa-solid fa-bullhorn"></i></span>
                        <span class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Pusat Informasi</span>
                        <span class="text-[9px] font-bold text-amber-600 ml-auto">@{{ formatTime(chat.waktu_chat) }}</span>
                    </div>
                    <p class="text-sm text-amber-900 font-semibold">@{{ chat.pesan }}</p>
                    <div v-if="chat.foto_chat" class="mt-3 rounded-xl overflow-hidden border border-amber-200 shadow-sm">
                        <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat" class="w-full max-h-60 object-cover">
                    </div>
                </div>

                <div v-else class="max-w-[85%] md:max-w-[70%] group flex items-end gap-2">
                    <div v-if="chat.id_pengirim == @js(Auth::id())" class="relative self-center">
                        <button @click.stop="toggleMenu(chat.id)" class="opacity-0 group-hover:opacity-100 p-2 text-slate-400">
                            <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                        </button>
                        <div v-if="activeMenu === chat.id" class="absolute right-0 bottom-full mb-2 w-32 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 py-1">
                            <button @click="deleteChat(chat.id)" class="w-full text-left px-4 py-2 text-[11px] font-bold text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-trash-can mr-2"></i>HAPUS
                            </button>
                        </div>
                    </div>

                    <div :class="chat.id_pengirim == @js(Auth::id()) ? 'bg-indigo-600 text-white rounded-3xl rounded-tr-none' : 'bg-white text-slate-700 border border-slate-200 rounded-3xl rounded-tl-none'"
                         class="px-4 py-3 shadow-sm min-w-[80px]">
                        <div v-if="chat.foto_chat" class="mb-2 rounded-lg overflow-hidden">
                            <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat" class="max-h-64 w-full object-cover">
                        </div>
                        <p class="text-sm font-medium">@{{ chat.pesan }}</p>
                        <div class="flex items-center justify-end gap-1.5 mt-2" :class="chat.id_pengirim == @js(Auth::id()) ? 'opacity-80' : 'text-slate-400'">
                            <span class="text-[9px] font-bold">@{{ formatTime(chat.waktu_chat) }}</span>
                            <i v-if="chat.id_pengirim == @js(Auth::id())" :class="chat.status == 'dibaca' ? 'fa-solid fa-check-double' : 'fa-solid fa-check'" class="text-[10px]"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-slate-100">
            <div v-if="imagePreview" class="mb-4 flex items-center justify-between p-3 bg-indigo-50 border border-indigo-100 rounded-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white"><i class="fa-solid fa-image"></i></div>
                    <span class="text-xs font-bold text-indigo-800 truncate max-w-[200px]">@{{ selectedFile?.name }}</span>
                </div>
                <button @click="cancelImage" class="text-red-500"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-[2rem] border-2 border-transparent focus-within:border-indigo-500 focus-within:bg-white transition-all shadow-inner">
                <label class="w-12 h-12 flex items-center justify-center text-slate-400 hover:text-indigo-600 cursor-pointer shrink-0">
                    <i class="fa-solid fa-paperclip text-xl"></i>
                    <input type="file" @change="handleFileUpload" class="hidden" accept="image/*" id="file-input-field">
                </label>
                <input type="text" v-model="newMessage" @keyup.enter="sendChat" placeholder="Tulis pesan..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm font-medium outline-none">
                <button @click="sendChat" :disabled="!newMessage && !selectedFile" class="bg-indigo-600 text-white w-12 h-12 rounded-full hover:bg-indigo-700 disabled:bg-slate-300 transition-all flex items-center justify-center shadow-lg active:scale-90">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [v-cloak] { display: none; }
    .scrollbar-thin::-webkit-scrollbar { width: 5px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const { createApp, ref, onMounted, nextTick } = Vue;
    createApp({
        setup() {
            const chats = ref([]);
            const newMessage = ref('');
            const selectedFile = ref(null);
            const imagePreview = ref(false);
            const activeMenu = ref(null);

            const scrollToBottom = () => nextTick(() => {
                const el = document.getElementById('chat-container');
                if (el) el.scrollTop = el.scrollHeight;
            });

            const loadChats = () => {
                axios.get(`/chat/${@js($admin->id)}`).then(res => {
                    chats.value = res.data.chats;
                    scrollToBottom();
                });
            };

            const toggleMenu = (id) => activeMenu.value = activeMenu.value === id ? null : id;

            const handleFileUpload = (e) => {
                const file = e.target.files[0];
                if (file) { selectedFile.value = file; imagePreview.value = true; }
            };

            const cancelImage = () => {
                selectedFile.value = null; imagePreview.value = false;
                document.getElementById('file-input-field').value = '';
            };

            const sendChat = () => {
                if (!newMessage.value && !selectedFile.value) return;
                const formData = new FormData();
                formData.append('id_penerima', @js($admin->id));
                formData.append('pesan', newMessage.value || '');
                if (selectedFile.value) formData.append('foto_chat', selectedFile.value);

                newMessage.value = '';
                cancelImage();

                axios.post('/chat', formData, { headers: { 'Content-Type': 'multipart/form-data' } }).then(() => {
                    scrollToBottom();
                });
            };

            const deleteChat = (id) => {
                activeMenu.value = null;
                axios.delete(`/chat/${id}`).then(() => {
                    chats.value = chats.value.filter(c => c.id !== id);
                });
            };

            const formatTime = (t) => {
                if (!t) return '';
                const d = new Date(t);
                return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
            };

            onMounted(() => {
                loadChats();
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
