<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function privacyPolicy()
    {
        return view("privacy.privacy-policy");
    }

    public function userDataDeletion()
    {
        return view("privacy.user-data-deletion");
    }
}
