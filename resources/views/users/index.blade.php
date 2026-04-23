@extends('layouts.userapp')

@section('title', 'User Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">🏠 Dashboard</h2>
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
        <!-- Pending Requests Section -->
        <div class="col mb-4">
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
                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to approve this request?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Approved">
                                                    <input type="hidden" name="redirect_to" value="user-dashboard">
                                                     <button type="submit" class="btn btn-sm btn-outline-success" title="Approve"><i class="fas fa-check"></i>      {{-- Approve --}}</button>
                                                </form>

                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to disapprove this request?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Disapproved">
                                                    <input type="hidden" name="redirect_to" value="user-dashboard">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Disapprove"><i class="fas fa-times"></i>      {{-- Disapprove --}}</button>
                                                </form>

                                                <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to disregard this request?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Disregarded">
                                                    <input type="hidden" name="redirect_to" value="user-dashboard">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-ban"></i>        {{-- Disregard --}}</button>
                                                </form>
                                            </td>
                                        </tr>
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
