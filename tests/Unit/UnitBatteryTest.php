<?php

namespace Tests\Unit;

use App\Models\Artist;
use App\Models\User;
// use App\Models\Album;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Support\Facades\Artisan;

use \App\Http\Controllers\AlbumController;

class UnitBatteryTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseMigrations;


    /*Register and validation tests*/
    /**
     * Test that verifies that a guest can register.
     *
     * @return void
     */
    public function test_new_user_can_register_standard(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Overdubs2025',
            'password_confirmation' => 'Overdubs2025',

            'usertype' => 'User',
            'year' => '2000',
            'gender' => 'Woman',
            'profile_pic' => 'https://static.vecteezy.com/system/resources/previews/021/578/906/non_2x/positive-face-woman-upper-body-icon-illustration-vector.jpg',
            'country' => 'Spain'
        ]);

        $this->assertAuthenticated();
    }

    /**
     * Test that verifies that a guest can not do a register without a correct password (required field).
     *
     * @return void
     */
    public function test_new_user_cant_register_without_password(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        $response = $this->post('/register', [
            'name' => 'User No Pass',
            'email' => 'nopass@example.com',
            'password' => '',
            'password_confirmation' => '',

            'usertype' => 'User',
            'year' => 1994,
            'gender' => 'Woman',
            'profile_picture' => 'https://static.vecteezy.com/system/resources/previews/021/578/906/non_2x/positive-face-woman-upper-body-icon-illustration-vector.jpg',
            'country' => 'Spain'
        ]);

        $response->assertSessionHasErrors();
        // $this->assertGuest();
    }

    /**
     * Test that verifies that a guest can not do a register without a correct gender (non required field).
     *
     * @return void
     */
    public function test_new_user_cant_register_wrong_gender(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $response = $this->post('/register', [
            'name' => 'User No Pass',
            'email' => 'nopass@example.com',
            'password' => '',
            'password_confirmation' => '',
            'usertype' => 'User',
            'year' => 1994,
            'gender' => 'NoGender',
            'profile_picture' => 'https://static.vecteezy.com/system/resources/previews/021/578/906/non_2x/positive-face-woman-upper-body-icon-illustration-vector.jpg',
            'country' => 'Spain'
        ]);
        $response->assertSessionHasErrors();
    }

    /*Endpoints*/
    /**
     * Test that verifies that an user can search an album.
     *
     * @return void
     */
    public function test_search(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->get('/search')->assertStatus(200);

    }

    /**
     * Test that verifies that an user can see an album's page.
     *
     * @return void
     */
    public function test_show_album(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->get('/show_album/2')->assertStatus(200);
    }

    /**
     * Test that verifies that non-administrator users cannot access restricted content.
     *
     * @return void
     */
    public function test_user_cannot_access_admin_content(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->get('/admin')->assertStatus(404);
    }

    /**
     * Test that verifies that non-administrator users cannot access data reports.
     *
     * @return void
     */
    public function test_user_cannot_access_data_report(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->get('/admin/data_report/')->assertStatus(403);

    }


    /*Middleware tests*/
    /**
     * Test that verifies that an user can follow or unfollow another user.
     *
     * @return void
     */
    public function test_follow(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $user = User::where('id', '2')->first();
        $this->actingAs($user)->post('/follow_or_unfollow', ['follower_id' => 2, 'following_id' => 20])->assertStatus(302);
    }

    /**
     * Test that verifies that artists cannot do follows or unfollows.
     *
     * @return void
     */
    public function test_artist_cannot_follow(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        $artist = User::where('id', '22')->first();
        $this->actingAs($artist)->post('/follow_or_unfollow', ['follower_id' => 22, 'following_id' => 19])->assertStatus(403);
    }

    /*Seeding database*/
    /**
     * Test that verifies the database seeding.
     *
     * @return void
     */
    public function test_seeding_database(): void
    {
        $this->seed();
        $this->assertGreaterThan(0, User::count());
    }

}
