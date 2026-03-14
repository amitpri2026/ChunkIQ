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
            'status' => ['required', 'in:succeeded,failed'],
            'logs'   => ['nullable', 'string'],
        ]);

        $job->markFinished(
            $request->status,
            $request->input('logs', 'Function App reported: ' . $request->status)
        );

        return response()->json(['ok' => true]);
    }
}
