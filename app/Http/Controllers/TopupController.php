<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\TopupPackage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopupController extends Controller
{
    /**
     * Show topup form
     */
    public function showTopupForm(Game $game)
    {
        $packages = $game->activeTopupPackages()->orderBy('amount')->get();
        
        return view('topup.form', compact('game', 'packages'));
    }

    /**
     * Process topup order
     */
    public function processTopup(Request $request, Game $game)
    {
        $validator = validator($request->all(), [
            'topup_package_id' => 'required|exists:topup_packages,id',
            'player_id' => 'required|string|max:50',
            'player_name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $package = TopupPackage::findOrFail($request->topup_package_id);
        
        // Verify package belongs to the game
        if ($package->game_id !== $game->id) {
            return back()->withErrors(['topup_package_id' => 'Paket tidak valid untuk game ini.'])->withInput();
        }

        // Generate transaction code
        $transactionCode = 'TRX-' . date('Y') . '-' . str_pad(Transaction::count() + 1, 6, '0', STR_PAD_LEFT);

        // Create transaction
        $transaction = Transaction::create([
            'transaction_code' => $transactionCode,
            'user_id' => auth()->id(),
            'topup_package_id' => $package->id,
            'player_id' => $request->player_id,
            'player_name' => $request->player_name,
            'amount' => $package->price,
            'status' => 'pending',
        ]);

        return redirect()->route('topup.confirmation', $transaction)
            ->with('success', 'Pesanan top-up berhasil dibuat!');
    }

    /**
     * Show topup confirmation
     */
    public function showConfirmation(Transaction $transaction)
    {
        // Ensure user can only see their own transaction
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('topup.confirmation', compact('transaction'));
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction(Transaction $transaction)
    {
        // Ensure user can only cancel their own pending transaction
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dapat dibatalkan.');
        }

        $transaction->update(['status' => 'cancelled']);

        return redirect()->route('user.dashboard')->with('success', 'Transaksi berhasil dibatalkan.');
    }
} 