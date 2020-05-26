<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function showPage(Request $request) {
    	$user = auth()->user();
    	return view('mainPage', ['user' => $user]);
    }
}
