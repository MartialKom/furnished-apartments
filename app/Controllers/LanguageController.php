<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LanguageController extends BaseController
{
    public function switch($language = 'en')
    {
        $supportedLanguages = ['en', 'fr'];
        
        if (in_array($language, $supportedLanguages)) {
            $session = session();
            $session->set('language', $language);
            
            // Set the locale for the current request
            $this->request->setLocale($language);
            
            // Store in cookie for persistence
            $response = service('response');
            $response->setCookie('language', $language, 60 * 60 * 24 * 365); // 1 year
        }
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}