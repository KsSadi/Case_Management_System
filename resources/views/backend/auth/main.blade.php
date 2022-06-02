<!DOCTYPE html>

<html lang="en">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link href="dist/images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>@yield('auth-title', 'Authentication') - Panel</title>
    @include('backend.layouts.partials.styles')
</head>
<!-- END: Head -->
<body class="login">
<div class="container sm:px-10">
    <div class="block xl:grid grid-cols-2 gap-4">
        <!-- BEGIN: Login Info -->
        <div class="hidden xl:flex flex-col min-h-screen">
            <a href="" class="-intro-x flex items-center pt-5">
                <img alt="Midone Tailwind HTML Admin Template" style="margin-top: -18px" class="w-6" src="{{ asset('dashboard-assets/dist/images/bdg.png') }}">
                <img alt="BDG"  src="{{ asset('dashboard-assets/dist/images/mg-logo.png') }}">
            </a>
            <div class="my-auto">
                <img alt="" class="-intro-x w-1/2 -mt-16" src="{{ asset('dashboard-assets/dist/images/loginlogo2.png') }}">
                <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                   Monthly Report
                    <br>
                    BDG - Law Department
                </div>

                <div class="float-left">

                </div>
            </div>
        </div>
        <!-- END: Login Info -->
        <!-- BEGIN: Login Form -->
    @yield('auth-content')
    <!-- END: Login Form -->
    </div>

</div>
@include('backend.layouts.partials.scripts')
</body>
</html>
