<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Centro de Mensajería') }}
        </h2>
    </x-slot>

    {{-- Contenedor principal: Altura dinámica para ocupar el espacio restante --}}
    <div class="py-6 h-[calc(100vh-80px)]"> 
        <div class="mx-auto h-full px-4 sm:px-6 lg:px-8"> 
            <div 
                class="flex h-full bg-white dark:bg-gray-800 shadow-2xl rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700"
                x-data="chatPanel()" 
                x-init="init()"
            >
                
                {{-- Panel Lateral de Chats y Usuarios --}}
                <div 
                    class="w-full md:w-1/3 bg-white dark:bg-gray-800 border-r dark:border-gray-700 flex flex-col flex-shrink-0"
                    {{-- LÓGICA DE VISIBILIDAD MEJORADA: Oculta en móvil si hay chat seleccionado y no se pide la lista de usuarios --}}
                    :class="{'hidden md:flex': selectedChatId && !showUserList && window.innerWidth < 768, 'flex': !selectedChatId || showUserList || window.innerWidth >= 768}"
                >
                    
                    <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700 flex-shrink-0">
                        <h2 class="text-lg font-semibold flex items-center space-x-2" :class="{'text-blue-600 dark:text-blue-400': showUserList, 'text-gray-800 dark:text-gray-200': !showUserList}">
                            {{-- Ícono SVG para la lista de Chats --}}
                            <template x-if="!showUserList">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </template>
                             {{-- Ícono SVG para la lista de Usuarios --}}
                            <template x-if="showUserList">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </template>
                            <span x-text="showUserList ? 'Usuarios Disponibles' : 'Chats Activos'"></span>
                        </h2>
                        
                        {{-- Botón para Alternar Vista (Chats/Usuarios) --}}
                        <button 
                            @click="showUserList = !showUserList; selectedChatId = null;"
                            class="p-2 rounded-full transition duration-150"
                            :class="{'bg-blue-500 text-white hover:bg-blue-600': showUserList, 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700': !showUserList}"
                            title="Alternar vista de usuarios disponibles"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Lista de Chats Activos --}}
                    <div x-show="!showUserList" class="flex-1 overflow-y-auto">
                        <template x-if="chats.length === 0 && !isLoading">
                            <div class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                No hay conversaciones activas.
                            </div>
                        </template>

                        <template x-for="chat in chats" :key="chat.id">
                            <div 
                                @click="selectChat(chat.id)"
                                class="p-3 border-b dark:border-gray-700 cursor-pointer transition duration-150 flex items-start space-x-3"
                                :class="{'bg-blue-50 dark:bg-blue-900/40 border-l-4 border-blue-500': selectedChatId === chat.id, 'hover:bg-gray-50 dark:hover:bg-gray-700': selectedChatId !== chat.id}"
                            >
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-base">
                                    <span x-text="getChatInitials(chat)"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <span class="text-sm font-semibold truncate text-gray-800 dark:text-gray-100" x-text="getChatHeaderName(chat)"></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2" x-text="formatTime(chat.updated_at)"></span>
                                    </div>
                                    <p x-if="chat.ultimo_mensaje" class="text-xs text-gray-600 dark:text-gray-400 truncate mt-1">
                                        <span class="font-normal" x-text="chat.ultimo_mensaje.emisor.id === userId ? 'Tú: ' : ''"></span>
                                        <span x-text="chat.ultimo_mensaje.contenido"></span>
                                    </p>
                                    <p x-else class="text-xs text-gray-400 dark:text-gray-500 italic mt-1">
                                        Sin mensajes
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    {{-- Lista de Usuarios Disponibles --}}
                    <div x-show="showUserList" class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900/30">
                        <div class="p-4 text-sm font-semibold text-gray-700 dark:text-gray-300 border-b dark:border-gray-700">Inicia una conversación:</div>
                        
                        <template x-if="availableUsers.length === 0 && !isLoading">
                            <div class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                No hay más usuarios para iniciar un chat.
                            </div>
                        </template>
                        
                        <template x-for="user in availableUsers" :key="user.id">
                            <div 
                                @click="startNewChat(user.id)"
                                class="p-3 border-b dark:border-gray-700 cursor-pointer hover:bg-green-50 dark:hover:bg-green-900/40 transition duration-150 flex items-center space-x-3"
                            >
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-base">
                                    <span x-text="user.nombre.substring(0, 2).toUpperCase()"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-semibold truncate text-gray-800 dark:text-gray-100" x-text="user.nombre"></span>
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">Clic para chatear</p>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Indicador de Carga --}}
                    <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-75 dark:bg-opacity-75 flex items-center justify-center z-10">
                        <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                {{-- Panel de Mensajes --}}
                <div 
                    class="w-full md:w-2/3 flex flex-col h-full bg-gray-100 dark:bg-gray-900/50"
                    {{-- LÓGICA DE VISIBILIDAD MEJORADA: Oculta en móvil si no hay chat seleccionado o si se pide la lista de usuarios --}}
                    :class="{'hidden md:flex': !selectedChatId || showUserList, 'flex': selectedChatId && !showUserList}"
                >
                    <template x-if="!selectedChatId">
                        <div class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400 text-base">
                            <div class="text-center p-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-blue-400 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.5V16M7.5 16H16.5M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
                                <p class="text-lg font-medium">¡Bienvenido al Chat!</p>
                                <p class="text-sm mt-1">Selecciona o inicia una conversación para comenzar a chatear.</p>
                            </div>
                        </div>
                    </template>

                    <template x-if="selectedChatId && currentChat">
                        <div class="flex flex-col flex-1">
                            <div class="p-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700 shadow-sm flex-shrink-0 flex items-center">
                                {{-- Botón de Regreso (Solo en Móvil) --}}
                                <button @click="selectedChatId = null; showUserList = false" class="md:hidden mr-3 text-gray-600 dark:text-gray-300 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                                </button>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="getChatHeaderName(currentChat)"></h3>
                            </div>

                            {{-- Contenedor de Mensajes --}}
                            <div class="flex-1 p-6 space-y-4 overflow-y-auto" x-ref="messageContainer">
                                <template x-for="message in messages" :key="message.id">
                                    <div 
                                        class="flex" 
                                        :class="{'justify-end': message.usuario_emisor_id === userId, 'justify-start': message.usuario_emisor_id !== userId}"
                                    >
                                        <div 
                                            class="p-3 max-w-xs md:max-w-md rounded-xl shadow-md"
                                            :class="{
                                                'bg-blue-600 text-white rounded-br-none': message.usuario_emisor_id === userId, 
                                                'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-tl-none': message.usuario_emisor_id !== userId
                                            }"
                                        >
                                            <p class="text-sm whitespace-pre-wrap" x-text="message.contenido"></p>
                                            <span 
                                                class="text-xs mt-1 block text-right" 
                                                :class="{'text-blue-100': message.usuario_emisor_id === userId, 'text-gray-500 dark:text-gray-400': message.usuario_emisor_id !== userId}"
                                                x-text="formatTime(message.created_at)"
                                            ></span>
                                        </div>
                                    </div>
                                </template>
                                
                                {{-- Indicador de Carga para mensajes --}}
                                <template x-if="isLoadingMessages">
                                    <div class="flex justify-center py-4">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </template>
                            </div>

                            {{-- Formulario de Envío de Mensajes --}}
                            <div class="p-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex-shrink-0">
                                <form @submit.prevent="sendMessage()">
                                    <div class="flex items-center">
                                        <input 
                                            type="text" 
                                            x-model="newMessage" 
                                            placeholder="Escribe un mensaje..."
                                            class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-full py-2 px-4 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            required
                                            @keydown.enter.prevent="sendMessage()"
                                        >
                                        <button 
                                            type="submit" 
                                            class="ml-3 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition duration-150 disabled:bg-blue-300"
                                            :disabled="!newMessage.trim() || isLoadingMessages"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function chatPanel() {
            return {
                // --- Estado ---
                csrfToken: '{{ csrf_token() }}',
                userId: {{ Auth::id() }},
                chats: [],
                availableUsers: [],
                selectedChatId: null,
                currentChat: null,
                messages: [],
                newMessage: '',
                isLoading: false, // Carga de chats/usuarios
                isLoadingMessages: false, // Carga de mensajes/Envío
                showUserList: false, 
                
                // --- Inicialización ---
                init() {
                    this.fetchChats();
                    this.fetchAvailableUsers();
                },
                
                // --- Lógica de Vistas/Visualización ---
                getChatHeaderName(chat) {
                    if (chat.es_grupo) {
                        return chat.nombre || 'Chat de Grupo';
                    }
                    if (!chat.participantes || chat.participantes.length < 2) return 'Chat Desconocido';
                    
                    // Encuentra al otro participante
                    const other = chat.participantes.find(p => p.id !== this.userId);
                    return other ? other.nombre : chat.nombre || 'Chat contigo mismo';
                },
                
                getChatInitials(chat) {
                    const name = this.getChatHeaderName(chat);
                    if (!name) return '??';
                    return name.substring(0, 2).toUpperCase();
                },
                
                formatTime(timestamp) {
                    // Muestra solo la hora
                    return new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                // --- API Calls ---

                async fetchChats() {
                    this.isLoading = true;
                    try {
                        const response = await fetch('/api/chats');
                        const data = await response.json();
                        if (response.ok) {
                            this.chats = data;
                        } else {
                            console.error('Error al cargar chats:', data.error);
                        }
                    } catch (e) {
                        console.error('Error de red al cargar chats:', e);
                    }
                    this.isLoading = false;
                },

                async fetchAvailableUsers() {
                    try {
                        const response = await fetch('/api/usuarios-disponibles');
                        const data = await response.json();
                        if (response.ok) {
                            // Filtra los usuarios para que no aparezcan aquellos con chats activos (opcional, mejora UX)
                            const activeChatUserIds = new Set(
                                this.chats.filter(c => !c.es_grupo)
                                    .map(c => c.participantes.find(p => p.id !== this.userId)?.id)
                                    .filter(id => id)
                            );
                            this.availableUsers = data.filter(user => !activeChatUserIds.has(user.id));

                        } else {
                            console.error('Error al cargar usuarios disponibles:', data.error);
                        }
                    } catch (e) {
                        console.error('Error de red al cargar usuarios disponibles:', e);
                    }
                },
                
                async fetchMessages(chatId) {
                    this.messages = [];
                    this.isLoadingMessages = true;
                    try {
                        const response = await fetch(`/api/chats/${chatId}`);
                        const data = await response.json();
                        if (response.ok) {
                            this.messages = data;
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        } else {
                            console.error('Error al cargar mensajes:', data.error);
                        }
                    } catch (e) {
                        console.error('Error de red al cargar mensajes:', e);
                    } finally {
                        this.isLoadingMessages = false;
                    }
                },
                
                selectChat(chatId) {
                    this.selectedChatId = chatId;
                    this.currentChat = this.chats.find(chat => chat.id === chatId);
                    this.showUserList = false;
                    this.fetchMessages(chatId);
                    this.newMessage = ''; // Limpia el input al cambiar de chat
                },

                async startNewChat(userId) {
                    if (this.isLoading) return;
                    this.isLoading = true;
                    
                    const url = `/api/chat/iniciar/${userId}`; 
                    
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Content-Type': 'application/json' // Añadir Content-Type en POST
                            },
                            // No body necesario ya que el ID va en la URL
                        });
                        
                        const data = await response.json();

                        if (response.ok && data.success) {
                            await this.fetchChats(); 
                            this.selectChat(data.conversacion_id); 
                            await this.fetchAvailableUsers(); // Refresca lista de usuarios
                            
                        } else {
                            console.error('Error al crear/obtener chat:', data.error);
                            alert('Error: ' + (data.error || 'No se pudo iniciar el chat.'));
                        }
                    } catch (e) {
                        console.error('Error de red al iniciar chat:', e);
                        alert('Error de red. Intenta nuevamente.');
                    }
                    this.isLoading = false;
                },
                
                async sendMessage() {
                    if (!this.selectedChatId || !this.newMessage.trim()) return;

                    const content = this.newMessage.trim();
                    this.newMessage = '';
                    this.isLoadingMessages = true; // Bloquea el botón
                    
                    const url = `/api/chats/${this.selectedChatId}/mensajes`;

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                            },
                            body: JSON.stringify({ contenido: content })
                        });
                        
                        const data = await response.json();

                        if (response.ok && data.success) {
                            // Simplemente añade el mensaje nuevo, evita recargar todo el historial.
                            const emisor = { id: this.userId, nombre: 'Tú' }; // Data mínima para el emisor
                            this.messages.push({
                                ...data.mensaje,
                                emisor: emisor,
                            });
                            
                            // Actualiza el chat para reflejar el último mensaje y el timestamp
                            const chatIndex = this.chats.findIndex(c => c.id === this.selectedChatId);
                            if(chatIndex !== -1) {
                                this.chats[chatIndex].ultimo_mensaje = {
                                    contenido: content,
                                    emisor: emisor
                                };
                                this.chats[chatIndex].updated_at = new Date().toISOString();
                                
                                // Mueve el chat al inicio (simulación de orden por updated_at)
                                const chatToMove = this.chats.splice(chatIndex, 1)[0];
                                this.chats.unshift(chatToMove);
                            }
                            
                            this.$nextTick(() => this.scrollToBottom());
                            
                        } else {
                            console.error('Error al enviar mensaje:', data.error);
                            alert('Error al enviar mensaje: ' + (data.error || 'Desconocido'));
                            this.newMessage = content; // Devuelve el contenido al input
                        }
                    } catch (e) {
                        console.error('Error de red al enviar mensaje:', e);
                        alert('Error de red. Intenta nuevamente.');
                        this.newMessage = content;
                    } finally {
                        this.isLoadingMessages = false;
                    }
                },
                
                scrollToBottom() {
                    const container = this.$refs.messageContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }
        }
    </script>
</x-app-layout>