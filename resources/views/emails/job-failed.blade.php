@component('mail::message')
# Pipeline Job Failed

The pipeline job **{{ $job->name }}** has failed.

- **Workspace:** {{ $job->tenant->name ?? 'N/A' }}
- **Type:** {{ $job->type }}
- **Failed at:** {{ $job->finished_at ?? now() }}

Please review the job logs in the admin portal.

@component('mail::button', ['url' => url('/admin/tenants/' . $job->tenant_id)])
View Workspace
@endcomponent

Thanks,
ChunkIQ
@endcomponent
