@section('page-title')
    Old Case Histories
@endsection


@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <div class="flex flex-wrap gap-2 mt-8 mb-2">
        <a href="{{ route('dashboard.histories.index') }}" style="max-width: 220px" class="button w-100 mr-2 flex bg-theme-9 text-white"> 
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg> 
            Back to Current Histories
        </a>
        
        @if (Auth::guard('admin')->user()->can('history.create'))
        <a href="{{ route('dashboard.histories.create') }}" style="max-width: 220px" class="button w-100 mr-2 flex bg-theme-1 text-white"> 
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg> Add New Case History 
        </a>
        @endif
    </div>

    <div class="intro-y box p-3 md:p-5 mt-5 mb-3">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-theme-6 mr-2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <h2 class="text-lg font-medium">পুরানো মামলার ইতিহাস (Old Case Histories)</h2>
        </div>
        <p class="text-gray-600 mt-2">এই মামলাগুলোর ধার্য তারিখ পার হয়ে গেছে। আপনি এখানে তারিখ আপডেট করতে পারবেন।</p>
    </div>

    <style>
    /* Premium Filter Styling */
    .cms-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .cms-filter-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 5px;
    }
    .cms-filter-control {
        width: 100% !important;
        height: 38px !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
        transition: all 0.2s ease !important;
        display: block !important;
    }
    select.cms-filter-control {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: right 0.65rem center !important;
        background-repeat: no-repeat !important;
        background-size: 1.25em 1.25em !important;
        padding-right: 2.25rem !important;
        -webkit-appearance: none !important;
        appearance: none !important;
    }
    .cms-filter-control:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
        outline: none !important;
    }
    </style>

    <!-- BEGIN: Filter Card -->
    <div class="intro-y cms-filter-card p-4 md:p-5 mt-4 mb-3 no-print">
        <!-- Filter Header -->
        <div class="flex flex-wrap items-center justify-between pb-3 border-b border-gray-100 cursor-pointer" onclick="toggleFilterPanel()">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-red-100 text-theme-6 flex items-center justify-center mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-base text-gray-800">পুরানো মামলা ফিল্টার (Filter Old Cases)</h2>
                    <p class="text-xs text-gray-500">প্রজেক্ট, বিভাগ, আদালত, আইনজীবী বা তারিখ অনুযায়ী অনুসন্ধান করুন</p>
                </div>
                @if($hasActiveFilter)
                    <span class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-theme-6 border border-red-300">
                        {{ count($appliedFilters) }} টি ফিল্টার সক্রিয়
                    </span>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                @if($hasActiveFilter)
                    <a href="{{ route('dashboard.histories.old') }}" onclick="event.stopPropagation();" class="button button--sm bg-gray-200 text-gray-700 hover:bg-gray-300 mr-2">
                        ✕ রিসেট
                    </a>
                @endif
                <button type="button" class="p-1 rounded hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition" title="টগল করুন">
                    <svg id="filter-chevron" class="w-5 h-5 transform transition-transform duration-200 {{ $hasActiveFilter ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filter Form Body -->
        <div id="filter-body" class="mt-4 {{ $hasActiveFilter ? '' : 'hidden md:block' }}">
            <form id="filter-form" action="{{ route('dashboard.histories.old') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    
                    <!-- 1. Project Name -->
                    <div>
                        <label class="cms-filter-label">প্রজেক্টের নাম (Project)</label>
                        <select name="project" class="cms-filter-control">
                            <option value="">সকল প্রজেক্ট</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Division -->
                    <div>
                        <label class="cms-filter-label">মামলার বিভাগ (Division)</label>
                        <select name="division" class="cms-filter-control">
                            <option value="">সকল বিভাগ</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ request('division') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Case Type -->
                    <div>
                        <label class="cms-filter-label">মামলার ধরন (Case Type)</label>
                        <select name="case_type" class="cms-filter-control">
                            <option value="">সকল ধরন</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ request('case_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. Court Name -->
                    <div>
                        <label class="cms-filter-label">আদালতের নাম (Court)</label>
                        <select name="court_name" class="cms-filter-control">
                            <option value="">সকল আদালত</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}" {{ request('court_name') == $court->id ? 'selected' : '' }}>
                                    {{ $court->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 5. Advocate Name -->
                    <div>
                        <label class="cms-filter-label">আইনজীবীর নাম (Advocate)</label>
                        <select name="adv_name" class="cms-filter-control">
                            <option value="">সকল আইনজীবী</option>
                            @foreach($advocates as $advocate)
                                <option value="{{ $advocate->id }}" {{ request('adv_name') == $advocate->id ? 'selected' : '' }}>
                                    {{ $advocate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 6. Company Name -->
                    <div>
                        <label class="cms-filter-label">কোম্পানির নাম (Company)</label>
                        <select name="company_id" class="cms-filter-control">
                            <option value="">সকল কোম্পানি</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 7. Date From -->
                    <div>
                        <label class="cms-filter-label">ধার্য তারিখ হতে (Date From)</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="cms-filter-control" title="তারিখ হতে">
                    </div>

                    <!-- 8. Date To -->
                    <div>
                        <label class="cms-filter-label">ধার্য তারিখ পর্যন্ত (Date To)</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="cms-filter-control" title="তারিখ পর্যন্ত">
                    </div>

                </div>

                <!-- Form Action Buttons -->
                <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-3 border-t border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="submit" class="button bg-theme-1 text-white flex items-center px-4 py-2 rounded-md shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            ফিল্টার করুন (Apply Filter)
                        </button>

                        <a href="{{ route('dashboard.histories.old') }}" class="button bg-gray-200 text-gray-700 hover:bg-gray-300 flex items-center px-4 py-2 rounded-md">
                            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            রিসেট (Reset)
                        </a>
                    </div>

                    <div class="text-xs text-gray-600">
                        ফলাফল পাওয়া গেছে: <strong class="text-theme-6 text-sm">{{ count($histories) }}</strong> টি পুরানো মামলা
                    </div>
                </div>
            </form>

            <!-- Active Filter Badges -->
            @if(!empty($appliedFilters))
                <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-dashed border-gray-200">
                    <span class="text-xs font-semibold text-gray-600">সক্রিয় ফিল্টারসমূহ:</span>
                    @foreach($appliedFilters as $label => $val)
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                            <strong>{{ $label }}:</strong>&nbsp;{{ $val }}
                        </span>
                    @endforeach
                    <a href="{{ route('dashboard.histories.old') }}" class="text-xs text-red-600 hover:underline font-medium ml-2">
                        সব মুছুন (Clear All)
                    </a>
                </div>
            @endif
        </div>
    </div>
    <!-- END: Filter Card -->

    <div class="intro-y datatable-wrapper box p-3 md:p-5 mt-5" id="print-section" style="overflow: visible;">
        
        <!-- Print Header (only visible when printing) -->
        <div id="print-header" style="display:none;">
            <div style="text-align:center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 14px;">
                <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 4px 0;">পুরানো মামলার ইতিহাস</h1>
                <h2 style="font-size: 14px; font-weight: normal; color: #444; margin: 0 0 6px 0;">Old Case Histories</h2>
                <p style="font-size: 11px; color: #666; margin: 0;">মুদ্রণের তারিখ: <span id="print-date"></span> &nbsp;|&nbsp; মোট রেকর্ড: <strong>{{ count($histories) }}</strong></p>
            </div>
        </div>

        <!-- Print Button -->
        @if(count($histories) > 0)
        <div class="flex justify-end mb-3 no-print">
            <button onclick="printOldTable()" class="button flex items-center bg-gray-700 text-white">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                প্রিন্ট করুন ({{ count($histories) }} টি রেকর্ড)
            </button>
        </div>
        @endif

        <!-- Mobile Responsive: Horizontal scroll wrapper -->
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
                    <th class="whitespace-no-wrap hidden md:table-cell">নির্ধারিত কার্যক্রম</th>
                    <th class="whitespace-no-wrap">ধার্য তারিখ (Expired)</th>
                    @if (Auth::guard('admin')->user()->can('history.edit') || Auth::guard('admin')->user()->can('history.delete'))
                    <th class="text-center whitespace-no-wrap no-print">ACTIONS</th>
                    @endif

            </tr>
            </thead>
            <tbody>


            @forelse($histories as $history)

                <tr class="bg-red-50">


                    <td>
                        <span class="font-medium">{{ $loop->iteration }}</span>

                    </td>
                    <td>
                        <a class="flex items-center mr-3" href="{{ route('dashboard.histories.show', $history->id) }}">
                            <span class="font-medium text-theme-1">@if($history->cases)
                                    {{ $history->cases->case_no}}
                                @else
                                    Not Found
                                @endif</span>
                        </a>
                        @if($history->cases?->companies)
                            <div class="text-xs text-gray-500 font-normal mt-0.5">
                                🏢 {{ $history->cases->companies->name }}
                            </div>
                        @endif
                    </td>
                    <td class="hidden md:table-cell">
                        <span class="font-medium">@if($history->cases && $history->cases->projects)
                                {{ $history->cases->projects->name}}
                            @else
                                Not Found
                            @endif</span>

                    </td>
                    <td class="hidden lg:table-cell">
                        <span class="font-medium">@if($history->cases && $history->cases->divisions)
                                {{ $history->cases->divisions->name}}
                            @else
                                Not Found
                            @endif</span>

                    </td>
                    <td class="hidden lg:table-cell">
                        <span class="font-medium">@if($history->cases && $history->cases->types)
                                {{ $history->cases->types->name }}
                            @else
                                Not Found
                            @endif</span>

                    </td>
                    <td class="hidden xl:table-cell">
                        <span class="font-medium">@if($history->cases && $history->cases->courts)
                                {{ $history->cases->courts->name }}
                            @else
                                Not Found
                            @endif</span>

                    </td>
                    <td class="hidden md:table-cell">
                        <span class="font-medium"> @if($history->cases && $history->cases->advocates)
                                {{ $history->cases->advocates->name }}
                            @else
                                Not Found
                            @endif </span>

                    </td>
                    <td class="hidden md:table-cell">
                        <span class="font-medium">{{ $history->status ?? '—' }}</span>
                    </td>
                    <td data-order="{{ $history->next_date ?? '' }}">
                        <span class="font-medium text-theme-6">{{ \Carbon\Carbon::parse($history->next_date)->format('d M Y') }}</span>

                    </td>


                    @if (Auth::guard('admin')->user()->can('history.edit') || Auth::guard('admin')->user()->can('history.delete'))
                    <td class="table-report__action w-56 no-print">
                        <div class="flex justify-center items-center">
                            @if (Auth::guard('admin')->user()->can('history.edit'))
                            <a class="flex items-center mr-3" href="{{ route('dashboard.histories.edit', $history->id) }}"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg> Edit 
                            </a>
                            @endif

                            @if (Auth::guard('admin')->user()->can('history.delete'))
                            <a class="flex items-center text-theme-6" href="{{ route('dashboard.histories.destroy', $history->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $history->id }}').submit()"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg> Delete 
                            </a>
                            <form id="delete-form-{{$history->id}}" action="{{ route('dashboard.histories.destroy', $history->id) }}" method="POST" style="display: none">
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
                    <td colspan="9" class="text-center py-5 text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-gray-400">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p class="text-lg font-medium">কোন পুরানো মামলা নেই!</p>
                        <p class="text-sm">সব মামলার তারিখ আপডেট আছে।</p>
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
        </div>
        <!-- End overflow wrapper -->
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
function toggleFilterPanel() {
    var body = document.getElementById('filter-body');
    var chevron = document.getElementById('filter-chevron');
    if (body.classList.contains('hidden')) {
        body.classList.remove('hidden');
        if (chevron) chevron.classList.add('rotate-180');
    } else {
        body.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    }
}

function printOldTable() {
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
