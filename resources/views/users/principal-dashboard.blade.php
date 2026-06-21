@extends('layouts.admin2app')

@section('title', 'Principal Dashboard')

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

    body {
        background: #f0f4f8;
        font-family: 'DM Sans', sans-serif;
        color: #1a2a3a;
    }

    /* Page Header - Perfect size */
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
        top: -60px;
        right: -60px;
        width: 180px;
        height: 180px;
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

    .page-header-title i {
        color: var(--gold);
        font-size: 1.4rem;
    }

    .page-header-sub {
        font-size: 0.78rem;
        color: var(--muted);
        margin-top: 4px;
        letter-spacing: 0.03em;
    }

    .btn-refresh {
        background: var(--gold);
        color: var(--navy);
        border: none;
        border-radius: 9px;
        padding: 8px 20px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s, transform 0.15s;
        text-decoration: none;
    }

    .btn-refresh:hover {
        background: var(--gold-light);
        color: var(--navy);
        transform: translateY(-1px);
    }

    /* Stats Cards - Good size */
    .stats-row {
        margin-bottom: 20px;
    }

    .stat-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 3px 15px rgba(10,22,40,0.07);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 22px rgba(10,22,40,0.11);
    }

    .stat-card-body {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-info h6 {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .stat-number {
        font-family: 'DM Serif Display', serif;
        font-size: 1.9rem;
        font-weight: 600;
        margin: 0;
        line-height: 1.2;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    /* Card Styles - Balanced */
    .dash-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 3px 15px rgba(10,22,40,0.07);
        overflow: hidden;
        margin-bottom: 20px;
        animation: fadeUp 0.45s ease both;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    .dash-card-header i {
        font-size: 1rem;
    }

    .dash-card-body {
        padding: 18px 20px;
        background: #fff;
    }

    /* Quick Actions - Balanced */
    .quick-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .quick-action-btn {
        flex: 1;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.85rem;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .quick-action-btn-primary {
        background: var(--navy-mid);
        color: var(--cream);
        border: 1px solid rgba(201,168,76,0.3);
    }

    .quick-action-btn-primary:hover {
        background: var(--navy);
        color: var(--gold);
        transform: translateY(-1px);
    }

    .quick-action-btn-secondary {
        background: #fff;
        color: var(--navy);
        border: 1px solid #e2e8f0;
    }

    .quick-action-btn-secondary:hover {
        border-color: var(--gold);
        color: var(--gold);
        transform: translateY(-1px);
    }

    /* Tables - Balanced */
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

    .modern-table tbody tr {
        transition: background 0.15s;
    }

    .modern-table tbody tr:hover {
        background: #f7fafc;
    }

    .modern-table tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid #f0f4f8;
        color: #2d3748;
        vertical-align: middle;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badges - Balanced */
    .badge-modern {
        padding: 4px 11px;
        border-radius: 18px;
        font-size: 0.72rem;
        font-weight: 500;
        letter-spacing: 0.03em;
        display: inline-block;
    }

    .badge-pending {
        background: rgba(243, 156, 18, 0.12);
        color: #b7770d;
    }

    .badge-approved {
        background: rgba(46, 204, 113, 0.12);
        color: #27ae60;
    }

    .badge-disapproved {
        background: rgba(231, 76, 60, 0.12);
        color: #c0392b;
    }

    /* Scrollable table wrapper - Balanced height */
    .table-scroll {
        max-height: 340px;
        overflow-y: auto;
        border-radius: 10px;
    }

    .table-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .table-scroll::-webkit-scrollbar-track {
        background: #f0f4f8;
        border-radius: 10px;
    }

    .table-scroll::-webkit-scrollbar-thumb {
        background: var(--gold);
        border-radius: 10px;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px 25px;
        color: var(--muted);
        font-size: 0.88rem;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    /* Action button */
    .action-btn {
        background: rgba(17,34,64,0.08);
        color: var(--navy-mid);
        padding: 5px 12px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 0.73rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: var(--gold);
        color: var(--navy);
    }

    /* Footer */
    footer {
        margin-top: 12px;
        padding: 10px 0;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-gavel"></i> Principal Dashboard
        </h2>
        <p class="page-header-sub">Overview of leave requests and faculty management</p>
    </div>
    <button class="btn-refresh" id="refreshStats">
        <i class="fa fa-sync-alt"></i> Refresh Data
    </button>
</div>

<!-- Stats Cards Row -->
<div class="row stats-row">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--muted);"><i class="fa fa-file-alt me-1"></i> TOTAL REQUESTS</h6>
                    <p class="stat-number" style="color: var(--navy);">{{ $totalRequests ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(17, 34, 64, 0.08); color: var(--navy-mid);">
                    <i class="fa fa-folder-open"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--warning);"><i class="fa fa-clock me-1"></i> PENDING</h6>
                    <p class="stat-number" style="color: var(--warning);">{{ $pendingRequests ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1); color: var(--warning);">
                    <i class="fa fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--success);"><i class="fa fa-check-circle me-1"></i> APPROVED</h6>
                    <p class="stat-number" style="color: var(--success);">{{ $approvedRequests ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(46, 204, 113, 0.1); color: var(--success);">
                    <i class="fa fa-thumbs-up"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--danger);"><i class="fa fa-times-circle me-1"></i> DISAPPROVED</h6>
                    <p class="stat-number" style="color: var(--danger);">{{ $disapprovedRequests ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(231, 76, 60, 0.1); color: var(--danger);">
                    <i class="fa fa-ban"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row mb-3">
    <div class="col-12">
        <div class="dash-card">
            <div class="dash-card-header primary">
                <i class="fa fa-bolt"></i> Quick Actions
            </div>
            <div class="dash-card-body">
                <div class="quick-actions">
                    <a href="{{ url('/admin-leave-requests') }}" class="quick-action-btn quick-action-btn-primary">
                        <i class="fa fa-file-alt"></i> Review Leave Requests
                        @if(($pendingRequests ?? 0) > 0)
                            <span class="badge" style="background: var(--gold); color: var(--navy); border-radius: 18px; padding: 2px 7px; font-size: 0.72rem;">{{ $pendingRequests }}</span>
                        @endif
                    </a>
                    <a href="#" class="quick-action-btn quick-action-btn-secondary" onclick="window.print();">
                        <i class="fa fa-print"></i> Print Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Leave Requests Table -->
<div class="dash-card">
    <div class="dash-card-header primary">
        <i class="fa fa-clock"></i> Recent Leave Requests
        <span style="margin-left: auto; font-size: 0.72rem; font-weight: normal;">
            <i class="fa fa-arrow-right"></i> Latest 5 requests
        </span>
    </div>
    <div class="dash-card-body">
        @if(isset($recentRequests) && count($recentRequests) > 0)
            <div class="table-scroll">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Employee ID</th>
                            <th>Request Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Day Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRequests as $request)
                        <tr>
                            <td>{{ $request->request_id }}</td>
                            <td>{{ $request->employee_idno }}</td>
                            <td>{{ $request->request_type }}</td>
                            <td>{{ date('D, F j, Y', strtotime($request->datetime_start)) }}</td>
                            <td>{{ date('D, F j, Y', strtotime($request->datetime_end)) }}</td>
                            <td>
                                @if($request->request_day_type == 'Half Day')
                                    <span class="badge-modern" style="background: rgba(201,168,76,0.12); color: #b8860b;">Half Day</span>
                                @else
                                    <span class="badge-modern" style="background: rgba(136,146,164,0.12); color: var(--muted);">Whole Day</span>
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
                                <a href="#" class="action-btn">
                                    <i class="fa fa-eye"></i> View
                                </a>
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

<script>
    document.getElementById('refreshStats')?.addEventListener('click', function() {
        location.reload();
    });
</script>
@endsection