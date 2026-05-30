<div class="text-gray-600">
    Showing {{ $companies->firstItem() ?? 0 }} to {{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }} entries
    @if($search)
        <span class="font-medium">(filtered from search: "{{ $search }}")</span>
    @endif
</div>
