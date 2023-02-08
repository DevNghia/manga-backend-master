<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class StaticController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->success('It\'s working!');
    }
}
