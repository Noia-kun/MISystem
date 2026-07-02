@extends('layouts.userapp')

@section('title', 'User Dashboard')

@section('content')
<style>
    :root {
        --navy: #0a1628;
        --navy-mid: #112240;
        --gold: #c9a84c;
        --gold-light: #e8c97a;
        --cream: #f5f0e8;
        --muted: #8892a4;
    }

    body {
        background: #f0f4f8;
        font-family: 'DM Sans', sans-serif;
        color: #1a2a3a;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--navy-mid) 0%, var(--navy) 100%);
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 32px rgba(10,22,40,0.18);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,168,76,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1.8rem;
        color: var(--cream);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header-title i { color: var(--gold); font-size: 1.5rem; }

    .page-header-sub {
        font-size: 0.82rem;
        color: var(--muted);
        margin-top: 4px;
        letter-spacing: 0.03em;
    }

    /* Alert */
    .alert-modern {
        border-radius: 10px;
        border: none;
        padding: 14px 20px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        animation: fadeUp 0.4s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Card */
    .dash-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(10,22,40,0.08);
        overflow: hidden;
        margin-bottom: 24px;
        animation: fadeUp 0.5s ease both;
    }

    .dash-card-header {
        padding: 16px 22px;
        font-family: 'DM Serif Display', serif;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: none;
        background: linear-gradient(90deg, var(--navy), var(--navy-mid));
        color: var(--cream);
    }

    .dash-card-body {
        padding: 16px;
        background: #fff;
    }

    /* Table */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.85rem;
    }

    .modern-table thead th {
        background: #f0f4f8;
        color: var(--navy);
        font-weight: 500;
        font-size: 0.75rem;
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

    /* Action buttons */
    .action-btn {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        transition: transform 0.15s, opacity 0.15s;
    }

    .action-btn:hover { transform: scale(1.1); opacity: 0.85; }
    .action-btn.approve    { background: rgba(46,204,113,0.12); color: #27ae60; }
    .action-btn.disapprove { background: rgba(231,76,60,0.12);  color: #e74c3c; }
    .action-btn.disregard  { background: rgba(108,117,125,0.12); color: #6c757d; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* Scrollable table */
    .table-scroll { max-height: 500px; overflow-y: auto; border-radius: 10px; }
    .table-scroll::-webkit-scrollbar { width: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: #f0f4f8; }
    .table-scroll::-webkit-scrollbar-thumb { background: #c9a84c55; border-radius: 10px; }

    /* Stats Cards */
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
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-house"></i> User Dashboard
        </h2>
        <p class="page-header-sub">View and manage your pending requests</p>
    </div>
</div>
<!-- Stats Cards Row -->
<div class="row stats-row">
    <!-- Total Requests -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--muted);"><i class="fa fa-file-alt me-1"></i> TOTAL REQUESTS</h6>
                    <p class="stat-number" style="color: var(--navy);">{{ $totalRequests ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(17,34,64,0.08); color: var(--navy-mid);">
                    <i class="fa fa-folder-open"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--warning);"><i class="fa fa-clock me-1"></i> PENDING</h6>
                    <p class="stat-number" style="color: var(--warning);">{{ $pendingRequestsCount ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(243,156,18,0.1); color: var(--warning);">
                    <i class="fa fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--success);"><i class="fa fa-check-circle me-1"></i> APPROVED</h6>
                    <p class="stat-number" style="color: var(--success);">{{ $approvedRequestsCount ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(46,204,113,0.1); color: var(--success);">
                    <i class="fa fa-thumbs-up"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Disapproved -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-info">
                    <h6 style="color: var(--danger);"><i class="fa fa-times-circle me-1"></i> DISAPPROVED</h6>
                    <p class="stat-number" style="color: var(--danger);">{{ $disapprovedRequestsCount ?? 0 }}</p>
                </div>
                <div class="stat-icon" style="background: rgba(231,76,60,0.1); color: var(--danger);">
                    <i class="fa fa-ban"></i>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Alerts --}}
@if(session('success'))
    <div id="success-alert" class="alert-modern alert alert-success">
        <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
    </div>
@endif
@if(session('errors'))
    <div id="error-alert" class="alert-modern alert alert-danger">
        <i class="fa fa-circle-xmark me-2"></i>{{ session('errors') }}
    </div>
@endif

<!-- Pending Requests -->
<div class="dash-card">
    <div class="dash-card-header">
        <i class="fa fa-clipboard-list"></i> Pending Requests
    </div>
    <div class="dash-card-body">
        @if($pendingRequests->count())
            <div class="table-scroll" style="max-height: 500px;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Date Filed</th>
                            <th>Date Requested</th>
                            <th>Type</th>
                            <th>Participant</th>
                            <th>Purpose</th>
                            <th>Materials</th>
                            <th>Venue</th>
                            <th>Requester</th>
                            <th>Time Slot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                            <tr>
                                <td>{{ $request->created_at->format('d-m-Y') }}</td>
                                <td>{{ date('d-m-Y', strtotime($request->borrowed_at)) }}</td>
                                <td>{{ $request->request_type }}</td>
                                <td>{{ $request->item->category ?? $request->level ?? 'N/A' }}</td>
                                <td>{{ $request->item->item_name ?? $request->reason ?? 'N/A' }}</td>
                                <td>{{ $request->material }}</td>
                                <td>{{ $request->location }}</td>
                                <td>{{ $request->requester_name }}</td>
                                <td>{{ date('h:i A', strtotime($request->time_from)) }} - {{ date('h:i A', strtotime($request->time_to)) }}</td>
                                <td>
                                    <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Approve this request?');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Approved">
                                        <input type="hidden" name="redirect_to" value="user-dashboard">
                                        <button type="submit" class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Disapprove this request?');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Disapproved">
                                        <input type="hidden" name="redirect_to" value="user-dashboard">
                                        <button type="submit" class="action-btn disapprove" title="Disapprove"><i class="fas fa-times"></i></button>
                                    </form>
                                    <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Disregard this request?');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Disregarded">
                                        <input type="hidden" name="redirect_to" value="user-dashboard">
                                        <button type="submit" class="action-btn disregard" title="Disregard"><i class="fas fa-ban"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-clipboard fa-2x mb-2 d-block"></i>
                No pending requests found.
            </div>
        @endif
    </div>
</div>

<script>
    ['success-alert', 'error-alert'].forEach(id => {
        setTimeout(() => {
            const el = document.getElementById(id);
            if (el) {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }
        }, 2000);
    });
</script>

@endsection