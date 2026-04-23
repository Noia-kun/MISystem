@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">🏠 Dashboard</h2>
    <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#borrowItemModal">📥 Borrow Item</a>
    </div>
    <hr>
    @if(session('success'))
        <div id='success-alert' class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('errors'))
        <div id='error-alert' class="alert alert-danger">
            {{ session('errors') }}
        </div>
    @endif

    <div class="row">
        <!-- Recent Borrows Section -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">📥 Borrowed Items (to be return) </h5>
                </div>
                <div class="card-body">
                    @if($recentBorrows->count())
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date Scheduled</th>
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
                        <p class="text-muted">No recent borrows found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Requests Section -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Pending Requests</h5>
                </div>
                <div class="card-body">
                    @if($pendingRequests->count())
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date Filed</th>
                                        <th>Date Requested</th>
                                        <th>Request Type</th>
                                        <th>Item/Participant</th>
                                        <th>Name/Purpose</th>
                                        <th>Materials</th>
                                        <th>Venue</th>
                                        <th>Requester</th>
                                        <th>Time Slot</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($pendingRequests as $type => $requests)
                                    <tr>
                                        <td colspan="10" class="table-secondary fw-bold">{{ $type }}</td>
                                    </tr>
                                    @foreach($requests as $request)
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
                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to approve this request?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Approved">
                                                     <button type="submit" class="btn btn-sm btn-outline-success" title="Approve"><i class="fas fa-check"></i>      {{-- Approve --}}</button>
                                                </form>

                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to disapprove this request?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Disapproved">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Disapprove"><i class="fas fa-times"></i>      {{-- Disapprove --}}</button>
                                                </form>

                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to disregard this request?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Disregarded">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-ban"></i>        {{-- Disregard --}}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No pending requests found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
        <!-- Scheduled Room Bookings Section -->
<div class="card shadow mb-4">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📅 Scheduled Room Bookings</h5>
    </div>
    <div class="card-body">
        @if($roomSchedules->count())
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-sm table-bordered">
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
                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ $roomStatus }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('roomscheduler.destroy', $schedule->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to remove this schedule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-2" title="Remove">
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
            <p class="text-muted">No scheduled rooms found.</p>
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
                    <h5 class="modal-title" id="borrowItemModalLabel">📥 Borrow an Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="inventory_item_id" class="form-label">Select Item</label>
                        <select name="inventory_item_id" id="inventory_item_id" class="form-select" required>
                            <option value="">-- Choose an item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ $item->status === 'Unavailable' ? 'disabled' : '' }}>
                                    {{ $item->item_name }} - {{ $item->category }} {{ $item->status === 'Unavailable' ? '(Unavailable)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="borrower_name" class="form-label">Borrower Name</label>
                        <input type="text" name="borrower_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Venue</label>
                        <input type="text" name="location" class="form-control" required>
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

                    <div class="mb-3">
                        <label for="reason" class="form-label">Purpose</label>
                        <input type="text" name="reason" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="borrowed_at" class="form-label">Issued Date</label>
                        <input type="date" name="borrowed_at" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-hide alert after 4 seconds
    setTimeout(function() {
        let alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 2000); // 2000ms = 2 seconds
    setTimeout(function() {
        let alert = document.getElementById('error-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 2000); // 2000ms = 2 seconds
</script>

@endsection
