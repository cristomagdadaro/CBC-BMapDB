<?php

namespace Tests\Unit;

use Tests\Feature\BaseTest;
use Tests\TestCase;

class CommodityCrudTest extends BaseTest
{
    /**
     * @test
     */
    public function get_all_commodities(): void
    {
        $response = $this->getJson('/api/commodities');

        $response->assertStatus(200);
        $this->assertEquals(10, $response['meta']['total']);
    }

    /**
     * @test
     */
    public function get_a_specific_commodity(): void
    {
        $response = $this->getJson('/api/commodities/2');

        $response->assertStatus(200);
        $this->assertEquals(2, $response['id']);
    }

    /**
     * @test
     */
    public function get_a_specific_commodity_that_does_not_exist(): void
    {
        $response = $this->getJson('/api/commodities/999');

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function create_a_commodity(): void
    {
        $breeder = \App\Models\Breeder::factory()->create();

        $response = $this->postJson('/api/commodities', [
            'name' => 'Test',
            'variety' => 'test',
            'scientific_name' => 'mekus mekis',
            'breeder_id' => $breeder->id,
            'germplasm' => 'test',
            'accession' => 'test',
            'population' => 3482463873,
            'yield' =>  12324357,
            'maturity_period' => '3 months',
            'description' => 'test',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('commodities', [
            'name' => 'Test',
            'variety' => 'test',
            'scientific_name' => 'mekus mekis',
            'breeder_id' => $breeder->id,
            'germplasm' => 'test',
            'accession' => 'test',
            'population' => 3482463873,
            'yield' =>  12324357,
            'maturity_period' => '3 months',
            'description' => 'test',
        ]);
    }

    /**
     * @test
     */
    public function create_a_commodity_with_existing_name(): void
    {
        $commodity = \App\Models\Commodity::factory()->create();

        $response = $this->postJson('/api/commodities', $commodity->toArray());

        $response->assertStatus(422);
        $this->assertDatabaseCount('commodities', 11);
    }

    /**
     * @test
     */
    public function update_a_commodity(): void
    {
        $commodity = \App\Models\Commodity::factory()->create();

        $response = $this->putJson('/api/commodities/'.$commodity->id, [
            'name' => 'Test',
            'variety' => 'test',
            'scientific_name' => 'mekus mekis',
            'breeder_id' => $commodity->breeder_id,
            'germplasm' => 'test',
            'accession' => 'test',
            'population' => 3482463873,
            'yield' =>  12324357,
            'maturity_period' => '3 months',
            'description' => 'test',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('commodities', [
            'name' => 'Test',
            'variety' => 'test',
            'scientific_name' => 'mekus mekis',
            'breeder_id' => $commodity->breeder_id,
            'germplasm' => 'test',
            'accession' => 'test',
            'population' => 3482463873,
            'yield' =>  12324357,
            'maturity_period' => '3 months',
            'description' => 'test',
        ]);
    }

    /**
     * @test
     */
    public function update_a_commodity_with_existing_name(): void
    {
        $commodity = \App\Models\Commodity::factory()->create();
        $com = \App\Models\Commodity::factory()->create();
        $response = $this->putJson('/api/commodities/'.$commodity->id, [
            'name' => $com->name,
            'variety' => 'test',
            'scientific_name' => 'mekus mekis',
            'breeder_id' => $commodity->breeder_id,
            'germplasm' => 'test',
            'accession' => 'test',
            'population' => 3482463873,
            'yield' =>  12324357,
            'maturity_period' => '3 months',
            'description' => 'test',
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function delete_a_commodity(): void
    {
        $commodity = \App\Models\Commodity::factory()->create();

        $response = $this->deleteJson('/api/commodities/'.$commodity->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('commodities', [
            'id' => $commodity->id,
        ]);
    }

    /**
     * @test
     */
    public function delete_a_commodity_that_does_not_exist(): void
    {
        $response = $this->deleteJson('/api/commodities/999');

        $response->assertStatus(404);
    }
}
