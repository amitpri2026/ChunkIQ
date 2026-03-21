@component('mail::message')
# New User Registered

**{{ $user->name }}** ({{ $user->email }}) just created a ChunkIQ account.

@component('mail::button', ['url' => url('/admin/users')])
View Users
@endcomponent

Thanks,
ChunkIQ
@endcomponent
