<?php

namespace App\Http\Controllers\Api;

use App\Partner;
use App\Http\Controllers\Controller;

class PartnersController extends Controller
{
    public function getAll()
    {
        return Partner::all();
    }
}
