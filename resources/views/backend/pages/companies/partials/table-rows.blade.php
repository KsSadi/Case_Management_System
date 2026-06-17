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
