<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription membership upgrade page.
     */
    public function index(): View
    {
        return view('subscription.index');
    }
}
