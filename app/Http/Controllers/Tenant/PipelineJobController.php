<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PipelineJob;
use App\Services\AzureFunctionTrigger;
use App\Support\TenantManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PipelineJobController extends Controller
{
    public function __construct(
        private TenantManager $manager,
        private AzureFunctionTrigger $trigger,
    ) {}

    public function index(): View
    {
        $tenant = $this->manager->get();
        $jobs   = $tenant->pipelineJobs()
            ->with(['connector', 'triggeredBy'])
            ->latest()
            ->get();

        return view('tenant.jobs.index', compact('tenant', 'jobs'));
    }

    public function create(): View
    {
        $tenant     = $this->manager->get();
        $connectors = $tenant->connectors()->where('status', 'active')->get();

        return view('tenant.jobs.create', compact('tenant', 'connectors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'type'         => ['required', 'in:ingestion,processing'],
            'connector_id' => ['nullable', 'integer'],
        ]);

        $tenant = $this->manager->get();

        // Validate connector belongs to this tenant
        if ($request->connector_id) {
            $connector = $tenant->connectors()->findOrFail($request->connector_id);
        }

        $job = $tenant->pipelineJobs()->create([
            'connector_id'   => $request->connector_id ?: null,
            'triggered_by'   => $request->user()->id,
            'name'           => $request->name,
            'type'           => $request->type,
            'status'         => 'pending',
            'config'         => json_encode(['notes' => $request->input('notes', '')]),
            'callback_token' => PipelineJob::generateCallbackToken(),
        ]);

        return redirect()->route('tenant.jobs.show', ['tenantSlug' => $tenant->slug, 'job' => $job->id])
            ->with('success', 'Job created. Click "Run" to trigger it.');
    }

    public function show(Request $request): View
    {
        $id     = (int) $request->route('job');
        $tenant = $this->manager->get();
        $job    = PipelineJob::findOrFail($id);
        abort_if($job->tenant_id !== $tenant->id, 403);

        $job->load(['connector', 'triggeredBy']);

        return view('tenant.jobs.show', compact('tenant', 'job'));
    }

    public function run(Request $request): JsonResponse|RedirectResponse
    {
        $id     = (int) $request->route('job');
        $tenant = $this->manager->get();
        $job    = PipelineJob::findOrFail($id);
        abort_if($job->tenant_id !== $tenant->id, 403);

        if ($job->status === 'running') {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Job is already running.'], 422);
            }
            return back()->withErrors(['run' => 'Job is already running.']);
        }

        if ($tenant->atDocumentLimit()) {
            $limit = number_format($tenant->documentLimit());
            $msg   = "Document limit reached ({$limit} docs on {$tenant->planLabel()} plan). Please upgrade to run more jobs.";
            if ($request->wantsJson()) {
                return response()->json(['error' => $msg], 422);
            }
            return back()->withErrors(['run' => $msg]);
        }

        // Refresh callback token on each run
        $job->update(['callback_token' => PipelineJob::generateCallbackToken()]);
        $job->refresh();

        $this->trigger->trigger($job);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'status' => $job->fresh()->status]);
        }

        return redirect()->route('tenant.jobs.show', ['tenantSlug' => $tenant->slug, 'job' => $job->id])
            ->with('success', 'Job triggered.');
    }

    public function status(Request $request): JsonResponse
    {
        $id     = (int) $request->route('job');
        $tenant = $this->manager->get();
        $job    = PipelineJob::findOrFail($id);
        abort_if($job->tenant_id !== $tenant->id, 403);

        return response()->json([
            'status'      => $job->status,
            'label'       => PipelineJob::STATUSES[$job->status]['label'] ?? $job->status,
            'color'       => $job->getStatusBadgeColor(),
            'logs'        => $job->logs,
            'started_at'  => $job->started_at?->format('M j, H:i'),
            'finished_at' => $job->finished_at?->format('M j, H:i'),
        ]);
    }

    public function cancel(Request $request): RedirectResponse
    {
        $id     = (int) $request->route('job');
        $tenant = $this->manager->get();
        $job    = PipelineJob::findOrFail($id);
        abort_if($job->tenant_id !== $tenant->id, 403);

        if (!in_array($job->status, ['pending', 'running'])) {
            return back()->withErrors(['cancel' => 'Cannot cancel a job that is not pending or running.']);
        }

        $job->markFinished('cancelled', 'Cancelled by ' . $request->user()->name . '.');

        return back()->with('success', 'Job cancelled.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $id     = (int) $request->route('job');
        $tenant = $this->manager->get();
        $job    = PipelineJob::findOrFail($id);
        abort_if($job->tenant_id !== $tenant->id, 403);

        $job->delete();

        return redirect()->route('tenant.jobs.index', ['tenantSlug' => $tenant->slug])
            ->with('success', 'Job deleted.');
    }
}
