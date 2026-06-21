<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestsController extends Controller
{
    public function index()
    {
        $adminId = session('admin_id');
    
        // Base query with joins
        $query = DB::connection('dtr')
            ->table('tbl_requests')
            ->leftJoin('tbl_employee', 'tbl_requests.employee_idno', '=', 'tbl_employee.employee_idno')
            ->leftJoin('tbl_department', 'tbl_employee.department', '=', 'tbl_department.department_name')
            ->select(
                'tbl_requests.*',
                'tbl_employee.first_name',
                'tbl_employee.middle_name',
                'tbl_employee.last_name',
                'tbl_employee.department as department_name',
                'tbl_department.description as department_description'
            );
        
        // Apply department filtering based on admin_id
        $filteredQuery = $this->applyDepartmentFilter(clone $query, $adminId);
        $leaveRequests = $filteredQuery->orderBy('tbl_requests.datetime_requested', 'desc')->get();
        
        // Get attachments separately
        $attachments = DB::connection('dtr')
            ->table('tbl_request_attachments')
            ->get()
            ->groupBy('request_id');
        
        // Get TOTAL counts for the admin (for filter tabs - always show these)
        $totalPendingCount = (clone $filteredQuery)->where('tbl_requests.status', 'Pending')->count();
        $totalApprovedCount = (clone $filteredQuery)->where('tbl_requests.status', 'Approved')->count();
        $totalDisapprovedCount = (clone $filteredQuery)->where('tbl_requests.status', 'Disapproved')->count();
        
        return view('users.leave-requests', compact(
            'leaveRequests', 
            'attachments', 
            'totalPendingCount', 
            'totalApprovedCount', 
            'totalDisapprovedCount'
        ));
    }
    
    private function applyDepartmentFilter($query, $adminId)
    {
        switch ($adminId) {
            case 2: // HR - sees everything
                return $query;
                
            case 3: // Sister - Non Academic only (description = 'Non Academic')
                return $query->where('tbl_department.description', 'Non Academic');
                
            case 4: // Principal - Academic Non-Teaching + Teaching departments
                return $query->whereIn('tbl_department.description', [
                    'Academic Non-Teaching',
                    'Academic Teaching - Grade School',
                    'Academic Teaching - Junior High School',
                    'Academic Teaching - Senior High School'
                ]);
                
            case 5: // AP GS
                return $query->where('tbl_department.description', 'Academic Teaching - Grade School');
                
            case 6: // AP JHS
                return $query->where('tbl_department.description', 'Academic Teaching - Junior High School');
                
            case 7: // AP SHS
                return $query->where('tbl_department.description', 'Academic Teaching - Senior High School');
                
            default:
                return $query;
        }
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Disapproved'
        ]);
        
        $adminId = session('admin_id');
        $newStatus = $request->status;
        
        // Get the request and employee department
        $requestData = DB::connection('dtr')
            ->table('tbl_requests')
            ->leftJoin('tbl_employee', 'tbl_requests.employee_idno', '=', 'tbl_employee.employee_idno')
            ->leftJoin('tbl_department', 'tbl_employee.department', '=', 'tbl_department.department_name')
            ->select('tbl_requests.*', 'tbl_department.description as department_description')
            ->where('tbl_requests.request_id', $id)
            ->first();
        
        if (!$requestData) {
            return redirect()->back()->with('error', 'Request not found');
        }
        
        $departmentDesc = $requestData->department_description;
        $updateData = ['datetime_status_changed' => now()];
        
        // Determine if this is a teaching department (2-stage approval)
        $isTeachingDept = in_array($departmentDesc, [
            'Academic Teaching - Grade School',
            'Academic Teaching - Junior High School',
            'Academic Teaching - Senior High School'
        ]);
        
        // Handle based on admin role
        switch ($adminId) {
            case 2: // HR - read-only, cannot change status
                return redirect()->back()->with('error', 'HR does not have permission to change request status');
                
            case 3: // Sister - Non Academic only (single-stage)
                if ($departmentDesc !== 'Non Academic') {
                    return redirect()->back()->with('error', 'Sister can only approve Non Academic requests');
                }
                $updateData['status'] = $newStatus;
                break;
                
            case 4: // Principal
                if ($departmentDesc === 'Academic Non-Teaching') {
                    // Single-stage for Academic Non-Teaching
                    $updateData['status'] = $newStatus;
                } elseif ($isTeachingDept) {
                    // Teaching departments - Principal acts on principal_status
                    if ($requestData->ap_status !== 'Approved') {
                        return redirect()->back()->with('error', 'This request must be approved by the Assistant Principal first');
                    }
                    $updateData['principal_status'] = $newStatus;
                    $updateData['principal_acted_by'] = $adminId;
                    $updateData['principal_acted_at'] = now();
                    
                    // Update final status based on principal's action
                    if ($newStatus === 'Approved') {
                        $updateData['status'] = 'Approved';
                    } else {
                        $updateData['status'] = 'Disapproved';
                    }
                } else {
                    return redirect()->back()->with('error', 'Principal cannot approve this department');
                }
                break;
                
            case 5: // AP GS
            case 6: // AP JHS
            case 7: // AP SHS
                if (!$isTeachingDept) {
                    return redirect()->back()->with('error', 'Assistant Principal can only approve teaching department requests');
                }
                
                // Check if AP matches their department
                $apDeptMap = [
                    5 => 'Academic Teaching - Grade School',
                    6 => 'Academic Teaching - Junior High School',
                    7 => 'Academic Teaching - Senior High School'
                ];
                
                if ($departmentDesc !== $apDeptMap[$adminId]) {
                    return redirect()->back()->with('error', 'You can only approve requests from your department');
                }
                
                // AP acts on ap_status
                $updateData['ap_status'] = $newStatus;
                $updateData['ap_acted_by'] = $adminId;
                $updateData['ap_acted_at'] = now();
                
                if ($newStatus === 'Approved') {
                    // Set principal_status to Pending so Principal can act
                    $updateData['principal_status'] = 'Pending';
                    // Keep status as Pending until Principal approves
                } else {
                    // Disapproved - cascade to final status
                    $updateData['status'] = 'Disapproved';
                }
                break;
                
            default:
                return redirect()->back()->with('error', 'Unauthorized action');
        }
        
        DB::connection('dtr')
            ->table('tbl_requests')
            ->where('request_id', $id)
            ->update($updateData);
        
        return redirect()->back()->with('success', 'Request has been ' . strtolower($newStatus) . '!');
    }
    
    public function filterByStatus($status)
    {
        $adminId = session('admin_id');
        
        $query = DB::connection('dtr')
            ->table('tbl_requests')
            ->leftJoin('tbl_employee', 'tbl_requests.employee_idno', '=', 'tbl_employee.employee_idno')
            ->leftJoin('tbl_department', 'tbl_employee.department', '=', 'tbl_department.department_name')
            ->select(
                'tbl_requests.*',
                'tbl_employee.first_name',
                'tbl_employee.middle_name',
                'tbl_employee.last_name',
                'tbl_employee.department as department_name',
                'tbl_department.description as department_description'
            );
        
        // Apply department filtering first
        $filteredQuery = $this->applyDepartmentFilter(clone $query, $adminId);
        
        // Get TOTAL counts for the admin (for filter tabs - always show these)
        $totalPendingCount = (clone $filteredQuery)->where('tbl_requests.status', 'Pending')->count();
        $totalApprovedCount = (clone $filteredQuery)->where('tbl_requests.status', 'Approved')->count();
        $totalDisapprovedCount = (clone $filteredQuery)->where('tbl_requests.status', 'Disapproved')->count();
        
        // Then apply status filter
        if ($status !== 'all') {
            if ($status === 'ap_pending') {
                $filteredQuery->where('tbl_requests.ap_status', 'Pending');
            } elseif ($status === 'principal_pending') {
                $filteredQuery->where('tbl_requests.principal_status', 'Pending');
            } else {
                $filteredQuery->where('tbl_requests.status', ucfirst($status));
            }
        }
        
        $leaveRequests = $filteredQuery->orderBy('tbl_requests.datetime_requested', 'desc')->get();
        
        // Get attachments separately
        $attachments = DB::connection('dtr')
            ->table('tbl_request_attachments')
            ->get()
            ->groupBy('request_id');
        
        return view('users.leave-requests', compact(
            'leaveRequests', 
            'attachments', 
            'totalPendingCount', 
            'totalApprovedCount', 
            'totalDisapprovedCount',
            'status'
        ));
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