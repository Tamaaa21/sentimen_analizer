<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Get user's sentiment stats if logged in
            $user = Auth::user();
            $sentimentsCount = $user->sentiments()->count();
            $positiveSentiments = $user->sentiments()->where('sentiment', 'positive')->count();
            $neutralSentiments = $user->sentiments()->where('sentiment', 'neutral')->count();
            $negativeSentiments = $user->sentiments()->where('sentiment', 'negative')->count();
            
            $recentSentiments = $user->sentiments()->latest()->take(5)->get();
            
            return view('home', compact('user', 'sentimentsCount', 'positiveSentiments', 
                'neutralSentiments', 'negativeSentiments', 'recentSentiments'));
        }

        // Return homepage for guests
        return view('home');
    }
}