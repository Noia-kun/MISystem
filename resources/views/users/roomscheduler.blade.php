@extends('layouts.userapp')

@section('title', 'User RoomScheduler')

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

    body { background: #f0f4f8; font-family: 'DM Sans', sans-serif; color: #1a2a3a; }

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

    .page-header-title i { color: var(--gold); }
    .page-header-sub { font-size: 0.82rem; color: var(--muted); margin-top: 4px; }

    .btn-header {
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
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

    .btn-header:hover { transform: translateY(-2px); }
    .btn-header.gold  { background: var(--gold); color: var(--navy); }
    .btn-header.gold:hover { background: var(--gold-light); color: var(--navy); }
    .btn-header.ghost { background: rgba(255,255,255,0.1); color: var(--cream); border: 1px solid rgba(255,255,255,0.2); }
    .btn-header.ghost:hover { background: rgba(255,255,255,0.18); color: var(--cream); }

    .btn-submit { background: var(--gold); color: var(--navy); border: none; border-radius: 8px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; transition: background 0.2s; }
    .btn-submit:hover { background: var(--gold-light); color: var(--navy); }
    .btn-cancel { border-radius: 8px; font-size: 0.9rem; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Cards */
    .dash-card {
        border: none; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(10,22,40,0.08);
        overflow: hidden;
        margin-bottom: 24px;
        animation: fadeUp 0.5s ease both;
    }

    .dash-card-header {
        padding: 16px 22px;
        font-family: 'DM Serif Display', serif;
        font-size: 1rem;
        display: flex; align-items: center; gap: 10px;
        border-bottom: none;
    }

    .dash-card-header.blue  { background: linear-gradient(90deg, var(--navy), var(--navy-mid)); color: var(--cream); }
    .dash-card-header.teal  { background: linear-gradient(90deg, #117a8b, #23b9d4); color: #fff; }
    .dash-card-header.green { background: linear-gradient(90deg, #1a6b3a, #2ecc71); color: #fff; }
    .dash-card-header.dark  { background: linear-gradient(90deg, #1a1a2e, #2d2d44); color: var(--cream); }

    .dash-card-body { padding: 16px; background: #fff; }

    /* Table */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.83rem;
    }

    .modern-table thead th {
        background: #f0f4f8;
        color: var(--navy);
        font-weight: 500;
        font-size: 0.72rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 10px 12px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .modern-table tbody tr { transition: background 0.15s; }
    .modern-table tbody tr:hover { background: #f7fafc; }

    .modern-table tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f4f8;
        color: #2d3748;
        vertical-align: middle;
    }

    .modern-table tbody tr:last-child td { border-bottom: none; }

    /* Modal */
    .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }

    .modal-header {
        border-radius: 16px 16px 0 0;
        border-bottom: none;
        padding: 20px 24px;
    }

    .modal-header.navy { background: linear-gradient(135deg, var(--navy-mid), var(--navy)); color: var(--cream); }
    .modal-header.info-head { background: linear-gradient(135deg, #117a8b, #23b9d4); color: #fff; }
    .modal-header.warn-head { background: linear-gradient(135deg, #b7770d, var(--gold-light)); color: var(--navy); }

    .modal-header .btn-close.light { filter: invert(1); }
    .modal-title { font-family: 'DM Serif Display', serif; font-size: 1.15rem; }
    .modal-body { padding: 24px; }
    .modal-footer { border-top: 1px solid #f0f4f8; padding: 16px 24px; }

    .form-label {
        font-size: 0.75rem; font-weight: 500;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--muted); margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 8px; border: 1px solid #e2e8f0;
        padding: 10px 14px; font-size: 0.9rem;
        transition: border-color 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
    }
    /* Status badge */
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 500;
    }

    .status-approved    { background: rgba(46,204,113,0.12); color: #27ae60; }
    .status-disapproved { background: rgba(231,76,60,0.12);  color: #c0392b; }
    .status-disregarded { background: rgba(108,117,125,0.12); color: #6c757d; }
    .status-pending     { background: rgba(243,156,18,0.12); color: #b7770d; }

    /* Not returned badge */
    .badge-not-returned { background: rgba(231,76,60,0.12); color: #c0392b; font-size: 0.72rem; padding: 3px 8px; border-radius: 20px; }
    .badge-returned     { background: rgba(46,204,113,0.12); color: #27ae60; font-size: 0.72rem; padding: 3px 8px; border-radius: 20px; }

    /* Action buttons */
    .action-btn {
        width: 30px; height: 30px;
        border-radius: 8px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.78rem; cursor: pointer;
        transition: transform 0.15s, opacity 0.15s;
    }

    .action-btn:hover { transform: scale(1.1); opacity: 0.85; }
    .action-btn.delete   { background: rgba(231,76,60,0.12);  color: #e74c3c; }
    
    /* Table scroll */
    .table-scroll { max-height: 320px; overflow-y: auto; border-radius: 10px; }
    .table-scroll::-webkit-scrollbar { width: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: #f0f4f8; }
    .table-scroll::-webkit-scrollbar-thumb { background: #c9a84c55; border-radius: 10px; }

    /* Empty state */
    .empty-state { text-align: center; padding: 30px; color: var(--muted); font-size: 0.9rem; }

</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-school"></i> Room Scheduler
        </h2>
        <p class="page-header-sub">Manage room bookings and view schedule history</p>
    </div>
    <a href="#" class="btn-header gold" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="fa fa-plus"></i> Add Room Schedule
    </a>
</div>

    @if(session('success'))
        <div id="success-alert" class="alert-modern alert alert-success">
            <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Scheduled Room Bookings Section -->
<div class="dash-card shadow mb-4">
    <!-- Schbeduled Room Bookings Header -->
    <div class="dash-card-header green">
        <div class="header-left"><i class="fa fa-calendar-days"></i> Scheduled Room Bookings</div>
    </div>
    <div class="dash-card-body">
        @if($roomSchedules->count())
            <div class="table-scroll" style="max-height: 500px;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Date Scheduled</th>
                            <th>Room Name</th>
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
                                $now = strtotime(date('Y-m-d H:i:s'));
                                $start = strtotime($schedule->scheduled_at . ' ' . $schedule->time_from);
                                $end = strtotime($schedule->scheduled_at . ' ' . $schedule->time_to);

                                 // Skip the booking if it has already finished
                                if ($now > $end) {
                                    continue;
                                }

                                if ($now >= $start && $now <= $end) {
                                    $roomStatus = 'In Use';
                                    $badgeColor = 'danger'; // Red
                                } elseif ($now < $start) {
                                    $roomStatus = 'Upcoming';
                                    $badgeColor = 'warning'; // Yellow
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
                                <td>
                                    <span class="status-badge {{ $roomStatus === 'In Use' ? 'status-disapproved' : 'status-pending' }}">
                                        {{ $roomStatus }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('user.roomscheduler.destroy', $schedule->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to remove this schedule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Remove">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            
        @else
            <div class="empty-state">
                <i class="fa fa-calendar-xmark fa-2x mb-2 d-block"></i>
                No scheduled rooms found.
            </div>
        @endif
    </div>
</div>

<!-- Room Schedule Logs Section -->
<div class="dash-card shadow mb-4 mt-5">
    <div class="dash-card-header dark">
        <div class="header-left"><i class="fa fa-clock-rotate-left"></i> Room Schedule Logs</div>
    </div>
    <div class="dash-card-body">
        @if($roomLogs->count())
            <div class="table-scroll" style="max-height: 500px;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Date Scheduled</th>
                            <th>Date Filed</th>
                            <th>Room Name</th>
                            <th>Booked By</th>
                            <th>Participant</th>
                            <th>Department</th>
                            <th>Purpose</th>
                            <th>Materials</th>
                            <th>Time Slot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomLogs as $log)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($log->scheduled_at)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($log->created_at)) }}</td>
                                <td>{{ $log->room_name }}</td>
                                <td>{{ $log->requester_name }}</td>
                                <td>{{ $log->level }}</td>
                                <td>{{ $log->department }}</td>
                                <td>{{ $log->purpose }}</td>
                                <td>{{ $log->material }}</td>
                                <td>{{ date('h:i A', strtotime($log->time_from)) }} - {{ date('h:i A', strtotime($log->time_to)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="empty-state">
                <i class="fa fa-calendar-xmark fa-2x mb-2 d-block"></i>
                No room schedule logs found.
            </div>
        @endif
    </div>
</div>



<!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('user.roomscheduler.store') }}" method="POST">
                    @csrf
                    <div class="modal-header navy">
                        <h5 class="modal-title"><i class="fa fa-calendar me-2"></i>Schedule a Room</h5>
                        <button type="button" class="btn-close light" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="room_name" class="form-label">Select Room</label>
                            <select name="room_name" id="room_name" class="form-select" required>
                                <option value="">-- Choose a room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->room_name }}" {{ $room->status === 'Unavailable' ? 'disabled' : '' }}>
                                        {{ $room->room_name }} - {{ $room->room_location }} {{ $room->status === 'Unavailable' ? '(Unavailable)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requester_name" class="form-label">Booked By</label>
                            <input type="text" name="requester_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Participant</label>
                            <input type="text" name="level" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <input type="text" name="purpose" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="material" class="form-label">Materials Needed</label>
                            <input type="text" name="material" class="form-control" placeholder="e.g. Projector, Whiteboard">
                        </div>
                        <div class="mb-3">
                            <label for="scheduled_at" class="form-label">Schedule Date</label>
                            <input type="date" name="scheduled_at" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="time_from" class="form-label">Time From</label>
                                <input type="time" name="time_from" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time_to" class="form-label">Time To</label>
                                <input type="time" name="time_to" class="form-control" required>
                            </div>
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

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var addRoomModal = new bootstrap.Modal(document.getElementById('addRoomModal'));
                addRoomModal.show();
            });
        </script>
    @endif
    <script>
        setTimeout(function() {
            let alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
        
    </script>
@endsection
