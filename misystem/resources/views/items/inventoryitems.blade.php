@extends('layouts.app')

@section('title', 'Inventory Items')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">📦 Lent Items</h2>
        <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="fa fa-plus me-1"></i> Add Item</a>
    </div>

    <hr>
    @if(session('success'))
        <div id="success-alert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    {{-- <th>Item ID</th> --}}
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Item Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    {{-- <td>{{ $item->id }}</td> --}}
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->location ?? '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">✏️ Edit</button>

                            <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">🗑️ Delete</button>
                            </form>

                            @if($item->status === 'Unavailable')
                                <form action="{{ route('items.return', $item->id) }}" method="POST" onsubmit="return confirm('Mark item as returned?');">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success">🔄 Return</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                @foreach($items as $item)
                <!-- Edit Modal for each item -->
                <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('items.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editItemLabel{{ $item->id }}">✏️ Edit Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="item_name_{{ $item->id }}" class="form-label">Item Name</label>
                                        <input type="text" name="item_name" id="item_name_{{ $item->id }}" value="{{ $item->item_name }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_{{ $item->id }}" class="form-label">Category</label>
                                        <input type="text" name="category" id="category_{{ $item->id }}" value="{{ $item->category }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="location_{{ $item->id }}" class="form-label">Location (optional)</label>
                                        <input type="text" name="location" id="location_{{ $item->id }}" value="{{ $item->location }}" class="form-control">
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
        <p class="text-muted">No inventory items found.</p>
    @endif
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

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addItemModalLabel">➕ Add Inventory Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="item_name" class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" id="item_name" required>
          </div>

          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" class="form-control" id="category" required>
          </div>

          {{-- <div class="mb-3">
            <label for="location" class="form-label">Location (optional)</label>
            <input type="text" name="location" class="form-control" id="location">
          </div> --}}
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Item</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
