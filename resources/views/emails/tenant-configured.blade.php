@component('mail::message')
# Workspace Azure Setup Completed

**{{ $tenant->name }}** has completed their Azure configuration wizard.

Their pipeline is ready to run.

@component('mail::button', ['url' => url('/admin/tenants/' . $tenant->id)])
View Workspace
@endcomponent

Thanks,
ChunkIQ
@endcomponent
