<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryItem;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Base query for leave requests with department join
        $query = DB::connection('dtr')
            ->table('tbl_requests')
            ->leftJoin('tbl_employee', 'tbl_requests.employee_idno', '=', 'tbl_employee.employee_idno')
            ->leftJoin('tbl_department', 'tbl_employee.department', '=', 'tbl_department.department_name');
        
        // Apply department filter for Sister (Non Academic only)
        $filteredQuery = $query->where('tbl_department.description', 'Non Academic');
        
        // Get counts
        $totalRequests = (clone $filteredQuery)->count();
        $pendingRequests = (clone $filteredQuery)->where('tbl_requests.status', 'Pending')->count();
        $approvedRequests = (clone $filteredQuery)->where('tbl_requests.status', 'Approved')->count();
        $disapprovedRequests = (clone $filteredQuery)->where('tbl_requests.status', 'Disapproved')->count();
        
        // Get recent requests (latest 5) for the table
        $recentRequests = (clone $filteredQuery)
            ->select(
                'tbl_requests.*',
                'tbl_employee.first_name',
                'tbl_employee.middle_name',
                'tbl_employee.last_name',
                'tbl_employee.department as department_name',
                'tbl_department.description as department_description'
            )
            ->orderBy('tbl_requests.datetime_requested', 'desc')
            ->limit(5)
            ->get();
        
        // Total inventory (sister inventory - update later)
        $totalInventory = InventoryItem::count();
        
        return view('users.admin-dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'disapprovedRequests',
            'totalInventory',
            'recentRequests'
        ));
    }
}