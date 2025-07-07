<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\TopupPackage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
        ];

        $recent_transactions = Transaction::with(['user', 'topupPackage.game'])
            ->latest()
            ->take(10)
            ->get();

        $monthly_revenue = Transaction::where('status', 'completed')
            ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
            ->selectRaw("strftime('%m', created_at) as month, SUM(amount) as total")
            ->groupBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_transactions', 'monthly_revenue'));
    }

    /**
     * Show users management
     */
    public function users()
    {
        $users = User::withCount('transactions')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user edit form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,operator,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only(['name', 'email', 'role', 'phone', 'address']));

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Show all games
     */
    public function games()
    {
        $games = \App\Models\Game::paginate(10);
        return view('admin.games.index', compact('games'));
    }

    /**
     * Show create game form
     */
    public function createGame()
    {
        return view('admin.games.create');
    }

    /**
     * Store new game
     */
    public function storeGame(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'currency_name' => 'required|string|max:50',
            'is_active' => 'required',
        ]);
        \App\Models\Game::create([
            'name' => $request->name,
            'description' => $request->description,
            'currency_name' => $request->currency_name,
            'is_active' => (bool) $request->is_active,
        ]);
        return redirect()->route('admin.games')->with('success', 'Game berhasil ditambahkan!');
    }

    /**
     * Show edit game form
     */
    public function editGame(\App\Models\Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    /**
     * Update game
     */
    public function updateGame(Request $request, \App\Models\Game $game)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'currency_name' => 'required|string|max:50',
            'is_active' => 'required',
        ]);
        $game->update([
            'name' => $request->name,
            'description' => $request->description,
            'currency_name' => $request->currency_name,
            'is_active' => (bool) $request->is_active,
        ]);
        return redirect()->route('admin.games')->with('success', 'Game berhasil diupdate!');
    }

    /**
     * Delete game
     */
    public function deleteGame(\App\Models\Game $game)
    {
        $game->delete();
        return redirect()->route('admin.games')->with('success', 'Game berhasil dihapus!');
    }

    /**
     * Show transactions management
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'topupPackage.game', 'processedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->latest()->paginate(20);
        $users = User::where('role', 'user')->get();

        return view('admin.transactions.index', compact('transactions', 'users'));
    }

    /**
     * Process transaction
     */
    public function processTransaction(Request $request, Transaction $transaction)
    {
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

        return back()->with('success', 'Transaksi berhasil diproses!');
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,operator,user',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'password' => \Hash::make($request->password),
        ]);
        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan!');
    }
} 