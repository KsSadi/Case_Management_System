@section('page-title')
    Dashboard Home
@endsection


@extends('backend.layouts.main')

@section('admin-section')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xxl:col-span-9 grid grid-cols-12 gap-6">
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

            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                       Up Comming Case -  <span class="rounded" style="background:mediumvioletred;color: #F1F5F8;padding: 5px;">{{$nextdays->count()}}</span>
                    </h2>

                   <i data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> Next 7 Days
                </div>

                <div class="intro-y datatable-wrapper box p-5 mt-5">

                    <table class="table table-report table-report--bordered display datatable w-full">
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
                        <span href="" class="font-medium"> @if($nextday->cases)
                                {{ $nextday->cases->advocates->name }}
                            @else
                                Not Found
                            @endif </span>

                                </td>
                                <td>
                                    <span href="" class="font-medium">{{ $nextday->next_date }}</span>

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
                <!-- END: Datatable -->
            </div>
        </div>
        <!----End Left Side---->
        <div class="col-span-12 xxl:col-span-3 xxl:border-l border-theme-5 -mb-10 pb-10">
            <div class="xxl:pl-6 grid grid-cols-12 gap-6">

                <!-- BEGIN: Transactions -->
                <div class="col-span-12 md:col-span-6 xl:col-span-4 xxl:col-span-12 mt-3 xxl:mt-8">
                    <div class="intro-x flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Statistics - <span class=" text-theme-6">{{ $month_name}}</span>
                        </h2>
                    </div>
                    <div class="mt-5">
                        <div class="intro-x">
                            <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                    <img alt="Midone Tailwind HTML Admin Template" src="dist/images/profile-14.jpg">
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Leonardo DiCaprio</div>
                                    <div class="text-gray-600 text-xs">6 August 2022</div>
                                </div>
                                <div class="text-theme-9">+$23</div>
                            </div>
                        </div>
                        <div class="intro-x">
                            <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                    <img alt="Midone Tailwind HTML Admin Template" src="dist/images/profile-10.jpg">
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Tom Cruise</div>
                                    <div class="text-gray-600 text-xs">21 July 2020</div>
                                </div>
                                <div class="text-theme-9">+$83</div>
                            </div>
                        </div>
                        <div class="intro-x">
                            <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                    <img alt="Midone Tailwind HTML Admin Template" src="dist/images/profile-12.jpg">
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Al Pacino</div>
                                    <div class="text-gray-600 text-xs">5 January 2021</div>
                                </div>
                                <div class="text-theme-9">+$199</div>
                            </div>
                        </div>
                        <div class="intro-x">
                            <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                    <img alt="Midone Tailwind HTML Admin Template" src="dist/images/profile-6.jpg">
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Russell Crowe</div>
                                    <div class="text-gray-600 text-xs">22 April 2020</div>
                                </div>
                                <div class="text-theme-9">+$43</div>
                            </div>
                        </div>
                        <div class="intro-x">
                            <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                    <img alt="Midone Tailwind HTML Admin Template" src="dist/images/profile-15.jpg">
                                </div>
                                <div class="ml-4 mr-auto">
                                    <div class="font-medium">Al Pacino</div>
                                    <div class="text-gray-600 text-xs">8 October 2022</div>
                                </div>
                                <div class="text-theme-9">+$112</div>
                            </div>
                        </div>
                        <a href="" class="intro-x w-full block text-center rounded-md py-3 border border-dotted border-theme-15 text-theme-16">View More</a>
                    </div>
                </div>
                <!-- END: Transactions -->
            </div>
        </div>


    </div>

@endsection
