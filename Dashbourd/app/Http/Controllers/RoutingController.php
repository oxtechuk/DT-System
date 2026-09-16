<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingController extends Controller
{
    public function root()
    {
        return view('index');
    }

    /**
     * second level route
     */
    public function secondLevel(Request $request, $first, $second)
    {
        $view = $first . '.' . $second;
        if (view()->exists($view)) {
            return view($view);
        }

        // Graceful fallbacks for settings subpages
        if ($first === 'settings') {
            if ($second === 'rooms') {
                return redirect('/rooms');
            }
            if ($second === 'payment-methods' || $second === 'payments') {
                return redirect('/payments');
            }
            return redirect('/settings/general');
        }

        abort(404);
    }

    /**
     * third level route
     */
    public function thirdLevel(Request $request, $first, $second, $third)
    {
        $view = $first . '.' . $second . '.' . $third;
        if (view()->exists($view)) {
            return view($view);
        }

        abort(404);
    }
}
