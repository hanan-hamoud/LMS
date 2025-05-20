<?php

use function Pest\Laravel\get;

it('shows filament login page', function () {
    $response = get('/admin/login');
    $response->assertOk();
});
