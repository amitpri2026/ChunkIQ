<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800">Create Support Ticket</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <form method="POST" action="{{ route('tickets.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-colors"
                               placeholder="Brief description of your issue" required maxlength="150">
                        @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-colors">
                            <option value="low" {{ old('priority')=='low' ? 'selected' : '' }}>Low — General question or feature request</option>
                            <option value="medium" {{ old('priority','medium')=='medium' ? 'selected' : '' }}>Medium — Something isn't working as expected</option>
                            <option value="high" {{ old('priority')=='high' ? 'selected' : '' }}>High — Pipeline is blocked or data loss</option>
                        </select>
                        @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="6"
                                  class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-colors resize-none"
                                  placeholder="Describe your issue in detail. Include any error messages, steps to reproduce, and what you expected to happen."
                                  required maxlength="5000">{{ old('message') }}</textarea>
                        @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Submit Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
