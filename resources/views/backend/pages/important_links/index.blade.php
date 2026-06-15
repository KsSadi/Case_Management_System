@section('page-title')
    Important Links
@endsection

@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Important Links List
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="{{ route('dashboard.important-links.create') }}" class="button text-white bg-theme-1 shadow-md mr-2 flex items-center">
                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Add New Link
            </a>
        </div>
    </div>

    <!-- BEGIN: Datatable -->
    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <div class="overflow-x-auto">
            <table class="table table-report table-report--bordered display datatable w-full">
                <thead>
                <tr>
                    <th class="whitespace-no-wrap" style="width: 80px;">ক্রঃ নং</th>
                    <th class="whitespace-no-wrap">নাম (Name)</th>
                    <th class="whitespace-no-wrap">লিঙ্ক (Link)</th>
                    <th class="whitespace-no-wrap" style="width: 120px;">ভিজিট (Visit)</th>
                    <th class="text-center whitespace-no-wrap" style="width: 180px;">ACTIONS</th>
                </tr>
                </thead>
                <tbody>
                @foreach($links as $link)
                    <tr>
                        <td>
                            <span class="font-medium">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <span class="font-medium">{{ $link->name }}</span>
                        </td>
                        <td>
                            <span class="font-medium text-gray-600 break-all">{{ $link->link }}</span>
                        </td>
                        <td>
                            <a href="{{ $link->link }}" target="_blank" class="button inline-block text-white bg-theme-9 px-3 py-1 rounded text-xs flex items-center justify-center w-20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                Visit
                            </a>
                        </td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="{{ route('dashboard.important-links.edit', $link->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> Edit
                                </a>
                                <a class="flex items-center text-theme-6" href="{{ route('dashboard.important-links.destroy', $link->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $link->id }}').submit()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
                                </a>
                                <form id="delete-form-{{$link->id}}" action="{{ route('dashboard.important-links.destroy', $link->id) }}" method="POST" style="display: none">
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
@endsection
