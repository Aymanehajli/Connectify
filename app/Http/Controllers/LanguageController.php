<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage(Request $request)
    {
        $language = $request->input('language');
        $languages = ['en', 'fr', 'es']; // Add more languages as needed

        if (in_array($language, $languages)) {
            Session::put('app_locale', $language);
            App::setLocale($language);
        }
         
        return redirect()->back();
    }

}