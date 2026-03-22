<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PipelineJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobCallbackController extends Controller
{
    // Called by Azure Function App when a job completes
    // URL: POST /api/jobs/{token}/callback
    public function __invoke(Request $request, string $token): JsonResponse
    {
        $job = PipelineJob::where('callback_token', $token)->firstOrFail();

        $request->validate([
            'status'  => ['required', 'in:succeeded,failed'],
            'message' => ['nullable', 'string'],
            'logs'    => ['nullable', 'string'],
        ]);

        $summary = $request->input('message') ?? ('Function App reported: ' . $request->status);
        $detail  = $request->input('logs', '');

        $job->markFinished($request->status, $summary, $detail);

        return response()->json(['ok' => true]);
    }
}
