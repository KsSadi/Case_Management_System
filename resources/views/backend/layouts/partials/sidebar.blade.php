<nav class="side-nav">
    <a href="" class="-intro-x flex items-center pt-5">
        <img alt="BDG" class="w-6" src="{{ asset('dashboard-assets/dist/images/bdg.png') }}">

        <img alt="BDG"  src="{{ asset('dashboard-assets/dist/images/mg-logo.png') }}">
{{--       <h2> <span class="text-white text-lg ml-3" style="color: green;"><b>B</b></span> <span class="text-white text-lg ml-3"  style="color: red;"><b>D</b></span> <span class="text-white text-lg ml-3" style="color: green"><b>G</b></span></h2>--}}
{{--    </a>--}}
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>

            <a href="{{ route('dashboard') }}" class="side-menu
                                            @if (Request::is('dashboard'))
                                            side-menu--active
                                            @endif
            ">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>

{{--        Case Part Started--}}

        <li class="side-nav__devider my-6"></li>
        @if (Auth::guard('admin')->user()->can('history.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/histories*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg> </div>
                    <div class="side-menu__title"> Case History <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard.histories.index') }}" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View All Cases </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('history.create'))
                        <li>
                            <a href="{{ route('dashboard.histories.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create History </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->can('report.month'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/reports*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trello"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                    </div>
                    <div class="side-menu__title"> Case Report <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    @if (Auth::guard('admin')->user()->can('report.month'))
                        <li>
                            <a href="{{ route('dashboard.reports.month') }}" class="side-menu">
                                <div class="side-menu__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </div><div class="side-menu__title">Current Month</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->can('report.date'))
                    <li>
                        <a href="{{ route('dashboard.reports.date.index') }}" class="side-menu">
                            <div class="side-menu__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
                            <div class="side-menu__title"> Case Date </div>
                        </a>
                    </li>
                        @endif
                    @if (Auth::guard('admin')->user()->can('report.filter'))
                        <li>
                            <a href="{{ route('dashboard.reports.filter.index') }}" class="side-menu">
                                <div class="side-menu__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg> </div>
                                <div class="side-menu__title"> Case Filter</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <li class="side-nav__devider my-6"></li>
        <li class=""></li>
        @if (Auth::guard('admin')->user()->can('case.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/cases*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>   </div>
                    <div class="side-menu__title"> Case Item <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard.cases.index') }}" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View Cases Item </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('case.create'))
                        <li>
                            <a href="{{ route('dashboard.cases.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create Case </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (Auth::guard('admin')->user()->can('project.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/projects*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-aperture"><circle cx="12" cy="12" r="10"></circle><line x1="14.31" y1="8" x2="20.05" y2="17.94"></line><line x1="9.69" y1="8" x2="21.17" y2="8"></line><line x1="7.38" y1="12" x2="13.12" y2="2.06"></line><line x1="9.69" y1="16" x2="3.95" y2="6.06"></line><line x1="14.31" y1="16" x2="2.83" y2="16"></line><line x1="16.62" y1="12" x2="10.88" y2="21.94"></line></svg></div>
                    <div class="side-menu__title"> Project <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard.projects.index') }}" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View Project </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('project.create'))
                        <li>
                            <a href="{{ route('dashboard.projects.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create Project </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <li class=""></li>

        @if (Auth::guard('admin')->user()->can('type.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/types*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg> </div>
                    <div class="side-menu__title"> Case Type <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard.types.index') }}" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View Case Type </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('type.create'))
                        <li>
                            <a href="{{ route('dashboard.types.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create Case Type </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <li class=""></li>
        @if (Auth::guard('admin')->user()->can('division.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/divisions*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg> </div>
                    <div class="side-menu__title"> Case Division <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard.divisions.index') }}" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View Case Division </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('division.create'))
                        <li>
                            <a href="{{ route('dashboard.divisions.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create Case Division </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <li class=""></li>
        @if (Auth::guard('admin')->user()->can('court.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/courts*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> </div>
                    <div class="side-menu__title"> Court Name <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard.courts.index') }}" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View Court Name </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('court.create'))
                        <li>
                            <a href="{{ route('dashboard.courts.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create Court Name </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (Auth::guard('admin')->user()->can('advocate.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/advocates*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                    </div>
                    <div class="side-menu__title"> Advocate <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard.advocates.index') }}" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View Advocate </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('advocate.create'))
                        <li>
                            <a href="{{ route('dashboard.advocates.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create Advocate </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <div class="side-nav__devider my-6"></div>

{{--        Case PArt Ended--}}
        @if (Auth::guard('admin')->user()->can('role.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                            @if (Request::is('dashboard/roles*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award mx-auto"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg> </div>
                    <div class="side-menu__title"> Roles <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard') }}/roles" class="side-menu">
                            <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> </div>
                            <div class="side-menu__title"> View Roles </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('role.create'))
                        <li>
                            <a href="{{ route('dashboard.roles.create') }}" class="side-menu">
                                <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> </div>
                                <div class="side-menu__title"> Create Role </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->can('admin.view'))
            <li>
                <a href="javascript:;" class="side-menu
                                        @if (Request::is('dashboard/admins*'))
                    side-menu--active
@endif
                    ">
                    <div class="side-menu__icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users mx-auto"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> </div>
                    <div class="side-menu__title"> Admins <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>
                </a>
                <ul class="">
                    <li>
                        <a href="{{ route('dashboard') }}/admins" class="side-menu">
                            <div class="side-menu__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mx-auto"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            </div>
                            <div class="side-menu__title"> Admins List </div>
                        </a>
                    </li>
                    @if (Auth::guard('admin')->user()->can('user.create'))
                        <li>
                            <a href="{{ route('dashboard.admins.create') }}" class="side-menu">
                                <div class="side-menu__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                </div>
                                <div class="side-menu__title"> Create Admin </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

{{--        Admin End--}}





    </ul>
</nav>
