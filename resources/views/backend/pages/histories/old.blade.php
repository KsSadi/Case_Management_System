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
                            <span class="font-medium">@if($history->cases)
                                    {{ $history->cases->case_no}}
                                @else
                                    Not Found
                                @endif</span>
                        </a>

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
