<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestsController extends Controller
{
    public function index()
    {
        // Get all requests with employee names
        $leaveRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->leftJoin('tbl_employee', 'tbl_requests.employee_idno', '=', 'tbl_employee.employee_idno')
            ->select(
                'tbl_requests.*',
                'tbl_employee.first_name',
                'tbl_employee.middle_name',
                'tbl_employee.last_name',
                'tbl_employee.department'
            )
            ->orderBy('tbl_requests.datetime_requested', 'desc')
            ->get();
        
        // Get attachments separately
        $attachments = DB::connection('dtr')
            ->table('tbl_request_attachments')
            ->get()
            ->groupBy('request_id');
        
        // Get counts for filters
        $pendingCount = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Pending')
            ->count();
        
        $approvedCount = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Approved')
            ->count();
        
        $disapprovedCount = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Disapproved')
            ->count();
        
        return view('users.leave-requests', compact('leaveRequests', 'attachments', 'pendingCount', 'approvedCount', 'disapprovedCount'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Disapproved'
        ]);
        
        DB::connection('dtr')
            ->table('tbl_requests')
            ->where('request_id', $id)
            ->update([
                'status' => $request->status,
                'datetime_status_changed' => now()
            ]);
        
        return redirect()->back()->with('success', 'Request has been ' . strtolower($request->status) . '!');
    }
    
    public function filterByStatus($status)
    {
        $query = DB::connection('dtr')
            ->table('tbl_requests')
            ->leftJoin('tbl_employee', 'tbl_requests.employee_idno', '=', 'tbl_employee.employee_idno')
            ->select(
                'tbl_requests.*',
                'tbl_employee.first_name',
                'tbl_employee.middle_name',
                'tbl_employee.last_name',
                'tbl_employee.department'
            );
        
        if ($status !== 'all') {
            $query->where('tbl_requests.status', ucfirst($status));
        }
        
        $leaveRequests = $query->orderBy('tbl_requests.datetime_requested', 'desc')->get();
        
        // Get attachments separately
        $attachments = DB::connection('dtr')
            ->table('tbl_request_attachments')
            ->get()
            ->groupBy('request_id');
        
        // Get counts for filters
        $pendingCount = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Pending')
            ->count();
        
        $approvedCount = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Approved')
            ->count();
        
        $disapprovedCount = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Disapproved')
            ->count();
        
        return view('users.leave-requests', compact('leaveRequests', 'attachments', 'pendingCount', 'approvedCount', 'disapprovedCount', 'status'));
    }

    public function downloadAttachment($id)
    {
        // Get attachment record
        $attachment = DB::connection('dtr')
            ->table('tbl_request_attachments')
            ->where('attachment_id', $id)
            ->first();
        
        if (!$attachment) {
            abort(404, 'Attachment record not found in database');
        }
        
        // Get the full file path
        $fileName = basename($attachment->file_path);
        $fullPath = 'C:/xampp/htdocs/dtr/uploads/request_attachments/' . $fileName;
        
        // Check if file exists
        if (!file_exists($fullPath)) {
            abort(404, 'File not found at: ' . $fullPath);
        }
        
        // Return the file as download
        return response()->download($fullPath, $attachment->file_name);
    }
}