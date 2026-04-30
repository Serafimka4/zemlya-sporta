<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Exports\ApplicationsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $adminPassword = config('app.admin_password');

        if ($request->password === $adminPassword) {
            session(['admin_authenticated' => true]);
            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['password' => 'Неверный пароль']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect('/admin');
    }

    public function dashboard(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin');
        }

        $query = Application::with('participants')->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('subject_rf')) {
            $query->where('subject_rf', $request->subject_rf);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->paginate(25);

        $stats = [
            'total' => Application::count(),
            'individual' => Application::where('type', 'individual')->count(),
            'team' => Application::where('type', 'team')->count(),
            'family' => Application::where('type', 'family')->count(),
        ];

        return view('admin.dashboard', compact('applications', 'stats'));
    }

    public function export(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin');
        }

        $filename = 'заявки_земля_спорта_' . now()->format('Y-m-d_H-i') . '.xlsx';
        return Excel::download(new ApplicationsExport, $filename);
    }

    public function show($id)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin');
        }

        $application = Application::with('participants.legalRepresentative')->findOrFail($id);
        return view('admin.show', compact('application'));
    }
}
