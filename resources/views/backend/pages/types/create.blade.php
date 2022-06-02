@section('page-title')
    Create Case Type
@endsection


@extends('backend.layouts.main')

@section('admin-section')

    @include('backend.layouts.partials.alerts')

{{--    <link rel="stylesheet" href="http://rubick.left4code.com/dist/css/app.css" />--}}

    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
               @if(isset($type))
                    Update Case Type
                @else
                Create Case Type
                @endif
            </h2>
        </div>
        <div class="p-5" id="horizontal-form">
            <form  id="submit-form" method="POST">
                @if(isset($type))
                    @method('PUT')
                @endif

                @csrf
                <div class="preview" style="">
                    <div class="flex flex-col sm:flex-row items-center">
                        <label class="w-full sm:w-20 sm:text-right sm:mr-5">Case Type </label>
                        <input type="text" class="input w-full border mt-2 flex-1" placeholder=""  @if(isset($type))value="{{$type->name}}" @endif id="name" name="name" required>
                    </div>


                    <div class="sm:ml-20 sm:pl-5 mt-5">
                        <input type="submit" class="button text-white" style="background: steelblue"  @if(isset($type)) value="Update" @else value="Create" @endif />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){

         var form=$('#submit-form');
         form.on('submit',function (e) {
             e.preventDefault();

             var _token = $("input[name='_token']").val();
             var data = form.serialize();
             @if(isset($type))
             var actionurl = "{{route('dashboard.types.update',$type->id)}}";
             @else

             var actionurl = "{{route('dashboard.types.store')}}"; @endif
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
                         @if(!isset($type))
                         $('#name').val('')
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
