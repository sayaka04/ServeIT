<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\Report;
use App\Models\Technician;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->is_technician) {
            return $this->indexTechnician($request->year);
        } elseif (Auth::user()->is_admin) {
            return $this->indexAdmin();
        } else {
            return $this->indexClient();

            // return 'Dashboard for client is still being developed.';
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function indexTechnician($requestYear)
    {



        $technician = Technician::where('technician_user_id', Auth::id())->first();

        $repairs = Repair::where('technician_id', $technician->id)->get();

        // $ongoing_repairs = $repairs->where('is_completed', 0)->where('status', '!=', "pending");
        $ongoing_repairs = Repair::where('technician_id', $technician->id)
            ->whereNotIn('status', ['pending', 'declined'])
            ->where('is_completed', 0)
            ->where('is_cancelled', 0)
            ->where('is_claimed', null)
            ->get();


        // $ongoing_repairs = $repairs->where('status', '!=', "pending");


        // Get the repairs for the line chart, excluding 'declined'
        // $line_chart_repairs = Repair::where('technician_id', $technician->id)
        //     ->where('status', '!=', 'declined')
        //     ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        //     ->groupBy('month')
        //     ->pluck('count', 'month')
        //     ->toArray();w
        if ($requestYear == null) {
            $year = now()->year;
        } else {
            $year = Carbon::parse($requestYear)->year;
        }
        // $year = now()->year;

        $line_chart_repairs = Repair::where('technician_id', $technician->id)
            ->where('status', '!=', 'declined')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();


        $data = array_fill(1, 12, 0); // Initialize an array with 12 zeros
        foreach ($line_chart_repairs as $month => $count) {
            $data[$month] = $count;
        }

        $line_chart_data = array_values($data); // Get the values in an indexed array

        //counts
        // $repairs_count = $repairs->count();
        // $unclaimed_repairs_count = $repairs->where('status', 'completed')->where('is_claimed', null)->count();
        // $cancelled_repairs_count = $repairs->where('status', 'cancelled')->where('is_cancelled', 1)->count();
        // $ongoing_repairs_count = $ongoing_repairs->count();
        // $completed_repairs_count = $repairs->where('is_completed', 1)->count();
        // $overdue_repairs_count = $repairs->where('completion_date', '<', now())->where('status', '==', 'in_progress')->count();
        if ($requestYear == null) {

            $repairs_count = $repairs->count();
            $unclaimed_repairs_count = $repairs->where('status', 'completed')->where('is_claimed', null)->count();
            $cancelled_repairs_count = $repairs->where('status', 'cancelled')->where('is_cancelled', 1)->count();
            $ongoing_repairs_count = $ongoing_repairs->count();
            $completed_repairs_count = $repairs->where('is_completed', 1)->count();
            $overdue_repairs_count = $repairs->where('completion_date', '<', now())->where('status', 'in_progress')->count();
        } else {

            $repairs_count = $repairs->filter(fn($r) => $r->created_at->year == $year)->count();
            $unclaimed_repairs_count = $repairs->filter(fn($r) => $r->created_at->year == $year && $r->status == 'completed' && $r->is_claimed == null)->count();
            $cancelled_repairs_count = $repairs->filter(fn($r) => $r->created_at->year == $year && $r->status == 'cancelled' && $r->is_cancelled == 1)->count();
            $ongoing_repairs_count = $ongoing_repairs->filter(fn($r) => $r->created_at->year == $year)->count();
            $completed_repairs_count = $repairs->filter(fn($r) => $r->created_at->year == $year && $r->is_completed == 1)->count();
            $overdue_repairs_count = $repairs->filter(fn($r) => $r->created_at->year == $year && $r->completion_date < now() && $r->status == 'in_progress')->count();
        }


        return view('dashboard.index', [
            'technician' => $technician,
            'repairs' => $repairs,
            'ongoing_repairs' => $ongoing_repairs,
            'repairs_count' => $repairs_count,
            'unclaimed_repairs_count' => $unclaimed_repairs_count,
            'cancelled_repairs_count' => $cancelled_repairs_count,
            'ongoing_repairs_count' => $ongoing_repairs_count,
            'completed_repairs_count' => $completed_repairs_count,
            'overdue_repairs_count' => $overdue_repairs_count,
            'line_chart_data' => $line_chart_data,
        ]);
    }


    public function indexClient()
    {
        $repairs = Repair::where('user_id', Auth::id())->get();

        $ongoing_repairs = $repairs->where('is_completed', 0)->where('status', '!=', "pending");


        //counts
        $repairs_count = $repairs->count();
        $unclaimed_repairs_count = $repairs->where('status', 'completed')->where('is_claimed', null)->count();
        $ongoing_repairs_count = $ongoing_repairs->count();
        $completed_repairs_count = $repairs->where('is_completed', 1)->count();
        $overdue_repairs_count = $repairs->where('completion_date', '<', now())->where('status', '==', 'in_progress')->count();


        return view('dashboard.index', [
            'repairs' => $repairs,
            'ongoing_repairs' => $ongoing_repairs,
            'repairs_count' => $repairs_count,
            'unclaimed_repairs_count' => $unclaimed_repairs_count,
            'ongoing_repairs_count' => $ongoing_repairs_count,
            'completed_repairs_count' => $completed_repairs_count,
            'overdue_repairs_count' => $overdue_repairs_count,
        ]);
    }

    public function indexAdmin()
    {



        $reports = Report::all();
        $reports_count = $reports->count();
        $reports_pending_count = $reports->where('status', 'pending')->count();
        $reports_review_count = $reports->where('status', 'under_review')->count();
        $reports_resolved_count = $reports->where('status', 'resolved')->count();
        $reports_closed_count = $reports->where('status', 'closed')->count();


        return view('dashboard.index', [
            'reports_count' => $reports_count,
            'reports_pending_count' => $reports_pending_count,
            'reports_review_count' => $reports_review_count,
            'reports_resolved_count' => $reports_resolved_count,
            'reports_closed_count' => $reports_closed_count,
        ]);
    }
}
