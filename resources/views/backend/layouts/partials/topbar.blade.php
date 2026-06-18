<div class="top-bar">
    <!-- BEGIN: Breadcrumb & Back Button -->
    <div class="-intro-x flex items-center mr-auto">
        @if(!request()->routeIs('dashboard'))
            <a href="javascript:void(0);" onclick="goBack()" class="button bg-gray-200 text-gray-700 mr-3 flex items-center no-print hover:bg-gray-300 transition-all duration-200 ease-in-out transform hover:-translate-x-0.5 shadow-xs border border-gray-300" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; border-radius: 0.375rem;">
                <i data-feather="arrow-left" class="w-3.5 h-3.5 mr-1.5"></i> ফিরে যান (Back)
            </a>
            <script>
                function goBack() {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.location.href = "{{ route('dashboard') }}";
                    }
                }
            </script>
        @endif
        <div class="breadcrumb hidden sm:flex"> <a href="{{ route('dashboard') }}" class="">Application</a> <i data-feather="chevron-right" class="breadcrumb__icon"></i> <a href="" class="breadcrumb--active">Dashboard</a> </div>
    </div>

    <!-- BEGIN: Notifications -->
    <div class="intro-x dropdown relative mr-auto sm:mr-6">

    </div>
    <!-- END: Notifications -->

    <!-- BEGIN: Account Menu -->
    <div class="intro-x dropdown w-8 h-8 relative">
        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
            <img alt="Midone Tailwind HTML Admin Template" style="border-radius: 25px;border: 2px solid darkgreen;" src="{{ asset('dashboard-assets/dist/images/bdg.png') }}">
        </div>
        <div class="dropdown-box mt-10 absolute w-56 top-0 right-0 z-20">
            <div class="dropdown-box__content box bg-theme-38 text-white">
                <div class="p-4 border-b border-theme-40">
                    <div class="font-medium">{{ Auth::guard('admin')->user()->name }}</div>
                    <div class="text-xs text-theme-41">Admin</div>
                </div>

                <div class="p-2 border-t border-theme-40">
                    <form method="POST" action="{{ route('dashboard.logout.submit') }}">
                        @csrf
                        <a href="{{ route('dashboard.logout.submit') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md" onclick="event.preventDefault();
                        this.closest('form').submit();"> <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Account Menu -->
</div>
