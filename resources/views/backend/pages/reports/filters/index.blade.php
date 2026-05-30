@section('page-title')
    Case Filtering
@endsection


@extends('backend.layouts.main')

@section('admin-section')

    @include('backend.layouts.partials.alerts')


    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
Case Filtering
            </h2>
        </div>
        <div class="p-5" id="horizontal-form">
            <form  id="submit-form" action="{{ route('dashboard.reports.filter.store') }}" method="POST">
                @csrf

                <div>
                    <label class="flex flex-col sm:flex-row" style="margin-bottom: 10px;">প্রজেক্টের নাম  </label>

                        <select data-placeholder="Select" id="project"  name="project" class="select2 w-full">
                            <option value="" selected>Select</option>
                            @foreach($projects as $projects)

                                <option value="{{ $projects->id }}"> {{ $projects->name }}</option>

                            @endforeach

                        </select>


                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"style="margin-bottom: 10px;">মামলার বিভাগ </label>

                        <select data-placeholder="Select" id="division"  name="division" class="select2 w-full">
                            <option value="" selected>Select</option>
                            @foreach($divisions as $division)

                                <option value="{{ $division->id }}"> {{ $division->name }}</option>

                            @endforeach

                        </select>

                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"style="margin-bottom: 10px;"> মামলার ধরন </label>

                        <select data-placeholder="Select"  id="case_type"  name="case_type" class="select2 w-full">
                            <option value="" selected>Select</option>
                            @foreach($types as $type)

                                <option value="{{ $type->id }}"> {{ $type->name }}</option>

                            @endforeach

                        </select>

                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> বিচারাধীন বিজ্ঞ আদালতের নাম</label>

                        <select data-placeholder="Select" id="court_name"   name="court_name" class="select2 w-full">
                            <option value="" selected>Select</option>
                            @foreach($courts as $court)

                                <option value="{{ $court->id }}"> {{ $court->name }}</option>

                            @endforeach

                        </select>

                </div>

                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row"> কোম্পানির নিয়োজিত বিজ্ঞ আইনজীবীর নাম </label>

                        <select data-placeholder="Select"  id="adv_name"  name="adv_name" class="select2 w-full">
                            <option value="" selected>Select</option>
                            @foreach($advocates as $advocate)

                                <option value="{{ $advocate->id }}"> {{ $advocate->name }}</option>

                            @endforeach

                        </select>

                </div>
                <div class="mt-3">
                    <label class="flex flex-col sm:flex-row" style="margin-bottom: 10px;">কোম্পানির নাম</label>
                    <select data-placeholder="Select" id="company_id" name="company_id" class="select2 w-full">
                        <option value="" selected>Select</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label class="flex flex-col sm:flex-row mb-2 font-medium">মামলার অবস্থা (নিষ্পত্তি)</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="nispotti_status" value="all"
                                class="mr-2" {{ old('nispotti_status', request('nispotti_status', 'all')) === 'all' ? 'checked' : '' }}>
                            <span>সব মামলা</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="nispotti_status" value="active"
                                class="mr-2" {{ old('nispotti_status', request('nispotti_status')) === 'active' ? 'checked' : '' }}>
                            <span class="text-blue-700 font-medium">চলমান মামলা (নিষ্পত্তি ছাড়া)</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="nispotti_status" value="nispotti"
                                class="mr-2" {{ old('nispotti_status', request('nispotti_status')) === 'nispotti' ? 'checked' : '' }}>
                            <span class="text-green-700 font-medium">শুধু নিষ্পত্তি মামলা</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="button bg-theme-1 text-white mt-5"   />  Search </button>

            </form>
        </div>
    </div>

    <!-- Start : Datatable -->

    <div class="intro-y datatable-wrapper box p-5 mt-5" id="print-section">

        <!-- Print Header (only visible when printing) -->
        <div id="print-header" style="display:none;">
            <div style="text-align:center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 14px;">
                <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 4px 0;">মামলার তালিকা</h1>
                <h2 style="font-size: 14px; font-weight: normal; color: #444; margin: 0 0 6px 0;">Case Filter Report</h2>
                <p style="font-size: 11px; color: #666; margin: 0;">মুদ্রণের তারিখ: <span id="print-date"></span> &nbsp;|&nbsp; মোট রেকর্ড: <strong>{{ count($histories) }}</strong></p>
            </div>
            @if(!empty($appliedFilters))
            <div style="margin-bottom: 12px; padding: 8px 12px; background: #f5f5f5; border-left: 4px solid #333; font-size: 11px;">
                @foreach($appliedFilters as $label => $value)
                    <span style="display:inline-block; margin-right:16px;">
                        <span style="color:#555;">{{ $label }}:</span>
                        <strong>{{ $value }}</strong>
                    </span>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Print Button -->
        @if(count($histories) > 0)
        <div class="flex justify-end mb-3 no-print">
            <button onclick="printTable()" class="button flex items-center bg-gray-700 text-white">
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
                        <span class="font-medium">{{ $loop->iteration }}</span>

                    </td>
                    <td>
                        <a class="flex items-center mr-3" href="{{ route('dashboard.histories.show', $history->id) }}">
                            <span href="" class="font-medium">@if($history->cases)
                                    {{ $history->cases->case_no}}
                                @else
                                    Not Found
                                @endif</span>
                        </a>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ $history->cases?->projects?->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ $history->cases?->divisions?->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ $history->cases?->types?->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ $history->cases?->courts?->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span href="" class="font-medium">{{ $history->cases?->advocates?->name ?? 'Not Found' }}</span>

                    </td>
                    <td>
                        <span class="font-medium">{{ $history->cases?->companies?->name ?? '—' }}</span>
                    </td>
                    <td>
                        @if($history->is_nispotti)
                            <div>
                                <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-400">নিষ্পত্তি</span>
                                @if($history->nispotti_date)
                                    <div class="text-xs text-green-700 mt-1">{{ \Carbon\Carbon::parse($history->nispotti_date)->format('d M Y') }}</div>
                                @endif
                            </div>
                        @else
                            <span class="font-medium">{{ $history->next_date ? \Carbon\Carbon::parse($history->next_date)->format('d M Y') : '-' }}</span>
                        @endif
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
    #print-section { position: absolute; top: 0; left: 0; width: 100%; padding: 20px; }

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
    }
    #result-table thead tr { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
    #result-table tbody tr:nth-child(even) { background-color: #fafafa !important; -webkit-print-color-adjust: exact; }

    /* Nispotti badge in print */
    #result-table .bg-green-100 { background-color: #dcfce7 !important; -webkit-print-color-adjust: exact; }

    @page { margin: 15mm; size: A4 landscape; }
}
</style>

<script>
function printTable() {
    document.getElementById('print-date').textContent = new Date().toLocaleDateString('bn-BD', {
        year: 'numeric', month: 'long', day: 'numeric'
    });

    // Show ALL rows in DataTable before printing
    var table = $('#result-table').DataTable();
    var prevLen = table.page.len();
    table.page.len(-1).draw();  // -1 = show all

    setTimeout(function () {
        window.print();
        // Restore original page length after print dialog closes
        table.page.len(prevLen).draw();
    }, 400);
}
</script>

@endsection
