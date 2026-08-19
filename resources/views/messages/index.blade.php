<x-app-layout>
    @push('styles')
        <style>
            .chat-workspace {
                height: calc(100vh - 70px);
                background-color: var(--brand-light);
            }

            .conversations-sidebar {
                width: 320px;
                min-width: 320px;
                background: #ffffff;
                border-right: 1px solid var(--border-color);
            }

            .conversation-item {
                border-bottom: 1px solid #f1f5f9;
                transition: background-color 0.2s ease;
                cursor: pointer;
                text-decoration: none !important;
                display: block;
            }

            .conversation-item:hover, .conversation-item.active {
                background-color: var(--brand-tint);
            }

            .conversation-item.active {
                border-left: 3px solid var(--brand-primary);
            }

            .chat-body-stream {
                height: calc(100vh - 220px);
                overflow-y: auto;
                background-color: #f8fafc;
                padding: 1.25rem;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .message-bubble {
                max-width: 72%;
                padding: 0.75rem 1rem;
                border-radius: 12px;
                font-size: 0.88rem;
                line-height: 1.45;
                position: relative;
                word-wrap: break-word;
            }

            .message-bubble.sent {
                background-color: var(--brand-primary);
                color: #ffffff;
                align-self: flex-end;
                border-bottom-right-radius: 2px;
            }

            .message-bubble.received {
                background-color: #ffffff;
                color: #1e293b;
                border: 1px solid var(--border-color);
                align-self: flex-start;
                border-bottom-left-radius: 2px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            .chat-input-bar {
                background: #ffffff;
                border-top: 1px solid var(--border-color);
                padding: 0.85rem 1.25rem;
            }

            @media (max-width: 991.98px) {
                .conversations-sidebar {
                    width: 100%;
                    min-width: 100%;
                    border-right: none;
                }
                .chat-workspace {
                    height: auto;
                }
                .chat-body-stream {
                    height: 420px;
                }
            }
        </style>
    @endpush

    <div class="chat-workspace d-flex flex-column flex-lg-row">
        <!-- Conversations List Sidebar -->
        <div class="conversations-sidebar d-flex flex-column {{ $activeConversation ? 'd-none d-lg-flex' : 'd-flex' }}">
            <div class="p-3 border-bottom bg-white d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-comments text-primary me-2"></i> Direct Messages</h5>
                    <small class="text-muted" style="font-size: 0.73rem;">Platform User-to-User Chat</small>
                </div>
                <a href="{{ route('portfolio.edit') }}" class="btn btn-outline-secondary btn-sm rounded-pill" title="Back to Dashboard">
                    <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
                </a>
            </div>

            <!-- Conversations Search -->
            <div class="p-2 border-bottom bg-light">
                <input type="text" id="searchConversations" class="form-control form-control-sm border-0 shadow-none bg-white" placeholder="Search conversations...">
            </div>

            <!-- List of Conversations -->
            <div class="flex-grow-1 overflow-auto" id="conversationsContainer">
                @forelse($conversations as $conv)
                    @php
                        $otherUser = $conv->getOtherUser(Auth::id());
                        $unreadCount = $conv->unreadCountFor(Auth::id());
                        $isActive = $activeConversation && $activeConversation->id === $conv->id;
                        $latestMsg = $conv->latestMessage;
                    @endphp
                    <a href="{{ route('messages.index', ['conversation' => $conv->id]) }}" class="conversation-item p-3 {{ $isActive ? 'active' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative flex-shrink-0">
                                @if($otherUser && $otherUser->portfolio && $otherUser->portfolio->profile_image)
                                    <img src="{{ Storage::url($otherUser->portfolio->profile_image) }}" class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px; font-size: 1rem;">
                                        {{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>

                            <div class="min-w-0 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate small">{{ $otherUser->name ?? 'User' }}</h6>
                                    @if($conv->last_message_at)
                                        <small class="text-muted ms-1 flex-shrink-0" style="font-size: 0.68rem;">{{ $conv->last_message_at->diffForHumans(null, true) }}</small>
                                    @endif
                                </div>
                                <p class="text-muted mb-0 text-truncate" style="font-size: 0.78rem;">
                                    @if($latestMsg)
                                        {{ $latestMsg->sender_id === Auth::id() ? 'You: ' : '' }}{{ $latestMsg->body }}
                                    @else
                                        <em>No messages yet</em>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 px-3 text-muted">
                        <i class="fa-solid fa-comments-wait fa-2x mb-2 text-secondary"></i>
                        <h6 class="fw-bold text-dark">No active conversations</h6>
                        <p class="small text-muted mb-0">Connect with other registered professionals to start messaging.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Stream Window -->
        <div class="flex-grow-1 d-flex flex-column bg-white min-w-0">
            @if($activeConversation)
                @php
                    $targetUser = $activeConversation->getOtherUser(Auth::id());
                @endphp

                <!-- Active Chat Header -->
                <div class="p-3 border-bottom bg-white d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <a href="{{ route('messages.index') }}" class="btn btn-sm btn-light border d-lg-none me-1" title="Back to conversations">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        @if($targetUser && $targetUser->portfolio && $targetUser->portfolio->profile_image)
                            <img src="{{ Storage::url($targetUser->portfolio->profile_image) }}" class="rounded-circle border flex-shrink-0" style="width: 42px; height: 42px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 42px; height: 42px;">
                                {{ strtoupper(substr($targetUser->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $targetUser->name ?? 'User' }}</h6>
                            <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;">{{ $targetUser->portfolio->position ?? 'Platform Professional' }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        @if($targetUser && $targetUser->username)
                            <a href="{{ route('portfolio.show', $targetUser->username) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="fa-solid fa-user me-1"></i> View Portfolio
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Chat Messages Stream -->
                <div class="chat-body-stream flex-grow-1" id="chatStream">
                    @forelse($messages as $msg)
                        @php $isSent = $msg->sender_id === Auth::id(); @endphp
                        <div class="message-bubble {{ $isSent ? 'sent' : 'received' }}">
                            <div class="message-text">{!! nl2br(e($msg->body)) !!}</div>
                            <div class="d-flex align-items-center justify-content-end gap-1 mt-1" style="font-size: 0.65rem; opacity: 0.8;">
                                <span>{{ $msg->created_at->format('g:i A') }}</span>
                                @if($isSent)
                                    <i class="fa-solid {{ $msg->is_read ? 'fa-check-double text-warning' : 'fa-check' }}"></i>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center my-auto py-5 text-muted">
                            <i class="fa-solid fa-paper-plane fa-2x mb-2 text-secondary"></i>
                            <h6 class="fw-bold text-dark mb-1">Start of your conversation</h6>
                            <p class="small text-muted mb-0">Send a friendly greeting to start messaging {{ $targetUser->name }}.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Message Bar -->
                <div class="chat-input-bar">
                    <form id="sendMessageForm" action="{{ route('messages.send', $activeConversation->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <textarea name="body" id="messageInput" class="form-control border-end-0 shadow-none" rows="1" placeholder="Type your message..." required style="resize: none; border-radius: 8px 0 0 8px;"></textarea>
                            <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 0 8px 8px 0;">
                                <i class="fa-solid fa-paper-plane me-1"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center my-auto py-5 text-muted px-4">
                    <i class="fa-solid fa-comments fa-3x mb-3 text-secondary"></i>
                    <h5 class="fw-bold text-dark">Select a conversation to start chatting</h5>
                    <p class="small text-muted mb-3" style="max-width: 420px; margin: 0 auto;">Choose a contact from the left list or click "Send Message" on any platform user's profile to begin a direct conversation.</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var stream = document.getElementById('chatStream');
                if (stream) {
                    stream.scrollTop = stream.scrollHeight;
                }

                var msgInput = document.getElementById('messageInput');
                if (msgInput) {
                    msgInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            document.getElementById('sendMessageForm').submit();
                        }
                    });
                }

                @if($activeConversation)
                    var conversationId = {{ $activeConversation->id }};
                    var authUserId = {{ Auth::id() }};

                    // Auto-refresh message stream every 4 seconds
                    setInterval(function() {
                        fetch("{{ route('messages.fetch', $activeConversation->id) }}")
                            .then(function(res) { return res.json(); })
                            .then(function(data) {
                                if (data && data.messages) {
                                    var html = '';
                                    data.messages.forEach(function(m) {
                                        var isSent = m.sender_id === authUserId;
                                        var timeStr = new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                        var checkIcon = isSent ? (m.is_read ? '<i class="fa-solid fa-check-double text-warning"></i>' : '<i class="fa-solid fa-check"></i>') : '';
                                        
                                        html += '<div class="message-bubble ' + (isSent ? 'sent' : 'received') + '">';
                                        html += '<div class="message-text">' + escapeHtml(m.body).replace(/\n/g, '<br>') + '</div>';
                                        html += '<div class="d-flex align-items-center justify-content-end gap-1 mt-1" style="font-size: 0.65rem; opacity: 0.8;">';
                                        html += '<span>' + timeStr + '</span>' + checkIcon;
                                        html += '</div></div>';
                                    });
                                    if (html) {
                                        var currentScrollBottom = stream.scrollHeight - stream.scrollTop - stream.clientHeight;
                                        stream.innerHTML = html;
                                        if (currentScrollBottom < 100) {
                                            stream.scrollTop = stream.scrollHeight;
                                        }
                                    }
                                }
                            });
                    }, 4000);

                    function escapeHtml(text) {
                        return text
                            .replace(/&/g, "&amp;")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;")
                            .replace(/"/g, "&quot;")
                            .replace(/'/g, "&#039;");
                    }
                @endif
            });
        </script>
    @endpush
</x-app-layout>
