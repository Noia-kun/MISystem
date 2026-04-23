@extends('layouts.app')

@section('title', 'MIS Inventory Items')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🔨 Inventory Items</h2>
        <div class="d-flex gap-2">
        <a href="{{ route('officeitems.export') }}" class="btn btn-success">
            ⬇️ Export to CSV
        </a>
        <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="fa fa-plus me-1"></i> Add Inventory Item</a>
        </div>
    </div>

    <hr>
    @if(session('success'))
        <div id="success-alert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->count())
    <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    {{-- <th>Item ID</th> --}}
                    <th style="cursor:pointer;">Item Name</th>
                    <th style="cursor:pointer;">Property No.</th>
                    <th style="cursor:pointer;">Category</th>
                    <th style="cursor:pointer;">Item Set (If Applicable)</th>
                    <th style="cursor:pointer;">Location</th>
                    <th style="cursor:pointer;">Description</th>
                    <th style="cursor:pointer;">Condition</th>
                    <th style="cursor:pointer;">Date Purchased</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    {{-- <td>{{ $item->id }}</td> --}}
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->propertynum }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->item_set ?? 'N/A' }}</td>
                    <td>{{ $item->location ?? '-' }}</td>
                    <td>{{ $item->description ?? '' }}</td>
                    <td>{{ $item->condition }}</td>
                    <td>{{ date('m-d-Y', strtotime($item->date_purchased)) }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">✏️ Edit</button>

                            <form action="{{ route('officeitems.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">🗑️ Delete</button>
                            </form>
                            <!-- View Location History Button -->
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#locationHistoryModal{{ $item->id }}">
                                📜 View Location History
                            </button>
                            <!-- View Usable Notes History Button -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#usableNotesHistoryModal{{ $item->id }}">
                                📝 View Usable Notes History
                            </button>

                            {{-- @if($item->status === 'Unavailable')
                                <form action="{{ route('items.return', $item->id) }}" method="POST" onsubmit="return confirm('Mark item as returned?');">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success">🔄 Return</button>
                                </form>
                            @endif --}}
                        </div>
                    </td>
                </tr>
                @endforeach
                @foreach($items as $item)
                <!-- Edit Modal for each item -->
                <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('officeitems.update', $item->id) }}" method="POST">
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
                                        <label for="propertynum" class="form-label">Property No.</label>
                                        <input type="text" name="propertynum" class="form-control" id="propertynum_{{ $item->id }}" value="{{ $item->propertynum }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_{{ $item->id }}" class="form-label">Category</label>
                                        <input type="text" name="category" id="category_{{ $item->id }}" value="{{ $item->category }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="item_set" class="form-label">Item Set (If Applicable)</label>
                                        <input type="text" name="item_set" class="form-control" id="item_set_{{ $item->id }}" value="{{ $item->item_set }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="location_{{ $item->id }}" class="form-label">Location (optional)</label>
                                        <input type="text" name="location" id="location_{{ $item->id }}" value="{{ $item->location }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" name="description" class="form-control" id="description_{{ $item->id }}" value="{{ $item->description }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="condition" class="form-label">Condition</label>
                                        <select name="condition" class="form-select" id="condition_{{ $item->id }}" required>
                                            <option value="" disabled selected>-- Choose condition --</option>
                                            <option value="Good" {{ $item->condition == 'Good' ? 'selected' : '' }}>Good</option>
                                            <option value="Usable" {{ $item->condition == 'Usable' ? 'selected' : '' }}>Usable</option>
                                            <option value="For Replacement/Disposal" {{ $item->condition == 'For Replacement/Disposal' ? 'selected' : '' }}>For Replacement/Disposal</option>
                                        </select>
                                        <div class="mb-3 d-none" id="usableNotesWrapper_{{ $item->id }}">
                                            <label class="form-label">Condition Notes</label>
                                            <input type="text"
                                                name="usable_notes"
                                                id="usable_notes_{{ $item->id }}"
                                                class="form-control"
                                                value="{{ $item->usable_notes ?? '' }}">
                                            <label for="fixed_date_{{ $item->id }}" class="form-label mt-2">Fixed Date</label>
                                            <input type="date"
                                                name="fixed_date"
                                                id="fixed_date_{{ $item->id }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_purchased" class="form-label">Date Purchased</label>
                                        <input type="date" name="date_purchased" class="form-control" id="date_purchased_{{ $item->id }}" value="{{ $item->date_purchased }}" required>
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
                <!-- Location History Modal -->
                <div class="modal fade" id="locationHistoryModal{{ $item->id }}" tabindex="-1" aria-labelledby="locationHistoryLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="locationHistoryLabel{{ $item->id }}">
                                    📜 Location History — {{ $item->item_name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                @if($item->locationHistories->isNotEmpty())
                                    <ul class="list-group">
                                        @foreach($item->locationHistories as $history)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    {{-- <strong>{{ $history->old_location ?? '—' }}</strong>
                                                    <span class="mx-2">→</span> --}}
                                                    <strong>{{ $history->new_location }}</strong>
                                                </div>
                                                <small class="text-muted">
                                                    ({{ date('M d, Y h:i A', strtotime($history->changed_at)) }})
                                                </small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center">No location changes recorded for this item yet.</p>
                                @endif
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usable Notes History Modal -->
                <div class="modal fade" id="usableNotesHistoryModal{{ $item->id }}" tabindex="-1" aria-labelledby="usableNotesHistoryLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title" id="usableNotesHistoryLabel{{ $item->id }}">
                                    📝 Usable Notes History — {{ $item->item_name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                @if($item->usableNotesHistories->isNotEmpty())
                                    <ul class="list-group">
                                        @foreach($item->usableNotesHistories as $history)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $history->usable_notes }}</strong>
                                                    <span class="text-muted"> - ({{ $history->fixed_date ? date('M d, Y', strtotime($history->fixed_date)) : '—' }})</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center">No usable notes history recorded for this item yet.</p>
                                @endif
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
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
      <form action="{{ route('officeitems.store') }}" method="POST">
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
            <label for="propertynum" class="form-label">Property No.</label>
            <input type="text" name="propertynum" class="form-control" id="propertynum" required>
          </div>

          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" class="form-control" id="category" required>
          </div>

          <div class="mb-3">
            <label for="item_set" class="form-label">Item Set (If Applicable)</label>
            <input type="text" name="item_set" class="form-control" id="item_set">
          </div>

          <div class="mb-3">
            <label for="location" class="form-label">Location (Optional)</label>
            <input type="text" name="location" class="form-control" id="location">
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" class="form-control" id="description">
          </div>

          <div class="mb-3">
            <label for="condition" class="form-label">Condition</label>
            <select name="condition" class="form-select" id="condition" required>
                <option value="" disabled selected>-- Choose condition --</option>
                <option value="Good">Good</option>
                <option value="Usable">Usable</option>
                <option value="For Replacement/Disposal">For Replacement/Disposal</option>
            </select>
            <div class="mb-3 d-none" id="usableNotesWrapper">
                <label for="usable_notes" class="form-label">Condition Notes</label>
                <input type="text" name="usable_notes" class="form-control" id="usable_notes"
                    placeholder="Enter remarks/condition history">
                <label for="fixed_date" class="form-label mt-2">Fixed Date</label>
                <input type="date" name="fixed_date" class="form-control" id="fixed_date">
            </div>

          </div>

          <div class="mb-3">
            <label for="date_purchased" class="form-label">Date Purchased</label>
            <input type="date" name="date_purchased" class="form-control" id="date_purchased" required>
          </div>

          
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Item</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const table = document.querySelector("table");
    const headers = table.querySelectorAll("th");
    let sortDirections = {};

    headers.forEach((header, index) => {
        if (index === headers.length - 1) return; // Skip "Actions" column

        header.addEventListener("click", () => {
            const rows = Array.from(table.querySelectorAll("tbody tr"));
            const isAsc = sortDirections[index] = !sortDirections[index];

            rows.sort((a, b) => {
                let cellA = a.children[index].innerText.trim().toLowerCase();
                let cellB = b.children[index].innerText.trim().toLowerCase();

                // Detect if it's a date
                if (Date.parse(cellA) && Date.parse(cellB)) {
                    return isAsc
                        ? new Date(cellA) - new Date(cellB)
                        : new Date(cellB) - new Date(cellA);
                }

                // Detect if it's a number
                if (!isNaN(cellA) && !isNaN(cellB)) {
                    return isAsc ? cellA - cellB : cellB - cellA;
                }

                // Default: string comparison
                return isAsc
                    ? cellA.localeCompare(cellB)
                    : cellB.localeCompare(cellA);
            });

            rows.forEach(row => table.querySelector("tbody").appendChild(row));
        });
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ADD ITEM MODAL
    const conditionSelect = document.getElementById("condition");
    const usableWrapper = document.getElementById("usableNotesWrapper");
    const usableInput = document.getElementById("usable_notes");
    const fixedDateInput = document.getElementById("fixed_date");

    if (conditionSelect) {
        conditionSelect.addEventListener("change", function () {
            const isUsable = this.value === "Usable";

            usableWrapper.classList.toggle("d-none", !isUsable);
            usableInput.required = isUsable;
            fixedDateInput.required = isUsable;

            if (!isUsable) {
                usableInput.value = "";
                fixedDateInput.value = "";
            } else {
                // Prefill with today's date so the required validation passes
                if (!fixedDateInput.value) {
                    fixedDateInput.value = new Date().toISOString().split('T')[0];
                }
            }
            
            {{-- usableWrapper.classList.toggle("d-none", this.value !== "Usable"); --}}
        });
    }

    // EDIT MODALS
    document.querySelectorAll("select[id^='condition_']").forEach(select => {
        const itemId = select.id.replace("condition_", "");
        const wrapper = document.getElementById("usableNotesWrapper_" + itemId);
        const usableInput = wrapper.querySelector('input[name="usable_notes"]');
        const fixedDateInput = wrapper.querySelector('input[name="fixed_date"]');
        const originalNotes = usableInput.value;

        const toggle = () => {
            const isUsable = select.value === "Usable";

            wrapper.classList.toggle("d-none", !isUsable);
            usableInput.required = isUsable;
            fixedDateInput.required = isUsable && usableInput.value !== originalNotes;

            if (!isUsable) {
                usableInput.value = "";
                fixedDateInput.value = "";
            } else {
                // If notes changed, ensure there's a date so form submit can pass validation.
                if (fixedDateInput.required && !fixedDateInput.value) {
                    fixedDateInput.value = new Date().toISOString().split('T')[0];
                }
            }
        };

        // When usable_notes changes, we may need to make `fixed_date` required.
        usableInput.addEventListener("input", () => {
            const isUsable = select.value === "Usable";
            fixedDateInput.required = isUsable && usableInput.value !== originalNotes;
            if (fixedDateInput.required && !fixedDateInput.value) {
                fixedDateInput.value = new Date().toISOString().split('T')[0];
            }
        });
        
        {{-- const toggle = () => {
            wrapper.classList.toggle("d-none", select.value !== "Usable");
        }; --}}

        select.addEventListener("change", toggle);
        toggle(); // Run once on page load (important!)
    });
});
</script>

@endsection
