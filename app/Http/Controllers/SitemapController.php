<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;

class SitemapController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index(Request $request)
    {
        return Response()->view('sitemap')->header('content-Type', 'text/xml');
    }
}
