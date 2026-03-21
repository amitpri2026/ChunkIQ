@component('mail::message')
# New Workspace Created

A new workspace **{{ $tenant->name }}** has been created.

- **Slug:** {{ $tenant->slug }}
- **Owner:** {{ $tenant->owner->name ?? 'N/A' }} ({{ $tenant->owner->email ?? '' }})

@component('mail::button', ['url' => url('/admin/tenants/' . $tenant->id)])
View Workspace
@endcomponent

Thanks,
ChunkIQ
@endcomponent
