<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    protected $endpoint = '/companies';

    /**
     * Get all companies
     *
     * @return void
     */
    public function test_get_all_companies()
    {
        Company::factory(10)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertJsonCount(10, 'data');
        $response->assertOk();
    }

    /**
     * Error get not found company
     *
     * @return void
     */
    public function test_error_get_not_found_company()
    {
        $company = 'fake-url';

        $response = $this->getJson("{$this->endpoint}/{$company}");
        $response->assertNotFound();
    }

    /**
     * Get single company
     *
     * @return void
     */
    public function test_get_single_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$company->uuid}");

        $response->dump();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'slug',
                'email',
                'phone',
                'whatsapp',
                'facebook',
                'instagram',
                'youtube',
                'category' => [
                    'id',
                    'title',
                    'slug',
                    'description',
                    'date_created'
                ]
            ]
        ]);

        $response->assertOk();
    }

    /**
     * Validation required fields store company
     *
     * @return void
     */
    public function test_validation_required_fields_store_company()
    {
        $response = $this->postJson($this->endpoint, [
            'category'  => '',
            'name'      => '',
            'email'     => '',
            'phone'     => '',
        ]);

        $response->assertJsonValidationErrors(['category', 'name', 'email', 'phone']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Validation unique fields store company
     *
     * @return void
     */
    public function test_validation_unique_fields_store_company()
    {
        $company = Company::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'name'  => $company->name,
            'email' => $company->email,
        ]);

        $response->assertJsonValidationErrors(['name', 'email']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    /**
     * Error category does not exists store company
     *
     * @return void
     */
    public function test_error_category_does_not_exists_store_company()
    {
        $response = $this->postJson($this->endpoint, [
            'category'  => 'fake-id',
            'name'      => 'Company Fake Test',
            'email'     => 'test@fake.com',
            'phone'     => '9999999999',
            'whatsapp'  => '9999999999',
            'facebook'  => 'user_account',
            'instagram' => 'user_account',
            'youtube'   => 'user_account',
        ]);

        $response->assertJsonValidationErrors(['category']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Store company
     *
     * @return void
     */
    public function test_store_company()
    {
        $category = Category::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'category'  => $category->id,
            'name'      => 'Company Fake Test',
            'email'     => 'test@fake.com',
            'phone'     => '9999999999',
            'whatsapp'  => '9999999999',
            'facebook'  => 'user_account',
            'instagram' => 'user_account',
            'youtube'   => 'user_account',
        ]);

        $response->assertJsonStructure([
            'uuid',
            'name',
            'slug',
            'email',
            'phone',
            'whatsapp',
            'facebook',
            'instagram',
            'youtube',
            'category' => [
                'id',
                'title',
                'slug',
                'description',
                'date_created'
            ]
        ]);

        $response->assertCreated();
    }

    /**
     * Error update not found company
     *
     * @return void
     */
    public function test_error_update_not_found_company()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/fake-uuid", [
            'category'  => $category->id,
            'name'      => 'Company Fake Test Updated',
            'email'     => 'test.updated@fake.com',
            'phone'     => '999999999'
        ]);

        $response->assertNotFound();
    }

    /**
     * Validation unique fields update company
     *
     * @return void
     */
    public function test_validation_unique_fields_update_company()
    {
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$company2->uuid}", [
            'category'  => $category->id,
            'name'      => $company1->name,
            'email'     => $company1->email,
            'phone'     => '999999999'
        ]);

        $response->assertJsonValidationErrors(['name', 'email']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Validation required fields update company
     *
     * @return void
     */
    public function test_validation_required_fields_update_company()
    {
        $company = Company::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", [
            'category'  => '',
            'name'      => '',
            'email'     => '',
            'phone'     => '',
        ]);

        $response->assertJsonValidationErrors(['category', 'name', 'email', 'phone']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Update company
     *
     * @return void
     */
    public function test_update_company()
    {
        $company  = Company::factory()->create();
        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", [
            'category' => $category->id,
            'name'     => 'Company Fake Test Updated',
            'email'    => 'test.updated@fake.com',
            'phone'    => '999999999'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Error delete not found company
     *
     * @return void
     */
    public function test_error_delete_not_found_company()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake-uuid");
        $response->assertNotFound();
    }

    /**
     * Delete company
     *
     * @return void
     */
    public function test_delete_company()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$company->uuid}");

        $response->assertNoContent();
    }
}
