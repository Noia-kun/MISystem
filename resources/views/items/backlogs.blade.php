@extends('layouts.app')

@section('title', 'Back Logs')

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
            position: absolute;
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
<div class="page-header no-print">
    <div>
        <h2 class="page-header-title">
            <i class="fa fa-clock-rotate-left"></i> Back Logs
        </h2>
        <p class="page-header-sub">Complete history of requests, borrows, and room schedules</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn-header ghost" onclick="exportAllCSV()">
            <i class="fa fa-download"></i> Export CSV
        </button>
        <button class="btn-header gold" onclick="printLogs()">
            <i class="fa fa-print"></i> Print / PDF
        </button>
    </div>
</div>

<!-- ===== PRINTABLE AREA ===== -->
<div id="printArea" style="display:none;">
    <div class="print-header">
        <h2>MIS Office — Back Logs Report</h2>
        <p>Generated: <span id="printDate"></span></p>
    </div>

    <div class="print-section-title">Request Logs</div>
    <table id="printRequestTable">
        <thead>
            <tr>
                <th>Date Requested</th><th>Type</th><th>Item</th><th>Requester</th>
                <th>Venue</th><th>Purpose</th><th>Materials</th><th>Status</th><th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td>{{ $req->created_at->format('Y-m-d') }}</td>
                    <td>{{ $req->request_type }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->requester_name }}</td>
                    <td>{{ $req->location }}</td>
                    <td>{{ $req->reason }}</td>
                    <td>{{ $req->material }}</td>
                    <td>{{ $req->status }}</td>
                    <td>{{ date('h:i A', strtotime($req->time_from)) }} - {{ date('h:i A', strtotime($req->time_to)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="print-section-title">Borrowed Items Logs</div>
    <table id="printBorrowTable">
        <thead>
            <tr>
                <th>Issued Date</th><th>Item</th><th>Item ID</th>
                <th>Borrower</th><th>Duration</th><th>Returned Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrows as $borrow)
                <tr>
                    <td>{{ date('Y-m-d', strtotime($borrow->borrowed_at)) }}</td>
                    <td>{{ $borrow->item_name }}</td>
                    <td>{{ $borrow->inventory_item_id }}</td>
                    <td>{{ $borrow->borrower_name }}</td>
                    <td>{{ date('h:i A', strtotime($borrow->time_from)) }} - {{ date('h:i A', strtotime($borrow->time_to)) }}</td>
                    <td>{{ $borrow->returned_date ?? 'Not Returned' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="print-section-title">Room Schedule Logs</div>
    <table id="printRoomTable">
        <thead>
            <tr>
                <th>Date Scheduled</th><th>Date Filed</th><th>Room</th><th>Booked By</th>
                <th>Participant</th><th>Department</th><th>Purpose</th><th>Materials</th><th>Time Slot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roomLogs as $log)
                <tr>
                    <td>{{ date('m-d-Y', strtotime($log->scheduled_at)) }}</td>
                    <td>{{ date('m-d-Y', strtotime($log->created_at)) }}</td>
                    <td>{{ $log->room_name }}</td>
                    <td>{{ $log->requester_name }}</td>
                    <td>{{ $log->level }}</td>
                    <td>{{ $log->department }}</td>
                    <td>{{ $log->purpose }}</td>
                    <td>{{ $log->material }}</td>
                    <td>{{ date('h:i A', strtotime($log->time_from)) }} - {{ date('h:i A', strtotime($log->time_to)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- ===== END PRINTABLE AREA ===== -->

<!-- Request Logs -->
<div class="dash-card">
    <div class="dash-card-header teal">
        <i class="fa fa-file-lines"></i> Request Logs
    </div>
    <div class="dash-card-body">
        @if($requests->count())
            <div class="table-scroll" style="max-height: 300px;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Date Requested</th><th>Type</th><th>Item</th><th>Requester</th>
                            <th>Venue</th><th>Purpose</th><th>Materials</th><th>Status</th><th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                            <tr>
                                <td>{{ $req->created_at->format('Y-m-d') }}</td>
                                <td>{{ $req->request_type }}</td>
                                <td>{{ $req->item_name }}</td>
                                <td>{{ $req->requester_name }}</td>
                                <td>{{ $req->location }}</td>
                                <td>{{ $req->reason }}</td>
                                <td>{{ $req->material }}</td>
                                <td>
                                    @php
                                        $statusClass = match(strtolower($req->status)) {
                                            'approved'    => 'status-approved',
                                            'disapproved' => 'status-disapproved',
                                            'disregarded' => 'status-disregarded',
                                            default       => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $req->status }}</span>
                                </td>
                                <td>{{ date('h:i A', strtotime($req->time_from)) }} - {{ date('h:i A', strtotime($req->time_to)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state"><i class="fa fa-file-lines fa-2x mb-2 d-block"></i>No request logs found.</div>
        @endif
    </div>
</div>

<!-- Borrow Logs -->
<div class="dash-card">
    <div class="dash-card-header green">
        <i class="fa fa-box-open"></i> Borrowed Items Logs
    </div>
    <div class="dash-card-body">
        @if($borrows->count())
            <div class="table-scroll" style="max-height: 300px;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Issued Date</th><th>Item</th><th>Item ID</th>
                            <th>Borrower</th><th>Duration</th><th>Returned Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrows as $borrow)
                            <tr>
                                <td>{{ date('Y-m-d', strtotime($borrow->borrowed_at)) }}</td>
                                <td>{{ $borrow->item_name }}</td>
                                <td>{{ $borrow->inventory_item_id }}</td>
                                <td>{{ $borrow->borrower_name }}</td>
                                <td>{{ date('h:i A', strtotime($borrow->time_from)) }} - {{ date('h:i A', strtotime($borrow->time_to)) }}</td>
                                <td>
                                    @if($borrow->returned_date)
                                        <span class="badge-returned">{{ $borrow->returned_date }}</span>
                                    @else
                                        <span class="badge-not-returned">Not Returned</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state"><i class="fa fa-box fa-2x mb-2 d-block"></i>No borrow logs found.</div>
        @endif
    </div>
</div>

<!-- Room Schedule Logs -->
<div class="dash-card">
    <div class="dash-card-header dark">
        <i class="fa fa-calendar-days"></i> Room Schedule Logs
    </div>
    <div class="dash-card-body">
        @if($roomLogs->count())
            <div class="table-scroll" style="max-height: 300px;">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Date Scheduled</th><th>Date Filed</th><th>Room</th><th>Booked By</th>
                            <th>Participant</th><th>Department</th><th>Purpose</th><th>Materials</th><th>Time Slot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomLogs as $log)
                            <tr>
                                <td>{{ date('m-d-Y', strtotime($log->scheduled_at)) }}</td>
                                <td>{{ date('m-d-Y', strtotime($log->created_at)) }}</td>
                                <td>{{ $log->room_name }}</td>
                                <td>{{ $log->requester_name }}</td>
                                <td>{{ $log->level }}</td>
                                <td>{{ $log->department }}</td>
                                <td>{{ $log->purpose }}</td>
                                <td>{{ $log->material }}</td>
                                <td>{{ date('h:i A', strtotime($log->time_from)) }} - {{ date('h:i A', strtotime($log->time_to)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state"><i class="fa fa-calendar fa-2x mb-2 d-block"></i>No room schedule logs found.</div>
        @endif
    </div>
</div>

<script>
    // ===== PRINT / PDF =====
    function printLogs() {
        document.getElementById('printDate').textContent = new Date().toLocaleString();
        document.getElementById('printArea').style.display = 'block';
        window.print();
        document.getElementById('printArea').style.display = 'none';
    }

    // ===== CSV EXPORT =====
    function tableToCSV(tableId) {
        const rows = document.querySelectorAll('#' + tableId + ' tr');
        return Array.from(rows).map(row =>
            Array.from(row.querySelectorAll('th, td'))
                .map(cell => '"' + cell.innerText.replace(/"/g, '""') + '"')
                .join(',')
        ).join('\n');
    }

    function downloadCSV(content, filename) {
        const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    function exportAllCSV() {
        const date = new Date().toISOString().split('T')[0];

        // Show print area temporarily to read table data
        document.getElementById('printArea').style.display = 'block';

        const requestCSV  = "REQUEST LOGS\n"       + tableToCSV('printRequestTable');
        const borrowCSV   = "\n\nBORROW LOGS\n"    + tableToCSV('printBorrowTable');
        const roomCSV     = "\n\nROOM SCHEDULE LOGS\n" + tableToCSV('printRoomTable');

        document.getElementById('printArea').style.display = 'none';

        downloadCSV(requestCSV + borrowCSV + roomCSV, 'backlogs_' + date + '.csv');
    }
</script>

@endsection