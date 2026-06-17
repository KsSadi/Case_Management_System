@section('page-title')
    Company
@endsection


@extends('backend.layouts.main')

@section('admin-section')
@include('backend.layouts.partials.alerts')

<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Companies List</h2>
    @if (Auth::guard('admin')->user()->can('company.create'))
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('dashboard.companies.create') }}" class="button w-full sm:w-auto flex items-center text-white bg-theme-1 shadow-md mr-2">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            New Company
        </a>
    </div>
    @endif
</div>

<!-- Search Box -->
<div class="intro-y box p-5 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
        <form id="search-form" class="xl:flex sm:mr-auto w-full sm:w-auto">
            <div class="sm:flex items-center w-full sm:w-auto">
                <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Search:</label>
                <input
                    id="search-input"
                    name="search"
                    type="text"
                    class="input w-full sm:w-64 xxl:w-full mt-2 sm:mt-0 border border-gray-300"
                    placeholder="Search by name..."
                    value="{{ $search ?? '' }}"
                    autocomplete="off">
                <button type="button" id="clear-search" class="button w-full sm:w-24 bg-gray-200 text-gray-600 mt-2 sm:mt-0 sm:ml-2 {{ !$search ? 'hidden' : '' }}">
                    Clear
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results Summary -->
<div class="intro-y mt-5" id="results-summary">
    <div class="flex items-center">
        <div class="text-gray-600">
            Showing {{ $companies->firstItem() ?? 0 }} to {{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }} entries
            @if($search)
                <span class="font-medium">(filtered from search: "{{ $search }}")</span>
            @endif
        </div>
    </div>
</div>

<!-- Loading Indicator -->
<div id="loading-indicator" class="intro-y mt-5 hidden">
    <div class="flex items-center justify-center py-4">
        <svg class="animate-spin h-8 w-8 text-theme-1 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-600">Searching...</span>
    </div>
</div>

<!-- Table Container -->
<div class="intro-y box mt-5 overflow-x-auto">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="whitespace-no-wrap text-center" width="10%">ICON</th>
                <th class="whitespace-no-wrap">COMPANY NAME</th>
                @if (Auth::guard('admin')->user()->can('company.edit') || Auth::guard('admin')->user()->can('company.delete'))
                <th class="text-center whitespace-no-wrap">ACTIONS</th>
                @endif
            </tr>
        </thead>
        <tbody id="companies-table-body">
            @forelse ($companies as $company)
            <tr class="intro-x">
                <td class="text-center">
                    <div class="flex justify-center">
                        <div class="w-10 h-10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase text-theme-1">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="font-medium whitespace-no-wrap">{{ $company->name }}</span>
                </td>
                @if (Auth::guard('admin')->user()->can('company.edit') || Auth::guard('admin')->user()->can('company.delete'))
                <td class="table-report__action">
                    <div class="flex justify-center items-center">
                        @if (Auth::guard('admin')->user()->can('company.edit'))
                        <a class="flex items-center mr-3 text-theme-1" href="{{ route('dashboard.companies.edit', $company->id) }}" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                        @endif
                        @if (Auth::guard('admin')->user()->can('company.delete'))
                        <a class="flex items-center text-theme-6" href="{{ route('dashboard.companies.destroy', $company->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $company->id }}').submit()" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            <span class="hidden sm:inline">Delete</span>
                        </a>
                        <form id="delete-form-{{$company->id}}" action="{{ route('dashboard.companies.destroy', $company->id) }}" method="POST" style="display: none">
                            @method('DELETE')
                            @csrf
                        </form>
                        @endif
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ Auth::guard('admin')->user()->can('company.edit') || Auth::guard('admin')->user()->can('company.delete') ? 3 : 2 }}" class="text-center py-8">
                    <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox text-gray-400 mb-3">
                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                            <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                        </svg>
                        <p class="text-gray-600 text-lg">No companies found</p>
                        @if($search)
                            <p class="text-gray-500 text-sm mt-2">Try adjusting your search criteria</p>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div id="pagination-container" class="mt-5">
    @if($companies->hasPages())
    <div class="intro-y flex flex-wrap sm:flex-row sm:flex-no-wrap items-center">
        <div class="w-full sm:w-auto sm:mr-auto">
            {{ $companies->appends(['search' => $search])->links() }}
        </div>
        <div class="text-center sm:text-right text-gray-600 mt-3 sm:mt-0">
            Page {{ $companies->currentPage() }} of {{ $companies->lastPage() }}
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let searchTimeout;

        // Live search with AJAX
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimeout);
            const searchValue = $(this).val();

            searchTimeout = setTimeout(function() {
                performSearch(searchValue, 1);
            }, 500);
        });

        // Clear search
        $('#clear-search').on('click', function() {
            $('#search-input').val('');
            $('#clear-search').addClass('hidden');
            performSearch('', 1);
        });

        // Pagination clicks
        $(document).on('click', '#pagination-container .pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const page = new URL(url).searchParams.get('page');
            const searchValue = $('#search-input').val();
            performSearch(searchValue, page);
        });

        function performSearch(searchValue, page = 1) {
            $('#loading-indicator').removeClass('hidden');
            $('#results-summary').addClass('hidden');

            if (searchValue.length > 0) {
                $('#clear-search').removeClass('hidden');
            } else {
                $('#clear-search').addClass('hidden');
            }

            $.ajax({
                url: '{{ route("dashboard.companies.index") }}',
                type: 'GET',
                data: {
                    search: searchValue,
                    page: page,
                },
                success: function(data) {
                    $('#companies-table-body').html(data.html);
                    $('#pagination-container').html(data.pagination);
                    $('#results-summary').html(data.summary);
                    $('#loading-indicator').addClass('hidden');
                    $('#results-summary').removeClass('hidden');
                },
                error: function() {
                    $('#loading-indicator').addClass('hidden');
                    $('#results-summary').removeClass('hidden');
                }
            });
        }
    });
</script>
@endsection
