<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create(): View
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();
        
        if (!$colocation) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to join a colocation first.');
        }

        $categories = $colocation->categories()->orderBy('name')->get();

        return view('expenses.create', compact('colocation', 'categories'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();
        
        if (!$colocation) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to join a colocation first.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Verify the category belongs to the user's colocation
        $category = Category::find($validated['category_id']);
        if ($category->colocation_id !== $colocation->id) {
            return back()->with('error', 'Invalid category selected.');
        }

        $expense = Expense::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'category_id' => $validated['category_id'],
            'payer_id' => $user->id,
            'colocation_id' => $colocation->id,
        ]);

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Expense added successfully!');
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense): View
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();
        
        if (!$colocation || $expense->colocation_id !== $colocation->id) {
            abort(403, 'Unauthorized access to this expense.');
        }

        // Only the payer can edit the expense
        if ($expense->payer_id !== $user->id) {
            abort(403, 'Only the payer can edit this expense.');
        }

        $categories = $colocation->categories()->orderBy('name')->get();

        return view('expenses.edit', compact('expense', 'colocation', 'categories'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();
        
        if (!$colocation || $expense->colocation_id !== $colocation->id) {
            abort(403, 'Unauthorized access to this expense.');
        }

        // Only the payer can update the expense
        if ($expense->payer_id !== $user->id) {
            abort(403, 'Only the payer can update this expense.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Verify the category belongs to the user's colocation
        $category = Category::find($validated['category_id']);
        if ($category->colocation_id !== $colocation->id) {
            return back()->with('error', 'Invalid category selected.');
        }

        $expense->update($validated);

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Expense updated successfully!');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();
        
        if (!$colocation || $expense->colocation_id !== $colocation->id) {
            abort(403, 'Unauthorized access to this expense.');
        }

        // Only the payer can delete the expense
        if ($expense->payer_id !== $user->id) {
            abort(403, 'Only the payer can delete this expense.');
        }

        $expense->delete();

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Expense deleted successfully!');
    }
}
