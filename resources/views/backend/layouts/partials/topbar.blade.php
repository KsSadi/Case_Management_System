<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex"> <a href="" class="">Application</a> <i data-feather="chevron-right" class="breadcrumb__icon"></i> <a href="" class="breadcrumb--active">Dashboard</a> </div>

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
