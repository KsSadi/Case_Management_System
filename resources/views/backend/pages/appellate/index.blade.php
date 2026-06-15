@section('page-title')
    Appellate Division Cases
@endsection

@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 no-print">
        <h2 class="text-lg font-medium mr-auto">
            Appellate Division Cases List
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="{{ route('dashboard.supreme-court.appellate.create') }}" class="button text-white bg-theme-1 shadow-md mr-2 flex items-center">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Add New Appellate Case
            </a>
        </div>
    </div>

    <!-- BEGIN: Datatable Container -->
    <div class="intro-y datatable-wrapper box p-5 mt-5" id="print-section">
        
        <!-- Print Header (only visible when printing) -->
        <div id="print-header" style="display:none;">
            <div style="text-align:center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 14px;">
                <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 4px 0;">মামলার তালিকা (Appellate Division)</h1>
                <h2 style="font-size: 14px; font-weight: normal; color: #444; margin: 0 0 6px 0;">Appellate Division Cases Report</h2>
                <p style="font-size: 11px; color: #666; margin: 0;">মুদ্রণের তারিখ: <span id="print-date"></span> &nbsp;|&nbsp; মোট রেকর্ড: <strong>{{ count($cases) }}</strong></p>
            </div>
        </div>

        <!-- Print Button -->
        @if(count($cases) > 0)
        <div class="flex justify-end mb-3 no-print">
            <button onclick="printAppellateTable()" class="button flex items-center bg-gray-700 text-white">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                প্রিন্ট করুন ({{ count($cases) }} টি রেকর্ড)
            </button>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="table table-report table-report--bordered display datatable w-full" id="result-table">
                <thead>
                <tr>
                    <th class="whitespace-no-wrap">ক্রঃ নং</th>
                    <th class="whitespace-no-wrap">মামলা নং</th>
                    <th class="whitespace-no-wrap">সংশ্লিষ্ট পক্ষদের নাম</th>
                    <th class="whitespace-no-wrap">প্রথম আদেশ</th>
                    <th class="whitespace-no-wrap">শেষ আদেশ</th>
                    <th class="text-center whitespace-no-wrap no-print">ACTIONS</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cases as $case)
                    <tr>
                        <td>
                            <span class="font-medium">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <a class="flex items-center mr-3 text-theme-1 no-print" href="{{ route('dashboard.supreme-court.appellate.show', $case->id) }}">
                                <span class="font-medium">{{ $case->case_no }}</span>
                            </a>
                            <span class="font-medium print-only" style="display:none;">{{ $case->case_no }}</span>
                        </td>
                        <td>
                            <span class="font-medium">{{ $case->parties_name }}</span>
                        </td>
                        <td>
                            <span class="font-medium">{{ $case->first_order }}</span>
                        </td>
                        <td>
                            <span class="font-medium">{{ $case->last_order }}</span>
                        </td>
                        <td class="table-report__action w-56 no-print">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="{{ route('dashboard.supreme-court.appellate.edit', $case->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> Edit
                                </a>
                                <a class="flex items-center text-theme-6" href="{{ route('dashboard.supreme-court.appellate.destroy', $case->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $case->id }}').submit()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
                                </a>
                                <form id="delete-form-{{$case->id}}" action="{{ route('dashboard.supreme-court.appellate.destroy', $case->id) }}" method="POST" style="display: none">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- END: Datatable -->

<style>
@media print {
    /* Hide everything */
    body * { visibility: hidden; }

    /* Show only print section */
    #print-section, #print-section * { visibility: visible; }

    /* Reset layout wrappers to static block flow so they don't occupy space/margins */
    .app, .flex, .content {
        position: static !important;
        display: block !important;
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        min-height: 0 !important;
    }

    #print-section {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }

    /* Show print header */
    #print-header { display: block !important; }

    /* Hide non-print elements */
    .no-print { display: none !important; }

    /* Show print-only elements */
    .print-only { display: inline !important; }

    /* Hide DataTable controls */
    .dataTables_length,
    .dataTables_filter,
    .dataTables_info,
    .dataTables_paginate,
    .dataTables_wrapper .row:first-child,
    .dataTables_wrapper .row:last-child { display: none !important; }

    /* Table styling for print */
    #result-table { width: 100%; border-collapse: collapse; font-size: 11px; }
    #result-table th, #result-table td {
        border: 1px solid #333;
        padding: 5px 8px;
        text-align: left;
        word-break: break-word;
    }
    #result-table thead tr { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
    #result-table tbody tr:nth-child(even) { background-color: #fafafa !important; -webkit-print-color-adjust: exact; }

    @page { margin: 15mm; size: A4 landscape; }
}
</style>

<script>
function printAppellateTable() {
    var printDateEl = document.getElementById('print-date');
    if (printDateEl) {
        printDateEl.textContent = new Date().toLocaleDateString('bn-BD', {
            year: 'numeric', month: 'long', day: 'numeric'
        });
    }

    var hasDataTable = $.fn.dataTable && $.fn.dataTable.isDataTable('#result-table');

    if (hasDataTable) {
        var table = $('#result-table').DataTable();
        var prevLen = table.page.len();
        table.page.len(-1).draw();

        setTimeout(function () {
            window.print();
            table.page.len(prevLen).draw();
        }, 400);
    } else {
        setTimeout(function () {
            window.print();
        }, 200);
    }
}
</script>
@endsection
