@extends('layouts.app')

@section('title', 'MIS Inventory Items')

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

    /* Alert */
    .alert-modern {
        border-radius: 10px; border: none;
        padding: 14px 20px; font-size: 0.9rem;
        margin-bottom: 20px;
        animation: fadeUp 0.4s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Card */
    .dash-card {
        border: none; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(10,22,40,0.08);
        overflow: hidden;
        animation: fadeUp 0.5s ease both;
    }

    .dash-card-header {
        padding: 16px 22px;
        font-family: 'DM Serif Display', serif;
        font-size: 1rem;
        display: flex; align-items: center; gap: 10px;
        background: linear-gradient(90deg, var(--navy), var(--navy-mid));
        color: var(--cream);
    }

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
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
    }

    .modern-table thead th:hover { background: #e8ecf2; }
    .modern-table thead th:last-child { cursor: default; }
    .modern-table thead th:last-child:hover { background: #f0f4f8; }

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
    
    /* Condition badge */
    .condition-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .cond-good        { background: rgba(46,204,113,0.12); color: #27ae60; }
    .cond-usable      { background: rgba(243,156,18,0.12);  color: #b7770d; }
    .cond-disposal    { background: rgba(231,76,60,0.12);   color: #c0392b; }

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
    .action-btn.location { background: rgba(23,162,184,0.12); color: #117a8b; }
    .action-btn.notes    { background: rgba(243,156,18,0.12); color: #b7770d; }

    /* Empty state */
    .empty-state { text-align: center; padding: 40px; color: var(--muted); font-size: 0.9rem; }

    /* Scrollable table */
    .table-scroll { max-height: 650px; overflow-y: auto; border-radius: 10px; }
    .table-scroll::-webkit-scrollbar { width: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: #f0f4f8; }
    .table-scroll::-webkit-scrollbar-thumb { background: #c9a84c55; border-radius: 10px; }

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

    .btn-submit { background: var(--gold); color: var(--navy); border: none; border-radius: 8px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; transition: background 0.2s; }
    .btn-submit:hover { background: var(--gold-light); color: var(--navy); }
    .btn-cancel { border-radius: 8px; font-size: 0.9rem; }

    /* History list */
    .history-list { list-style: none; padding: 0; margin: 0; }
    .history-list li {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding: 12px 16px; border-bottom: 1px solid #f0f4f8;
        font-size: 0.88rem;
    }
    .history-list li:last-child { border-bottom: none; }
    .history-list .h-label { font-weight: 500; color: #2d3748; }
    .history-list .h-date  { font-size: 0.78rem; color: var(--muted); white-space: nowrap; margin-left: 12px; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-screwdriver-wrench"></i> Inventory Items
        </h2>
        <p class="page-header-sub">Manage office inventory, conditions, and location history</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('officeitems.export') }}" class="btn-header ghost">
            <i class="fa fa-download"></i> Export CSV
        </a>
        <a href="#" class="btn-header gold" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="fa fa-plus"></i> Add Item
        </a>
    </div>
</div>

<!-- Search Form -->
<form method="GET" action="{{ route('officeinventory') }}" class="mb-4">
    <div class="d-flex gap-2" style="max-width: 500px;">
        <div style="position: relative; flex: 1;">
            <input type="text" name="search" id="searchInput"
                   class="form-control"
                   placeholder="Search item name, property no., item set..."
                   value="{{ request('search') }}"
                   style="border-radius:8px; border:1px solid #e2e8f0; padding:10px 14px; padding-right:36px; font-size:0.9rem;">
            @if(request('search'))
                <span onclick="clearSearch()" 
                      style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--muted);">
                    <i class="fa fa-xmark"></i>
                </span>
            @endif
        </div>
        <button type="submit" class="btn-header gold">
            <i class="fa fa-search"></i> Search
        </button>
    </div>
</form>

@if(session('success'))
    <div id="success-alert" class="alert-modern alert alert-success">
        <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
    </div>
@endif

<!-- Table Card -->
<div class="dash-card">
    <div class="dash-card-header">
        <i class="fa fa-table-list"></i> Office Inventory
    </div>
    <div class="dash-card-body">
        @if($items->count())
                <table class="modern-table" id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Property No.</th>
                            <th>Serial No.</th>
                            <th>Category</th>
                            <th>Item Set</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Condition</th>
                            <th>Date Purchased</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->propertynum }}</td>
                                <td>{{ $item->serialnum ?? '-' }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->item_set ?? 'N/A' }}</td>
                                <td>{{ $item->location ?? '-' }}</td>
                                <td>{{ $item->description ?? '' }}</td>
                                <td>
                                    @php
                                        $condClass = match($item->condition) {
                                            'Good' => 'cond-good',
                                            'Usable' => 'cond-usable',
                                            default => 'cond-disposal'
                                        };
                                    @endphp
                                    <span class="condition-badge {{ $condClass }}">{{ $item->condition }}</span>
                                </td>
                                <td>{{ date('m-d-Y', strtotime($item->date_purchased)) }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <form action="{{ route('officeitems.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?');" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button class="action-btn delete" type="submit" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                        <button class="action-btn location" data-bs-toggle="modal" data-bs-target="#locationHistoryModal{{ $item->id }}" title="Location History">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                        <button class="action-btn notes" data-bs-toggle="modal" data-bs-target="#usableNotesHistoryModal{{ $item->id }}" title="Usable Notes History">
                                            <i class="fas fa-note-sticky"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            <div class="d-flex justify-content-end mt-3">
                {{ $items->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-box-open fa-2x mb-2 d-block"></i>
                No inventory items found.
            </div>
        @endif
    </div>
</div>

{{-- Modals per item --}}
@foreach($items as $item)

{{-- Edit Modal --}}
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('officeitems.update', $item->id) }}" method="POST">
                @csrf @method('PUT')
                
                <input type="hidden" name="page" value="{{ request('page', 1) }}">

                <div class="modal-header navy">
                    <h5 class="modal-title"><i class="fa fa-pen me-2"></i>Edit Item</h5>
                    <button type="button" class="btn-close light" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" value="{{ $item->item_name }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Property No.</label>
                        <input type="text" name="propertynum" value="{{ $item->propertynum }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Serial No. (Optional)</label> <input type="text" name="serialnum" value="{{ $item->serialnum }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ $item->category }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item Set (If Applicable)</label>
                        <input type="text" name="item_set" value="{{ $item->item_set }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location (optional)</label>
                        <input type="text" name="location" value="{{ $item->location }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" value="{{ $item->description }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-select" id="condition_{{ $item->id }}" required>
                            <option value="" disabled>-- Choose condition --</option>
                            <option value="Good" {{ $item->condition == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Usable" {{ $item->condition == 'Usable' ? 'selected' : '' }}>Usable</option>
                            <option value="For Replacement/Disposal" {{ $item->condition == 'For Replacement/Disposal' ? 'selected' : '' }}>For Replacement/Disposal</option>
                        </select>
                        <div class="mb-3 d-none mt-2" id="usableNotesWrapper_{{ $item->id }}">
                            <label class="form-label">Condition Notes</label>
                            <input type="text" name="usable_notes" id="usable_notes_{{ $item->id }}" class="form-control" value="{{ $item->usable_notes ?? '' }}">
                            <label class="form-label mt-2">Fixed Date</label>
                            <input type="date" name="fixed_date" id="fixed_date_{{ $item->id }}" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Purchased</label>
                        <input type="date" name="date_purchased" value="{{ $item->date_purchased }}" class="form-control" required>
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

{{-- Location History Modal --}}
<div class="modal fade" id="locationHistoryModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header info-head">
                <h5 class="modal-title"><i class="fa fa-map-marker-alt me-2"></i>Location History — {{ $item->item_name }}</h5>
                <button type="button" class="btn-close light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($item->locationHistories->isNotEmpty())
                    <ul class="history-list">
                        @foreach($item->locationHistories as $history)
                            <li>
                                <span class="h-label">{{ $history->new_location }}</span>
                                <span class="h-date">{{ date('M d, Y h:i A', strtotime($history->changed_at)) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state"><i class="fa fa-map-marker-alt fa-2x mb-2 d-block"></i>No location changes recorded yet.</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Usable Notes History Modal --}}
<div class="modal fade" id="usableNotesHistoryModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header warn-head">
                <h5 class="modal-title"><i class="fa fa-note-sticky me-2"></i>Usable Notes — {{ $item->item_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($item->usableNotesHistories->isNotEmpty())
                    <ul class="history-list">
                        @foreach($item->usableNotesHistories as $history)
                            <li>
                                <span class="h-label">{{ $history->usable_notes }}</span>
                                <span class="h-date">{{ $history->fixed_date ? date('M d, Y', strtotime($history->fixed_date)) : '—' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state"><i class="fa fa-note-sticky fa-2x mb-2 d-block"></i>No usable notes recorded yet.</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endforeach

{{-- Add Item Modal --}}
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('officeitems.store') }}" method="POST">
                @csrf
                <div class="modal-header navy">
                    <h5 class="modal-title"><i class="fa fa-plus me-2"></i>Add Inventory Item</h5>
                    <button type="button" class="btn-close light" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Property No.</label>
                        <input type="text" name="propertynum" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Serial No. (Optional)</label>  <input type="text" name="serialnum" value="{{ $item->serialnum }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item Set (If Applicable)</label>
                        <input type="text" name="item_set" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location (Optional)</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-select" id="condition" required>
                            <option value="" disabled selected>-- Choose condition --</option>
                            <option value="Good">Good</option>
                            <option value="Usable">Usable</option>
                            <option value="For Replacement/Disposal">For Replacement/Disposal</option>
                        </select>
                        <div class="mb-3 d-none mt-2" id="usableNotesWrapper">
                            <label class="form-label">Condition Notes</label>
                            <input type="text" name="usable_notes" class="form-control" id="usable_notes" placeholder="Enter remarks/condition history">
                            <label class="form-label mt-2">Fixed Date</label>
                            <input type="date" name="fixed_date" class="form-control" id="fixed_date">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Purchased</label>
                        <input type="date" name="date_purchased" class="form-control" required>
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
    // Auto-hide alert
    setTimeout(() => {
        const el = document.getElementById('success-alert');
        if (el) { el.style.transition = 'opacity 0.5s ease'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
    }, 2000);

    // Sortable table headers
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("inventoryTable");
        const headers = table.querySelectorAll("th");
        let sortDirections = {};

        headers.forEach((header, index) => {
            if (index === headers.length - 1) return;
            header.addEventListener("click", () => {
                const rows = Array.from(table.querySelectorAll("tbody tr"));
                const isAsc = sortDirections[index] = !sortDirections[index];
                rows.sort((a, b) => {
                    let cellA = a.children[index].innerText.trim().toLowerCase();
                    let cellB = b.children[index].innerText.trim().toLowerCase();
                    if (Date.parse(cellA) && Date.parse(cellB)) return isAsc ? new Date(cellA) - new Date(cellB) : new Date(cellB) - new Date(cellA);
                    if (!isNaN(cellA) && !isNaN(cellB)) return isAsc ? cellA - cellB : cellB - cellA;
                    return isAsc ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                });
                rows.forEach(row => table.querySelector("tbody").appendChild(row));
            });
        });

        // Add Item — condition toggle
        const conditionSelect = document.getElementById("condition");
        const usableWrapper   = document.getElementById("usableNotesWrapper");
        const usableInput     = document.getElementById("usable_notes");
        const fixedDateInput  = document.getElementById("fixed_date");

        if (conditionSelect) {
            conditionSelect.addEventListener("change", function () {
                const isUsable = this.value === "Usable";
                usableWrapper.classList.toggle("d-none", !isUsable);
                usableInput.required = isUsable;
                fixedDateInput.required = isUsable;
                if (!isUsable) { usableInput.value = ""; fixedDateInput.value = ""; }
                else if (!fixedDateInput.value) { fixedDateInput.value = new Date().toISOString().split('T')[0]; }
            });
        }

        // Edit Modals — condition toggle
        document.querySelectorAll("select[id^='condition_']").forEach(select => {
            const itemId      = select.id.replace("condition_", "");
            const wrapper     = document.getElementById("usableNotesWrapper_" + itemId);
            const uInput      = wrapper.querySelector('input[name="usable_notes"]');
            const fdInput     = wrapper.querySelector('input[name="fixed_date"]');
            const origNotes   = uInput.value;

            const toggle = () => {
                const isUsable = select.value === "Usable";
                wrapper.classList.toggle("d-none", !isUsable);
                uInput.required  = isUsable;
                fdInput.required = isUsable && uInput.value !== origNotes;
                if (!isUsable) { uInput.value = ""; fdInput.value = ""; }
                else if (fdInput.required && !fdInput.value) { fdInput.value = new Date().toISOString().split('T')[0]; }
            };

            uInput.addEventListener("input", () => {
                const isUsable = select.value === "Usable";
                fdInput.required = isUsable && uInput.value !== origNotes;
                if (fdInput.required && !fdInput.value) fdInput.value = new Date().toISOString().split('T')[0];
            });

            select.addEventListener("change", toggle);
            toggle();
        });
    });

    // Clear search results
    function clearSearch() {
        window.location.href = "{{ route('officeinventory') }}";
    }
</script>

@endsection