@section('page-title')
    Case Details
@endsection


@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')



    <!-- BEGIN: Bordered Table -->
    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Case Details
            </h2>

        </div>
        <div class="p-5" id="bordered-table">
            <div class="preview">
                <div class="overflow-x-auto">
                    <table class="table">

                        <tbody>
                        <tr>

                            <th class="border">ক্রঃ নং</th>
                            <td class="border">{{ $case->id }}</td>
                        </tr>
                        <tr>

                            <th class="border">মামলা নং</th>
                            <td class="border">{{ $case->case_no }}</td>
                        </tr>
                        <tr>

                            <th class="border">বিচারাধীন বিজ্ঞ আদালতের নাম</th>
                            <td class="border">@if($case->courts)
                                    {{ $case->courts->name }}
                                @else
                                    Not Found
                                @endif</td>
                        </tr>
                        <tr>

                            <th class="border">সংশ্লিষ্ট পক্ষদের নাম</th>
                            <td class="border">{{ $case->parties_name }}</td>
                        </tr>
                        <tr>

                            <th class="border">মামলার সংক্ষিপ্ত বিবরণ</th>
                            <td class="border">{{ $case->case_details }}</td>
                        </tr>
                        <tr>

                            <th class="border">মামলার বিষয় ও ধারা</th>
                            <td class="border">{{ $case->case_subject }}</td>
                        </tr>
                        <tr>

                            <th class="border">মামলা দায়ের / শুনানীর সময় প্রথম আদেশ</th>
                            <td class="border">{{ $case->first_order }}</td>
                        </tr>
                        <tr>

                            <th class="border">কোম্পানির নিয়োজিত বিজ্ঞ আইনজীবীর নাম</th>
                            <td class="border">@if($case->advocates)
                                    {{ $case->advocates->name }}
                                @else
                                    Not Found
                                @endif</td>
                        </tr>



                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Bordered Table -->
@endsection
