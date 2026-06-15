@section('page-title')
    @if(isset($link)) Update Important Link @else Create Important Link @endif
@endsection

@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                @if(isset($link)) Update Important Link @else Create Important Link @endif
            </h2>
        </div>

        <!-- BEGIN: Form -->
        <div class="p-5">
            <form id="submit-form" method="POST">
                @if(isset($link))
                    @method('PUT')
                @endif
                @csrf

                <div>
                    <label for="name" class="font-medium text-base">নাম (Name)</label>
                    <input type="text" id="name" name="name" class="input w-full border mt-2" placeholder="e.g. Supreme Court of Bangladesh" value="{{ $link->name ?? '' }}" required>
                </div>

                <div class="mt-3">
                    <label for="link" class="font-medium text-base">লিঙ্ক (Link)</label>
                    <input type="url" id="link" name="link" class="input w-full border mt-2" placeholder="e.g. https://www.supremecourt.gov.bd" value="{{ $link->link ?? '' }}" required>
                </div>

                <button type="submit" class="button bg-theme-1 text-white mt-5">
                    @if(isset($link)) Update @else Create @endif
                </button>
                <a href="{{ route('dashboard.important-links.index') }}" class="button bg-gray-200 text-gray-700 mt-5 ml-2 inline-block">Cancel</a>
            </form>
        </div>
        <!-- END: Form -->
    </div>

    <script>
        $(document).ready(function(){
            $('#submit-form').on('submit', function (e) {
                e.preventDefault();

                var form = $(this);
                var data = form.serialize();

                @if(isset($link))
                    var actionurl = "{{ route('dashboard.important-links.update', $link->id) }}";
                @else
                    var actionurl = "{{ route('dashboard.important-links.store') }}";
                @endif

                $.ajax({
                    url: actionurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if(response.status === 'success'){
                            Swal.fire(
                                'Success!',
                                response.msg,
                                'success'
                            ).then(function() {
                                window.location.href = "{{ route('dashboard.important-links.index') }}";
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.msg || 'Something went wrong',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Something went wrong!';
                        if(xhr.responseJSON && xhr.responseJSON.msg) {
                            msg = xhr.responseJSON.msg;
                        }
                        Swal.fire('Error!', msg, 'error');
                    }
                });
            });
        });
    </script>
@endsection
