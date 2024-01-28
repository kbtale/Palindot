<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;

uses(RefreshDatabase::class);

it('registers a new user', function () {
    $response = postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated();
});

it('registers a user, retrieves the info of the user, and updates and deletes it, and cannot store users if it is not the admin', function () {

    $response = postJson('/api/v1/register', [
        'name' => 'Test2 User',
        'email' => 'test2@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated();

    $response = postJson('/api/v1/login', [
        'email' => 'test2@example.com',
        'password' => 'password',
    ]);


    $response = getJson('/api/v1/user', []);
    $response->assertOk();
    $loggedUser = $response->json();

    $response = getJson('/api/v1/user/' . $loggedUser['id'], []);
    $response->assertOk();

    $response = putJson('/api/v1/user/' . $loggedUser['id'], [
        'name' => $loggedUser['name'],
        'password' => 'newpassword'
    ]);
    $response->assertOk();
    
    $response = postJson('/api/v1/user/', [
        'name' => 'Test3 User',
        'email' => 'test3@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(403);

    $response = deleteJson('/api/v1/user/' . $loggedUser['id'], [
        'name' => $loggedUser['name'],
        'password' => 'newpassword'
    ]);
    $response->assertOk();
});

it('logs in an existing user', function () {
    
    $user = User::factory()->create([
        'password' => bcrypt($password = 'password'),
    ]);

    $response = postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertOk();
});

it('logs out an authenticated user', function () {
    
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson('/api/v1/logout');

    $response->assertOk();
});

it('creates a link', function () {
    
    $response = postJson('/api/v1/urls/create', [
        'base_url' => 'https://www.google.es'
    ]);

    $response->assertOk();
});

it('creates, shows, updates, and deletes subsets and urls', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = postJson('/api/v1/subsets', [
        'subset_name' => 'Main',
        'subset_descr' => 'The main subset created for the user by default'
    ]);
    $response->assertOk();
    $mainSubset = $response->json();

    $response = postJson('/api/v1/subsets', [
        'subset_name' => 'Branch2',
        'subset_descr' => 'Another subset created'
    ]);
    $response->assertOk();

    $response = getJson('/api/v1/subsets/2', [
        'subset_name' => 'Branch2',
        'subset_descr' => 'Another subset created'
    ]);
    $response->assertOk();

    $response = putJson('/api/v1/subsets/2', [
        'subset_name' => 'Branch3',
        'subset_descr' => 'subset replaced (more than 20 characters)'
    ]);
    $response->assertOk();

    $response = getJson('/api/v1/subsets', []);
    $response->assertOk();
    
    $response = deleteJson('/api/v1/subsets/2', [
        
    ]);
    $response->assertOk();

    $response = postJson('/api/v1/urls/create', [
        'base_url' => 'https://www.google.com',
        'subset_id' => $mainSubset['id']
    ]);
    $response->assertOk();

    $response = postJson('/api/v1/urls/create', [
        'base_url' => 'https://www.gmail.com',
        'subset_id' => $mainSubset['id']
    ]);
    $response->assertOk();
    $createdUrl = $response->json();

    $response = getJson('/api/v1/urls', [
    ]);
    $response->assertOk();

    $response = putJson('/api/v1/urls/' . $createdUrl['id'], [
        'base_url' => 'https://www.hotmail.com'
    ]);
    $response->assertOk();

    $response = getJson('/api/v1/urls/' . $createdUrl['id'], [
    ]);
    $response->assertOk();

    $response = deleteJson('/api/v1/urls/' . $createdUrl['id'], [
        'base_url' => 'https://www.hotmail.com'
    ]);
    $response->assertOk();
});

it('stores users if the user is the admin', function () {
    $response = postJson('/api/v1/register', [
        'name' => 'kbtale',
        'email' => 'testadmin@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated();

    $response = postJson('/api/v1/login', [
        'email' => 'testadmin@example.com',
        'password' => 'password',
    ]);

    $response = postJson('/api/v1/user/', [
        'name' => 'Test3 User',
        'email' => 'test3@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertOk();
});