<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Cara 1: Query terpisah (paling aman)
            $sentimentsCount = Sentiment::where('user_id', $user->id)->count();
            $positiveSentiments = Sentiment::where('user_id', $user->id)
                                        ->where('sentiment', 'positive')
                                        ->count();
            $neutralSentiments = Sentiment::where('user_id', $user->id)
                                      ->where('sentiment', 'neutral')
                                      ->count();
            $negativeSentiments = Sentiment::where('user_id', $user->id)
                                        ->where('sentiment', 'negative')
                                        ->count();
            
            $recentSentiments = Sentiment::where('user_id', $user->id)
                                     ->latest()
                                     ->take(5)
                                     ->get();
            
            return view('home', compact(
                'user',
                'sentimentsCount',
                'positiveSentiments',
                'neutralSentiments',
                'negativeSentiments',
                'recentSentiments'
            ));
        }

        return view('home');
    }
}