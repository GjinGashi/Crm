<?php

test('the API requires authentication', function () {
    $response = $this->getJson('/api/user');

    $response->assertUnauthorized();
});
