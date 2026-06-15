@extends(session('admin_id') == 4 ? 'layouts.admin2app' : 'layouts.adminapp')

@section('title', 'Leave Requests')

@section('content')
<style>
    :root {
        --navy: #0a1628;
        --navy-mid: #112240;
        --gold: #c9a84c;
        --gold-light: #e8c97a;
        --cream: #f5f0e8;
        --muted: #8892a4;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
    }

    .page-header {
        background: linear-gradient(135deg, var(--navy-mid) 0%, var(--navy) 100%);
        border-radius: 14px;
        padding: 22px 28px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 6px 20px rgba(10,22,40,0.14);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1.6rem;
        color: var(--cream);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header-title i { color: var(--gold); font-size: 1.4rem; }
    .page-header-sub { font-size: 0.78rem; color: var(--muted); margin-top: 4px; }

    .dash-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 3px 15px rgba(10,22,40,0.07);
        overflow: hidden;
        margin-bottom: 20px;
        animation: fadeUp 0.45s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .dash-card-header {
        padding: 13px 20px;
        font-family: 'DM Serif Display', serif;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: none;
    }

    .dash-card-header.primary {
        background: linear-gradient(90deg, var(--navy), var(--navy-mid));
        color: var(--cream);
    }

    .dash-card-body { padding: 18px 20px; background: #fff; }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 20px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        background: #f0f4f8;
        color: var(--navy);
    }

    .filter-btn:hover {
        background: var(--gold);
        color: var(--navy);
        transform: translateY(-1px);
    }

    .filter-btn.active {
        background: var(--navy-mid);
        color: var(--gold);
    }

    /* Tables */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.82rem;
    }

    .modern-table thead th {
        background: #f0f4f8;
        color: var(--navy);
        font-weight: 600;
        font-size: 0.72rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 10px 14px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .modern-table tbody tr { transition: background 0.15s; }
    .modern-table tbody tr:hover { background: #f7fafc; }

    .modern-table tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid #f0f4f8;
        color: #2d3748;
        vertical-align: middle;
    }

    .modern-table tbody tr:last-child td { border-bottom: none; }
    /* Adjust Department and Leave Type column widths */
    .modern-table th:nth-child(4),
    .modern-table td:nth-child(4) {
        width: 8%;
        white-space: normal;
    }

    .modern-table th:nth-child(5),
    .modern-table td:nth-child(5) {
        width: 8%;
        white-space: normal;
    }
    /* Badges */
    .badge-modern {
        padding: 4px 11px;
        border-radius: 18px;
        font-size: 0.72rem;
        font-weight: 500;
        letter-spacing: 0.03em;
        display: inline-block;
    }

    .badge-pending     { background: rgba(243,156,18,0.12); color: #b7770d; }
    .badge-approved    { background: rgba(46,204,113,0.12); color: #27ae60; }
    .badge-disapproved { background: rgba(231,76,60,0.12); color: #c0392b; }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 5px 12px;
        border-radius: 7px;
        font-size: 0.73rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-approve {
        background: rgba(46,204,113,0.12);
        color: #27ae60;
    }

    .btn-approve:hover {
        background: #27ae60;
        color: white;
        transform: translateY(-1px);
    }

    .btn-disapprove {
        background: rgba(231,76,60,0.12);
        color: #c0392b;
    }

    .btn-disapprove:hover {
        background: #c0392b;
        color: white;
        transform: translateY(-1px);
    }

    .btn-view {
        background: rgba(17,34,64,0.08);
        color: var(--navy-mid);
    }

    .btn-view:hover {
        background: var(--gold);
        color: var(--navy);
        transform: translateY(-1px);
    }

    .alert-modern {
        border-radius: 10px;
        border: none;
        padding: 14px 20px;
        margin-bottom: 20px;
        animation: fadeUp 0.4s ease both;
    }

    .table-scroll {
        max-height: 500px;
        overflow-y: auto;
        border-radius: 10px;
    }

    .table-scroll::-webkit-scrollbar { width: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: #f0f4f8; border-radius: 10px; }
    .table-scroll::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 10px; }

    .empty-state { text-align: center; padding: 60px 25px; color: var(--muted); }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; opacity: 0.5; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-file-alt"></i> Leave Requests
        </h2>
        <p class="page-header-sub">Manage and review faculty leave requests</p>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div id="success-alert" class="alert-modern alert alert-success">
        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="{{ route('leave-requests.index') }}" class="filter-btn {{ !isset($status) ? 'active' : '' }}">
        All <span class="badge" style="background: var(--gold); color: var(--navy); margin-left: 5px;">{{ ($pendingCount ?? 0) + ($approvedCount ?? 0) + ($disapprovedCount ?? 0) }}</span>
    </a>
    <a href="{{ route('leave-requests.filter', 'pending') }}" class="filter-btn {{ isset($status) && $status == 'pending' ? 'active' : '' }}">
        Pending <span class="badge" style="background: var(--warning); color: white; margin-left: 5px;">{{ $pendingCount ?? 0 }}</span>
    </a>
    <a href="{{ route('leave-requests.filter', 'approved') }}" class="filter-btn {{ isset($status) && $status == 'approved' ? 'active' : '' }}">
        Approved <span class="badge" style="background: var(--success); color: white; margin-left: 5px;">{{ $approvedCount ?? 0 }}</span>
    </a>
    <a href="{{ route('leave-requests.filter', 'disapproved') }}" class="filter-btn {{ isset($status) && $status == 'disapproved' ? 'active' : '' }}">
        Disapproved <span class="badge" style="background: var(--danger); color: white; margin-left: 5px;">{{ $disapprovedCount ?? 0 }}</span>
    </a>
</div>

<!-- Leave Requests Table -->
<div class="dash-card">
    <div class="dash-card-header primary">
        <i class="fa fa-list"></i> Leave Requests List
        <span style="margin-left: auto; font-size: 0.72rem; font-weight: normal;">
            <i class="fa fa-clock"></i> Sorted by date requested
        </span>
    </div>
    <div class="dash-card-body">
        @if(isset($leaveRequests) && count($leaveRequests) > 0)
            <div class="table-scroll">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Leave ID</th>
                            <th>Date Filed</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Leave Type</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th># Days</th>
                            <th>Reason</th>
                            <th>Attachments</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                        @php
                            // Combine names
                            $fullName = trim($request->first_name . ' ' . 
                                            ($request->middle_name ? substr($request->middle_name, 0, 1) . '. ' : '') . 
                                            $request->last_name);
                        @endphp
                        <tr>
                            <td>{{ $request->request_id }}</td>
                            <td>{{ date('M d, Y', strtotime($request->datetime_requested)) }}<br>
                                <small style="color: var(--muted);">{{ date('h:i A', strtotime($request->datetime_requested)) }}</small>
                            </td>
                            <td>
                                <strong>{{ $fullName }}</strong><br>
                                <small style="color: var(--muted);">ID: {{ $request->employee_idno }}</small>
                            </td>
                            <td>{{ $request->department ?? 'N/A' }}</td>
                            <td>{{ $request->request_type }}</td>
                            <td>{{ date('D, M d, Y', strtotime($request->datetime_start)) }}</td>
                            <td>{{ date('D, M d, Y', strtotime($request->datetime_end)) }}</td>
                            <td>
                                @if($request->request_day_type == 'Half Day')
                                    <span class="badge-modern" style="background: rgba(201,168,76,0.12); color: #b8860b;">Half Day</span>
                                @else
                                    <span class="badge-modern" style="background: rgba(136,146,164,0.12); color: var(--muted);">Whole Day</span>
                                @endif
                            </td>
                            <td>
                                <small style="color: var(--muted);">{{ Str::limit($request->request_details, 50) }}</small>
                            </td>
                            <td class="text-center">
                                @php
                                    $requestAttachments = $attachments[$request->request_id] ?? collect();
                                    $hasAttachments = $requestAttachments->count() > 0;
                                @endphp
                                
                                @if($hasAttachments)
                                    <button type="button" class="btn-action btn-view" onclick="viewAttachments({{ $request->request_id }})">
                                        <i class="fa fa-paperclip"></i> 
                                        {{ $requestAttachments->count() }} file(s)
                                    </button>
                                @else
                                    <span style="color: var(--muted);">No file</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $status = $request->status ?? 'Pending';
                                    $badgeClass = match($status) {
                                        'Approved' => 'badge-approved',
                                        'Disapproved' => 'badge-disapproved',
                                        default => 'badge-pending'
                                    };
                                @endphp
                                <span class="badge-modern {{ $badgeClass }}">{{ $status }}</span>
                            </td>
                            <td>
                                <div class="action-group">
                                    @if($request->status == 'Pending')
                                        <form action="{{ route('leave-requests.update-status', $request->request_id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Approved">
                                            <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this request?')">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('leave-requests.update-status', $request->request_id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Disapproved">
                                            <button type="submit" class="btn-action btn-disapprove" onclick="return confirm('Disapprove this request?')">
                                                <i class="fa fa-times"></i> Disapprove
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn-action btn-view" disabled style="opacity: 0.5; cursor: not-allowed;">
                                            <i class="fa fa-lock"></i> {{ $status }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-inbox"></i>
                <p>No leave requests found.</p>
            </div>
        @endif
    </div>
</div>
<!-- Attachments Modal -->
<div class="modal fade" id="attachmentsModal" tabindex="-1" aria-labelledby="attachmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--navy-mid), var(--navy)); color: var(--cream);">
                <h5 class="modal-title" id="attachmentsModalLabel">
                    <i class="fa fa-paperclip me-2"></i>Request Attachments
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="attachmentsList">
                <!-- Attachments will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const el = document.getElementById('success-alert');
        if (el) {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }
    }, 3000);
</script>
<script>
    setTimeout(() => {
        const el = document.getElementById('success-alert');
        if (el) {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }
    }, 3000);
    
    // Function to view attachments
    function viewAttachments(requestId) {
        // Get attachments data from the page
        const attachments = @json($attachments);
        const requestAttachments = attachments[requestId] || [];
        const validAttachments = requestAttachments.filter(att => att.attachment_id !== null);
        
        if (validAttachments.length === 0) {
            document.getElementById('attachmentsList').innerHTML = '<p class="text-center text-muted">No attachments found.</p>';
        } else {
            let html = '<div class="list-group">';
            validAttachments.forEach(att => {
                const fileUrl = att.file_path;
                const fileName = att.file_name;
                const uploadedAt = new Date(att.uploaded_at).toLocaleString();
                
                html += `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa fa-file me-2" style="color: var(--gold);"></i>
                                <strong>${fileName}</strong>
                                <br>
                                <small class="text-muted">Uploaded: ${uploadedAt}</small>
                            </div>
                            <a href="/misystem/download-attachment/${att.attachment_id}" class="btn-action btn-approve">
                                <i class="fa fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            document.getElementById('attachmentsList').innerHTML = html;
        }
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('attachmentsModal'));
        modal.show();
    }
</script>
@endsection