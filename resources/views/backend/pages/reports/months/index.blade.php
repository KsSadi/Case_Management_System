@section('page-title')
    Monthly Report
@endsection


@extends('backend.layouts.main')

@section('admin-section')

    @include('backend.layouts.partials.alerts')


    <!-- BEGIN: Filter -->
    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Monthly Report
            </h2>
        </div>
        <div class="p-5">
            <form action="{{ route('dashboard.reports.month') }}" method="GET">
                <div class="flex flex-col sm:flex-row items-end gap-3">
                    <div class="flex-1 w-full">
                        <label class="flex flex-col sm:flex-row" style="margin-bottom: 8px;">Month</label>
                        <select name="month" class="input w-full border">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="flex flex-col sm:flex-row" style="margin-bottom: 8px;">Year</label>
                        <select name="year" class="input w-full border">
                            @foreach(range(date('Y') + 1, 2018) as $y)
                                <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="button text-white" style="background: steelblue">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END: Filter -->

    <!-- Start : Datatable -->
    <div class="intro-y datatable-wrapper box p-5 mt-5" id="print-section">

        <!-- Print Header (only visible when printing) -->
        <div id="print-header" style="display:none;">
            <div style="text-align:center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 14px;">
                <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 4px 0;">মামলার তালিকা</h1>
                <h2 style="font-size: 14px; font-weight: normal; color: #444; margin: 0 0 6px 0;">Monthly Report</h2>
                <p style="font-size: 11px; color: #666; margin: 0;">মুদ্রণের তারিখ: <span id="print-date"></span> &nbsp;|&nbsp; মোট রেকর্ড: <strong>{{ count($histories) }}</strong></p>
            </div>
            <div style="margin-bottom: 12px; padding: 8px 12px; background: #f5f5f5; border-left: 4px solid #333; font-size: 11px;">
                <span style="display:inline-block; margin-right:16px;">
                    <span style="color:#555;">মাস:</span>
                    <strong>{{ $month_name }} {{ $year }}</strong>
                </span>
            </div>
        </div>

        <!-- Print Button -->
        @if(count($histories) > 0)
        <div class="flex justify-end mb-3 no-print">
            <button onclick="printMonthTable()" class="button flex items-center bg-gray-700 text-white">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                প্রিন্ট করুন ({{ count($histories) }} টি রেকর্ড)
            </button>
        </div>
        @endif

        <div class="overflow-x-auto">
        <table class="table table-report table-report--bordered display datatable w-full" id="result-table">
            <thead>
            <tr>

                <th class="whitespace-no-wrap">ক্রঃ নং</th>
                <th class="whitespace-no-wrap">মামলা নং</th>
                <th class="whitespace-no-wrap">প্রজেক্টের নাম</th>
                <th class="whitespace-no-wrap">মামলার বিভাগ</th>
                <th class="whitespace-no-wrap">মামলার ধরন</th>
                <th class="whitespace-no-wrap">আদালতের নাম</th>
                <th class="whitespace-no-wrap">আইনজীবীর নাম</th>
                <th class="whitespace-no-wrap">কোম্পানির নাম</th>
                <th class="whitespace-no-wrap">পরবর্তী তারিখ </th>
                <th class="text-center whitespace-no-wrap no-print">ACTIONS</th>

            </tr>
            </thead>
            <tbody>


            @foreach($histories as $history)

                <tr>


                    <td>
                        <span href="" class="font-medium">{{ $loop->iteration }}</span>

                    </td>
                    <td>
                        <a class="flex items-center mr-3" href="{{ route('dashboard.histories.show', $history->id) }}">
                            <span href="" class="font-medium">{{ optional($history->cases)->case_no ?? 'Not Found' }}</span>
                        </a>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ optional(optional($history->cases)->projects)->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ optional(optional($history->cases)->divisions)->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ optional(optional($history->cases)->types)->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ optional(optional($history->cases)->courts)->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ optional(optional($history->cases)->advocates)->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span class="font-medium">{{ optional(optional($history->cases)->companies)->name ?? '—' }}</span>

                    </td>
                    <td data-order="{{ $history->next_date ?? '' }}">
                        <span href="" class="font-medium no-print">{{ $history->next_date ? \Carbon\Carbon::parse($history->next_date)->format('d M Y') : '' }}</span>
                        <span class="font-medium print-only" style="display:none;">{{ $history->next_date ? \Carbon\Carbon::parse($history->next_date)->format('d M Y') : '' }}</span>

                    </td>


                    <td class="table-report__action w-56 no-print">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="{{ route('dashboard.histories.edit', $history->id) }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> Edit </a>

                            <a class="flex items-center text-theme-6" href="{{ route('dashboard.histories.destroy', $history->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $history->id }}').submit()"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete </a>
                            <form id="delete-form-{{$history->id}}" action="{{ route('dashboard.histories.destroy', $history->id) }}" method="POST" style="display: none">
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
function printMonthTable() {
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
