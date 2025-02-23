<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Models\User;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use SetupTrait;


    static function indexSuccessProvider()
    {
        return [
            'Admin has access' => [RoleEnum::ADMIN, 200],
            'Moderator has access' => [RoleEnum::MODERATOR, 200],
            'Customer has no access' => [RoleEnum::CUSTOMER, 403],
        ];


    }

    public function test_user_create()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
    }

    public function test_user_delete()
    {
        $user = User::factory()->create();

        $user->forceDelete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_verified_user()
    {
        $response = $this->post(route('register'), [
            'name' => 'John ',
            'lastname' => 'Doe',
            'phone' => '0123456789',
            'email' => 'johndoee@gmail.com',
            'birthday' => '01/01/1990',
            'password' => 'password',
        ]);;
        $response->assertStatus(302);


    }

    public function test_verified_user_incorrect()
    {
        $response = $this->post(route('register'), [
            'name' => 'John ',
            'lastname' => 'Doe',
            'phone' => '0123456780000009',
            'email' => 'johndoeegmail.com',
            'birthday' => '01/01/2025',
            'password' => 'password0000000000000000000000000000000000000000000000',
        ]);;
        $response->assertStatus(422);
    }


    public function test_user_login()
    {
        User::factory()->create([
            'name' => 'John ',
            'lastname' => 'Doe',
            'phone' => '0123456789',
            'email' => 'johndoee@gmail.com',
            'birthday' => '01/01/1990',
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'johndoee@gmail.com',
            'password' => 'password',
        ]);
        $response->assertStatus(302);
    }

    public function test_user_login_negative()
    {
        User::factory()->create([
            'name' => 'John ',
            'lastname' => 'Doe',
            'phone' => '0123456789',
            'email' => 'johndoee@gmail.com',
            'birthday' => '01/01/1990',
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'invalid_email',
            'password' => '123',
        ]);
        $response->assertStatus(422);
    }

  #[Dataprovider('indexSuccessProvider')]
public function test_login_dashboard(RoleEnum $role, int $status)
  {
      $user = $this->user();
      $user -> assignRole($role);

      $this->assertDatabaseHas('roles', [
          'name' => $role->value,
      ]);

     $response = $this->actingAs($user)->get(route('admin.dashboard'));
     $response->assertStatus($status);
  }




}

