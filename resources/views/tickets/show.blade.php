<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $ticket->subject }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    @php
                        $pc = ['low'=>'green','medium'=>'yellow','high'=>'red'][$ticket->priority] ?? 'gray';
                        $sc = ['open'=>'blue','in_progress'=>'yellow','closed'=>'gray'][$ticket->status] ?? 'gray';
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $pc }}-100 text-{{ $pc }}-700">{{ ucfirst($ticket->priority) }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $sc }}-100 text-{{ $sc }}-700">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                    <span class="text-xs text-gray-400">Opened {{ $ticket->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <!-- Opening message -->
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                        {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $ticket->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $ticket->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    <span class="ml-auto text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Opening message</span>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</p>
            </div>

            <!-- Replies -->
            @foreach($ticket->replies as $reply)
            <div class="{{ $reply->is_admin_reply ? 'bg-blue-50 border-blue-100' : 'bg-white border-gray-100' }} rounded-xl border p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 {{ $reply->is_admin_reply ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $reply->user->name }}
                            @if($reply->is_admin_reply)
                            <span class="ml-1 text-xs font-normal text-blue-500">· Support Team</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-400">{{ $reply->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</p>
            </div>
            @endforeach

            <!-- Reply form / Closed state -->
            @if($ticket->status !== 'closed')
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Add a Reply</h3>
                <form method="POST" action="{{ route('tickets.reply', $ticket) }}">
                    @csrf
                    <textarea name="message" rows="4"
                              class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 resize-none"
                              placeholder="Type your reply..."></textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <div class="flex items-center gap-3 mt-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Send Reply
                        </button>
                        <form method="POST" action="{{ route('tickets.close', $ticket) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-gray-500 text-sm font-medium hover:text-red-500 transition-colors"
                                    onclick="return confirm('Close this ticket?')">
                                Close Ticket
                            </button>
                        </form>
                    </div>
                </form>
            </div>
            @else
            <div class="text-center py-6 bg-gray-50 rounded-xl border border-gray-100 text-sm text-gray-400">
                This ticket has been closed.
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
