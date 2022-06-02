<!-- Start : Datatable -->

<div class="intro-y datatable-wrapper box p-5 mt-5" id="date-range">


    <table class="table table-report table-report--bordered display datatable w-full">
        <thead>
        <tr>

            <th class="whitespace-no-wrap">ক্রঃ নং</th>
            <th class="whitespace-no-wrap">মামলা নং</th>
            <th class="whitespace-no-wrap">প্রজেক্টের নাম</th>
            <th class="whitespace-no-wrap">মামলার বিভাগ</th>
            <th class="whitespace-no-wrap">মামলার ধরন</th>
            <th class="whitespace-no-wrap">বিচারাধীন বিজ্ঞ আদালতের নাম</th>
            <th class="whitespace-no-wrap">কোম্পানির নিয়োজিত বিজ্ঞ আইনজীবীর নাম</th>
            <th class="whitespace-no-wrap">পরবর্তী তারিখ</th>
            <th class="text-center whitespace-no-wrap">ACTIONS</th>

        </tr>
        </thead>
        <tbody>


        @foreach($historydates as $historydate)

            <tr>


                <td>
                    <span href="" class="font-medium">{{ $historydate->id }}</span>

                </td>
                <td>
                    <a class="flex items-center mr-3" href="{{ route('dashboard.histories.show', $historydate->id) }}">
                            <span href="" class="font-medium">@if($historydate->cases)
                                    {{ $historydate->cases->case_no}}
                                @else
                                    Not Found
                                @endif</span>
                    </a>

                </td>
                <td>
                        <span href="" class="font-medium">@if($historydate->cases)
                                {{ $historydate->cases->projects->name}}
                            @else
                                Not Found
                            @endif</span>

                </td>
                <td>
                        <span href="" class="font-medium">@if($historydate->cases)
                                {{ $historydate->cases->divisions->name}}
                            @else
                                Not Found
                            @endif</span>

                </td>
                <td>
                        <span href="" class="font-medium">@if($historydate->cases)
                                {{ $historydate->cases->types->name }}
                            @else
                                Not Found
                            @endif</span>

                </td>
                <td>
                        <span href="" class="font-medium">@if($historydate->cases)
                                {{ $historydate->cases->courts->name }}
                            @else
                                Not Found
                            @endif</span>

                </td>
                <td>
                        <span href="" class="font-medium"> @if($historydate->cases)
                                {{ $historydate->cases->advocates->name }}
                            @else
                                Not Found
                            @endif </span>

                </td>
                <td>
                    <span href="" class="font-medium">{{ $historydate->next_date }}</span>

                </td>


                <td class="table-report__action w-56">
                    <div class="flex justify-center items-center">
                        <a class="flex items-center mr-3"
                           href="{{ route('dashboard.histories.edit', $historydate->id) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            Edit </a>

                        <a class="flex items-center text-theme-6"
                           href="{{ route('dashboard.histories.destroy', $historydate->id) }}"
                           onclick="event.preventDefault(); document.getElementById('delete-form-{{ $historydate->id }}').submit()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            Delete </a>
                        <form id="delete-form-{{$historydate->id}}"
                              action="{{ route('dashboard.histories.destroy', $historydate->id) }}" method="POST"
                              style="display: none">
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
<!-- END: Datatable -->
