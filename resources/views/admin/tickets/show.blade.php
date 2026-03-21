<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tickets.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Super Admin</span>
                    <span class="text-xs text-gray-400">Ticket #{{ $ticket->id }}</span>
                </div>
                <h2 class="font-semibold text-xl text-gray-800">{{ $ticket->subject }}</h2>
            </div>
            <!-- Status changer -->
            <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <select name="status"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:border-blue-400">
                    @foreach(['open'=>'Open','in_progress'=>'In Progress','closed'=>'Closed'] as $val => $label)
                    <option value="{{ $val }}" {{ $ticket->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded-lg hover:bg-gray-900 transition-colors">
                    Update
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <!-- User info card -->
            <div class="bg-white rounded-xl border border-gray-100 p-4 mb-4 flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $ticket->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $ticket->user->email }}</p>
                </div>
                @php $pc = ['low'=>'green','medium'=>'yellow','high'=>'red'][$ticket->priority] ?? 'gray'; @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $pc }}-100 text-{{ $pc }}-700">
                    {{ ucfirst($ticket->priority) }} priority
                </span>
                <span class="text-xs text-gray-400">{{ $ticket->created_at->format('M j, Y') }}</span>
            </div>

            <div class="space-y-3">
                <!-- Opening message -->
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 font-bold text-xs">
                            {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                        </div>
                        <p class="text-sm font-semibold text-gray-700">{{ $ticket->user->name }}</p>
                        <p class="text-xs text-gray-400 ml-1">{{ $ticket->created_at->diffForHumans() }}</p>
                        <span class="ml-auto text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Opening message</span>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</p>
                </div>

                <!-- Replies -->
                @foreach($ticket->replies as $reply)
                <div class="{{ $reply->is_admin_reply ? 'bg-blue-50 border-blue-100' : 'bg-white border-gray-100' }} rounded-xl border p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 {{ $reply->is_admin_reply ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                        </div>
                        <p class="text-sm font-semibold text-gray-700">
                            {{ $reply->user->name }}
                            @if($reply->is_admin_reply)<span class="ml-1 text-xs font-normal text-blue-500">· Support Team</span>@endif
                        </p>
                        <p class="text-xs text-gray-400 ml-1">{{ $reply->created_at->diffForHumans() }}</p>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</p>
                </div>
                @endforeach

                <!-- Admin Reply Form -->
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Reply as Support Team</h3>
                    <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}">
                        @csrf
                        <textarea name="message" rows="4"
                                  class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 resize-none"
                                  placeholder="Type your support reply..."></textarea>
                        @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Send Reply
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
