@extends('layouts.admin')

@section('title', 'Chat - AGRIS')

@section('content')
<div class="flex flex-col md:flex-row bg-slate-100 relative h-[calc(100vh-64px)] overflow-hidden" id="chat-app" v-cloak>
    <div :class="activeTarget ? 'hidden md:flex' : 'flex'"
         class="w-full md:w-80 flex-col bg-white border-r border-slate-200 shrink-0 h-full shadow-xl">
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100 shrink-0 bg-white">
            <div class="flex flex-col">
                <h1 class="text-xl font-black text-green-700 leading-none">AGRIS</h1>
                <span class="text-[10px] font-bold text-slate-400 tracking-[0.2em] uppercase">Messenger</span>
            </div>
            <button @click="openGlobalChat"
                    :class="activeTarget === 'GLOBAL' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700'"
                    class="px-3 py-2 rounded-xl text-[10px] font-black transition-all hover:scale-105 active:scale-95 shadow-sm">
                BROADCAST
            </button>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @foreach($users as $u)
            <div @click="loadChat(@js($u->id), @js($u->namaLengkap ?? 'Agen'), @js($u->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($u->namaLengkap ?? 'U').'&background=dcfce7&color=15803d'))"
                 :class="activeTarget == @js($u->id) ? 'bg-green-50 border-r-4 border-green-600' : 'hover:bg-slate-50 border-r-4 border-transparent'"
                 class="p-4 border-b border-slate-50 cursor-pointer transition-all flex items-center gap-4">
                <div class="relative shrink-0">
                    <img src="{{ $u->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($u->namaLengkap ?? 'U').'&background=dcfce7&color=15803d' }}"
                         class="w-12 h-12 rounded-2xl object-cover shadow-md border-2 border-white"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($u->namaLengkap ?? 'U') }}&background=dcfce7&color=15803d'">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex justify-between items-start mb-0.5">
                        <p class="font-bold text-slate-800 text-sm truncate">{{ $u->namaLengkap ?? 'Agen' }}</p>
                    </div>
                    <p class="text-[11px] text-slate-400 truncate font-medium">Klik untuk membalas pesan</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div :class="activeTarget ? 'flex' : 'hidden md:flex'"
         class="flex-1 flex-col min-w-0 bg-[#f8fafc] h-full relative overflow-hidden">
        <template v-if="activeTarget">
            <div class="h-20 px-6 flex items-center justify-between border-b border-slate-200 shrink-0 bg-white/80 backdrop-blur-md z-20 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="activeTarget = null" class="md:hidden p-2 -ml-2 text-slate-400 hover:text-green-600">
                        <i class="fa-solid fa-chevron-left text-lg"></i>
                    </button>
                    <div class="relative">
                        <img :src="activeTargetPhoto" class="w-11 h-11 rounded-2xl object-cover shadow-sm border border-slate-100"
                             onerror="this.src='https://ui-avatars.com/api/?background=dcfce7&color=15803d'">
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 border-2 border-white"></span>
                        </span>
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-black text-slate-800 text-sm tracking-tight uppercase truncate">@{{ activeTargetName }}</h2>
                        <span class="text-[10px] text-green-600 font-bold uppercase tracking-wider">Sedang Aktif</span>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-8 custom-scrollbar" id="chat-container">
                <div v-for="chat in chats" :key="chat.id"
                     :class="chat.id_pengirim == @js(Auth::id()) ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[85%] md:max-w-[70%] group relative flex items-end gap-3">
                        <div v-if="chat.id_pengirim == @js(Auth::id())" class="relative self-center">
                            <button @click.stop="toggleMenu(chat.id)" class="opacity-0 group-hover:opacity-100 p-2 text-slate-400 hover:text-red-500 transition-all">
                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                            </button>
                            <div v-if="activeMenu === chat.id" class="absolute right-0 bottom-full mb-2 w-32 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 py-1 overflow-hidden">
                                <button @click="deleteChat(chat.id)" class="w-full text-left px-4 py-2 text-[11px] font-bold text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fa-solid fa-trash-can mr-2"></i>HAPUS
                                </button>
                            </div>
                        </div>

                        <div :class="chat.id_penerima == 'GLOBAL' ? 'bg-amber-400 text-amber-950 rounded-2xl rounded-bl-none shadow-lg' : (chat.id_pengirim == @js(Auth::id()) ? 'bg-green-600 text-white rounded-3xl rounded-tr-none shadow-lg shadow-green-100' : 'bg-white text-slate-700 border border-slate-100 rounded-3xl rounded-tl-none shadow-sm')"
                             class="px-4 py-3 relative transition-all">
                            <div v-if="chat.id_penerima == 'GLOBAL'" class="text-[8px] font-black text-amber-800 mb-1 tracking-[0.2em] uppercase">Broadcasting</div>
                            <div v-if="chat.foto_chat" class="mb-3 mt-1 overflow-hidden rounded-2xl">
                                <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat"
                                     class="w-full max-h-96 object-cover cursor-pointer hover:scale-[1.02] transition-transform"
                                     @click="window.open(chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat)">
                            </div>
                            <p v-if="chat.pesan" class="text-sm leading-relaxed font-medium">@{{ chat.pesan }}</p>
                            <div class="flex items-center justify-end gap-2 mt-2" :class="chat.id_pengirim == @js(Auth::id()) ? 'text-green-100' : 'text-slate-400'">
                                <span class="text-[9px] font-bold uppercase">@{{ formatTime(chat.waktu_chat) }}</span>
                                <i v-if="chat.id_pengirim == @js(Auth::id())"
                                   :class="chat.status == 'dibaca' ? 'fa-solid fa-check-double' : 'fa-solid fa-check'"
                                   class="text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-6 bg-white border-t border-slate-200">
                <div v-if="imagePreview" class="mb-4 flex items-center justify-between p-3 bg-green-50 border border-green-100 rounded-2xl animate-bounce-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-white">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <span class="text-xs font-bold text-green-800 truncate max-w-[200px]">@{{ selectedFile?.name }}</span>
                    </div>
                    <button @click="cancelImage" class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-100 rounded-full transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="flex items-center gap-3 bg-slate-100 p-2 rounded-[2rem] border-2 border-transparent focus-within:border-green-500 focus-within:bg-white transition-all shadow-inner">
                    <label class="cursor-pointer w-12 h-12 flex items-center justify-center text-slate-400 hover:text-green-600 transition-colors shrink-0">
                        <i class="fa-solid fa-paperclip text-xl"></i>
                        <input type="file" @change="handleFileUpload" class="hidden" accept="image/*" id="file-input-field">
                    </label>
                    <input type="text" v-model="newMessage" @keyup.enter="sendChat" placeholder="Tulis pesan..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm font-medium outline-none text-slate-700">
                    <button @click="sendChat" :disabled="!newMessage && !selectedFile" class="bg-green-600 text-white w-12 h-12 rounded-full hover:bg-green-700 disabled:bg-slate-300 transition-all flex items-center justify-center shrink-0 shadow-lg shadow-green-200 active:scale-90">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </template>

        <div v-else class="hidden md:flex flex-1 flex-col items-center justify-center bg-slate-50">
            <div class="w-24 h-24 bg-white rounded-[2.5rem] flex items-center justify-center shadow-xl border border-slate-100 mb-6 text-green-600">
                <i class="fa-solid fa-comments text-5xl"></i>
            </div>
            <h3 class="text-slate-800 font-black text-lg">AGRIS MESSENGER</h3>
            <p class="text-slate-400 text-xs font-bold tracking-widest uppercase mt-2">Pilih obrolan untuk memulai</p>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    [v-cloak] { display: none; }
    @keyframes bounce-in {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-bounce-in { animation: bounce-in 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
</style>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const { createApp, ref, onMounted, nextTick } = Vue;
    createApp({
        setup() {
            const chats = ref([]);
            const activeTarget = ref(null);
            const activeTargetName = ref('');
            const activeTargetPhoto = ref('');
            const newMessage = ref('');
            const selectedFile = ref(null);
            const imagePreview = ref(false);
            const activeMenu = ref(null);

            const scrollToBottom = () => nextTick(() => {
                const el = document.getElementById('chat-container');
                if (el) el.scrollTop = el.scrollHeight;
            });

            const loadChat = (id, name, photo) => {
                activeTarget.value = id;
                activeTargetName.value = name;
                activeTargetPhoto.value = photo;
                axios.get(`/chat/${id}`).then(res => {
                    chats.value = res.data.chats;
                    scrollToBottom();
                });
            };

            const toggleMenu = (id) => activeMenu.value = activeMenu.value === id ? null : id;

            const openGlobalChat = () => {
                loadChat('GLOBAL', 'PENGUMUMAN GLOBAL', 'https://ui-avatars.com/api/?name=G&background=fef3c7&color=92400e');
            };

            const handleFileUpload = (e) => {
                const file = e.target.files[0];
                if (file) {
                    selectedFile.value = file;
                    imagePreview.value = true;
                }
            };

            const cancelImage = () => {
                selectedFile.value = null;
                imagePreview.value = false;
                const input = document.getElementById('file-input-field');
                if(input) input.value = '';
            };

            const sendChat = () => {
                if (!newMessage.value && !selectedFile.value) return;
                const formData = new FormData();
                formData.append('id_penerima', activeTarget.value);
                formData.append('pesan', newMessage.value || '');
                if (selectedFile.value) formData.append('foto_chat', selectedFile.value);

                newMessage.value = '';
                cancelImage();

                axios.post('/chat', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }).then(() => {
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
                const date = new Date(t);
                return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
            };

            onMounted(() => {
                window.addEventListener('click', () => activeMenu.value = null);
                const checkEcho = setInterval(() => {
                    if (window.Echo) {
                        clearInterval(checkEcho);
                        window.Echo.private(`chat.${@js(Auth::id())}`)
                            .listen('.MessageSent', (e) => {
                                if (e.is_delete) {
                                    chats.value = chats.value.filter(c => c.id !== e.chat.id);
                                    return;
                                }
                                if (activeTarget.value == e.chat.id_pengirim || (activeTarget.value == e.chat.id_penerima && e.chat.id_penerima != 'GLOBAL')) {
                                    if (!chats.value.some(c => c.id === e.chat.id)) {
                                        chats.value.push(e.chat);
                                        scrollToBottom();
                                    }
                                }
                            });
                        window.Echo.channel('chat.global')
                            .listen('.MessageSent', (e) => {
                                if (activeTarget.value === 'GLOBAL' && !chats.value.some(c => c.id === e.chat.id)) {
                                    chats.value.push(e.chat);
                                    scrollToBottom();
                                }
                            });
                    }
                }, 500);
            });

            return { chats, activeTarget, activeTargetName, activeTargetPhoto, newMessage, imagePreview, selectedFile, activeMenu, loadChat, openGlobalChat, sendChat, handleFileUpload, cancelImage, deleteChat, formatTime, toggleMenu };
        }
    }).mount('#chat-app');
</script>
@endsection
