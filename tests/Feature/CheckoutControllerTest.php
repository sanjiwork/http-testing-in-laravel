<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        factory(Product::class)->create(['price' => 20]);
        factory(Product::class)->create(['price' => 30]);
        factory(Product::class)->create(['price' => 50]);
        $response = $this->post('/checkout', [
            'products' => [
                ['id' => 1, 'quantity' => 1],
                ['id' => 2, 'quantity' => 2],
                ['id' => 3, 'quantity' => 1],
            ]
        ]);

        $response->assertStatus(200)
            ->assertViewIs('checkout.index')
            ->assertViewHas('totalCount', 1+2+1)
            ->assertViewHas('totalAmount', 1*20+2*30*0.9+1*50)
            ->assertSeeText('結帳');
    }

    public function testShouldReturnStatusCode200WhenVisitCheckoutPage()
    {
        factory(Product::class)->create(['price' => 200]);
        factory(Product::class)->create(['price' => 300]);
        factory(Product::class)->create(['price' => 500]);
        $response = $this->post('/checkout', [
            'products' => [
                ['id' => 1, 'quantity' => 1],
                ['id' => 2, 'quantity' => 2],
                ['id' => 3, 'quantity' => 1],
            ]
        ]);

        $response->assertStatus(200)
            ->assertViewIs('checkout.index')
            ->assertViewHas('totalCount', 1+2+1)
            ->assertViewHas('totalAmount', (1*200+2*300*0.9+1*500)*0.8)
            ->assertSeeText('結帳');
    }
}
