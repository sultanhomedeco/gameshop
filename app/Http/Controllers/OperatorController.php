<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /**
     * Show operator dashboard
     */
    public function dashboard()
    {
        $pending_transactions = Transaction::with(['user', 'topupPackage.game'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $recent_processed = Transaction::with(['user', 'topupPackage.game'])
            ->where('processed_by', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending_count' => Transaction::where('status', 'pending')->count(),
            'processed_today' => Transaction::where('processed_by', auth()->id())
                ->whereDate('processed_at', today())
                ->count(),
        ];

        return view('operator.dashboard', compact('pending_transactions', 'recent_processed', 'stats'));
    }

    /**
     * Show pending transactions
     */
    public function pendingTransactions()
    {
        $transactions = Transaction::with(['user', 'topupPackage.game'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('operator.pending-transactions', compact('transactions'));
    }

    /**
     * Show transaction detail
     */
    public function showTransaction(Transaction $transaction)
    {
        return view('operator.transaction-detail', compact('transaction'));
    }

    /**
     * Process transaction
     */
    public function processTransaction(Request $request, Transaction $transaction)
    {
        if (!in_array($transaction->status, ['pending', 'processing'])) {
            return back()->with('error', 'Transaksi tidak dapat diproses.');
        }

        $validator = validator($request->all(), [
            'status' => 'required|in:processing,completed,failed',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $transaction->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        return redirect()->route('operator.dashboard')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Show processed transactions
     */
    public function processedTransactions(Request $request)
    {
        $query = Transaction::with(['user', 'topupPackage.game'])
            ->where('processed_by', auth()->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('processed_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('processed_at', '<=', $request->end_date);
        }

        $transactions = $query->latest()->paginate(20);

        return view('operator.processed-transactions', compact('transactions'));
    }
} 