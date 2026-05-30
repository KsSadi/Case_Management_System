<div class="text-gray-600">
    Showing {{ $advocates->firstItem() ?? 0 }} to {{ $advocates->lastItem() ?? 0 }} of {{ $advocates->total() }} entries
    @if($search)
        <span class="font-medium">(filtered from search: "{{ $search }}")</span>
    @endif
</div>
