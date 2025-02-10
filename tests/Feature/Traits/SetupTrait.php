<?php

namespace Tests\Traits;

namespace Tests\Feature\Traits;

use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\PermissionsAndRolesSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait SetupTrait
{
    use RefreshDatabase;

    protected function afterRefreshingDatabase()
    {
        $this->seed([
            PermissionsAndRolesSeeder::class,
            UserSeeder::class,
        ]);
    }

    protected function user(RoleEnum $role = RoleEnum::ADMIN): User
    {
        return User::role($role->value)->firstOrFail();
    }
}
