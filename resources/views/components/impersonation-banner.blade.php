@if($isImpersonating ?? false)
<div class="bg-amber-500 text-white text-sm py-2 px-4 flex items-center justify-center gap-4 z-50">
    <span>⚠️ You are impersonating <strong>{{ Auth::user()->name }}</strong></span>
    <form method="POST" action="{{ route('impersonation.stop') }}" class="inline">
        @csrf
        <button type="submit" class="underline font-bold hover:text-amber-100 transition-colors">
            Stop Impersonating
        </button>
    </form>
</div>
@endif
