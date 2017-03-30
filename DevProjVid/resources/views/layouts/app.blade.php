<?php
$url_base = getBaseUrl();
$pathToBase = dirname(dirname(dirname(dirname(__FILE__))));
$pathToBase = str_replace('\\', '/', $pathToBase);
$pathToViews = $pathToBase . '/resources/views';
$languagesSelectFile = $pathToViews . '/language/languages.php';
?>
<a href="../../../../../php-shoppingCart/php-shoppingCart/app/core/Model.php"></a>
<!DOCTYPE html>
<html lang="en" ng-app="main">
    <head>

        <!----------------------------------------------------------------------------------------->
        <!-- Meta -->
        <!----------------------------------------------------------------------------------------->

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token()}}">

        <!----------------------------------------------------------------------------------------->
        <!-- Title -->
        <!----------------------------------------------------------------------------------------->

        <title>{{ config('app.name', 'Laravel')}}</title>

        <!----------------------------------------------------------------------------------------->
        <!-- Styles -->
        <!----------------------------------------------------------------------------------------->

        <link href="/css/lib/bootstrap-lumen.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="/css/lib/jquery-ui.min.css" rel="stylesheet">
        <link href="/css/custom/custom.css" rel="stylesheet">

        <script src="/js/lib/jquery-ui.min.js"></script>


        <!----------------------------------------------------------------------------------------->
        <!-- Scripts -->
        <!----------------------------------------------------------------------------------------->

        <script>
            window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
]);
?>
        </script>

    </head>

    <!----------------------------------------------------------------------------------------->
    <!-- Body -->
    <!----------------------------------------------------------------------------------------->
    <body ng-controller="mainCtrl">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!----------------------------------------------------------------------------------------->
                    <!-- Collapsed Hamburger -->
                    <!----------------------------------------------------------------------------------------->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!----------------------------------------------------------------------------------------->
                    <!-- Branding Image -->
                    <!----------------------------------------------------------------------------------------->
                    <a class="navbar-brand" href="{{ url('/')}}">
                        {{ config('app.name', 'Laravel')}}
                    </a>


                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!----------------------------------------------------------------------------------------->
                    <!-- Left Side Of Navbar -->
                    <!----------------------------------------------------------------------------------------->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>
                    <!----------------------------------------------------------------------------------------->
                    <!-- Right Side Of Navbar -->
                    <!----------------------------------------------------------------------------------------->
                    <ul class="nav navbar-nav navbar-right">
                        <!----------------------------------------------------------------------------------------->
                        <!-- Actions & Notifications -->
                        <!----------------------------------------------------------------------------------------->
                        @if(Auth::user())
                        <li>
                            <a class="navbar-brand navbar-brand-sm" href="{{ url('home')}}">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="glyphicon glyphicon-bell"></span> 
                            </a>
                        </li>

                        <?php
                        if (isDeveloper()) {
                            ?>
                            <li class="dropdown" >
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-plus"></span> 
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('projects/user/'.Auth::user() - > id)}}">
                                            My Projects
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('project/create')}}">
                                            Create Project
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <?php
                        } else if (isCompany()) {
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-plus"></span> 
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('request/create')}}">
                                            Request a Developer
                                        </a>
                                    </li>
                                    <li>
                                        <?php
                                        $companyId = app('getCompanyIdFromUserId', [
                                            'userId' => Auth::id()
                                        ]);
                                        ?>
                                        <a href="{{ url('request/company/'.$companyId)}}">
                                            My Requests
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <?php
                        }
                        ?>
                        @endif
                        <!----------------------------------------------------------------------------------------->
                        <!-- User Logged Out -->                                    
                        <!----------------------------------------------------------------------------------------->
                        @if (Auth::guest())
                        <li><a href="{{ url('/login')}}">Login</a></li>
                        <li><a href="{{ url('/register')}}">Register</a></li>
                        @else
                        <!----------------------------------------------------------------------------------------->
                        <!-- User Logged In -->                                    
                        <!----------------------------------------------------------------------------------------->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <br>
                                    <a href="#">
                                        <span class="glyphicon glyphicon-user"></span>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <hr>
                                    <?php
                                    $userId = Auth::user()->id;
                                    ?>
                                    <!----------------------------------------------------------------------------------------->
                                    <a href="{{ url('user/'.$userId.'/edit')}}">
                                        <span class="glyphicon glyphicon-edit"></span> 
                                        Settings
                                    </a>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------->
                                    <a href="{{ url('home')}}" >
                                        <span class="glyphicon glyphicon-dashboard"></span>
                                        Dashboard
                                    </a>
                                    <hr>
                                    <!----------------------------------------------------------------------------------------->
                                    <a href="{{ url('/logout')}}"
                                       onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                        <span class="glyphicon glyphicon-log-out"></span> 
                                        Logout
                                    </a>
                                    <!----------------------------------------------------------------------------------------->
                                    <form id="logout-form" action="{{ url('/logout')}}" method="POST" style="display: none;">
                                        {{ csrf_field()}}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>               
                </div>
            </div>
        </nav>

        <div class="container">

            @include('layouts._partials._sessionFlashMessage')

            @yield('content')

        </div>

        <!----------------------------------------------------------------------------------------->
        <!-- Scripts -->
        <!----------------------------------------------------------------------------------------->


        <script src="/js/lib/angular.min.js"></script>

        <script src="/js/app.js"></script>
        <script src="/js/custom/angular/angular.js"></script>
        <script src="/js/auth/register.js"></script>

    </body>
    <footer>
        <div class="footer-block">

        </div>
    </footer>
</html>



