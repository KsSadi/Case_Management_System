@section('page-title')
    @if(isset($case)) Update High Court Case @else Create High Court Case @endif
@endsection

@extends('backend.layouts.main')

@section('admin-section')
    @include('backend.layouts.partials.alerts')

    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                @if(isset($case)) Update High Court Case @else Create High Court Case @endif
            </h2>
        </div>

        <!-- BEGIN: Form -->
        <div class="p-5">
            <form id="submit-form" method="POST">
                @if(isset($case))
                    @method('PUT')
                @endif
                @csrf

                <div>
                    <label for="case_no" class="font-medium text-base">মামলা নং (Case No)</label>
                    <input type="text" id="case_no" name="case_no" class="input w-full border mt-2" placeholder="e.g. WP 1234/2026" value="{{ $case->case_no ?? '' }}" required>
                </div>

                <div class="mt-3">
                    <label for="parties_name" class="font-medium text-base">সংশ্লিষ্ট পক্ষদের নাম (Name of the Party)</label>
                    <input type="text" id="parties_name" name="parties_name" class="input w-full border mt-2" placeholder="e.g. X vs Y" value="{{ $case->parties_name ?? '' }}" required>
                </div>

                <div class="mt-3">
                    <label for="case_details" class="font-medium text-base">মামলার সংক্ষিপ্ত বিবরণ (Case Details)</label>
                    <textarea id="case_details" name="case_details" class="input w-full border mt-2" rows="4" placeholder="Enter case details...">{{ $case->case_details ?? '' }}</textarea>
                </div>

                <div class="mt-3">
                    <label for="first_order" class="font-medium text-base">প্রথম আদেশ (1st Order)</label>
                    <textarea id="first_order" name="first_order" class="input w-full border mt-2" rows="4" placeholder="Enter first order details...">{{ $case->first_order ?? '' }}</textarea>
                </div>

                <div class="mt-3">
                    <label for="last_order" class="font-medium text-base">শেষ আদেশ (Last Order)</label>
                    <textarea id="last_order" name="last_order" class="input w-full border mt-2" rows="4" placeholder="Enter last order details...">{{ $case->last_order ?? '' }}</textarea>
                </div>

                <button type="submit" class="button bg-theme-1 text-white mt-5">
                    @if(isset($case)) Update @else Create @endif
                </button>
                <a href="{{ route('dashboard.supreme-court.high-court.index') }}" class="button bg-gray-200 text-gray-700 mt-5 ml-2 inline-block">Cancel</a>
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

                @if(isset($case))
                    var actionurl = "{{ route('dashboard.supreme-court.high-court.update', $case->id) }}";
                @else
                    var actionurl = "{{ route('dashboard.supreme-court.high-court.store') }}";
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
                                window.location.href = "{{ route('dashboard.supreme-court.high-court.index') }}";
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
