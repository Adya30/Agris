@extends('layouts.agen')

@section('title', 'Chat Admin - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto h-[80vh] bg-white shadow-xl rounded-xl overflow-hidden flex flex-col" id="chat-app">
    <div class="p-4 bg-indigo-600 text-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white text-indigo-600 rounded-full flex items-center justify-center font-bold">A</div>
            <div>
                <p class="font-bold">Admin</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" id="chat-container">
        <div v-for="chat in chats" :key="chat.id"
             :class="chat.id_pengirim == '{{ Auth::id() }}' ? 'flex justify-end' : 'flex justify-start'">
            <div :class="chat.id_penerima == 'GLOBAL' ? 'bg-yellow-100 border-yellow-300 text-yellow-800 w-full text-center' : (chat.id_pengirim == '{{ Auth::id() }}' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-800 border')"
                 class="max-w-md p-3 rounded-lg shadow-sm relative group">
                <span v-if="chat.id_penerima == 'GLOBAL'" class="block text-[10px] font-bold uppercase mb-1">📢 PENGUMUMAN</span>
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
            <label class="cursor-pointer p-2 hover:bg-gray-100 rounded-full">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <input type="file" @change="handleFileUpload" class="hidden" accept="image/*">
            </label>
            <input type="text" v-model="newMessage" @keyup.enter="sendChat"
                   placeholder="Tulis pesan ke admin..."
                   class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button @click="sendChat" class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const { createApp, ref, onMounted, nextTick } = Vue;
    createApp({
        setup() {
            const chats = ref(@json($chats));
            const newMessage = ref('');
            const selectedFile = ref(null);
            const adminId = '{{ $admin->id }}';

            const sendChat = () => {
                if (!newMessage.value && !selectedFile.value) return;
                const formData = new FormData();
                formData.append('id_penerima', adminId);
                formData.append('pesan', newMessage.value);
                if (selectedFile.value) formData.append('foto_chat', selectedFile.value);

                const tempMsg = newMessage.value;
                newMessage.value = '';
                selectedFile.value = null;

                axios.post('/agen/chat', formData).then(res => {
                    if (!chats.value.some(c => c.id === res.data.id)) {
                        chats.value.push(res.data);
                        scrollToBottom();
                    }
                }).catch((e) => {
                    console.error(e)
                    newMessage.value = tempMsg;
                });
            };

            const deleteChat = (id) => {
                axios.delete(`/agen/chat/${id}`).then(() => {
                    const chat = chats.value.find(c => c.id === id);
                    if (chat) { chat.pesan = 'Pesan ini telah dihapus'; chat.foto_chat = null; }
                });
            };

            const handleFileUpload = (e) => selectedFile.value = e.target.files[0];
            const formatTime = (t) => new Date(t).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const scrollToBottom = () => nextTick(() => {
                const el = document.getElementById('chat-container');
                if (el) el.scrollTop = el.scrollHeight;
            });

            onMounted(() => {
                scrollToBottom();
                const checkEcho = setInterval(() => {
                    if (window.Echo) {
                        clearInterval(checkEcho);
                        window.Echo.private(`chat.${window.currentUserId}`)
                            .listen('MessageSent', (e) => {
                                if (!chats.value.some(c => c.id === e.chat.id)) {
                                    chats.value.push(e.chat);
                                    scrollToBottom();
                                }
                            });
                        window.Echo.channel('announcement-channel')
                            .listen('MessageSent', (e) => {
                                if (!chats.value.some(c => c.id === e.chat.id)) {
                                    chats.value.push(e.chat);
                                    scrollToBottom();
                                }
                            });
                    }
                }, 500);
            });

            return { chats, newMessage, sendChat, handleFileUpload, deleteChat, formatTime };
        }
    }).mount('#chat-app');
</script>
@endsection
