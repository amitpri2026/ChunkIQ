<?php

namespace App\Support;

use App\Models\Tenant;

class TenantManager
{
    private ?Tenant $current = null;

    public function set(Tenant $tenant): void
    {
        $this->current = $tenant;
    }

    public function get(): ?Tenant
    {
        return $this->current;
    }

    public function check(): bool
    {
        return $this->current !== null;
    }

    public function forget(): void
    {
        $this->current = null;
    }
}
