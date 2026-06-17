@section('page-title')
    Advocate
@endsection


@extends('backend.layouts.main')

@section('admin-section')
@include('backend.layouts.partials.alerts')

<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Advocates List</h2>
    @if (Auth::guard('admin')->user()->can('advocate.create'))
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('dashboard.advocates.create') }}" class="button w-full sm:w-auto flex items-center text-white bg-theme-1 shadow-md mr-2">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            New Advocate
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
            Showing {{ $advocates->firstItem() ?? 0 }} to {{ $advocates->lastItem() ?? 0 }} of {{ $advocates->total() }} entries
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
                <th class="whitespace-no-wrap">ADVOCATE NAME</th>
                @if (Auth::guard('admin')->user()->can('advocate.edit') || Auth::guard('admin')->user()->can('advocate.delete'))
                <th class="text-center whitespace-no-wrap">ACTIONS</th>
                @endif
            </tr>
        </thead>
        <tbody id="advocates-table-body">
            @forelse ($advocates as $advocate)
            <tr class="intro-x">
                <td class="text-center">
                    <div class="flex justify-center">
                        <div class="w-10 h-10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check text-theme-1">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="font-medium whitespace-no-wrap">{{ $advocate->name }}</span>
                </td>
                @if (Auth::guard('admin')->user()->can('advocate.edit') || Auth::guard('admin')->user()->can('advocate.delete'))
                <td class="table-report__action">
                    <div class="flex justify-center items-center">
                        @if (Auth::guard('admin')->user()->can('advocate.edit'))
                        <a class="flex items-center mr-3 text-theme-1" href="{{ route('dashboard.advocates.edit', $advocate->id) }}" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                        @endif
                        @if (Auth::guard('admin')->user()->can('advocate.delete'))
                        <a class="flex items-center text-theme-6" href="{{ route('dashboard.advocates.destroy', $advocate->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $advocate->id }}').submit()" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            <span class="hidden sm:inline">Delete</span>
                        </a>
                        <form id="delete-form-{{$advocate->id}}" action="{{ route('dashboard.advocates.destroy', $advocate->id) }}" method="POST" style="display: none">
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
                <td colspan="{{ Auth::guard('admin')->user()->can('advocate.edit') || Auth::guard('admin')->user()->can('advocate.delete') ? 3 : 2 }}" class="text-center py-8">
                    <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox text-gray-400 mb-3">
                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                            <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                        </svg>
                        <p class="text-gray-600 text-lg">No advocates found</p>
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
    @if($advocates->hasPages())
    <div class="intro-y flex flex-wrap sm:flex-row sm:flex-no-wrap items-center">
        <div class="w-full sm:w-auto sm:mr-auto">
            {{ $advocates->appends(['search' => $search])->links() }}
        </div>
        <div class="text-center sm:text-right text-gray-600 mt-3 sm:mt-0">
            Page {{ $advocates->currentPage() }} of {{ $advocates->lastPage() }}
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let searchTimeout;
        let currentPage = 1;
        
        // Live search functionality with AJAX
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimeout);
            const searchValue = $(this).val();
            
            searchTimeout = setTimeout(function() {
                performSearch(searchValue, 1); // Reset to page 1 on new search
            }, 500); // Wait 500ms after user stops typing
        });
        
        // Clear search
        $('#clear-search').on('click', function() {
            $('#search-input').val('');
            $('#clear-search').addClass('hidden');
            performSearch('', 1);
        });
        
        // Handle pagination clicks
        $(document).on('click', '#pagination-container .pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const page = new URL(url).searchParams.get('page');
            const searchValue = $('#search-input').val();
            performSearch(searchValue, page);
        });
        
        // Perform AJAX search function
        function performSearch(searchValue, page = 1) {
            // Show loading indicator
            $('#loading-indicator').removeClass('hidden');
            $('#results-summary').addClass('hidden');
            
            $.ajax({
                url: '{{ route("dashboard.advocates.index") }}',
                type: 'GET',
                data: {
                    search: searchValue,
                    page: page
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    // Update table rows
                    $('#advocates-table-body').html(response.html);
                    
                    // Update pagination
                    $('#pagination-container').html(response.pagination);
                    
                    // Update summary
                    $('#results-summary').html(response.summary);
                    
                    // Hide loading, show summary
                    $('#loading-indicator').addClass('hidden');
                    $('#results-summary').removeClass('hidden');
                    
                    // Update URL without reloading
                    const url = new URL(window.location.href);
                    if (searchValue) {
                        url.searchParams.set('search', searchValue);
                    } else {
                        url.searchParams.delete('search');
                    }
                    if (page > 1) {
                        url.searchParams.set('page', page);
                    } else {
                        url.searchParams.delete('page');
                    }
                    window.history.pushState({}, '', url);
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error);
                    $('#loading-indicator').addClass('hidden');
                    $('#results-summary').removeClass('hidden');
                    alert('An error occurred while searching. Please try again.');
                }
            });
        }
        
        // Show/hide clear button
        $('#search-input').on('input', function() {
            if ($(this).val().length > 0) {
                $('#clear-search').removeClass('hidden');
            } else {
                $('#clear-search').addClass('hidden');
            }
        });
    });
</script>
@endsection
