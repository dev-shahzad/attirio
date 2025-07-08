<x-app-layout>
    <div class="max-w-4xl mx-auto h-screen flex flex-col bg-gray-50">
        <!-- Header -->
        <header class="flex justify-end items-center p-4 border-b border-gray-200 bg-white shadow-sm">
            <!-- Theme Toggle -->
            <button class="p-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors"
                aria-label="{{ __('Toggle theme') }}">
                <i class="fas fa-moon"></i>
            </button>
        </header>

        <!-- Chat Container -->
        <div id="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            @if(isset($messages) && count($messages) > 0)
                @foreach($messages as $message)
                    <div class="flex {{ $message['type'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs md:max-w-md lg:max-w-lg xl:max-w-xl rounded-lg p-4 shadow-sm 
                                                    {{ $message['type'] === 'user'
                    ? 'bg-indigo-600 text-white rounded-br-none'
                    : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                            <div class="message-content">
                                {{ $message['content'] }}
                            </div>
                            <div class="text-xs mt-1 {{ $message['type'] === 'user' ? 'text-indigo-200' : 'text-gray-500' }}">
                                {{ $message['created_at']->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Typing Indicator -->
        <div id="typingIndicator" class="hidden mx-4 mb-4">
            <div class="flex justify-start">
                <div class="bg-gray-100 p-3 rounded-lg rounded-bl-none shadow-sm w-20">
                    <div class="flex space-x-1 justify-center">
                        <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="p-4 border-t border-gray-200 bg-white shadow-lg">
            <form id="messageForm" action="{{ route('chat.send') }}" method="POST" class="w-full">
                @csrf
                <div class="flex items-center bg-gray-100 rounded-xl p-2 shadow-inner">
                    <input type="text" name="message"
                        class="flex-1 bg-transparent border-none focus:ring-0 text-gray-800 placeholder-gray-500 px-3 py-2"
                        placeholder="{{ __('Type your message...') }}" aria-label="{{ __('Message input') }}" required>

                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors flex items-center space-x-2 ml-2">
                        <span>{{ __('Send') }}</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>