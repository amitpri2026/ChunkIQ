@component('mail::message')
# Invite Accepted

**{{ $user->name }}** ({{ $user->email }}) has accepted their invitation and joined **{{ $tenant->name }}**.

@component('mail::button', ['url' => url('/admin/tenants/' . $tenant->id)])
View Workspace
@endcomponent

Thanks,
ChunkIQ
@endcomponent
