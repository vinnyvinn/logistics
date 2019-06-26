<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li> <a class="has-arrow waves-effect waves-dark" href="{{ url('/') }}" aria-expanded="false">
                        <i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
                </li>
                @can('view-customers')
                <li> <a class="has-arrow waves-effect waves-dark" href="{{ url('/customers')  }}" aria-expanded="false">
                        <i class="mdi mdi-account-multiple"></i><span class="hide-menu">All Customers</span></a>
                </li>
                @endcan
                @can('view-dsr')
                <li> <a class="has-arrow waves-effect waves-dark" href="{{ url('/dsr')  }}" aria-expanded="false">
                        <i class="mdi mdi-account-multiple"></i><span class="hide-menu">Jobs</span></a>
                </li>
                @endcan
                {{--<li> <a class="has-arrow waves-effect waves-dark" href="{{ url('/leads') }}" aria-expanded="false">--}}
                        {{--<i class="mdi mdi-account-multiple"></i><span class="hide-menu">Customers</span></a>--}}
                    {{--<ul aria-expanded="false" class="collapse">--}}
                        {{--<li><a href="{{ url('/leads/create') }}">New Lead</a></li>--}}
                        {{--<li><a href="{{ url('/leads') }}">Leads</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                @can('generate-quotation','view-quotation')
                <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="mdi mdi-account"></i><span class="hide-menu">Quotations</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('/quotations')}}">All Quotations</a></li>
                        <li><a href="{{ url('/my-quotations') }}">My Quotations</a></li>
{{--                        <li><a href="{{ url('/generate-quotation/export') }}">Gen. Export Quotation</a></li>--}}
                        {{--<li><a href="">Logistics</a></li>--}}
                    </ul>
                </li>
                @endcan
                {{--<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">--}}
                        {{--<i class="mdi mdi-basket"></i><span class="hide-menu">Cargo</span></a>--}}
                    {{--<ul aria-expanded="false" class="collapse">--}}
                        {{--<li><a href="{{ url('/good-types/create') }}">Add Cargo Type</a></li>--}}
                        {{--<li><a href="{{ url('/good-types') }}">Cargo Types</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}

                {{--<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">--}}
                        {{--<i class="mdi mdi-account"></i><span class="hide-menu">Reports</span></a>--}}
                    {{--<ul aria-expanded="false" class="collapse">--}}
                        {{--<li><a href="{{ url('/report/transport-revenue') }}">Transport/Cost - Revenue Record</a></li>--}}
                        {{--<li><a href="">Transport</a></li>--}}
                        {{--<li><a href="">Logistics</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                @can('manager')
                <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="mdi mdi-account"></i><span class="hide-menu">Logistics Manager</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('/logistics') }}">Dashboard</a></li>
                    </ul>
                </li>
                    @endcan
                <li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="mdi mdi-package-down"></i><span>Reports</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('/reports/create')}}">Jobs</a></li>
                        <li><a href="{{ url('/pos-report') }}">Pos</a></li>
                        <li><a href="{{ url('/leads-report') }}">Leads</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
