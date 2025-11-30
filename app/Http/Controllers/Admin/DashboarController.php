<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Business;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class DashboarController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {

        $userCount = 0;
        $sellerCount = 0;

        $activeBusinessCount = 0;
        $pendingBusinessCount = 0;
        return view('admin.dashboard', compact('userCount', 'sellerCount', 'activeBusinessCount', 'pendingBusinessCount'));
    }
}
