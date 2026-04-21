@extends('layouts.app')

@section('title', 'Room Scheduler')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🏫 Room Scheduler</h2>
        <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="fa fa-plus"></i> Schedule Room
        </a>
    </div>
    <hr>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📅 Scheduled Room Bookings</h5>
        </div>
        <div class="card-body">
            @if($roomSchedules->count())
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Room</th>
                                <th>Booked By</th>
                                <th>Grade Level</th>
                                <th>Department</th>
                                <th>Purpose</th>
                                <th>Time Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roomSchedules as $schedule)
                                <tr>
                                    <td>{{ date('Y-m-d', strtotime($schedule->scheduled_at)) }}</td>
                                    <td>{{ $schedule->room_name }}</td>
                                    <td>{{ $schedule->requester_name }}</td>
                                    <td>{{ $schedule->level }}</td>
                                    <td>{{ $schedule->department }}</td>
                                    <td>{{ $schedule->purpose }}</td>
                                    <td>{{ date('h:i A', strtotime($schedule->time_from)) }} - {{ date('h:i A', strtotime($schedule->time_to)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No scheduled rooms yet.</p>
            @endif
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('roomscheduler.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">📅 Schedule a Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="room_name" class="form-label">Room Name</label>
                            <input type="text" name="room_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="requester_name" class="form-label">Booked By</label>
                            <input type="text" name="requester_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="yearlevel" class="form-label">Grade Level</label>
                            <input type="text" name="yearlevel" class="form-control" required>
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
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
