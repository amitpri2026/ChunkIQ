<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 mb-2">Super Admin</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Support Tickets</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <!-- Status Tabs -->
            <div class="flex items-center gap-1 mb-5 bg-gray-100 p-1 rounded-xl w-fit">
                @foreach([''=>'All ('.$counts['all'].')','open'=>'Open ('.$counts['open'].')','in_progress'=>'In Progress ('.$counts['in_progress'].')','closed'=>'Closed ('.$counts['closed'].')'] as $val => $label)
                <a href="{{ route('admin.tickets.index', $val ? ['status' => $val] : []) }}"
                   class="{{ request('status', '') === $val ? 'bg-white shadow-sm text-gray-800 font-semibold' : 'text-gray-500 hover:text-gray-700' }} px-4 py-1.5 rounded-lg text-sm transition-all">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                @if($tickets->isEmpty())
                <div class="text-center py-16 text-gray-400 text-sm">No tickets found.</div>
                @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">User</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Subject</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Priority</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Created</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($tickets as $ticket)
                        @php
                            $pc = ['low'=>'green','medium'=>'yellow','high'=>'red'][$ticket->priority] ?? 'gray';
                            $sc = ['open'=>'blue','in_progress'=>'yellow','closed'=>'gray'][$ticket->status] ?? 'gray';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-400 text-xs font-mono">#{{ $ticket->id }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-800">{{ $ticket->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $ticket->user->email }}</p>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $ticket->subject }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $pc }}-100 text-{{ $pc }}-700">{{ ucfirst($ticket->priority) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $sc }}-100 text-{{ $sc }}-700">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $ticket->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-500 hover:text-blue-700 font-medium text-xs">View →</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-3 border-t border-gray-100">{{ $tickets->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
