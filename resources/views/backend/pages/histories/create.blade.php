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
                            <label class="flex flex-col sm:flex-row"> পরবর্তী তারিখ  </label>
                            <input type="date" id="next_date"  @if(isset($history))value="{{$history->next_date}}" @endif name="next_date" class="input w-full border mt-2" placeholder="">
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> বর্তমান অবস্থা/করণীয়/মন্তব্য </label>
                            <input type="text" id="status"   @if(isset($history))value="{{$history->status}}" @endif  name="status" class="input w-full border mt-2" placeholder="">
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

            var form=$('#submit-form');
            form.on('submit',function (e) {
                e.preventDefault();

                var _token = $("input[name='_token']").val();
                var data = form.serialize();
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
                            )
                            @if(!isset($history))
                            $('#case_id').val('')
                            $('#past_date').val('')
                            $('#date').val('')
                            $('#next_date').val('')
                            $('#status').val('')

                            @endif


                        }else {
                            Swal.fire(
                                'Good job!',
                                data.msg,
                                'success'
                            )
                        }



                    }
                });
            })

        });


    </script>

@endsection
