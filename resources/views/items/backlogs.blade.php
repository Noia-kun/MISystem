@extends('layouts.app')

@section('title', 'Back Logs')

@section('content')
    <h2>📚 All Back Logs</h2>
    <hr>

    {{-- Request Logs --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0 text-black">📝 Request Logs</h5>
        </div>
        <div class="card-body">
            @if($requests->count())
            <div style="max-height: 300px; overflow-y: auto;">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Date Requested</th>
                            <th>Request Type</th>
                            <th>Item</th>
                            <th>Requester</th>
                            <th>Venue</th>
                            <th>Purpose</th>
                            <th>Materials</th>
                            <th>Status</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            <tr>
                                <td>{{ $req->created_at->format('Y-m-d') }}</td>
                                <td>{{ $req->request_type }} </td>
                                <td>{{ $req->item_name }}</td>
                                <td>{{ $req->requester_name }}</td>
                                <td>{{ $req->location }}</td>
                                <td>{{ $req->reason }}</td>
                                <td>{{ $req->material }}</td>
                                <td>{{ $req->status }}</td>
                                <td>
                                    {{ date('h:i A', strtotime($req->time_from)) }} - {{ date('h:i A', strtotime($req->time_to)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted">No request logs found.</p>
            @endif
        </div>
    </div>

    {{-- Borrow Logs --}}
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">📦 Borrowed Items Logs</h5>
        </div>
        <div class="card-body">
            @if($borrows->count())
            <div style="max-height: 300px; overflow-y: auto;">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Issued Date</th>
                            <th>Item</th>
                            <th>Item ID</th>
                            <th>Borrower</th>
                            <th>Duration</th>
                            <th>Returned Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrows as $borrow)
                            <tr>
                                <td>{{ date('Y-m-d', strtotime($borrow->borrowed_at)) }}</td>
                                <td>{{ $borrow->item_name }}</td>
                                <td>{{ $borrow->inventory_item_id }}</td>
                                <td>{{ $borrow->borrower_name }}</td>
                                <td>
                                    {{ date('h:i A', strtotime($borrow->time_from)) }} - {{ date('h:i A', strtotime($borrow->time_to)) }}
                                </td>
                                <td>{{ $borrow->returned_date ?? 'Not Returned' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted">No borrow logs found.</p>
            @endif
        </div>
    </div>

    {{-- Room Schedule Logs --}}
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">📅 Room Schedule Logs</h5>
        </div>
        <div class="card-body">
            @if($roomLogs->count())
            <div style="max-height: 300px; overflow-y: auto;">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Date Scheduled</th>
                            <th>Date Filed</th>
                            <th>Room</th>
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
                                <td>{{ date('m-d-Y', strtotime($log->scheduled_at)) }}</td>
                                <td>{{ date('m-d-Y', strtotime($log->created_at)) }}</td>
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
                <p class="text-muted">No room schedule logs found.</p>
            @endif
        </div>
    </div>


@endsection
