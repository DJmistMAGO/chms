<?php

it('renders the forgot password page', function () {
    $response = $this->get(route('password.request'));

    $response->assertOk();
});
