@extends('layouts.publicapp') {{-- or create a simpler layout if needed --}}

@section('title', 'View Room Bookings')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📅 Scheduled Room Reservation </h2>

    {{-- Pending Room Requests --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <strong>Pending Room Requests</strong>
        </div>
        <div class="card-body">
            @if($pendingRequests->count())
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Date Filed</th>
                                <th>Date Requested</th>
                                <th>Room</th>
                                <th>Requester</th>
                                <th>Participant</th>
                                <th>Purpose</th>
                                <th>Time Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $request)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($request->created_at)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($request->borrowed_at)) }}</td>
                                    <td>{{ $request->location }}</td>
                                    <td>{{ $request->requester_name }}</td>
                                    <td>{{ $request->level }}</td>
                                    <td>{{ $request->reason }}</td>
                                    <td>{{ date('h:i A', strtotime($request->time_from)) }} - {{ date('h:i A', strtotime($request->time_to)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No pending requests at the moment.</p>
            @endif
        </div>
    </div>

    {{-- Approved Room Bookings --}}
    <div class="card">
        <div class="card-header bg-success text-white">
            <strong>Approved Room Bookings</strong>
        </div>
        <div class="card-body">
            @if($roomSchedules->count())
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Room</th>
                                <th>Requester</th>
                                <th>Participant</th>
                                <th>Purpose</th>
                                <th>Time Slot</th>
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
                                    <td>{{ $schedule->purpose }}</td>
                                    <td>{{ date('h:i A', strtotime($schedule->time_from)) }} - {{ date('h:i A', strtotime($schedule->time_to)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No upcoming room bookings.</p>
            @endif
        </div>
    </div>
</div>
@endsection
