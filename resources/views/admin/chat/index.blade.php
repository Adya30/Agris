@extends('layouts.admin')
@section('title', 'Chat - AGRIS')

@section('content')
<div class="flex flex-col md:flex-row bg-slate-100 relative h-[calc(100vh-64px)] overflow-hidden" id="chat-app" v-cloak>
    <div :class="activeTarget ? 'hidden md:flex' : 'flex'" class="w-full md:w-80 flex-col bg-white border-r border-slate-200 shrink-0 h-full shadow-xl">
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100 shrink-0 bg-white">
            <div class="flex flex-col">
                <h1 class="text-xl font-black text-green-700 leading-none">AGRIS</h1>
                <span class="text-[10px] font-bold text-slate-400 tracking-[0.2em] uppercase">Messenger</span>
            </div>
            <button @click="openGlobalChat" :class="activeTarget === 'GLOBAL' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700'" class="px-3 py-2 rounded-xl text-[10px] font-black transition-all hover:scale-105 active:scale-95 shadow-sm">
                BROADCAST
            </button>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @foreach($users as $u)
            <div @click="loadChat(@js($u->id), @js($u->namaLengkap ?? 'Agen'), @js($u->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($u->namaLengkap ?? 'U').'&background=dcfce7&color=15803d'))"
                 :class="activeTarget == @js($u->id) ? 'bg-green-50 border-r-4 border-green-600' : 'hover:bg-slate-50 border-r-4 border-transparent'"
                 class="p-4 border-b border-slate-50 cursor-pointer transition-all flex items-center gap-4">
                <div class="relative shrink-0">
                    <img src="{{ $u->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($u->namaLengkap ?? 'U').'&background=dcfce7&color=15803d' }}" class="w-12 h-12 rounded-2xl object-cover shadow-md border-2 border-white">
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-slate-800 text-sm truncate">{{ $u->namaLengkap ?? 'Agen' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div :class="activeTarget ? 'flex' : 'hidden md:flex'" class="flex-1 flex-col min-w-0 bg-[#f8fafc] h-full relative overflow-hidden">
        <template v-if="activeTarget">
            <div class="h-20 px-6 flex items-center justify-between border-b border-slate-200 shrink-0 bg-white/80 backdrop-blur-md z-20 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="activeTarget = null" class="md:hidden p-2 -ml-2 text-slate-400"><i class="fa-solid fa-chevron-left"></i></button>
                    <img :src="activeTargetPhoto" class="w-11 h-11 rounded-2xl object-cover">
                    <div class="min-w-0">
                        <h2 class="font-black text-slate-800 text-sm uppercase truncate">@{{ activeTargetName }}</h2>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-8 custom-scrollbar" id="chat-container">
                <div v-for="chat in chats" :key="chat.id" :class="chat.id_pengirim == @js(Auth::id()) ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[85%] md:max-w-[70%] group relative flex items-end gap-3">
                        <div v-if="chat.id_pengirim == @js(Auth::id())">
                            <button @click.stop="toggleMenu(chat.id)" class="opacity-0 group-hover:opacity-100 p-2 text-slate-400 hover:text-red-500"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                            <div v-if="activeMenu === chat.id" class="absolute right-0 bottom-full mb-2 w-32 bg-white rounded-xl shadow-2xl border z-50">
                                <button @click="deleteChat(chat.id)" class="w-full text-left px-4 py-2 text-[11px] font-bold text-red-600">HAPUS</button>
                            </div>
                        </div>
                        <div :class="chat.id_penerima == 'GLOBAL' ? 'bg-amber-400 text-amber-950' : (chat.id_pengirim == @js(Auth::id()) ? 'bg-green-600 text-white rounded-tr-none' : 'bg-white text-slate-700 border rounded-tl-none')" class="px-4 py-3 rounded-3xl shadow-sm">
                            <div v-if="chat.foto_chat" class="mb-2 rounded-xl overflow-hidden">
                                <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat" class="w-full max-h-96 object-cover">
                            </div>
                            <p class="text-sm font-medium">@{{ chat.pesan }}</p>
                            <div class="flex justify-end mt-1 text-[9px] font-bold">@{{ formatTime(chat.waktu_chat) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white border-t border-slate-200">
                <div v-if="imagePreview" class="mb-4 p-3 bg-green-50 rounded-2xl flex justify-between">
                    <span class="text-xs font-bold text-green-800">@{{ selectedFile?.name }}</span>
                    <button @click="cancelImage" class="text-red-500"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="flex items-center gap-3 bg-slate-100 p-2 rounded-[2rem]">
                    <label class="w-12 h-12 flex items-center justify-center text-slate-400 cursor-pointer">
                        <i class="fa-solid fa-paperclip text-xl"></i>
                        <input type="file" @change="handleFileUpload" class="hidden" id="file-input-field">
                    </label>
                    <input type="text" v-model="newMessage" @keyup.enter="sendChat" placeholder="Tulis pesan..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm">
                    <button @click="sendChat" class="bg-green-600 text-white w-12 h-12 rounded-full flex items-center justify-center"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </div>
        </template>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
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

            const openGlobalChat = () => loadChat('GLOBAL', 'Pusat Informasi', 'https://ui-avatars.com/api/?name=G&background=fef3c7&color=92400e');

            const sendChat = () => {
                if (!newMessage.value && !selectedFile.value) return;
                const formData = new FormData();
                formData.append('id_penerima', activeTarget.value);
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
                if(document.getElementById('file-input-field')) document.getElementById('file-input-field').value = '';
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
                window.addEventListener('click', () => activeMenu.value = null);
                const checkEcho = setInterval(() => {
                    if (window.Echo) {
                        clearInterval(checkEcho);
                        window.Echo.private(`chat.${@js(Auth::id())}`).listen('.MessageSent', (e) => {
                            if (e.is_delete) {
                                chats.value = chats.value.filter(c => c.id !== e.chat.id);
                            } else if (activeTarget.value == e.chat.id_pengirim || (activeTarget.value == e.chat.id_penerima && e.chat.id_penerima != 'GLOBAL')) {
                                if (!chats.value.some(c => c.id === e.chat.id)) {
                                    chats.value.push(e.chat);
                                    scrollToBottom();
                                }
                            }
                        });
                        window.Echo.channel('chat.global').listen('.MessageSent', (e) => {
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
