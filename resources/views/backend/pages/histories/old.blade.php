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
        
        <a href="{{ route('dashboard.histories.create') }}" style="max-width: 220px" class="button w-100 mr-2 flex bg-theme-1 text-white"> 
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg> Add New Case History 
        </a>
    </div>

    <div class="intro-y box p-3 md:p-5 mt-5 mb-3">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-theme-6 mr-2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <h2 class="text-lg font-medium">পুরানো মামলার ইতিহাস (Old Case Histories)</h2>
        </div>
        <p class="text-gray-600 mt-2">এই মামলাগুলোর পরবর্তী তারিখ পার হয়ে গেছে। আপনি এখানে তারিখ আপডেট করতে পারবেন।</p>
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
                    <th class="whitespace-no-wrap hidden xl:table-cell">বিচারাধীন বিজ্ঞ আদালতের নাম</th>
                    <th class="whitespace-no-wrap hidden md:table-cell">নিয়োজিত আইনজীবীর নাম</th>
                    <th class="whitespace-no-wrap">পরবর্তী তারিখ (Expired)</th>
                    <th class="text-center whitespace-no-wrap">ACTIONS</th>

            </tr>
            </thead>
            <tbody>


            @forelse($histories as $history)

                <tr class="bg-red-50">


                    <td>
                        <span class="font-medium">{{ $history->id }}</span>

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
                    <td>
                        <span class="font-medium text-theme-6">{{ \Carbon\Carbon::parse($history->next_date)->format('d M Y') }}</span>

                    </td>


                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="{{ route('dashboard.histories.edit', $history->id) }}"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg> Edit 
                            </a>

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
                        </div>
                    </td>
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




@endsection
