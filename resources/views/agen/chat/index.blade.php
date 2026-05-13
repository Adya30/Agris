@extends('layouts.agen')
@section('title', 'Chat - AGRIS')

@section('content')
<div class="fixed inset-0 bg-slate-100 z-50 overflow-hidden" id="chat-app" v-cloak>
    <div class="max-w-full mx-auto h-full flex flex-col bg-white shadow-2xl relative">
        <div class="h-20 px-4 md:px-6 flex items-center justify-between border-b border-slate-200 bg-white z-20">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('agen.produk.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-600 transition-colors">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </a>

                <img src="{{ $admin->fotoProfil ?? 'https://ui-avatars.com/api/?name=Admin&background=15803d&color=fff' }}" class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover">

                <div class="flex flex-col">
                    <h2 class="font-bold text-slate-800 text-xs md:text-sm uppercase leading-tight">Pusat Layanan Admin</h2>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <i class="fa-solid fa-comments text-slate-200 text-xl hidden md:block"></i>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6 bg-[#f8fafc]" id="chat-container">
            <div v-for="chat in chats" :key="chat.id" :class="chat.id_penerima == 'GLOBAL' ? 'flex justify-center' : (chat.id_pengirim == @js(Auth::id()) ? 'flex justify-end' : 'flex justify-start')">

                <div v-if="chat.id_penerima == 'GLOBAL'" class="w-full max-w-2xl bg-linear-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-amber-500 text-white p-1.5 rounded-lg text-[10px]"><i class="fa-solid fa-bullhorn"></i></span>
                        <span class="text-[10px] font-bold text-amber-700 uppercase">Pengumuman</span>
                        <span class="text-[9px] font-bold text-amber-600 ml-auto">@{{ formatTime(chat.waktu_chat) }}</span>
                    </div>
                    <p class="text-sm text-amber-900 font-semibold">@{{ chat.pesan }}</p>
                    <div v-if="chat.foto_chat" class="mt-3 rounded-xl overflow-hidden border-none shadow-sm">
                        <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat" class="w-full max-h-60 object-cover">
                    </div>
                </div>

                <div v-else class="max-w-[85%] md:max-w-[70%] group flex items-start gap-1">
                    <div v-if="chat.id_pengirim == @js(Auth::id())" class="flex items-center self-center gap-1 order-1">
                        <div v-if="activeMenu === chat.id" class="animate-in fade-in slide-in-from-right-1 duration-200">
                            <button @click.prevent="deleteChat(chat.id)" class="bg-white px-3 py-1.5 rounded-lg shadow-md border border-slate-100 text-[10px] font-black text-red-600 hover:bg-red-50 whitespace-nowrap">
                                HAPUS
                            </button>
                        </div>
                        <button @click.stop="toggleMenu(chat.id)" class="opacity-0 group-hover:opacity-100 p-1.5 text-slate-400 hover:text-green-600 transition-all">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                    </div>

                    <div :class="[chat.id_pengirim == @js(Auth::id()) ? 'bg-green-600 text-white rounded-tr-none order-2' : 'bg-white text-slate-700 rounded-tl-none border-none order-2']" class="px-4 py-3 rounded-3xl shadow-sm">
                        <div v-if="chat.foto_chat" class="mb-2 rounded-lg overflow-hidden">
                            <img :src="chat.foto_chat.startsWith('http') ? chat.foto_chat : '/storage/' + chat.foto_chat" class="max-h-64 w-full object-cover">
                        </div>
                        <p class="text-sm font-medium">@{{ chat.pesan }}</p>
                        <div class="flex justify-end items-center gap-1.5 mt-2 text-[9px] font-bold opacity-80">
                            <span>@{{ formatTime(chat.waktu_chat) }}</span>
                            <template v-if="chat.id_pengirim == @js(Auth::id())">
                                <i v-if="chat.status === 'dibaca'" class="fa-solid fa-check-double text-blue-200"></i>
                                <i v-else class="fa-solid fa-check text-green-200"></i>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-slate-100">
            <div v-if="imagePreview" class="mb-4 flex items-center justify-between p-3 bg-green-50 rounded-2xl">
                <span class="text-xs font-bold text-green-800">@{{ selectedFile?.name }}</span>
                <button @click="cancelImage" class="text-red-500"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-4xl">
                <label class="w-12 h-12 flex items-center justify-center text-slate-400 cursor-pointer hover:text-green-600 transition-colors">
                    <i class="fa-solid fa-image"></i>
                    <input type="file" @change="handleFileUpload" class="hidden" id="file-input-field">
                </label>
                <input type="text" v-model="newMessage" @keyup.enter="sendChat" placeholder="Tulis pesan..." class="flex-1 bg-transparent border-none focus:ring-0 focus:outline-none text-sm">
                <button @click="sendChat" class="bg-green-600 hover:bg-green-700 text-white w-12 h-12 rounded-full flex items-center justify-center shrink-0 transition-transform active:scale-95"><i class="fa-solid fa-paper-plane"></i></button>
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
                const field = document.getElementById('file-input-field');
                if(field) field.value = '';
            };

            const deleteChat = (id) => {
                axios.delete(`/chat/${id}`).then(() => {
                    chats.value = chats.value.filter(c => c.id !== id);
                    activeMenu.value = null;
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
                            } else if (e.is_read_update) {
                                chats.value.forEach(c => {
                                    if (c.id_pengirim == @js(Auth::id()) && c.id_penerima == e.chat.id_pengirim) {
                                        c.status = 'dibaca';
                                    }
                                });
                            } else {
                                if (!chats.value.some(c => c.id === e.chat.id)) {
                                    chats.value.push(e.chat);
                                    scrollToBottom();
                                    axios.get(`/chat/${e.chat.id_pengirim}`);
                                }
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
