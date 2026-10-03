<?php

use App\Models\User;

test('authenticated user can view movie detail page', function () {
    $user = User::factory()->user()->create();

    $response = $this->actingAs($user)->get('/movies/cyberpunk-shadows');

    $response->assertStatus(200);
    $response->assertSee('Cyberpunk Shadows');
    $response->assertSee('Overview');
    $response->assertSee('Episodes');
    $response->assertSee('Genres');
});
