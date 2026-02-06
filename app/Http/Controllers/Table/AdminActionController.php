<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminActionController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminAction::with(['admin', 'target', 'report']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('target', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $actions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin_actions.index', compact('actions'));
    }

    public function create()
    {
        $admins = User::where('is_admin', true)->get();
        $users = User::all();
        $reports = Report::all();

        return view('admin_actions.create', compact('admins', 'users', 'reports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'target_user_id' => 'required|exists:users,id|different:admin_id',
            'report_id' => 'nullable|exists:reports,id',
            'action_taken' => 'required|in:ban,warn,suspend,dismiss',
            'notes' => 'nullable|string',
        ]);

        AdminAction::create($validated);

        return redirect()->route('admin-actions.index')->with('success', 'Action recorded.');
    }

    public function show(AdminAction $adminAction)
    {
        $adminAction->load(['admin', 'target', 'report']);
        return view('admin_actions.show', compact('adminAction'));
    }

    public function edit(AdminAction $adminAction)
    {
        // Fetch admins - users with is_admin flag (adjust as per your system)
        $admins = User::where('is_admin', true)->get();

        // Fetch all users
        $users = User::all();

        // Fetch reports (optional)
        $reports = Report::all();

        return view('admin-actions.edit', compact('adminAction', 'admins', 'users', 'reports'));
    }


    public function update(Request $request, AdminAction $adminAction)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'target_user_id' => 'required|exists:users,id|different:admin_id',
            'report_id' => 'nullable|exists:reports,id',
            'action_taken' => 'required|in:ban,warn,suspend,dismiss',
            'notes' => 'nullable|string',
        ]);

        $adminAction->update($validated);

        return redirect()->route('admin-actions.index')->with('success', 'Action updated.');
    }

    public function destroy(AdminAction $adminAction)
    {
        $adminAction->delete();
        return redirect()->route('admin-actions.index')->with('success', 'Action deleted.');
    }
}
