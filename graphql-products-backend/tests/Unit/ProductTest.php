<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_product(): void
    {
        // Creamos (o reutilizamos) una categoría
        $category = Category::factory()->create();

        $payload = [
            'name'        => 'Café Premium',
            'description' => 'Café orgánico de origen único',
            'price'       => 15000,
            'category_id' => $category->id,
        ];

        $response = $this->postGraphQL([
            'query' => '
                mutation ($input: CreateProductInput!) {
                    createProduct(input: $input) {
                        id
                        name
                        price
                        category { id name }
                    }
                }
            ',
            'variables' => ['input' => $payload],
        ]);

        $response->assertSuccessful()
            ->assertJsonPath('data.createProduct.name', 'Café Premium')
            ->assertJsonPath('data.createProduct.category.id', (string) $category->id);

        $this->assertDatabaseHas('products', [
            'name'        => 'Café Premium',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function update_product(): void
    {
        $category = Category::factory()->create();
        $product  = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->postGraphQL([
            'query' => '
                mutation ($input: UpdateProductInput!) {
                    updateProduct(input: $input) {
                        id
                        name
                        price
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'id'          => $product->id,
                    'name'        => 'Nombre Nuevo',
                    'description' => 'Descripción actualizada',
                    'price'       => 9999,
                ],
            ],
        ]);

        $response->assertSuccessful()
            ->assertJsonPath('data.updateProduct.name', 'Nombre Nuevo');

        $this->assertDatabaseHas('products', [
            'id'   => $product->id,
            'name' => 'Nombre Nuevo',
        ]);
    }

    /** @test */
    public function delete_product(): void
    {
        $product = Product::factory()->create();

        // Ejecutamos la mutación deleteProduct
        $this->postGraphQL([
            'query' => '
                mutation ($id: ID!) {
                    deleteProduct(id: $id) { id }
                }
            ',
            'variables' => ['id' => $product->id],
        ])->assertSuccessful();

        // Verificamos que ya no está en la BD
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function list_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $response = $this->graphQL('{
            products {
                id
                name
                category { id name }
            }
        }');

        $response->assertSuccessful()
            ->assertJsonCount(2, 'data.products');
    }
}
