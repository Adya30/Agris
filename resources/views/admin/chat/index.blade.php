@extends('layouts.admin')

@section('content')
<div class="flex h-screen bg-gray-100" id="chat-app">
    <div class="w-1/3 bg-white border-r flex flex-col">
        <div class="p-4 border-b bg-indigo-600 text-white flex justify-between items-center">
            <h1 class="text-xl font-bold">Agris Chat</h1>
            <button @click="openGlobalChat" class="bg-yellow-400 hover:bg-yellow-500 text-indigo-900 px-3 py-1 rounded text-sm font-bold">
                PENGUMUMAN
            </button>
        </div>
        <div class="overflow-y-auto flex-1">
            @foreach($users as $u)
            <div @click="loadChat('{{ $u->id }}', '{{ $u->name }}')"
                 class="p-4 border-b cursor-pointer hover:bg-gray-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                    {{ substr($u->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $u->name }}</p>
                    <p class="text-xs text-gray-500">Klik untuk membalas</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="w-2/3 flex flex-col bg-gray-50">
        <template v-if="activeTarget">
            <div class="p-4 bg-white border-b shadow-sm flex justify-between items-center">
                <h2 class="font-bold text-gray-700">@{{ activeTargetName }}</h2>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-container">
                <div v-for="chat in chats" :key="chat.id"
                     :class="chat.id_pengirim == '{{ Auth::id() }}' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="chat.id_pengirim == '{{ Auth::id() }}' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-800 border'"
                         class="max-w-md p-3 rounded-lg shadow-sm relative group">
                        <img v-if="chat.foto_chat" :src="chat.foto_chat" class="rounded mb-2 max-w-full h-auto" />
                        <p class="text-sm">@{{ chat.pesan }}</p>
                        <span class="text-[10px] opacity-70 block mt-1 text-right">@{{ formatTime(chat.waktu_chat) }}</span>
                        <button v-if="chat.id_pengirim == '{{ Auth::id() }}' && chat.pesan !== 'Pesan ini telah dihapus'"
                                @click="deleteChat(chat.id)"
                                class="absolute top-0 right-0 hidden group-hover:block bg-red-500 text-white rounded-full p-1 -mt-2 -mr-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white border-t">
                <div class="flex items-center gap-2">
                    <label class="cursor-pointer p-2 bg-gray-100 rounded-full hover:bg-gray-200">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <input type="file" @change="handleFileUpload" class="hidden" accept="image/*">
                    </label>
                    <input type="text" v-model="newMessage" @keyup.enter="sendChat"
                           placeholder="Ketik pesan..."
                           class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button @click="sendChat" class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
                <p v-if="imagePreview" class="mt-2 text-xs text-indigo-600 font-semibold italic">Gambar siap dikirim...</p>
            </div>
        </template>
        <div v-else class="flex-1 flex items-center justify-center text-gray-400 italic">
            Pilih agen untuk memulai percakapan
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const { createApp, ref, onMounted, nextTick } = Vue;
    createApp({
        setup() {
            const chats = ref([]);
            const activeTarget = ref(null);
            const activeTargetName = ref('');
            const newMessage = ref('');
            const selectedFile = ref(null);
            const imagePreview = ref(false);

            const loadChat = (id, name) => {
                activeTarget.value = id;
                activeTargetName.value = name;
                axios.get(`/admin/chat/${id}`).then(res => {
                    chats.value = res.data.chats;
                    scrollToBottom();
                });
            };

            const openGlobalChat = () => loadChat('GLOBAL', 'PENGUMUMAN SEMUA AGEN');

            const handleFileUpload = (event) => {
                selectedFile.value = event.target.files[0];
                imagePreview.value = true;
            };

            const sendChat = () => {
                if (!newMessage.value && !selectedFile.value) return;
                const formData = new FormData();
                formData.append('id_penerima', activeTarget.value);
                formData.append('pesan', newMessage.value);
                if (selectedFile.value) formData.append('foto_chat', selectedFile.value);

                const tempMsg = newMessage.value;
                newMessage.value = '';
                selectedFile.value = null;
                imagePreview.value = false;

                axios.post('/admin/chat', formData).then(res => {
                    if (!chats.value.some(c => c.id === res.data.id)) {
                        chats.value.push(res.data);
                        scrollToBottom();
                    }
                }).catch(() => {
                    newMessage.value = tempMsg;
                });
            };

            const deleteChat = (id) => {
                axios.delete(`/admin/chat/${id}`).then(() => {
                    const chat = chats.value.find(c => c.id === id);
                    if (chat) chat.pesan = 'Pesan ini telah dihapus';
                });
            };

            const formatTime = (t) => new Date(t).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const scrollToBottom = () => nextTick(() => {
                const el = document.getElementById('chat-container');
                if (el) el.scrollTop = el.scrollHeight;
            });

            onMounted(() => {
                const checkEcho = setInterval(() => {
                    if (window.Echo) {
                        clearInterval(checkEcho);
                        window.Echo.private(`chat.${window.currentUserId}`)
                            .listen('MessageSent', (e) => {
                                if (activeTarget.value == e.chat.id_pengirim || e.chat.id_penerima == 'GLOBAL') {
                                    if (!chats.value.some(c => c.id === e.chat.id)) {
                                        chats.value.push(e.chat);
                                        scrollToBottom();
                                    }
                                }
                            });
                    }
                }, 500);
            });

            return { chats, activeTarget, activeTargetName, newMessage, loadChat, openGlobalChat, sendChat, handleFileUpload, deleteChat, formatTime, imagePreview };
        }
    }).mount('#chat-app');
</script>
@endsection
