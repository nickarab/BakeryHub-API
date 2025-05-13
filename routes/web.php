<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return response("acesso não autorizado",
    Response::HTTP_UNAUTHORIZED);
});

