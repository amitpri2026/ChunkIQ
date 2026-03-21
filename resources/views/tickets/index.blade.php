<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Support Tickets</h2>
            <a href="{{ route('tickets.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                + New Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            @if($tickets->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                <div class="text-4xl mb-3">🎫</div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No tickets yet</h3>
                <p class="text-gray-400 text-sm mb-5">Having an issue? Open a support ticket and we'll help you out.</p>
                <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
                    Create Your First Ticket
                </a>
            </div>
            @else
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Subject</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Priority</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Created</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $ticket->subject }}</td>
                            <td class="px-4 py-3">
                                @php $pc = ['low'=>'green','medium'=>'yellow','high'=>'red'][$ticket->priority] ?? 'gray'; @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $pc }}-100 text-{{ $pc }}-700">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php $sc = ['open'=>'blue','in_progress'=>'yellow','closed'=>'gray'][$ticket->status] ?? 'gray'; @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $sc }}-100 text-{{ $sc }}-700">
                                    {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $ticket->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-500 hover:text-blue-700 font-medium text-xs">View →</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-3 border-t border-gray-100">{{ $tickets->links() }}</div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
