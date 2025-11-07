<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * "Hakkımızda" sayfasını gösterir.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * "İletişim" sayfasını gösterir.
     */
    public function contact()
    {
        return view('pages.contact');
    }
}
