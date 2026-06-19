@section('page-title')
    নিষ্পত্তি তালিকা
@endsection


@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <div class="flex flex-wrap gap-2 mt-8 mb-2">
        <a href="{{ route('dashboard.histories.index') }}" style="max-width: 220px" class="button w-100 flex items-center bg-theme-1 text-white">
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            সকল ইতিহাস
        </a>
    </div>

    <div class="intro-y box p-3 md:p-5 mt-2 mb-3">
        <h2 class="font-medium text-base text-green-700 mb-3">
            <span class="px-2 py-1 rounded text-sm font-bold bg-green-100 text-green-800 border border-green-400">নিষ্পত্তি</span>
            &nbsp; মোট নিষ্পত্তি মামলা: <strong>{{ $histories->count() }}</strong>
        </h2>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('dashboard.histories.nispotti') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">বছর</label>
                <select name="year" class="input border" style="min-width:120px;">
                    <option value="">সব বছর</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">মাস</label>
                <select name="month" class="input border" style="min-width:140px;">
                    <option value="">সব মাস</option>
                    <option value="1"  {{ request('month') == '1'  ? 'selected' : '' }}>জানুয়ারি</option>
                    <option value="2"  {{ request('month') == '2'  ? 'selected' : '' }}>ফেব্রুয়ারি</option>
                    <option value="3"  {{ request('month') == '3'  ? 'selected' : '' }}>মার্চ</option>
                    <option value="4"  {{ request('month') == '4'  ? 'selected' : '' }}>এপ্রিল</option>
                    <option value="5"  {{ request('month') == '5'  ? 'selected' : '' }}>মে</option>
                    <option value="6"  {{ request('month') == '6'  ? 'selected' : '' }}>জুন</option>
                    <option value="7"  {{ request('month') == '7'  ? 'selected' : '' }}>জুলাই</option>
                    <option value="8"  {{ request('month') == '8'  ? 'selected' : '' }}>আগস্ট</option>
                    <option value="9"  {{ request('month') == '9'  ? 'selected' : '' }}>সেপ্টেম্বর</option>
                    <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>অক্টোবর</option>
                    <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>নভেম্বর</option>
                    <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>ডিসেম্বর</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="button bg-theme-1 text-white flex items-center">
                    <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    ফিল্টার
                </button>
                @if(request('year') || request('month'))
                <a href="{{ route('dashboard.histories.nispotti') }}" class="button bg-gray-300 text-gray-700 flex items-center">রিসেট</a>
                @endif
            </div>
        </form>
    </div>

    <div class="intro-y datatable-wrapper box p-3 md:p-5 mt-2" id="print-section" style="overflow: visible;">
        
        <!-- Print Header (only visible when printing) -->
        <div id="print-header" style="display:none;">
            <div style="text-align:center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 14px;">
                <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 4px 0;">নিষ্পত্তি মামলার তালিকা</h1>
                <h2 style="font-size: 14px; font-weight: normal; color: #444; margin: 0 0 6px 0;">Resolved Cases List</h2>
                <p style="font-size: 11px; color: #666; margin: 0;">মুদ্রণের তারিখ: <span id="print-date"></span> &nbsp;|&nbsp; মোট রেকর্ড: <strong>{{ count($histories) }}</strong></p>
            </div>
            @if(request('year') || request('month'))
            <div style="margin-bottom: 12px; padding: 8px 12px; background: #f5f5f5; border-left: 4px solid #333; font-size: 11px;">
                @if(request('year'))
                <span style="display:inline-block; margin-right:16px;">
                    <span style="color:#555;">বছর:</span>
                    <strong>{{ request('year') }}</strong>
                </span>
                @endif
                @if(request('month'))
                <span style="display:inline-block; margin-right:16px;">
                    <span style="color:#555;">মাস:</span>
                    <strong>
                        @php
                            $months_bn = [1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল', 5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট', 9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'];
                        @endphp
                        {{ $months_bn[request('month')] ?? '' }}
                    </strong>
                </span>
                @endif
            </div>
            @endif
        </div>

        <!-- Print Button -->
        @if(count($histories) > 0)
        <div class="flex justify-end mb-3 no-print">
            <button onclick="printNispottiTable()" class="button flex items-center bg-gray-700 text-white">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                প্রিন্ট করুন ({{ count($histories) }} টি রেকর্ড)
            </button>
        </div>
        @endif

        <div class="overflow-x-auto" style="width: 100%; overflow-x: auto;">
            <table class="table table-report table-report--bordered display datatable w-full" id="result-table" style="min-width: 600px;">
                <thead>
                <tr>
                    <th class="whitespace-no-wrap">ক্রঃ নং</th>
                    <th class="whitespace-no-wrap">মামলা নং</th>
                    <th class="whitespace-no-wrap hidden md:table-cell">প্রজেক্টের নাম</th>
                    <th class="whitespace-no-wrap hidden lg:table-cell">মামলার বিভাগ</th>
                    <th class="whitespace-no-wrap hidden lg:table-cell">মামলার ধরন</th>
                    <th class="whitespace-no-wrap hidden xl:table-cell">বিচারাধীন বিজ্ঞ আদালতের নাম</th>
                    <th class="whitespace-no-wrap hidden md:table-cell">নিয়োজিত আইনজীবীর নাম</th>
                    <th class="whitespace-no-wrap">নিষ্পত্তির তারিখ</th>
                    @if (Auth::guard('admin')->user()->can('history.edit') || Auth::guard('admin')->user()->can('history.delete'))
                    <th class="text-center whitespace-no-wrap no-print">ACTIONS</th>
                    @endif
                </tr>
                </thead>
                <tbody>

                @forelse($histories as $history)
                    <tr class="bg-green-50">
                        <td>
                            <span class="font-medium">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <a class="flex items-center mr-3" href="{{ route('dashboard.histories.show', $history->id) }}">
                                <span class="font-medium">
                                    {{ $history->cases?->case_no ?? 'Not Found' }}
                                </span>
                            </a>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="font-medium">{{ $history->cases?->projects?->name ?? 'Not Found' }}</span>
                        </td>
                        <td class="hidden lg:table-cell">
                            <span class="font-medium">{{ $history->cases?->divisions?->name ?? 'Not Found' }}</span>
                        </td>
                        <td class="hidden lg:table-cell">
                            <span class="font-medium">{{ $history->cases?->types?->name ?? 'Not Found' }}</span>
                        </td>
                        <td class="hidden xl:table-cell">
                            <span class="font-medium">{{ $history->cases?->courts?->name ?? 'Not Found' }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="font-medium">{{ $history->cases?->advocates?->name ?? 'Not Found' }}</span>
                        </td>
                        <td data-order="{{ $history->nispotti_date ?? '' }}">
                            @if($history->nispotti_date)
                                <span class="font-medium text-green-700">{{ \Carbon\Carbon::parse($history->nispotti_date)->format('d M Y') }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        @if (Auth::guard('admin')->user()->can('history.edit') || Auth::guard('admin')->user()->can('history.delete'))
                        <td class="table-report__action w-56 no-print">
                            <div class="flex justify-center items-center">
                                @if (Auth::guard('admin')->user()->can('history.edit'))
                                <a class="flex items-center mr-3" href="{{ route('dashboard.histories.edit', $history->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                    Edit
                                </a>
                                @endif

                                @if (Auth::guard('admin')->user()->can('history.delete'))
                                <a class="flex items-center text-theme-6" href="{{ route('dashboard.histories.destroy', $history->id) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-form-{{ $history->id }}').submit()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    Delete
                                </a>
                                <form id="delete-form-{{ $history->id }}" action="{{ route('dashboard.histories.destroy', $history->id) }}" method="POST" style="display: none">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-500 py-6">কোনো নিষ্পত্তি মামলা পাওয়া যায়নি।</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

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
        display: table-cell !important;
    }
    #result-table thead tr { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
    #result-table tbody tr:nth-child(even) { background-color: #fafafa !important; -webkit-print-color-adjust: exact; }

    @page { margin: 15mm; size: A4 landscape; }
}
</style>

<script>
function printNispottiTable() {
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
