<?php

namespace Tests\Integration;

use App\Models\Artist;
use App\Models\User;
// use App\Models\Album;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Support\Facades\Artisan;

use \App\Http\Controllers\AlbumController;

class IntegrationBatteryTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * Test that verifies that a standard user can write and send a review.
     *
     * @return void
     */
    public function test_user_sends_review(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->post('/send_review', ['user_id' => 2, 'album_id' => 1, 'title' => 'titulillo', 'text' => 'esta es mi review', 'rating' => 90])->assertStatus(302);
    }

    /**
     * Test that verifies that a standard user can delete his/her/its own review.
     *
     * @return void
     */
    public function test_user_deletes_review(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->get(uri: '/delete_review/3')->assertStatus(302);
    }

    /**
     * Test that verifies that a standard user see a list, made by itself or by another user.
     *
     * @return void
     */
    public function test_show_list(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->get(uri: '/show_list/5')->assertStatus(200);
    }

    /**
     * Test that verifies that a standard cannot delete a list created by another user.
     *
     * @return void
     */
    public function test_cannot_delete_list(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->post(uri: '/delete_list/5')->assertStatus(405);
    }

}
