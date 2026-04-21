@extends('layouts.app')

@section('title', 'Room Scheduler')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🏫 Room Scheduler</h2>
        <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="fa fa-plus"></i> Add Room Schedule
        </a>
    </div>
    <hr>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                                <td>{{ date('Y-m-d', strtotime($schedule->scheduled_at)) }}</td>
                                <td>{{ $schedule->room_name }}</td>
                                <td>{{ $schedule->requester_name }}</td>
                                <td>{{ $schedule->level }}</td>
                                <td>{{ $schedule->department }}</td>
                                <td>{{ $schedule->purpose }}</td>
                                <td>{{ $schedule->material ?? '-' }}</td>
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

<!-- Room Schedule Logs Section -->
<div class="card shadow mb-4 mt-5">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">📝 Room Schedule Logs</h5>
    </div>
    <div class="card-body">
        @if($roomLogs->count())
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-sm table-bordered">
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
                                <td>{{ date('Y-m-d', strtotime($log->scheduled_at)) }}</td>
                                <td>{{ date('Y-m-d', strtotime($log->created_at)) }}</td>
                                <td>{{ $log->room_name }}</td>
                                <td>{{ $log->requester_name }}</td>
                                <td>{{ $log->level }}</td>
                                <td>{{ $log->department }}</td>
                                <td>{{ $log->purpose }}</td>
                                <td>{{ $log->material ?? '-' }}</td>
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
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
