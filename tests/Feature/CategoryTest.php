<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';

    /**
     * Get all categories
     *
     * @return void
     */
    public function test_get_all_categories()
    {
        Category::factory(10)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertJsonCount(10, 'data');
        $response->assertOk();
    }

    /**
     * Error get not found category
     *
     * @return void
     */
    public function test_error_get_not_found_category()
    {
        $category = 'fake-url';

        $response = $this->getJson("{$this->endpoint}/{$category}");
        $response->assertNotFound();
    }

    /**
     * Get single category
     *
     * @return void
     */
    public function test_get_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$category->url}");

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'description',
                'date_created'
            ]
        ]);

        $response->assertOk();
    }

    /**
     * Validation required fields store category
     *
     * @return void
     */
    public function test_validation_required_fields_store_category()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => '',
            'description' => ''
        ]);

        $response->assertJsonValidationErrors(['title', 'description']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Validation unique fields store category
     *
     * @return void
     */
    public function test_validation_unique_fields_store_category()
    {
        $category = Category::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'title' => $category->title,
        ]);

        $response->assertJsonValidationErrors(['title']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Store category
     *
     * @return void
     */
    public function test_store_category()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => 'Category Fake Test',
            'description' => 'Description Fake Test'
        ]);

        $response->assertJsonStructure([
            'id',
            'title',
            'slug',
            'description',
            'date_created'
        ]);

        $response->assertCreated();
    }

    /**
     * Error update not found category
     *
     * @return void
     */
    public function test_error_update_not_found_category()
    {
        $response = $this->putJson("{$this->endpoint}/fake-url", [
            'title' => 'Category Fake Test',
            'description' => 'Description Fake Test'
        ]);

        $response->assertNotFound();
    }

    /**
     * Validation unique fields update category
     *
     * @return void
     */
    public function test_validation_unique_fields_update_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$category2->url}", [
            'title' => $category1->title,
        ]);

        $response->assertJsonValidationErrors(['title']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Validation required fields update category
     *
     * @return void
     */
    public function test_validation_required_fields_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$category->url}", [
            'title' => '',
            'description' => ''
        ]);

        $response->assertJsonValidationErrors(['title', 'description']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Update category
     *
     * @return void
     */
    public function test_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$category->url}", [
            'title' => 'Category Fake Test Updated',
            'description' => 'Description Fake Test Updated'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Error delete not found category
     *
     * @return void
     */
    public function test_error_delete_not_found_category()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake-url");
        $response->assertNotFound();
    }

    /**
     * Delete category
     *
     * @return void
     */
    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$category->url}");

        $response->assertNoContent();
    }
}
