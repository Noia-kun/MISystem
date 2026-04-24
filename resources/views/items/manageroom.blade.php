@extends('layouts.app')

@section('title', 'Room Management')

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

    /* Pagination */
    .pagination .page-link {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    color: var(--navy);
    font-size: 0.85rem;
    padding: 6px 12px;
    margin: 0 2px;
    }

    .pagination .page-item.active .page-link {
        background: var(--gold);
        border-color: var(--gold);
        color: var(--navy);
        font-weight: 600;
    }

    .pagination .page-link:hover {
        background: var(--gold-light);
        border-color: var(--gold-light);
        color: var(--navy);
    }
    
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
    .action-btn.edit     { background: rgba(52,152,219,0.12); color: #2980b9; }
    .action-btn.delete   { background: rgba(231,76,60,0.12);  color: #e74c3c; }

    /* Table scroll */
    .table-scroll { max-height: 320px; overflow-y: auto; border-radius: 10px; }
    .table-scroll::-webkit-scrollbar { width: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: #f0f4f8; }
    .table-scroll::-webkit-scrollbar-thumb { background: #c9a84c55; border-radius: 10px; }

    /* Empty state */
    .empty-state { text-align: center; padding: 30px; color: var(--muted); font-size: 0.9rem; }

    /* ===== PRINT STYLES ===== */
    @media print {
        body * { visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0a1628;
            padding-bottom: 10px;
        }

        .print-header h2 {
            font-size: 18px;
            color: #0a1628;
            margin: 0;
        }

        .print-header p {
            font-size: 11px;
            color: #555;
            margin: 4px 0 0;
        }

        .print-section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 8px;
            color: #0a1628;
            border-left: 4px solid #c9a84c;
            padding-left: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        table thead th {
            background: #0a1628 !important;
            color: #fff !important;
            padding: 6px 8px;
            text-align: left;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        table tbody td {
            padding: 5px 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        table tbody tr:nth-child(even) td {
            background: #f7fafc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print { display: none !important; }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-door-open"></i> Rooms Management
        </h2>
        <p class="page-header-sub">Manage room availability and details</p>
    </div>
    <a href="#" class="btn-header gold" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="fa fa-plus"></i> Add Room
    </a>
</div>

    @if(session('success'))
        <div id="success-alert" class="alert-modern alert alert-success">
            <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
        </div>
    @endif

<div class="dash-card">
    <div class="dash-card-header teal">
        <i class="fa fa-door-open"></i> Manage Rooms
    </div>    
    <div class="dash-card-body">
        @if($rooms->count())
            <table class="modern-table">
                <thead>
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
                        <td><span class="status-badge {{ $room->status === 'Available' ? 'status-approved' : 'status-notreturned' }}">
                            {{ $room->status }}
                        </span></td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this room?');">
                                    @csrf @method('DELETE')
                                    <button class="action-btn delete" type="submit" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3">
                {{ $rooms->links() }}
            </div>   
    @else
        <div class="empty-state">
            <i class="fa fa-door-open fa-2x mb-2 d-block"></i>
            No rooms found.
        </div>
    @endif
    </div>
</div>
        <!-- Edit Modal for each room -->
            @foreach($rooms as $room)
                <div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="editRoomLabel{{ $room->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header navy">
                                    <h5 class="modal-title" id="editRoomLabel{{ $room->id }}"><i class="fa fa-pen me-2"></i>Edit Room</h5>
                                    <button type="button" class="btn-close light" data-bs-dismiss="modal"></button>
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
                                    <button type="submit" class="btn btn-submit">Update</button>
                                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        <div class="modal-header navy">
          <h5 class="modal-title" id="addRoomModalLabel"><i class="fa fa-plus me-2"></i>Add Room</h5>
          <button type="button" class="btn-close light" data-bs-dismiss="modal"></button>
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
          <button type="submit" class="btn btn-submit">Save Room</button>
          <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
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
