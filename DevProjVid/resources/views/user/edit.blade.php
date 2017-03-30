@extends ('layouts.app')

@section('content')

<!---------------------------------------------------------------------------- 
Session vars -----------------------------------------------------------------
----------------------------------------------------------------------------->

<?php
if (session()->has('success')) {
    ?>
    <div class="alert alert-success">
        <?php
        echo session('success');
        ?>
    </div>
    <?php
} else if (session()->has('unsuccess')) {
    ?>
    <div class="alert alert-danger">
        <?php
        echo session('unsuccess');
        ?>
    </div>
    <?php
}
?>

<!---------------------------------------------------------------------------- 
Container --------------------------------------------------------------------
----------------------------------------------------------------------------->

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Settings</div>
                <div class="panel-body">
                    <!------------------------------------------------------------------->
                    <div class="row">
                        <div class="col-sm-4">
                            <label>E-Mail Address: </label>
                        </div>
                        <div class="col-sm-4 break-word">
                            <label>{{ Auth::user()->email }}</label>  
                        </div>
                        <div class="col-sm-4">
                            <a href="#"data-toggle="modal" data-target="#modal_email">
                                <span class="glyphicon glyphicon-pencil"></span>
                                Change
                            </a>
                        </div>
                    </div>
                    <hr>
                    <!------------------------------------------------------------------->
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Name: </label>
                        </div>
                        <div class="col-sm-4 break-word">
                            <label>{{ Auth::user()->name }}</label>  
                        </div>
                        <div class="col-sm-4">
                            <a href="#" data-toggle="modal" data-target="#modal_name">
                                <span class="glyphicon glyphicon-pencil"></span>
                                Change
                            </a>
                        </div>
                    </div>
                    <!------------------------------------------------------------------->

                </div>
            </div>
        </div>
    </div>
</div>

<!---------------------------------------------------------------------------- 
Modal - Email ----------------------------------------------------------------
----------------------------------------------------------------------------->
<div class="modal fade" id="modal_email" role="dialog">

    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Email Address</h4>
            </div>
            <div class="modal-body">
                <form id="updateUserEmail" class="form-horizontal" role="form" method="GET" action="{{ url('user/email/update') }}">
                    {{ csrf_field() }}
                    <!-------------------------------------------------------------------->
                    <!-- email -->
                    <!-------------------------------------------------------------------->
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <!-------------------------------------------------------------------->
                    <!-- submit email -->
                    <!-------------------------------------------------------------------->
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Change
                            </button>
                        </div>
                    </div>
                    <!-------------------------------------------------------------------->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!---------------------------------------------------------------------------- 
Modal - Name -----------------------------------------------------------------
----------------------------------------------------------------------------->
<div class="modal fade" id="modal_name" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Name</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="GET" action="{{ url('user/name/update') }}">
                    {{ csrf_field() }}
                    <!-------------------------------------------------------------------->
                    <!-- name -->
                    <!-------------------------------------------------------------------->
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Name</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <!-------------------------------------------------------------------->
                    <!-- submit name -->
                    <!-------------------------------------------------------------------->
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Change
                            </button>
                        </div>
                    </div>
                    <!-------------------------------------------------------------------->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div><!-- end modal name -->

@endsection
