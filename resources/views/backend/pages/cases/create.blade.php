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
                @if(isset($case))
                    Update Case
                @else
                    Create Case
                @endif
            </h2>
        </div>

        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">

            <div class="p-5" id="basic-datepicker">
                <div class="preview">
                    <form class="validate-form" id="submit-form" method="POST">
                        @if(isset($case))
                            @method('PUT')
                        @endif

                        @csrf
                        <div>
                            <label class="flex flex-col sm:flex-row" style="margin-bottom: 10px;">প্রজেক্টের নাম  </label>
                            <select data-placeholder="Select" id="project" name="project" class="select2 w-full">
                                <option value="" selected>Select</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}" @if(isset($case) && $case->project == $proj->id) selected @endif>{{ $proj->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"style="margin-bottom: 10px;">মামলার বিভাগ </label>
                            <select data-placeholder="Select" id="division" name="division" class="select2 w-full">
                                <option value="" selected>Select</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" @if(isset($case) && $case->division == $division->id) selected @endif>{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"style="margin-bottom: 10px;"> মামলার ধরন </label>
                            <select data-placeholder="Select" id="case_type" name="case_type" class="select2 w-full">
                                <option value="" selected>Select</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" @if(isset($case) && $case->case_type == $type->id) selected @endif>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> মামলা নং </label>
                            <textarea class="input w-full border mt-2" id="case_no"  name="case_no" placeholder="" minlength="10" required>@if(isset($case)) {{$case->case_no}} @endif </textarea>
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> বিচারাধীন বিজ্ঞ আদালতের নাম</label>
                            <select data-placeholder="Select" id="court_name" name="court_name" class="select2 w-full">
                                <option value="" selected>Select</option>
                                @foreach($courts as $court)
                                    <option value="{{ $court->id }}" @if(isset($case) && $case->court_name == $court->id) selected @endif>{{ $court->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> সংশ্লিষ্ট পক্ষদের নাম </label>
                            <input type="text" id="parties_name"   @if(isset($case))value="{{$case->parties_name}}" @endif name="parties_name" class="input w-full border mt-2" placeholder="">
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> মামলার সংক্ষিপ্ত বিবরণ </label>
                            <textarea class="input w-full border mt-2" id="case_details" name="case_details" placeholder="" minlength="10" required>@if(isset($case)) {{$case->case_details}} @endif </textarea>
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> মামলার বিষয় ও ধারা  </label>
                            <input type="text" id="case_subject"  @if(isset($case))value="{{$case->case_subject}}" @endif name="case_subject" class="input w-full border mt-2" placeholder="">
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> মামলা দায়ের / শুনানীর সময় প্রথম আদেশ </label>
                            <input type="text" id="first_order"   @if(isset($case))value="{{$case->first_order}}" @endif  name="first_order" class="input w-full border mt-2" placeholder="">
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row"> কোম্পানির নিয়োজিত বিজ্ঞ আইনজীবীর নাম </label>
                            <select data-placeholder="Select" id="adv_name" name="adv_name" class="select2 w-full">
                                <option value="" selected>Select</option>
                                @foreach($advocates as $advocate)
                                    <option value="{{ $advocate->id }}" @if(isset($case) && $case->adv_name == $advocate->id) selected @endif>{{ $advocate->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label class="flex flex-col sm:flex-row" style="margin-bottom: 10px;">কোম্পানি </label>
                            <select data-placeholder="Select" id="company_id" name="company_id" class="select2 w-full">
                                <option value="" selected>Select</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @if(isset($case) && $case->company_id == $company->id) selected @endif>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="button bg-theme-1 text-white mt-5"   /> @if(isset($case)) Update @else Create @endif </button>
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
                @if(isset($case))
                var actionurl = "{{route('dashboard.cases.update',$case->id)}}";
                @else

                var actionurl = "{{route('dashboard.cases.store')}}"; @endif
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
                                window.location.href = "{{ route('dashboard.cases.index') }}";
                            })


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


        function saveCategory(category) {

            //console.log($("#categoryName").val())

            if(searchValue.length>0)
            {

                //$('.search-result').show();



            }else
            {
                $('.search-result').hide();
            }


        }
    </script>

@endsection
