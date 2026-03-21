<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemConfigController extends Controller
{
    // All keys manageable by super admin
    public const KEYS = [
        'fa_url' => [
            'label'       => 'Function App URL',
            'description' => 'The full HTTPS URL of the ChunkIQ Azure Function App (run_pipeline endpoint).',
            'placeholder' => 'https://chunkiq-cloud-001.azurewebsites.net/api/run_pipeline',
            'encrypted'   => true,
        ],
        'fa_key' => [
            'label'       => 'Function App Key',
            'description' => 'The x-functions-key secret. Get it from Azure Portal → Function App → Functions → run_pipeline_http → Function Keys.',
            'placeholder' => 'Paste function key…',
            'encrypted'   => true,
        ],
    ];

    public function edit(): View
    {
        $configs = [];
        foreach (self::KEYS as $key => $meta) {
            $value = SystemConfig::get($key);
            $configs[$key] = array_merge($meta, [
                'value'  => $value,
                'masked' => $value
                    ? str_repeat('*', max(0, strlen($value) - 4)) . substr($value, -4)
                    : null,
            ]);
        }

        return view('admin.system-config', compact('configs'));
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (array_keys(self::KEYS) as $key) {
            $rules[$key] = ['nullable', 'string', 'max:1000'];
        }

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null && $value !== '') {
                SystemConfig::set($key, $value);
            }
        }

        return redirect()->route('admin.system-config')->with('success', 'Platform settings saved.');
    }
}
