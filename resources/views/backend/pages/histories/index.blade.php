@section('page-title')
    Route Control
@endsection


@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <div class="flex flex-wrap gap-2 mt-8 mb-2">
        @if (Auth::guard('admin')->user()->can('history.create'))
        <a href="{{ route('dashboard.histories.create') }}" style="max-width: 220px" class="button w-100 mr-2 flex bg-theme-1 text-white"> 
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg> Add New Case History 
        </a>
        @endif
        
        @if($oldHistoriesCount > 0)
        <a href="{{ route('dashboard.histories.old') }}" style="max-width: 250px" class="button w-100 flex bg-theme-6 text-white"> 
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg> 
            Old Histories ({{ $oldHistoriesCount }})
        </a>
        @endif
        <a href="{{ route('dashboard.histories.nispotti') }}" style="max-width: 250px" class="button w-100 flex items-center bg-green-600 text-white">
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            নিষ্পত্তি তালিকা
        </a>
    </div>

    <div class="intro-y datatable-wrapper box p-3 md:p-5 mt-5" style="overflow: visible;">
        <!-- Mobile Responsive: Horizontal scroll wrapper -->
        <div class="overflow-x-auto" style="width: 100%; overflow-x: auto;">
            <table class="table table-report table-report--bordered display datatable w-full" style="min-width: 600px;">
                <thead>
                <tr>
                    <th class="whitespace-no-wrap">ক্রঃ নং</th>
                    <th class="whitespace-no-wrap">মামলা নং</th>
                    <th class="whitespace-no-wrap hidden md:table-cell">প্রজেক্টের নাম</th>
                    <th class="whitespace-no-wrap hidden lg:table-cell">মামলার বিভাগ</th>
                    <th class="whitespace-no-wrap hidden lg:table-cell">মামলার ধরন</th>
                    <th class="whitespace-no-wrap hidden xl:table-cell">আদালতের নাম</th>
                    <th class="whitespace-no-wrap hidden md:table-cell">আইনজীবীর নাম</th>
                    <th class="whitespace-no-wrap hidden md:table-cell">কোম্পানির নাম</th>
                    <th class="whitespace-no-wrap">পরবর্তী তারিখ</th>
                    @if (Auth::guard('admin')->user()->can('history.edit') || Auth::guard('admin')->user()->can('history.delete'))
                    <th class="text-center whitespace-no-wrap">ACTIONS</th>
                    @endif

            </tr>
            </thead>
            <tbody>


            @foreach($histories as $history)
                @php
                    $isNispotti = $history->is_nispotti;
                    $isExpired = !$isNispotti && \Carbon\Carbon::parse($history->next_date)->lt(now()->startOfDay());
                @endphp
                <tr class="{{ $isExpired ? 'bg-red-50' : ($isNispotti ? 'bg-green-50' : '') }}">


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
                    <td class="hidden md:table-cell">
                        <span class="font-medium">{{ $history->cases?->companies?->name ?? '—' }}</span>
                    </td>
                    <td data-order="{{ $isNispotti ? ($history->nispotti_date ?? '') : ($history->next_date ?? '') }}">
                        @if($isNispotti)
                            <div>
                                <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-400">নিষ্পত্তি</span>
                                @if($history->nispotti_date)
                                    <div class="text-xs text-green-700 mt-1">{{ \Carbon\Carbon::parse($history->nispotti_date)->format('d M Y') }}</div>
                                @endif
                            </div>
                        @else
                            <span href="" class="font-medium {{ $isExpired ? 'text-theme-6 font-bold' : '' }}">{{ $history->next_date ? \Carbon\Carbon::parse($history->next_date)->format('d M Y') : '-' }}</span>
                        @endif
                    </td>


                    @if (Auth::guard('admin')->user()->can('history.edit') || Auth::guard('admin')->user()->can('history.delete'))
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            @if (Auth::guard('admin')->user()->can('history.edit'))
                            <a class="flex items-center mr-3" href="{{ route('dashboard.histories.edit', $history->id) }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> Edit </a>
                            @endif

                            @if (Auth::guard('admin')->user()->can('history.delete'))
                            <a class="flex items-center text-theme-6" href="{{ route('dashboard.histories.destroy', $history->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $history->id }}').submit()"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete </a>
                            <form id="delete-form-{{$history->id}}" action="{{ route('dashboard.histories.destroy', $history->id) }}" method="POST" style="display: none">
                                @method('DELETE')
                                @csrf
                            </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>

            @endforeach

            </tbody>
        </table>
        </div>
        <!-- End overflow wrapper -->
    </div>
    <!-- END: Datatable -->




@endsection
