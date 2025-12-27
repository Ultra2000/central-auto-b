<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLocale(Request $request)
    {
        $locale = $request->input('locale', 'fr');
        
        if (in_array($locale, ['fr', 'en'])) {
            session(['locale' => $locale]);
        }
        
        return redirect()->back();
    }
}
