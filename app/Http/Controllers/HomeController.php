<?php

namespace App\Http\Controllers;

use App\Http\Services\RemittanceService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Главная страница
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = app(RemittanceService::class)->getIndexData();
        return view('home', $data);
    }
}
