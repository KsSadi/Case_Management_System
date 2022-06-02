@section('page-title')
    Route Control
@endsection


@extends('backend.layouts.main')

@section('admin-section')
@include('backend.layouts.partials.alerts')

<a href="{{ route('dashboard.courts.create') }}" style="max-width: 220px" class="button mt-8 w-100 mr-2 mb-2 flex bg-theme-1 text-white"> <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Add  New Courts </a>
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="whitespace-no-wrap" width="10%"></th>
                <th class="whitespace-no-wrap">COURT NAME</th>
                <th class="text-center whitespace-no-wrap">ACTIONS</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($courts as $court)
            <tr class="intro-x">
                <td class="w-40">
                    <div class="flex">
                        <div class="w-10 h-10 image-fit" style="margin-top: 9px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>

                    </div>
                </td>
                <td>
                    <span href="" class="font-medium whitespace-no-wrap">{{ $court->name }}</span>

                </td>


                <td class="table-report__action w-56">
                    <div class="flex justify-center items-center">
                        <a class="flex items-center mr-3" href="{{ route('dashboard.courts.edit', $court->id) }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> Edit </a>

                        <a class="flex items-center text-theme-6" href="{{ route('dashboard.courts.destroy', $court->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $court->id }}').submit()"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete </a>
                        <form id="delete-form-{{$court->id}}" action="{{ route('dashboard.courts.destroy', $court->id) }}" method="POST" style="display: none">
                            @method('DELETE')
                            @csrf
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
