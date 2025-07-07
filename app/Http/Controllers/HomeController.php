<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show home page
     */
    public function index()
    {
        $games = Game::where('is_active', true)->get();
        
        return view('home', compact('games'));
    }

    /**
     * Show game detail page
     */
    public function showGame(Game $game)
    {
        $packages = $game->activeTopupPackages()->orderBy('amount')->get();
        
        return view('game-detail', compact('game', 'packages'));
    }

    /**
     * Show user dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->with(['topupPackage.game'])->latest()->paginate(10);
        
        return view('user.dashboard', compact('transactions'));
    }

    /**
     * Show transaction history
     */
    public function transactionHistory()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->with(['topupPackage.game', 'processedBy'])->latest()->paginate(15);
        
        return view('user.transaction-history', compact('transactions'));
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = auth()->user();
        
        return view('user.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only(['name', 'phone', 'address']));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
} 