<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class RouteResponseTest extends TestCase
{
    public function testSuccessfulResponse(): void
    {
        $this->get('/authentication')->assertStatus(200);
        $this->get('/registration')->assertStatus(200);
        $this->get('/patient/card')->assertRedirect('/authentication');

        Cookie::queue('auth', Str::random(60), 1440);
        $this->followingRedirects()->get('/patient/card')->assertStatus(200);
        Cookie::queue(Cookie::forget('auth'));
    }
}
