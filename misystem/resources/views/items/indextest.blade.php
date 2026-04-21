@extends('layouts.app')

@section('title', 'Inventory Items')

@section('content')
    <h2>Inventory Items</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Add New Item</a>

    @if($items->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->location ?? '-' }}</td>
                    <td>
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-muted">No inventory items found.</p>
    @endif
@endsection
