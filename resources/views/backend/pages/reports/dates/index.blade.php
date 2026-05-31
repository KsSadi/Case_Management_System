@section('page-title')
    Date Range Search
@endsection


@extends('backend.layouts.main')

@section('admin-section')

    @include('backend.layouts.partials.alerts')

    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
               Select Date Range
            </h2>
        </div>
        <div class="p-5" id="horizontal-form">
            <form  id="date-range" method="POST">
                <div class="preview" style="">
                    <div class="flex flex-col sm:flex-row items-center">
                        <label class="w-full sm:w-20 sm:text-right sm:mr-5">Start Date </label>
                        <input type="text" autocomplete="off" class="input w-full border mt-2 flex-1" placeholder="dd/mm/yyyy" id="start_date_display">
                        <input type="hidden" id="start_date" name="start_date" required>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center">
                        <label class="w-full sm:w-20 sm:text-right sm:mr-5">End Date </label>
                        <input type="text" autocomplete="off" class="input w-full border mt-2 flex-1" placeholder="dd/mm/yyyy" id="end_date_display">
                        <input type="hidden" id="end_date" name="end_date" required>
                    </div>


                    <div class="sm:ml-20 sm:pl-5 mt-5">
                        <input type="submit" class="button text-white" style="background: steelblue" value="Search" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="table-container">

    </div>

    <script>
        $(document).ready(function(){

            // Initialize dd/mm/yyyy date pickers; hidden inputs keep YYYY-MM-DD for the backend
            function initDatePicker(displayId, hiddenId) {
                $('#' + displayId).daterangepicker({
                    singleDatePicker: true,
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    }
                });

                $('#' + displayId).on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY'));
                    $('#' + hiddenId).val(picker.startDate.format('YYYY-MM-DD'));
                });

                $('#' + displayId).on('cancel.daterangepicker', function () {
                    $(this).val('');
                    $('#' + hiddenId).val('');
                });
            }

            initDatePicker('start_date_display', 'start_date');
            initDatePicker('end_date_display', 'end_date');

            var form=$('#date-range');
            form.on('submit',function (e) {
                e.preventDefault();
                var _token = $("input[name='_token']").val();
                var formdata = new FormData(document.getElementById('date-range'));
                console.log(formdata);

                var actionurl = "{{route('dashboard.reports.date-range')}}";


                    $.ajax({
                        url: actionurl,
                        type:'POST',processData: false, contentType: false,
                        headers: {
                            'X-CSRF-Token': _token
                        },
                        data: formdata,
                        success: function(data) {
                            $("#table-container").html(data.html);

                            // Destroy any existing DataTable instance then re-init for pagination
                            if ($.fn.dataTable && $.fn.dataTable.isDataTable('#result-table')) {
                                $('#result-table').DataTable().destroy();
                            }
                            $('#result-table').DataTable({ responsive: true });

                            console.log(data)
                        }
                    });



            })






        });



    </script>


@endsection
