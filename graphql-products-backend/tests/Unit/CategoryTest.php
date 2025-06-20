<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_create_category(): void
    {
        $name = 'Bebidas';

        $response = $this->postGraphQL([
            'query' => '
                mutation CreateCategory($input: CreateCategoryInput!) {
                    createCategory(input: $input) {
                        id
                        name
                    }
                }
            ',
            'variables' => [
                'input' => ['name' => $name],
            ],
        ]);

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.createCategory.name', $name);

        $this->assertDatabaseHas('categories', ['name' => $name]);
    }

    /** @test */
    public function test_update_category(): void
    {
        $category = Category::factory()->create(['name' => 'Original']);
        $newName  = 'Actualizado';

        $response = $this->postGraphQL([
            'query' => '
                mutation UpdateCategory($input: UpdateCategoryInput!) {
                    updateCategory(input: $input) {
                        id
                        name
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'id'   => $category->id,
                    'name' => $newName,
                ],
            ],
        ]);

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.updateCategory.name', $newName);

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => $newName,
        ]);
    }



    /** @test */
    public function test_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->graphQL('
            {
                categories {
                    id
                    name
                }
            }
        ');

        $response
            ->assertSuccessful()
            ->assertJsonCount(3, 'data.categories');
    }
}
