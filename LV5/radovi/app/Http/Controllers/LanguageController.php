<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch(Request $request, $locale)
    {
        // Validate the locale
        if (!in_array($locale, ['en', 'hr'])) {
            abort(400);
        }

        // Store the locale in session
        Session::put('locale', $locale);

        // Set the application locale
        App::setLocale($locale);

        return redirect()->back();
    }
}
