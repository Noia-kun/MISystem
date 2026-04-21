@extends('layouts.userapp')

@section('title', 'User ManageRoom')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🚪 Rooms Management</h2>
        <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="fa fa-plus me-1"></i> Add Room
        </a>
    </div>

    <hr>
    @if(session('success'))
        <div id="success-alert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($rooms->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    {{-- <th>Room ID</th> --}}
                    <th>Room Name</th>
                    <th>Room Location</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr>
                    {{-- <td>{{ $room->id }}</td> --}}
                    <td>{{ $room->room_name }}</td>
                    <td>{{ $room->room_location }}</td>
                    <td>{{ $room->notes ?? '-' }}</td>
                    <td>{{ $room->status }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">✏️ Edit</button>

                            <form action="{{ route('user.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Delete this room?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">🗑️ Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal for each room -->
                <div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="editRoomLabel{{ $room->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('user.rooms.update', $room->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editRoomLabel{{ $room->id }}">✏️ Edit Room</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="room_name_{{ $room->id }}" class="form-label">Room Name</label>
                                        <input type="text" name="room_name" id="room_name_{{ $room->id }}" value="{{ $room->room_name }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="room_location_{{ $room->id }}" class="form-label">Room Location</label>
                                        <select name="room_location" id="room_location_{{ $room->id }}" class="form-select" required>
                                            <option value="" disabled {{ $room->room_location == '' ? 'selected' : '' }}>-- Choose a building --</option>
                                            <option value="Stella Maris Bldg." {{ $room->room_location == 'Stella Maris Bldg.' ? 'selected' : '' }}>
                                                Stella Maris Bldg. - (Admin Bldg.)
                                            </option>
                                            <option value="Cavoli Bldg." {{ $room->room_location == 'Cavoli Bldg.' ? 'selected' : '' }}>
                                                Cavoli Bldg. - (SHS Bldg.)
                                            </option>
                                            <option value="Savio Bldg." {{ $room->room_location == 'Savio Bldg.' ? 'selected' : '' }}>
                                                Savio Bldg. - (New Bldg.)
                                            </option>
                                            <option value="Sacred Heart Bldg." {{ $room->room_location == 'Sacred Heart Bldg.' ? 'selected' : '' }}>
                                                Sacred Heart Bldg. - (JHS Bldg.)
                                            </option>
                                            <option value="St. Joseph Bldg." {{ $room->room_location == 'St. Joseph Bldg.' ? 'selected' : '' }}>
                                                St. Joseph Bldg. - (GS Bldg.)
                                            </option>
                                            <option value="Cimatti Bldg." {{ $room->room_location == 'Cimatti Bldg.' ? 'selected' : '' }}>
                                                Cimatti Bldg. - (Cimatti Dome)
                                            </option>
                                            <option value="Gym" {{ $room->room_location == 'Gym' ? 'selected' : '' }}>
                                                Gym
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="notes_{{ $room->id }}" class="form-label">Description</label>
                                        <textarea name="notes" id="notes_{{ $room->id }}" class="form-control">{{ $room->notes }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status_{{ $room->id }}" class="form-label">Status</label>
                                        <select name="status" id="status_{{ $room->id }}" class="form-select">
                                            <option value="Available" {{ $room->status === 'Available' ? 'selected' : '' }}>Available</option>
                                            <option value="Unavailable" {{ $room->status === 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">✅ Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-muted">No rooms found.</p>
    @endif

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('user.rooms.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addRoomModalLabel">➕ Add Room</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="room_name" class="form-label">Room Name</label>
            <input type="text" name="room_name" class="form-control" id="room_name" required>
          </div>

          <div class="mb-3">
            <label for="room_location" class="form-label">Room Location</label>
            <select name="room_location" id="room_location" class="form-select" required>
              <option value="" disabled selected>-- Choose a building --</option>
              <option value="Stella Maris Bldg.">Stella Maris Bldg. - (Admin Bldg.) </option>
              <option value="Cavoli Bldg.">Cavoli Bldg. - (SHS Bldg.)</option>
              <option value="Savio Bldg.">Savio Bldg. - (New Bldg.) </option>
              <option value="Sacred Heart Bldg.">Sacred Heart Bldg. - (JHS Bldg.)</option>
              <option value="St. Joseph Bldg.">St. Joseph Bldg. - (GS Bldg.)</option>
              <option value="Cimatti Bldg.">Cimatti Bldg. (Cimatti Dome)</option>
              <option value="Gym">Gym</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label">Description (optional)</label>
            <textarea name="notes" class="form-control" id="notes"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Room</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    setTimeout(function() {
        let alert = document.getElementById('success-alert');
        if(alert){
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 2000);
</script>
@endsection
