<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS</title>

    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        POS
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if (!Auth::guest())
                            @role('admin')
                            <li class="dropdown">
                                <a style href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Setting Up<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ route('suppliers.index') }}">Supplier</a></li>
                                    <li><a href="{{ route('departments.index') }}">Department</a></li>
                                    <li><a href="{{ route('locations.index') }}">Location</a></li>
                                    <li><a href="{{ route('items.index') }}">Item</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a style href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Reports<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ route('salesinvoices.index') }}">Sale Invoices</a></li>
                                    <li><a href="{{ route('sales.index') }}">Sales</a></li>
                                    <li><a href="{{ route('purchasesinvoices.index') }}">Purchase Invoices</a></li>
                                    <li><a href="{{ route('purchases.index') }}">Purchases</a></li>
                                    <li><a href="{{ route('reports.index') }}">Sale by Item</a></li>
                                    <li><a href="{{ route('reports.purchase') }}">Purchase by Item</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                            @endrole
                            <li><a href="{{ route('sales.create') }}">Sales</a></li>
                            <li><a href="{{ route('purchases.create') }}">Purchases</a></li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
