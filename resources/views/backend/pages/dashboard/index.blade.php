@section('page-title')
    Dashboard Home
@endsection


@extends('backend.layouts.main')

@section('admin-section')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        General Report
                    </h2>
                    <a href="" class="ml-auto flex text-theme-1"> <i data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data </a>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <i data-feather="trello" class="report-box__icon text-theme-10"></i>
                                    <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-6 tooltip cursor-pointer" title="33% Higher than last month"> <i data-feather="calendar" class="w-4 h-4"></i>   {{ $month_name}}</div>
                                    </div>
                                </div>
                                <div class="text-3xl font-bold leading-8 mt-6">{{$history}}</div>
                                <div class="text-base text-gray-600 mt-1">Running Case</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-aperture"><circle cx="12" cy="12" r="10"></circle><line x1="14.31" y1="8" x2="20.05" y2="17.94"></line><line x1="9.69" y1="8" x2="21.17" y2="8"></line><line x1="7.38" y1="12" x2="13.12" y2="2.06"></line><line x1="9.69" y1="16" x2="3.95" y2="6.06"></line><line x1="14.31" y1="16" x2="2.83" y2="16"></line><line x1="16.62" y1="12" x2="10.88" y2="21.94"></line></svg>

                                <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="2% Lower than last month"> Total </div>
                                    </div>
                                </div>
                                <div class="text-3xl font-bold leading-8 mt-6">{{$project}}</div>
                                <div class="text-base text-gray-600 mt-1">Project</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>

                                <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-11 tooltip cursor-pointer" title="12% Higher than last month"> Total </div>
                                    </div>
                                </div>
                                <div class="text-3xl font-bold leading-8 mt-6">{{$cases}}</div>
                                <div class="text-base text-gray-600 mt-1">Total Case Item</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <i data-feather="user" class="report-box__icon text-theme-9"></i>
                                    <div class="ml-auto">
                                        <div class="report-box__indicator bg-theme-13 tooltip cursor-pointer" title="22% Higher than last month"> Total  </div>
                                    </div>
                                </div>
                                <div class="text-3xl font-bold leading-8 mt-6">{{ $advocates }}</div>
                                <div class="text-base text-gray-600 mt-1">Total Advocates</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: General Report -->

            <!-- BEGIN: Today & Tomorrow Cases -->
            <div class="col-span-12 mt-8">
                <div class="grid grid-cols-12 gap-6">

                    <!-- Today's Cases -->
                    <div class="col-span-12 lg:col-span-6">
                        <div class="intro-y flex items-center h-10 mb-3">
                            <h2 class="text-lg font-medium truncate mr-3">
                                আজকের মামলা
                            </h2>
                            <span class="rounded px-2 py-1 text-white text-sm font-bold" style="background:#0d6efd;">{{ $todayCases->count() }}</span>
                            <span class="ml-2 text-gray-500 text-sm">{{ \Carbon\Carbon::today()->format('d M Y') }}</span>
                        </div>
                        <div class="intro-y box p-3 md:p-4" style="overflow:visible;">
                            <div class="overflow-x-auto">
                                <table class="table table-report table-report--bordered w-full" style="min-width:400px;">
                                    <thead>
                                        <tr>
                                            <th class="whitespace-no-wrap">ক্রঃ নং</th>
                                            <th class="whitespace-no-wrap">মামলা নং</th>
                                            <th class="whitespace-no-wrap">আইনজীবী</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($todayCases as $item)
                                            <tr>
                                                <td><span class="font-medium">{{ $loop->iteration }}</span></td>
                                                <td>
                                                    <a class="flex items-center text-theme-1 font-medium" href="{{ route('dashboard.histories.show', $item->id) }}">
                                                        {{ $item->cases ? $item->cases->case_no : 'Not Found' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="font-medium">
                                                        {{ ($item->cases && $item->cases->advocates) ? $item->cases->advocates->name : 'Not Found' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-gray-500 py-4">আজকের কোনো মামলা নেই</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tomorrow's Cases -->
                    <div class="col-span-12 lg:col-span-6">
                        <div class="intro-y flex items-center h-10 mb-3">
                            <h2 class="text-lg font-medium truncate mr-3">
                                আগামীকালের মামলা
                            </h2>
                            <span class="rounded px-2 py-1 text-white text-sm font-bold" style="background:#fd7e14;">{{ $tomorrowCases->count() }}</span>
                            <span class="ml-2 text-gray-500 text-sm">{{ \Carbon\Carbon::tomorrow()->format('d M Y') }}</span>
                        </div>
                        <div class="intro-y box p-3 md:p-4" style="overflow:visible;">
                            <div class="overflow-x-auto">
                                <table class="table table-report table-report--bordered w-full" style="min-width:400px;">
                                    <thead>
                                        <tr>
                                            <th class="whitespace-no-wrap">ক্রঃ নং</th>
                                            <th class="whitespace-no-wrap">মামলা নং</th>
                                            <th class="whitespace-no-wrap">আইনজীবী</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tomorrowCases as $item)
                                            <tr>
                                                <td><span class="font-medium">{{ $loop->iteration }}</span></td>
                                                <td>
                                                    <a class="flex items-center text-theme-1 font-medium" href="{{ route('dashboard.histories.show', $item->id) }}">
                                                        {{ $item->cases ? $item->cases->case_no : 'Not Found' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="font-medium">
                                                        {{ ($item->cases && $item->cases->advocates) ? $item->cases->advocates->name : 'Not Found' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-gray-500 py-4">আগামীকালের কোনো মামলা নেই</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END: Today & Tomorrow Cases -->

            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                       Up Comming Case -  <span class="rounded" style="background:mediumvioletred;color: #F1F5F8;padding: 5px;">{{$nextdays->count()}}</span>
                    </h2>

                   <i data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> Next 7 Days
                </div>

                <div class="intro-y datatable-wrapper box p-3 md:p-5 mt-5" style="overflow: visible;">
                    <!-- Mobile Responsive: Horizontal scroll wrapper -->
                    <div class="overflow-x-auto" style="width: 100%; overflow-x: auto;">
                        <table class="table table-report table-report--bordered display datatable w-full" style="min-width: 600px;">
                        <thead>
                        <tr>

                            <th class="whitespace-no-wrap">ক্রঃ নং</th>
                            <th class="whitespace-no-wrap">মামলা নং</th>
{{--                            <th class="whitespace-no-wrap">প্রজেক্টের নাম</th>--}}
{{--                            <th class="whitespace-no-wrap">মামলার বিভাগ</th>--}}
{{--                            <th class="whitespace-no-wrap">মামলার ধরন</th>--}}
{{--                            <th class="whitespace-no-wrap">বিচারাধীন বিজ্ঞ আদালতের নাম</th>--}}
                            <th class="whitespace-no-wrap">নিয়োজিত আইনজীবীর নাম</th>
                            <th class="whitespace-no-wrap">পরবর্তী তারিখ </th>
{{--                            <th class="text-center whitespace-no-wrap">ACTIONS</th>--}}

                        </tr>
                        </thead>
                        <tbody>


                        @foreach($nextdays as $nextday)

                            <tr>


                                <td>
                                    <span href="" class="font-medium">{{ $nextday->id }}</span>

                                </td>
                                <td>
                                    <a class="flex items-center mr-3" href="{{ route('dashboard.histories.show', $nextday->id) }}">
                            <span href="" class="font-medium">@if($nextday->cases)
                                    {{ $nextday->cases->case_no}}
                                @else
                                    Not Found
                                @endif</span>
                                    </a>

                                </td>
{{--                                <td>--}}
{{--                        <span href="" class="font-medium">@if($nextday->cases)--}}
{{--                                {{ $nextday->cases->projects->name}}--}}
{{--                            @else--}}
{{--                                Not Found--}}
{{--                            @endif</span>--}}

{{--                                </td>--}}
{{--                                <td>--}}
{{--                        <span href="" class="font-medium">@if($nextday->cases)--}}
{{--                                {{ $nextday->cases->divisions->name}}--}}
{{--                            @else--}}
{{--                                Not Found--}}
{{--                            @endif</span>--}}

{{--                                </td>--}}
{{--                                <td>--}}
{{--                        <span href="" class="font-medium">@if($nextday->cases)--}}
{{--                                {{ $nextday->cases->types->name }}--}}
{{--                            @else--}}
{{--                                Not Found--}}
{{--                            @endif</span>--}}

{{--                                </td>--}}
{{--                                <td>--}}
{{--                        <span href="" class="font-medium">@if($nextday->cases)--}}
{{--                                {{ $nextday->cases->courts->name }}--}}
{{--                            @else--}}
{{--                                Not Found--}}
{{--                            @endif</span>--}}

{{--                                </td>--}}
                                <td>
                        <span href="" class="font-medium"> @if($nextday->cases && $nextday->cases->advocates)
                                {{ $nextday->cases->advocates->name }}
                            @else
                                Not Found
                            @endif </span>

                                </td>
                                <td>
                                    <span href="" class="font-medium">{{ \Carbon\Carbon::parse($nextday->next_date)->format('d M Y') }}</span>

                                </td>


{{--                                <td class="table-report__action w-56">--}}
{{--                                    <div class="flex justify-center items-center">--}}
{{--                                        <a class="flex items-center mr-3" href="{{ route('dashboard.histories.edit', $nextday->id) }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> Edit </a>--}}

{{--                                        <a class="flex items-center text-theme-6" href="{{ route('dashboard.histories.destroy', $nextday->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $nextday->id }}').submit()"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete </a>--}}
{{--                                        <form id="delete-form-{{$nextday->id}}" action="{{ route('dashboard.histories.destroy', $nextday->id) }}" method="POST" style="display: none">--}}
{{--                                            @method('DELETE')--}}
{{--                                            @csrf--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                    </div>
                    <!-- End overflow wrapper -->
                </div>
                <!-- END: Datatable -->
            </div>
        </div>
    </div>

@endsection
