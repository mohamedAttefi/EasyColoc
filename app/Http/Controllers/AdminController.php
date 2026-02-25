<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Only super admins can access
        if (!$user->is_super_admin) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'active_colocations' => Colocation::where('status', 'active')->count(),
            'total_expenses' => Expense::count(),
            'total_amount' => Expense::sum('amount'),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'banned_users' => User::where('is_banned', true)->count(),
        ];

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent colocations
        $recentColocations = Colocation::with('owner')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentColocations'));
    }

    /**
     * Ban a user.
     */
    public function banUser(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->is_super_admin) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        // Don't allow banning other admins
        if ($user->is_super_admin) {
            return back()->with('error', 'Cannot ban other administrators.');
        }

        $user->update([
            'is_banned' => true,
            'banned_at' => now(),
        ]);

        return back()->with('success', 'User has been banned.');
    }

    /**
     * Unban a user.
     */
    public function unbanUser(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->is_super_admin) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $user->update([
            'is_banned' => false,
            'banned_at' => null,
        ]);

        return back()->with('success', 'User has been unbanned.');
    }
}
