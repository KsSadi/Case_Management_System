@section('page-title')
    Appellate Case Details
@endsection

@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <!-- BEGIN: Bordered Table -->
    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                Appellate Case Details
            </h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <a href="{{ route('dashboard.supreme-court.appellate.index') }}" class="button bg-gray-200 text-gray-700 mr-2 flex items-center">
                    Back to List
                </a>
                <a href="{{ route('dashboard.supreme-court.appellate.edit', $case->id) }}" class="button text-white bg-theme-1 shadow-md flex items-center">
                    Edit Case
                </a>
            </div>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th class="border w-48 font-semibold bg-gray-100">ID</th>
                        <td class="border">{{ $case->id }}</td>
                    </tr>
                    <tr>
                        <th class="border font-semibold bg-gray-100">মামলা নং (Case No)</th>
                        <td class="border">{{ $case->case_no }}</td>
                    </tr>
                    <tr>
                        <th class="border font-semibold bg-gray-100">সংশ্লিষ্ট পক্ষদের নাম (Name of the Party)</th>
                        <td class="border">{{ $case->parties_name }}</td>
                    </tr>
                    <tr>
                        <th class="border font-semibold bg-gray-100">মামলার সংক্ষিপ্ত বিবরণ (Case Details)</th>
                        <td class="border" style="white-space: pre-wrap;">{{ $case->case_details ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="border font-semibold bg-gray-100">প্রথম আদেশ (1st Order)</th>
                        <td class="border" style="white-space: pre-wrap;">{{ $case->first_order ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="border font-semibold bg-gray-100">শেষ আদেশ (Last Order)</th>
                        <td class="border" style="white-space: pre-wrap;">{{ $case->last_order ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="border font-semibold bg-gray-100">Created At</th>
                        <td class="border">{{ $case->created_at ? $case->created_at->format('d M Y, h:i A') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="border font-semibold bg-gray-100">Updated At</th>
                        <td class="border">{{ $case->updated_at ? $case->updated_at->format('d M Y, h:i A') : '—' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END: Bordered Table -->
@endsection
