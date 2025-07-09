<x-app-layout>
    @push('styles')
        <style>
            .message-content pre {
                background-color: #f4f4f4;
                padding: 1rem;
                border-radius: 0.5rem;
                overflow-x: auto;
                white-space: pre-wrap;
            }

            .message-content code {
                background-color: #f4f4f4;
                padding: 0.2rem 0.4rem;
                border-radius: 0.25rem;
                font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            }

            .typing-indicator {
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .typing-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: #6b7280;
                animation: typing 1.4s infinite ease-in-out;
            }

            .typing-dot:nth-child(1) {
                animation-delay: -0.32s;
            }

            .typing-dot:nth-child(2) {
                animation-delay: -0.16s;
            }

            @keyframes typing {

                0%,
                80%,
                100% {
                    transform: scale(0);
                }

                40% {
                    transform: scale(1);
                }
            }
        </style>
    @endpush

    <div class="flex bg-gray-50 -mt-16" style="height: calc(100vh - 64px);">

        <div class="w-80 bg-white border-r border-gray-200 flex flex-col">

            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Chat History</h2>
                    <button id="newChatBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        New Chat
                    </button>
                </div>

                <!-- Model Selection -->
                <div class="mb-4">
                    <label for="modelSelect" class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                    <select id="modelSelect"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                        <option value="gpt-4">GPT-4</option>
                        <option value="gpt-4-turbo">GPT-4 Turbo</option>
                    </select>
                </div>
            </div>


            <div class="flex-1 overflow-y-auto">
                <div id="conversationsList" class="p-2">

                </div>
            </div>
        </div>


        <div class="flex-1 flex flex-col">

            <div class="bg-white border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 id="chatTitle" class="text-xl font-semibold text-gray-900">Attirio</h1>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span>Online</span>
                            <span id="currentModel" class="ml-2 px-2 py-1 bg-gray-100 rounded-lg text-xs">GPT-3.5
                                Turbo</span>
                        </div>
                    </div>
                    <button id="deleteConversationBtn"
                        class="ml-10 text-red-600 hover:text-red-700 px-3 py-2 rounded-lg text-sm hidden">
                        <i class="fas fa-trash"></i>
                        Delete
                    </button>
                </div>
            </div>


            <div class="flex-1 overflow-y-auto p-4" id="chatMessages">
                <div id="welcomeMessage" class="text-center text-gray-500 mt-20">
                    <!-- <div class="text-6xl mb-4">🤖</div> -->
                    <h2 class="text-2xl font-semibold mb-2">Welcome Attirio</h2>
                    <p>Start a new conversation by typing a message below.</p>
                </div>
            </div>


            <div id="typingIndicator" class="px-4 pb-2 hidden">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-white text-sm">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="bg-gray-100 rounded-2xl px-4 py-3">
                        <div class="typing-indicator">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-white border-t border-gray-200 p-4">
                <form id="messageForm" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <textarea id="messageInput" name="message" placeholder="Type your message..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="1" style="max-height: 120px;"></textarea>
                    </div>
                    <button type="submit" id="sendButton"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-6 py-3 rounded-2xl flex items-center gap-2 transition-colors"
                        disabled>
                        <i class="fas fa-paper-plane"></i>
                        <span>Send</span>
                    </button>
                </form>
            </div>
        </div>
    </div>


    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
            <h3 class="text-lg font-semibold mb-4">Delete Conversation</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this conversation? This action cannot be
                undone.</p>
            <div class="flex gap-3">
                <button id="confirmDelete"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Delete</button>
                <button id="cancelDelete"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Cancel</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        console.log('Script block starting...');

        // Check if jQuery is loaded
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded!');
            alert('jQuery failed to load. Please refresh the page.');
        } else {
            console.log('jQuery loaded successfully:', jQuery.fn.jquery);
        }

        $(document).ready(function () {
            console.log('Chat page loaded, initializing...');
            console.log('CSRF token available:', $('meta[name="csrf-token"]').attr('content') ? 'Yes' : 'No');

            let currentConversationId = null;
            let availableModels = [];

            // Auto-resize textarea using event delegation
            $(document).on('input', '#messageInput', function () {
                console.log('Input changed:', this.value);
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';

                // Enable/disable send button - allow sending if there's text (conversation will be created automatically)
                const isDisabled = !this.value.trim();
                $('#sendButton').prop('disabled', isDisabled);
                console.log('Send button disabled:', isDisabled);
            });

            // Load available models
            function loadModels() {
                console.log('Loading models...');
                $.get('models', function (models) {
                    console.log('Models loaded:', models);
                    availableModels = models;
                    const $select = $('#modelSelect');
                    $select.empty();
                    models.forEach(model => {
                        $select.append(`<option value="${model.id}">${model.name}</option>`);
                    });

                    // Set initial current model display
                    const defaultModel = models.find(m => m.id === 'gpt-3.5-turbo') || models[0];
                    if (defaultModel) {
                        $('#currentModel').text(defaultModel.name);
                    }
                    console.log('Model selection updated');
                }).fail(function (xhr, status, error) {
                    console.error('Failed to load models:', error, xhr.responseText);
                    // Fallback models if API fails
                    availableModels = [{
                        id: 'gpt-3.5-turbo',
                        name: 'GPT-3.5 Turbo'
                    },
                    {
                        id: 'gpt-4',
                        name: 'GPT-4'
                    },
                    {
                        id: 'gpt-4-turbo',
                        name: 'GPT-4 Turbo'
                    }
                    ];
                    const $select = $('#modelSelect');
                    $select.empty();
                    availableModels.forEach(model => {
                        $select.append(`<option value="${model.id}">${model.name}</option>`);
                    });
                    $('#currentModel').text('GPT-3.5 Turbo');
                    console.log('Using fallback models');
                });
            }

            // Load conversations
            function loadConversations() {
                $.get('/conversations', function (conversations) {
                    const $list = $('#conversationsList');
                    $list.empty();

                    conversations.forEach(conversation => {
                        const $item = $(`
                            <div class="conversation-item p-3 hover:bg-gray-50 rounded-lg cursor-pointer border-l-4 border-transparent hover:border-blue-500 transition-colors" data-id="${conversation.id}">
                                <div class="font-medium text-gray-900 truncate">${conversation.title}</div>
                                <div class="text-sm text-gray-500 flex items-center justify-between mt-1">
                                    <span>${conversation.updated_at}</span>
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs">${conversation.model}</span>
                                </div>
                            </div>
                        `);
                        $list.append($item);
                    });
                });
            }

            // Load conversation messages
            function loadConversation(conversationId) {
                currentConversationId = conversationId;

                $.get(`/conversations/${conversationId}`, function (data) {
                    const conversation = data.conversation;
                    const messages = data.messages;

                    $('#chatTitle').text(conversation.title);
                    $('#currentModel').text(availableModels.find(m => m.id === conversation.model)?.name ||
                        conversation.model);
                    $('#deleteConversationBtn').removeClass('hidden');
                    $('#welcomeMessage').hide();

                    // Update model selector
                    $('#modelSelect').val(conversation.model);

                    // Clear and load messages
                    const $chatMessages = $('#chatMessages');
                    $chatMessages.empty();

                    messages.forEach(message => {
                        appendMessage(message);
                    });

                    scrollToBottom();

                    // Highlight selected conversation
                    $('.conversation-item').removeClass('bg-blue-50 border-blue-500').addClass(
                        'border-transparent');
                    $(`.conversation-item[data-id="${conversationId}"]`).addClass(
                        'bg-blue-50 border-blue-500').removeClass('border-transparent');
                });
            }

            // Append message to chat
            function appendMessage(message) {
                const isUser = message.role === 'user';
                const avatarIcon = isUser ? 'fa-user' : 'fa-robot';
                const avatarBg = isUser ? 'bg-blue-600' : 'bg-gray-600';
                const messageBg = isUser ? 'bg-blue-600 text-white' : 'bg-gray-100';
                const alignment = isUser ? 'ml-auto' : 'mr-auto';

                const $message = $(`
                    <div class="flex items-start gap-3 mb-4 ${isUser ? 'flex-row-reverse' : ''}">
                        <div class="w-8 h-8 ${avatarBg} rounded-full flex items-center justify-center text-white text-sm">
                            <i class="fas ${avatarIcon}"></i>
                        </div>
                        <div class="max-w-xs lg:max-w-md xl:max-w-lg ${alignment}">
                            <div class="${messageBg} rounded-2xl px-4 py-3">
                                <div class="message-content">${formatMessage(message.content)}</div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1 ${isUser ? 'text-right' : 'text-left'}">
                                ${message.created_at}
                            </div>
                        </div>
                    </div>
                `);

                $('#chatMessages').append($message);
            }

            // Format message content (basic markdown support)
            function formatMessage(content) {
                return content
                    .replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>')
                    .replace(/`([^`]+)`/g, '<code>$1</code>')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>')
                    .replace(/\n/g, '<br>');
            }

            // Scroll to bottom
            function scrollToBottom() {
                const $chatMessages = $('#chatMessages');
                $chatMessages.scrollTop($chatMessages[0].scrollHeight);
            }

            // New chat button event
            $(document).on('click', '#newChatBtn', function () {
                console.log('New Chat button clicked!');
                const selectedModel = $('#modelSelect').val() || 'gpt-3.5-turbo';
                console.log('Creating new chat with model:', selectedModel);

                $.post('/conversations', {
                    model: selectedModel,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function (conversation) {
                    console.log('New conversation created:', conversation);
                    currentConversationId = conversation.id;
                    $('#chatTitle').text(conversation.title);

                    let modelName = conversation.model;
                    if (availableModels && availableModels.length > 0) {
                        const model = availableModels.find(m => m.id === conversation.model);
                        modelName = model ? model.name : conversation.model;
                    }
                    $('#currentModel').text(modelName);

                    $('#deleteConversationBtn').removeClass('hidden');
                    $('#welcomeMessage').hide();
                    $('#chatMessages').empty();
                    loadConversations();

                    // Focus on input with slight delay
                    setTimeout(function () {
                        $('#messageInput').focus();
                    }, 100);
                }).fail(function (xhr, status, error) {
                    console.error('Failed to create conversation:', error, xhr.responseText);
                    alert('Failed to create new conversation. Please try again.');
                });
            });

            // Send message form event
            $(document).on('submit', '#messageForm', function (e) {
                e.preventDefault();
                console.log('Form submitted!');

                const message = $('#messageInput').val().trim();
                console.log('Message to send:', message);
                if (!message) {
                    console.log('No message to send');
                    return;
                }

                // If no conversation exists, create one first
                if (!currentConversationId) {
                    console.log('No conversation exists, creating one...');
                    const selectedModel = $('#modelSelect').val() || 'gpt-3.5-turbo';

                    $.post('/conversations', {
                        model: selectedModel,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }, function (conversation) {
                        console.log('Conversation created for message:', conversation);
                        currentConversationId = conversation.id;
                        $('#chatTitle').text(conversation.title);
                        $('#currentModel').text(availableModels.find(m => m.id === conversation
                            .model)?.name || conversation.model);
                        $('#deleteConversationBtn').removeClass('hidden');
                        $('#welcomeMessage').hide();

                        // Now send the message
                        sendMessage(message);
                        loadConversations();
                    }).fail(function (xhr, status, error) {
                        console.error('Failed to create conversation for message:', error);
                        alert('Failed to create conversation. Please try again.');
                    });
                } else {
                    console.log('Using existing conversation:', currentConversationId);
                    sendMessage(message);
                }
            });

            // Helper function to send message
            function sendMessage(message) {
                // Clear input and disable send button
                $('#messageInput').val('').trigger('input');
                $('#sendButton').prop('disabled', true);

                // Show typing indicator
                $('#typingIndicator').removeClass('hidden');
                scrollToBottom();

                $.post('/chat/send', {
                    conversation_id: currentConversationId,
                    message: message,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function (response) {
                    if (response.success) {
                        // Hide typing indicator
                        $('#typingIndicator').addClass('hidden');

                        // Update chat title if it changed
                        if (response.conversation.title) {
                            $('#chatTitle').text(response.conversation.title);
                        }

                        // Add messages to chat
                        appendMessage(response.user_message);
                        appendMessage(response.assistant_message);
                        scrollToBottom();

                        // Reload conversations to update timestamps
                        loadConversations();
                    } else {
                        alert('Error: ' + response.error);
                        $('#typingIndicator').addClass('hidden');
                    }
                }).fail(function () {
                    alert('An error occurred while sending the message.');
                    $('#typingIndicator').addClass('hidden');
                });
            }

            // Click on conversation
            $(document).on('click', '.conversation-item', function () {
                const conversationId = $(this).data('id');
                loadConversation(conversationId);
            });

            // Delete conversation
            $('#deleteConversationBtn').click(function () {
                $('#deleteModal').removeClass('hidden').addClass('flex');
            });

            $('#cancelDelete').click(function () {
                $('#deleteModal').addClass('hidden').removeClass('flex');
            });

            $('#confirmDelete').click(function () {
                if (currentConversationId) {
                    $.ajax({
                        url: `/conversations/${currentConversationId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            $('#deleteModal').addClass('hidden').removeClass('flex');
                            currentConversationId = null;
                            $('#chatTitle').text('Attirio');
                            $('#currentModel').text('GPT-3.5 Turbo');
                            $('#deleteConversationBtn').addClass('hidden');
                            $('#chatMessages').empty();
                            $('#welcomeMessage').show();
                            loadConversations();
                        }
                    });
                }
            });

            // Model change event using delegation
            $(document).on('change', '#modelSelect', function () {
                const selectedModel = $(this).val();
                console.log('Model changed to:', selectedModel);

                let modelName = selectedModel;
                if (availableModels && availableModels.length > 0) {
                    const model = availableModels.find(m => m.id === selectedModel);
                    modelName = model ? model.name : selectedModel;
                }

                $('#currentModel').text(modelName);
                console.log('Current model display updated to:', modelName);
            });




            // loadModels();
            loadConversations();


            setTimeout(function () {
                $('#messageInput').focus();
                console.log('Focused on message input');
            }, 100);
        });
    </script>
</x-app-layout>