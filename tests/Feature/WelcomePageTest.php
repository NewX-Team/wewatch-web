<?php

test('welcome page returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('WEWATCH');
    $response->assertSee('CYBERPUNK SHADOWS');
    $response->assertSee('Trending Across WeWatch');
    $response->assertSee('Start Free Trial');
});
