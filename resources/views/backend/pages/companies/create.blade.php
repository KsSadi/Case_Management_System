@section('page-title')
    Create Company
@endsection


@extends('backend.layouts.main')

@section('admin-section')

    @include('backend.layouts.partials.alerts')

    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                @if(isset($company))
                    Update Company
                @else
                    Create Company
                @endif
            </h2>
        </div>
        <div class="p-5" id="horizontal-form">
            <form id="submit-form" method="POST">
                @if(isset($company))
                    @method('PUT')
                @endif

                @csrf
                <div class="preview">
                    <div class="flex flex-col sm:flex-row items-center">
                        <label class="w-full sm:w-20 sm:text-right sm:mr-5">Company Name</label>
                        <input type="text" class="input w-full border mt-2 flex-1" placeholder="Enter company name" id="name" @if(isset($company)) value="{{ $company->name }}" @endif name="name" required>
                    </div>

                    <div class="sm:ml-20 sm:pl-5 mt-5">
                        <input type="submit" class="button text-white" style="background: steelblue" @if(isset($company)) value="Update" @else value="Create" @endif />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var form = $('#submit-form');
            form.on('submit', function(e) {
                e.preventDefault();

                var data = form.serialize();

                @if(isset($company))
                var actionurl = "{{ route('dashboard.companies.update', $company->id) }}";
                @else
                var actionurl = "{{ route('dashboard.companies.store') }}";
                @endif

                $.ajax({
                    url: actionurl,
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        if (data.status === 'success') {
                            Swal.fire('Success!', data.msg, 'success').then(function() {
                                window.location.href = "{{ route('dashboard.companies.index') }}";
                            });
                        } else {
                            Swal.fire('Error!', data.msg, 'error');
                        }
                    }
                });
            });

        });
    </script>
@endsection
