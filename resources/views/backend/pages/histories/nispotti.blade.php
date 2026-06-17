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

    <div class="intro-y datatable-wrapper box p-3 md:p-5 mt-2" style="overflow: visible;">
        <div class="overflow-x-auto" style="width: 100%; overflow-x: auto;">
            <table class="table table-report table-report--bordered display datatable w-full" style="min-width: 600px;">
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
                    <th class="text-center whitespace-no-wrap">ACTIONS</th>
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
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="{{ route('dashboard.histories.edit', $history->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                    Edit
                                </a>
                                <a class="flex items-center text-theme-6" href="{{ route('dashboard.histories.destroy', $history->id) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-form-{{ $history->id }}').submit()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    Delete
                                </a>
                                <form id="delete-form-{{ $history->id }}" action="{{ route('dashboard.histories.destroy', $history->id) }}" method="POST" style="display: none">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </td>
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

@endsection
