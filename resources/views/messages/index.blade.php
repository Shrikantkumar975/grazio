@extends('layouts.app')

@section('title', 'Messages & Communications')

@section('content')
<div class="h-[calc(100vh-140px)] flex gap-6" x-data="chat()">
    
    <!-- Sidebar: Conversation List -->
    <div class="w-80 flex-shrink-0 flex flex-col bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden transition-colors">
        <div class="p-4 border-b border-zenith-border">
            <h2 class="text-lg font-bold text-zenith-text">Messages</h2>
            <div class="mt-4 relative">
                <i class="material-icons absolute left-3 top-2.5 text-[18px] text-zenith-textLight">search</i>
                <input type="text" placeholder="Search managers..." class="w-full bg-zenith-hover text-sm text-zenith-text placeholder-zenith-textLight rounded-lg pl-10 pr-4 py-2 outline-none border border-transparent focus:border-zenith-border transition-colors">
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse($users as $user)
                <div @click="selectUser({{ $user['id'] }}, '{{ $user['name'] }}', '{{ $user['initials'] }}')"
                     :class="activeUserId === {{ $user['id'] }} ? 'bg-zenith-hover/80 border-l-2 border-zenith-blue' : 'hover:bg-zenith-hover border-l-2 border-transparent'"
                     class="p-4 border-b border-zenith-border/50 cursor-pointer transition-colors flex gap-3 relative">
                    <div class="w-10 h-10 rounded-full {{ $user['color'] }} flex items-center justify-center text-sm font-bold flex-shrink-0">
                        {{ $user['initials'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline justify-between mb-0.5">
                            <p class="text-sm font-semibold text-zenith-text truncate pr-4">{{ $user['name'] }}</p>
                            <span class="text-[10px] text-zenith-textLight flex-shrink-0">{{ $user['time'] }}</span>
                        </div>
                        <p class="text-xs text-zenith-textLight truncate">
                            {{ $user['last_message'] }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-zenith-textLight">
                    <p class="text-sm">No other managers found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-zenith-card rounded-xl shadow-sm border border-zenith-border overflow-hidden transition-colors">
        
        <template x-if="!activeUserId">
            <div class="flex-1 flex flex-col items-center justify-center text-zenith-textLight p-8">
                <i class="material-icons text-6xl mb-4 opacity-20">chat</i>
                <h3 class="text-lg font-bold text-zenith-text mb-2">Your Conversations</h3>
                <p class="text-sm text-center max-w-sm">Select a manager from the sidebar to view your message history and start chatting in real-time.</p>
            </div>
        </template>

        <template x-if="activeUserId">
            <div class="flex-1 flex flex-col min-h-0" @send-message.window="sendMessage($event.detail)">
                <!-- Chat Header -->
                <div class="p-4 border-b border-zenith-border flex items-center justify-between bg-zenith-hover/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-zenith-teal/20 text-zenith-teal flex items-center justify-center text-sm font-bold flex-shrink-0" x-text="activeUserInitials">
                        </div>
                        <div>
                            <h3 class="font-bold text-zenith-text" x-text="activeUserName"></h3>
                            <p class="text-xs text-zenith-teal flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-zenith-teal animate-pulse"></span> Online</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg text-zenith-textLight hover:bg-zenith-hover hover:text-zenith-text transition-colors">
                            <i class="material-icons text-[20px]">more_vert</i>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar bg-zenith-bg/30" x-ref="messagesContainer">
                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.is_me ? 'flex gap-4 max-w-[80%] ml-auto justify-end' : 'flex gap-4 max-w-[80%]'">
                            
                            <!-- Received Message Avatar -->
                            <template x-if="!msg.is_me">
                                <div class="w-8 h-8 rounded-full bg-zenith-teal/20 text-zenith-teal flex items-center justify-center text-xs font-bold flex-shrink-0 mt-auto" x-text="activeUserInitials"></div>
                            </template>

                            <!-- Message Body -->
                            <div :class="msg.is_me ? 'text-right' : ''">
                                <div :class="msg.is_me ? 'bg-zenith-blue text-white px-4 py-3 rounded-2xl rounded-br-sm text-sm shadow-sm' : 'bg-zenith-hover text-zenith-text px-4 py-3 rounded-2xl rounded-bl-sm text-sm shadow-sm'" x-text="msg.text"></div>
                                <span :class="msg.is_me ? 'text-[10px] text-zenith-textLight mt-1 mr-1 block' : 'text-[10px] text-zenith-textLight mt-1 ml-1 block'" x-text="msg.time"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Chat Input -->
                <div class="p-4 border-t border-zenith-border bg-zenith-hover/10">
                    <form class="flex items-center gap-3" x-data="{ message: '' }" @submit.prevent="if(message.trim()) { $dispatch('send-message', message); message = ''; }">
                        <div class="flex-1 relative">
                            <input type="text" x-model="message" placeholder="Type a message..." class="w-full bg-zenith-bg border border-zenith-border text-zenith-text text-sm rounded-full pl-4 pr-12 py-3 outline-none focus:border-zenith-blue transition-colors">
                            <button type="submit" class="absolute right-2 top-1.5 w-8 h-8 flex items-center justify-center rounded-full bg-zenith-blue text-white hover:opacity-90 transition-opacity disabled:opacity-50" :disabled="!message.trim()">
                                <i class="material-icons text-[16px] ml-1">send</i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chat', () => ({
            activeUserId: null,
            activeUserName: '',
            activeUserInitials: '',
            messages: [],
            interval: null,

            init() {
                this.interval = setInterval(() => {
                    if(this.activeUserId) {
                        this.fetchMessages(false);
                    }
                }, 3000); // Poll every 3 seconds for active chat
            },

            selectUser(id, name, initials) {
                this.activeUserId = id;
                this.activeUserName = name;
                this.activeUserInitials = initials;
                this.messages = [];
                this.fetchMessages(true);
            },

            async fetchMessages(scrollToBottom = true) {
                if(!this.activeUserId) return;
                
                const res = await fetch(`/messages/api/${this.activeUserId}`);
                if (!res.ok) return;

                const data = await res.json();
                const currentLength = this.messages.length;
                this.messages = data;
                
                if (scrollToBottom && data.length > currentLength) {
                    this.$nextTick(() => {
                        if(this.$refs.messagesContainer) {
                            this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                        }
                    });
                }
            },

            async sendMessage(text) {
                if(!this.activeUserId) return;

                // Optimistic UI update
                const tempMsg = {
                    id: Date.now(),
                    is_me: true,
                    text: text,
                    time: 'Just now'
                };
                this.messages.push(tempMsg);
                
                this.$nextTick(() => {
                    if(this.$refs.messagesContainer) {
                        this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                    }
                });

                // Post to server
                await fetch(`/messages/api/${this.activeUserId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ text })
                });
                
                // Fetch the real state
                this.fetchMessages();
            }
        }));
    });
</script>
@endpush
@endsection
