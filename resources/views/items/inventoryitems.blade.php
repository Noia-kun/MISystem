@extends('layouts.app')

@section('title', 'Inventory Items')

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

    body {
        background: #f0f4f8;
        font-family: 'DM Sans', sans-serif;
        color: #1a2a3a;
    }

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

    .btn-add {
        background: var(--gold);
        color: var(--navy);
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s, transform 0.15s;
        text-decoration: none;
    }

    .btn-add:hover { background: var(--gold-light); color: var(--navy); transform: translateY(-2px); }

    /* Alert */
    .alert-modern {
        border-radius: 10px;
        border: none;
        padding: 14px 20px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        animation: fadeUp 0.4s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Card */
    .dash-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(10,22,40,0.08);
        overflow: hidden;
        animation: fadeUp 0.5s ease both;
    }

    .dash-card-header {
        padding: 16px 22px;
        font-family: 'DM Serif Display', serif;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(90deg, #b8860b, var(--gold-light));
        color: var(--navy);
    }

    .dash-card-body {
        padding: 16px;
        background: #fff;
    }

    /* Table */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.85rem;
    }
    .modern-table thead th {
        background: #f0f4f8;
        color: var(--navy);
        font-weight: 500;
        font-size: 0.75rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 10px 14px;
        border-bottom: 2px solid #e2e8f0;
    }

    .modern-table tbody tr { transition: background 0.15s; }
    .modern-table tbody tr:hover { background: #f7fafc; }

    .modern-table tbody td {
        padding: 10px 14px;
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
    
    /* Status badge */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-available   { background: rgba(46,204,113,0.12); color: #27ae60; }
    .status-unavailable { background: rgba(231,76,60,0.12);  color: #c0392b; }

    /* Action buttons */
    .action-btn {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        transition: transform 0.15s, opacity 0.15s;
    }

    .action-btn:hover { transform: scale(1.1); opacity: 0.85; }
    .action-btn.edit   { background: rgba(52,152,219,0.12); color: #2980b9; }
    .action-btn.delete { background: rgba(231,76,60,0.12);  color: #e74c3c; }
    .action-btn.return { background: rgba(46,204,113,0.12); color: #27ae60; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* Scrollable table */
    .table-scroll { max-height: 600px; overflow-y: auto; border-radius: 10px; }
    .table-scroll::-webkit-scrollbar { width: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: #f0f4f8; }
    .table-scroll::-webkit-scrollbar-thumb { background: #c9a84c55; border-radius: 10px; }

    /* Modal */
    .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }

    .modal-header {
        background: linear-gradient(135deg, var(--navy-mid), var(--navy));
        color: var(--cream);
        border-radius: 16px 16px 0 0;
        border-bottom: none;
        padding: 20px 24px;
    }

    .modal-header .btn-close { filter: invert(1); }
    .modal-title { font-family: 'DM Serif Display', serif; font-size: 1.2rem; }
    .modal-body { padding: 24px; }
    .modal-footer { border-top: 1px solid #f0f4f8; padding: 16px 24px; }

    .form-label {
        font-size: 0.78rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
    }

    .btn-submit  { background: var(--gold); color: var(--navy); border: none; border-radius: 8px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; transition: background 0.2s; }
    .btn-submit:hover { background: var(--gold-light); color: var(--navy); }
    .btn-cancel  { border-radius: 8px; font-size: 0.9rem; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-box"></i> Lent Items
        </h2>
        <p class="page-header-sub">Manage all borrowed and available inventory items</p>
    </div>
    <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addItemModal">
        <i class="fa fa-plus"></i> Add Item
    </a>
</div>

@if(session('success'))
    <div id="success-alert" class="alert-modern alert alert-success">
        <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
    </div>
@endif

<!-- Items Table Card -->
<div class="dash-card">
    <div class="dash-card-header">
        <i class="fa fa-box-open"></i> Inventory List
    </div>
    <div class="dash-card-body">
        @if($items->count())
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->location ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $item->status === 'Unavailable' ? 'status-unavailable' : 'status-available' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                            @csrf @method('DELETE')
                                            <button class="action-btn delete" type="submit" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>

                                        @if($item->status === 'Unavailable')
                                            <form action="{{ route('items.return', $item->id) }}" method="POST" onsubmit="return confirm('Mark item as returned?');">
                                                @csrf @method('PATCH')
                                                <button class="action-btn return" type="submit" title="Return"><i class="fas fa-rotate-left"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            <div class="d-flex justify-content-end mt-3">
                {{ $items->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-box fa-2x mb-2 d-block"></i>
                No inventory items found.
            </div>
        @endif
    </div>
</div>

{{-- Edit Modals --}}
@foreach($items as $item)
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('items.update', $item->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" value="{{ $item->item_name }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ $item->category }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location (optional)</label>
                        <input type="text" name="location" value="{{ $item->location }}" class="form-control">
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

{{-- Add Item Modal --}}
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus me-2"></i>Add Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-submit">Save Item</button>
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const el = document.getElementById('success-alert');
        if (el) {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }
    }, 2000);
</script>

@endsection