<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboard;
use App\Http\Controllers\SuperAdmin\TenantAdminController;
use App\Http\Controllers\Tenant\ConnectorController;
use App\Http\Controllers\Tenant\HandoffController;
use App\Http\Controllers\Tenant\PipelineJobController;
use App\Http\Controllers\Tenant\TenantConfigController;
use App\Http\Controllers\Tenant\TenantController;
use App\Http\Controllers\Tenant\TenantInviteController;
use App\Http\Controllers\Tenant\TenantMemberController;
use Illuminate\Support\Facades\Route;

// ─── Public marketing site ────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'));

Route::get('/products/sharepoint',       fn() => view('products.sharepoint'))->name('products.sharepoint');
Route::get('/products/teams',            fn() => view('products.teams'))->name('products.teams');
Route::get('/products/onenote',          fn() => view('products.onenote'))->name('products.onenote');
Route::get('/products/onedrive',         fn() => view('products.onedrive'))->name('products.onedrive');
Route::redirect('/products/semantic-search', '/products/pipeline', 301);
Route::get('/products/pipeline',         fn() => view('products.pipeline'))->name('products.pipeline');
Route::get('/products/enterprise',       fn() => view('products.enterprise'))->name('products.enterprise');
Route::get('/products/enterprise-cloud', fn() => view('products.enterprise-cloud'))->name('products.enterprise-cloud');

// ─── Invite link (public — user may need to register first) ──────────────────
Route::get('/invite/{token}',  [TenantInviteController::class, 'show'])->name('invite.show');
Route::post('/invite/{token}', [TenantInviteController::class, 'accept'])->middleware('auth')->name('invite.accept');

// ─── Main portal (chunkiq.com) ───────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — shows the user's tenant list
    Route::get('/dashboard', [TenantController::class, 'index'])->name('dashboard');

    // Create / join a tenant
    Route::get('/tenants/create',  [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants',        [TenantController::class, 'store'])->name('tenants.store');

    // Open workspace — issues handoff token and redirects to subdomain
    Route::get('/tenants/{tenant}/open', [TenantController::class, 'open'])->name('tenants.open');

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Super Admin panel (/admin/...) ──────────────────────────────────────
    Route::middleware('super.admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/',                                    [SuperDashboard::class, 'index'])->name('dashboard');
        Route::get('/tenants',                             [TenantAdminController::class, 'index'])->name('tenants');
        Route::get('/tenants/{tenant}',                    [TenantAdminController::class, 'show'])->name('tenants.show');
        Route::get('/users',                               [TenantAdminController::class, 'users'])->name('users');
        Route::post('/jobs/{job}/cancel',                  [TenantAdminController::class, 'cancelJob'])->name('jobs.cancel');
        Route::post('/users/{user}/toggle-superadmin',     [TenantAdminController::class, 'toggleSuperAdmin'])->name('users.toggle-superadmin');
    });
});

// ─── Tenant subdomain routes ({slug}.chunkiq.com) ────────────────────────────
$appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? 'chunkiq.com';

// Handoff route — unauthenticated, validates token and logs user in
Route::domain('{tenantSlug}.' . $appHost)
    ->middleware(['web'])
    ->group(function () {
        Route::get('/auth/handoff', [HandoffController::class, 'handle'])->name('tenant.handoff');
    });

Route::domain('{tenantSlug}.' . $appHost)
    ->middleware(['web', 'auth', 'verified', 'tenant.member'])
    ->group(function () {

        // Tenant dashboard
        Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('tenant.dashboard');

        // Members (admin only)
        Route::middleware('tenant.admin')->group(function () {
            Route::get('/members',                            [TenantMemberController::class, 'index'])->name('tenant.members');
            Route::patch('/members/{user}/role',             [TenantMemberController::class, 'updateRole'])->name('tenant.members.role');
            Route::delete('/members/{user}',                 [TenantMemberController::class, 'remove'])->name('tenant.members.remove');

            // Invite links
            Route::post('/invites',                          [TenantInviteController::class, 'store'])->name('tenant.invites.store');

            // Azure / ADLS + Function App configuration
            Route::get('/settings/config',                   [TenantConfigController::class, 'edit'])->name('tenant.config.edit');
            Route::post('/settings/config',                  [TenantConfigController::class, 'update'])->name('tenant.config.update');

            // Connectors (admin manages)
            Route::get('/connectors',                        [ConnectorController::class, 'index'])->name('tenant.connectors.index');
            Route::get('/connectors/create',                 [ConnectorController::class, 'create'])->name('tenant.connectors.create');
            Route::post('/connectors',                       [ConnectorController::class, 'store'])->name('tenant.connectors.store');
            Route::get('/connectors/{connector}/edit',       [ConnectorController::class, 'edit'])->name('tenant.connectors.edit');
            Route::patch('/connectors/{connector}',          [ConnectorController::class, 'update'])->name('tenant.connectors.update');
            Route::delete('/connectors/{connector}',         [ConnectorController::class, 'destroy'])->name('tenant.connectors.destroy');

            // Pipeline Jobs (admin manages)
            Route::get('/jobs',                              [PipelineJobController::class, 'index'])->name('tenant.jobs.index');
            Route::get('/jobs/create',                       [PipelineJobController::class, 'create'])->name('tenant.jobs.create');
            Route::post('/jobs',                             [PipelineJobController::class, 'store'])->name('tenant.jobs.store');
            Route::get('/jobs/{job}',                        [PipelineJobController::class, 'show'])->name('tenant.jobs.show');
            Route::post('/jobs/{job}/run',                   [PipelineJobController::class, 'run'])->name('tenant.jobs.run');
            Route::post('/jobs/{job}/cancel',                [PipelineJobController::class, 'cancel'])->name('tenant.jobs.cancel');
            Route::delete('/jobs/{job}',                     [PipelineJobController::class, 'destroy'])->name('tenant.jobs.destroy');
        });
    });

require __DIR__.'/auth.php';

