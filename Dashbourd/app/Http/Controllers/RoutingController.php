<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingController extends Controller
{
    /**
     * Display main dashboard root view
     */
    public function root()
    {
        return view('index');
    }

    /**
     * Standard 404 Not Found response
     */
    public function notFound()
    {
        return response()->view('pages.404', [], 404);
    }

    /**
     * Redirect referral code to portal registration
     */
    public function referralRedirect(string $code)
    {
        return redirect()->route('portal.register', ['ref' => $code]);
    }

    /**
     * Legacy events redirect
     */
    public function eventsRedirect()
    {
        return redirect()->route('admin.events.index');
    }
}
