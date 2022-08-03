<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;

class UserController extends Controller
{
    //Giriş yapmış olan kişinin bilgisi
    public function me(): ?Authenticatable
    {
        return auth()->user();
    }
}
