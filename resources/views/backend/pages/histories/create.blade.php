@section('page-title')
    Create Case Item
@endsection


@extends('backend.layouts.main')

@section('admin-section')

    @include('backend.layouts.partials.alerts')
    {{--    <link rel="stylesheet" href="http://rubick.left4code.com/dist/css/app.css" />--}}

    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                @if(isset($history))
                    Update Case History
                @else
                    Create Case History
                @endif
            </h2>
        </div>

        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">

            <div class="p-5" id="basic-datepicker">
                <div class="preview">
                    <form class="validate-form" id="submit-form" method="POST">
                        @if(isset($history))
                            @method('PUT')
                        @endif

                        @csrf
{{--                        <div>--}}
{{--                            <label class="flex flex-col sm:flex-row" style="margin-bottom: 10px;">প্রজেক্টের নাম  </label>--}}
{{--                            @if(isset($history))--}}
{{--                                <input type="text" value="{{$case->projects->name}}" name="project" class="input w-full border mt-2" placeholder="" readonly>--}}


{{--                            @else--}}
{{--                                <select data-placeholder="Select" id="project"  name="project" class="select2 w-full">--}}
{{--                                    <option value="" selected>Select</option>--}}
{{--                                    @foreach($projects as $projects)--}}

{{--                                        <option value="{{ $projects->id }}"> {{ $projects->name }}</option>--}}

{{--                                    @endforeach--}}

{{--                                </select>--}}

{{--                            @endif--}}
{{--                        </div>--}}


                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"style="margin-bottom: 10px;">মামলা নং </label>
                            @if(isset($history))
                                <input type="text" value="{{$history->case_id}}" name="case_id" class="input w-full border mt-2" placeholder="" readonly>


                            @else
                                <select data-placeholder="Select" id="case_id"  name="case_id" class="select2 w-full">
                                    <option value="" selected>Select</option>
                                    @foreach($cases as $case)

                                        <option value="{{ $case->id }}"> {{ $case->case_no }}</option>

                                    @endforeach

                                </select>
                            @endif
                        </div>

                         <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> এন্ট্রি তারিখ </label>
                            <input type="date" id="date"   @if(isset($history))value="{{$history->date}}" @endif name="date" class="input w-full border mt-2" placeholder="">
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> পূর্ববর্তী তারিখ </label>
                            <input type="date" id="past_date"  @if(isset($history))value="{{$history->past_date}}" @endif name="past_date" class="input w-full border mt-2" placeholder="">
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> ধার্য তারিখ  </label>
                            <input type="date" id="next_date"  @if(isset($history))value="{{$history->next_date}}" @endif name="next_date" class="input w-full border mt-2" placeholder="">
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> নির্ধারিত কার্যক্রম </label>
                            <input type="text" id="status"   @if(isset($history))value="{{$history->status}}" @endif  name="status" class="input w-full border mt-2" placeholder="">
                        </div>                        <div class="mt-4 flex items-center">
                            <input type="checkbox" id="is_nispotti" name="is_nispotti" value="1"
                                class="input border mr-2" style="width:20px;height:20px;"
                                @if(isset($history) && $history->is_nispotti) checked @endif>
                            <label for="is_nispotti" class="font-medium text-theme-6 cursor-pointer">নিষ্পত্তি (মামলা নিষ্পত্তি হয়েছে)</label>
                        </div>
                        <div class="mt-3" id="nispotti_date_row" style="display:none;">
                            <label class="flex flex-col sm:flex-row"> নিষ্পত্তির তারিখ </label>
                            <input type="date" id="nispotti_date" name="nispotti_date"
                                @if(isset($history) && $history->nispotti_date) value="{{ $history->nispotti_date }}" @endif
                                class="input w-full border mt-2" placeholder="">
                        </div>
                        <button type="submit" class="button bg-theme-1 text-white mt-5"   /> @if(isset($history)) Update @else Create @endif </button>
                    </form>
                </div>

            </div>
        </div>
        <!-- END: Form Validation -->

    </div>

    <script>
        $(document).ready(function(){

            // Toggle next_date / nispotti_date based on নিষ্পত্তি checkbox
            function toggleNextDate() {
                if ($('#is_nispotti').is(':checked')) {
                    $('#next_date').val('').prop('disabled', true).closest('.mt-3').hide();
                    $('#nispotti_date_row').show();
                    $('#nispotti_date').prop('disabled', false);
                } else {
                    $('#next_date').prop('disabled', false).closest('.mt-3').show();
                    $('#nispotti_date_row').hide();
                    $('#nispotti_date').val('').prop('disabled', true);
                }
            }
            toggleNextDate();
            $('#is_nispotti').on('change', toggleNextDate);

            var form=$('#submit-form');
            form.on('submit',function (e) {
                e.preventDefault();

                var _token = $("input[name='_token']").val();
                // Temporarily enable disabled fields so they are serialized
                $('#next_date').prop('disabled', false);
                $('#nispotti_date').prop('disabled', false);
                var data = form.serialize();
                // Re-apply disabled state
                if ($('#is_nispotti').is(':checked')) {
                    $('#next_date').prop('disabled', true);
                } else {
                    $('#nispotti_date').prop('disabled', true);
                }
                @if(isset($history))
                var actionurl = "{{route('dashboard.histories.update',$history->id)}}";
                @else

                var actionurl = "{{route('dashboard.histories.store')}}"; @endif
                $.ajax({
                    url: actionurl,
                    type:'POST',

                    data: data,
                    success: function(data) {
                        console.log(data)
                        if(data.status==='success'){
                            Swal.fire(
                                'Success!',
                                data.msg,
                                'success'
                            ).then(function() {
                                window.location.href = "{{ route('dashboard.histories.index') }}";
                            });
                        }else {
                            Swal.fire(
                                'Error!',
                                data.msg,
                                'error'
                            )
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.msg) {
                            msg = xhr.responseJSON.msg;
                        }
                        Swal.fire('Error!', msg, 'error');
                    }
                });
            })

        });


    </script>

@endsection
