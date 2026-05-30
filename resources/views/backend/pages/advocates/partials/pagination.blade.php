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
