@extends('layouts.app')

@section('title', 'Dashboard')

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

    .page-header-title i {
        color: var(--gold);
        font-size: 1.5rem;
    }

    .page-header-sub {
        font-size: 0.82rem;
        color: var(--muted);
        margin-top: 4px;
        letter-spacing: 0.03em;
    }

    .btn-borrow {
        background: var(--gold);
        color: var(--navy);
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s, transform 0.15s;
        text-decoration: none;
    }

    .btn-borrow:hover {
        background: var(--gold-light);
        color: var(--navy);
        transform: translateY(-2px);
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

    /* Cards */
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
    }

    .dash-card-header.gold {
        background: linear-gradient(90deg, #b8860b, var(--gold-light));
        color: var(--navy);
    }

    .dash-card-header.blue {
        background: linear-gradient(90deg, var(--navy), var(--navy-mid));
        color: var(--cream);
    }

    .dash-card-header.green {
        background: linear-gradient(90deg, #1a6b3a, #2ecc71);
        color: #fff;
    }

    .dash-card-body {
        padding: 16px;
        background: #fff;
    }

    /* Tables */
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

    .modern-table .group-row td {
        background: #eef2f7;
        color: var(--navy);
        font-weight: 600;
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 8px 14px;
    }

    /* Badges */
    .badge-modern {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.03em;
    }

    .badge-inuse {
        background: rgba(231,76,60,0.12);
        color: #c0392b;
    }

    .badge-upcoming {
        background: rgba(243,156,18,0.12);
        color: #b7770d;
    }

    /* Action buttons */
    .action-btn {
        width: 30px;
        height: 30px;
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
    .action-btn.approve { background: rgba(46,204,113,0.12); color: #27ae60; }
    .action-btn.disapprove { background: rgba(231,76,60,0.12); color: #e74c3c; }
    .action-btn.disregard { background: rgba(108,117,125,0.12); color: #6c757d; }
    .action-btn.remove { background: rgba(231,76,60,0.12); color: #e74c3c; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 30px;
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* Scrollable table wrapper */
    .table-scroll {
        max-height: 420px;
        overflow-y: auto;
        border-radius: 10px;
    }

    .table-scroll::-webkit-scrollbar { width: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: #f0f4f8; }
    .table-scroll::-webkit-scrollbar-thumb { background: #c9a84c55; border-radius: 10px; }

    /* Modal */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--navy-mid), var(--navy));
        color: var(--cream);
        border-radius: 16px 16px 0 0;
        border-bottom: none;
        padding: 20px 24px;
    }

    .modal-header .btn-close { filter: invert(1); }
    .modal-title { font-family: 'DM Serif Display', serif; font-size: 1.2rem; }

    .modal-body { padding: 24px; }
    .modal-footer { border-top: 1px solid #f0f4f8; padding: 16px 24px; }

    .form-label {
        font-size: 0.78rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
    }

    .btn-submit {
        background: var(--gold);
        color: var(--navy);
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: background 0.2s;
    }

    .btn-submit:hover { background: var(--gold-light); color: var(--navy); }
    .btn-cancel { border-radius: 8px; font-size: 0.9rem; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-house"></i> Dashboard
        </h2>
        <p class="page-header-sub">Overview of inventory, requests and room schedules</p>
    </div>
    <a href="#" class="btn-borrow" data-bs-toggle="modal" data-bs-target="#borrowItemModal">
        <i class="fa fa-arrow-down"></i> Borrow Item
    </a>
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

<div class="row">
    <!-- Borrowed Items -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header gold">
                <i class="fa fa-box-open"></i> Borrowed Items (to be returned)
            </div>
            <div class="dash-card-body">
                @if($recentBorrows->count())
                    <div class="table-scroll" style="max-height: 500px;">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Borrower</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBorrows as $borrow)
                                    <tr>
                                        <td>{{ $borrow->borrowed_at->format('d-m-Y') }}</td>
                                        <td>{{ $borrow->item->item_name ?? 'N/A' }}</td>
                                        <td>{{ $borrow->item->category ?? 'N/A' }}</td>
                                        <td>{{ $borrow->item->location ?? 'N/A' }}</td>
                                        <td>{{ $borrow->borrower_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state"><i class="fa fa-inbox fa-2x mb-2 d-block"></i>No borrowed items found.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="col-md-6">
        <div class="dash-card">
            <div class="dash-card-header blue">
                <i class="fa fa-clipboard-list"></i> Pending Requests
            </div>
            <div class="dash-card-body">
                @if($pendingRequests->count())
                    <div class="table-scroll" style="max-height: 500px;">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Filed</th>
                                    <th>Requested</th>
                                    <th>Type</th>
                                    <th>Item</th>
                                    <th>Requester</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRequests as $type => $requests)
                                    <tr class="group-row">
                                        <td colspan="7">{{ $type }}</td>
                                    </tr>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{ $request->created_at->format('d-m-Y') }}</td>
                                            <td>{{ date('d-m-Y', strtotime($request->borrowed_at)) }}</td>
                                            <td>{{ $request->request_type }}</td>
                                            <td>{{ $request->item->item_name ?? $request->reason ?? 'N/A' }}</td>
                                            <td>{{ $request->requester_name }}</td>
                                            <td>{{ date('h:i A', strtotime($request->time_from)) }} - {{ date('h:i A', strtotime($request->time_to)) }}</td>
                                            <td>
                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Approve this request?');">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit" class="action-btn approve" title="Approve"><i class="fas fa-check"></i></button>
                                                </form>
                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Disapprove this request?');">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="Disapproved">
                                                    <button type="submit" class="action-btn disapprove" title="Disapprove"><i class="fas fa-times"></i></button>
                                                </form>
                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Disregard this request?');">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="Disregarded">
                                                    <button type="submit" class="action-btn disregard" title="Disregard"><i class="fas fa-ban"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state"><i class="fa fa-clipboard fa-2x mb-2 d-block"></i>No pending requests found.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Room Schedules -->
<div class="dash-card">
    <div class="dash-card-header green">
        <i class="fa fa-calendar-days"></i> Scheduled Room Bookings
    </div>
    <div class="dash-card-body">
        @if($roomSchedules->count())
            <div class="table-scroll" style="max-height: 500px;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Room</th>
                            <th>Booked By</th>
                            <th>Participant</th>
                            <th>Department</th>
                            <th>Purpose</th>
                            <th>Materials</th>
                            <th>Time Slot</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomSchedules as $schedule)
                            @php
                                $now   = strtotime(date('Y-m-d H:i:s'));
                                $start = strtotime($schedule->scheduled_at . ' ' . $schedule->time_from);
                                $end   = strtotime($schedule->scheduled_at . ' ' . $schedule->time_to);
                                if ($now > $end) continue;
                                if ($now >= $start && $now <= $end) {
                                    $roomStatus  = 'In Use';
                                    $badgeClass  = 'badge-inuse';
                                } else {
                                    $roomStatus  = 'Upcoming';
                                    $badgeClass  = 'badge-upcoming';
                                }
                            @endphp
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($schedule->scheduled_at)) }}</td>
                                <td>{{ $schedule->room_name }}</td>
                                <td>{{ $schedule->requester_name }}</td>
                                <td>{{ $schedule->level }}</td>
                                <td>{{ $schedule->department }}</td>
                                <td>{{ $schedule->purpose }}</td>
                                <td>{{ $schedule->material }}</td>
                                <td>{{ date('h:i A', strtotime($schedule->time_from)) }} - {{ date('h:i A', strtotime($schedule->time_to)) }}</td>
                                <td><span class="badge-modern {{ $badgeClass }}">{{ $roomStatus }}</span></td>
                                <td>
                                    <form action="{{ route('roomscheduler.destroy', $schedule->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Remove this schedule?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn remove" title="Remove"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state"><i class="fa fa-calendar-xmark fa-2x mb-2 d-block"></i>No scheduled rooms found.</div>
        @endif
    </div>
</div>

<!-- Borrow Item Modal -->
<div class="modal fade" id="borrowItemModal" tabindex="-1" aria-labelledby="borrowItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('borrows.store') }}" method="POST">
                @csrf
                <input type="hidden" name="request_type" value="Borrow Item">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrowItemModalLabel"><i class="fa fa-arrow-down me-2"></i>Borrow an Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Item</label>
                        <select name="inventory_item_id" class="form-select" required>
                            <option value="">-- Choose an item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ $item->status === 'Unavailable' ? 'disabled' : '' }}>
                                    {{ $item->item_name }} - {{ $item->category }} {{ $item->status === 'Unavailable' ? '(Unavailable)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Borrower Name</label>
                        <input type="text" name="borrower_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Venue</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time From</label>
                            <input type="time" name="time_from" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time To</label>
                            <input type="time" name="time_to" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purpose</label>
                        <input type="text" name="reason" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Issued Date</label>
                        <input type="date" name="borrowed_at" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-submit">Submit</button>
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    ['success-alert','error-alert'].forEach(id => {
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