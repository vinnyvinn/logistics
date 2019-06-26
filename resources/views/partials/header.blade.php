<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
                <h4 class="text-white">{{ config('app.name', 'Laravel') }} </h4>

            </a>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                        <div class="notify"> {!! count($v_notifications) > 0 ? '<span class="heartbit"></span><span class="point"></span>' : '' !!} </div>
                    </a>
                    <div class="dropdown-menu mailbox animated slideInUp">
                        <ul>
                            <li>
                                <div class="drop-title">Notifications</div>
                            </li>
                            <li>
                                <div class="message-center">
                                    @foreach($v_notifications as $notification)
                                        <a href="{{ url('/notifications/'.$notification->id) }}">
                                            <div class="btn btn-danger btn-circle"><i class="fa fa-bell"></i></div>
                                            <div class="mail-contnet">
                                                <h5>{{ ucwords($notification->title) }}</h5> <span class="mail-desc">{{ ucfirst($notification->text) }}</span></div>
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center" href="{{ url('/all-notifications') }}"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="flag-icon flag-icon-ke"></i></a>
                    {{--<div class="dropdown-menu dropdown-menu-right scale-up">--}}
                        {{--<a class="dropdown-item" href="#"><i class="flag-icon flag-icon-tz"></i> Tanzania</a>--}}
                        {{--<a class="dropdown-item" href="#"><i class="flag-icon flag-icon-ke"></i> Kenya</a>--}}
                    {{--</div>--}}
                </li>
                <li></li>
                @can('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administration</a>
                    <div class="dropdown-menu dropdown-menu-right scale-up">
                        <ul class="dropdown-user">
                            <li role="separator" class="divider"></li>
                            @can('add-roles')
                                <li>
                                    <a href="{{ url('manage-users') }}">Manage Users</a>
                                </li>
                            @endcan

                            {{--<li>--}}
                                {{--<a href="{{ url('departments') }}">Departments</a>--}}
                            {{--</li>--}}
                            @can('view-stages')
                            <li>
                                <a href="{{ route('stages.index') }}">Stages</a>
                            </li>
                        @endcan
                           @can('view-incoterm')
                            <li>
                                <a href="{{ url('incoterms') }}">Incoterms</a>
                            </li>
                            @endcan
                           @can('view-contracts')
                            <li>
                                <a href="{{ route('contracts.index') }}">Contracts</a>
                            </li>
                                @endcan
                           @can('view-services')
                            <li>
                                <a href="{{ route('services.index') }}">Services</a>
                            </li>
                                    @endcan
                           @can('manage-documents')
                            <li>
                                <a href="{{ route('required-docs.index') }}">Required Docs
                                </a>
                            </li>
                                        @endcan
                            @can('add-roles')
                            <li>
                                <a href="{{ url('all-roles') }}">Roles
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if(!\Illuminate\Support\Facades\Auth::guest())
                            {{mb_strimwidth(Auth::user()->name,0,12)}} <i class="fa fa-user fa-2x"></i>
                        @endif</a>
                    <div class="dropdown-menu dropdown-menu-right scale-up">
                        <ul class="dropdown-user">

                            <li>
                                <div class="dw-user-box">
                                    <div class="u-text">
                                        {{ \Illuminate\Support\Facades\Auth::guest() ? 'Guest' : Auth::user()->name }} <span class="caret"></span>
                                </div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            @if(!\Illuminate\Support\Facades\Auth::guest())
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off"></i>
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                                @endif
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>