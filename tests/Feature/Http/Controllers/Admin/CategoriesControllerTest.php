<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Models\Category;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;

class CategoriesControllerTest extends TestCase
{
    use SetupTrait;

    static public function indexSuccessProvider(): array
    {
        return [
            'admin role' => [
                'role' => RoleEnum::ADMIN,
                'categoriesQty' => 3,
            ],
            'moderator role' => [
                'role' => RoleEnum::MODERATOR,
                'categoriesQty' => 3
            ],
            '10 categories' => [
                'role' => RoleEnum::MODERATOR,
                'categoriesQty' => 10
            ],
        ];
    }

    #[DataProvider('indexSuccessProvider')]
    public function test_allows_to_see_categories(RoleEnum $role, int $categoriesQty): void
    {
        $categories = Category::factory($categoriesQty)->create();

        $response = $this->actingAs($this->user($role))
            ->get(route('admin.categories.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.index');
        $response->assertSeeInOrder($categories->pluck('name')->toArray());
    }

    public function test_not_allows_to_see_categories_for_customer_role(): void
    {
        $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))
            ->get(route('admin.categories.index'));

        $response->assertForbidden();
    }

    public function test_it_creates_category_with_valid_data(): void
    {
        $categoryData = Category::factory()->makeOne()->toArray();

        $this->assertDatabaseEmpty('categories');

        $response = $this->actingAs($this->user())
            ->post(route('admin.categories.store'), $categoryData);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.categories.index');

        $this->assertDatabaseHas('categories', $categoryData);

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', fn ($collection) => $collection->first()['message'] === "Category '$categoryData[name]' is created");
    }

    public function test_it_creates_category_with_parent_id(): void
    {
        $parent = Category::factory()->createOne();
        $category = Category::factory()->makeOne([
            'parent_id' => $parent->id
        ])->toArray();

        $this->assertDatabaseMissing('categories', $category);

        $this->actingAs($this->user())
            ->post(route('admin.categories.store'), $category);

        $this->assertDatabaseHas('categories', $category);
    }

    public function test_it_deletes_category(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', ['id' => $category->id,]);

        $response = $this->actingAs($this->user())->delete(route('admin.categories.destroy', $category));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

    }
    public function test_it_deletes_category_negative(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', ['id' => $category->id,]);

        $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))->delete(route('admin.categories.destroy', $category));

        $response->assertStatus(403);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

    }

    public function test_it_updates_category(): void
{
    $category = Category::factory()->create();

    $this->assertDatabaseHas('categories', ['id' => $category->id,]);

    $updatedData = [
        'name' => 'Updated Category Name',
        'description' => 'Updated Category Description',];

    $response = $this->actingAs($this->user())->put(route('admin.categories.update', $category), $updatedData);

    $this->assertDatabaseHas('categories',
        ['id' => $category->id,
        'name' => $updatedData['name'],]);
    $response->assertStatus(302);
}

 public function test_it_updates_category_negative(): void
{
    $category = Category::factory()->create();

    $this->assertDatabaseHas('categories', ['id' => $category->id,]);

    $updatedData = [
        'name' => 'Updated Category Name',
        'description' => 'Updated Category Description',];

    $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))->put(route('admin.categories.update', $category), $updatedData);

    $response->assertStatus(200);
}




}


