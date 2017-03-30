<!-- -----------------------------------------------------------------------
User
------------------------------------------------------------------------ -->

@extends ('layouts.app')
@section('content')

<!-- ------------------------------------------------------------------- -->

<h3>
    User Details
</h3>

<!-- ------------------------------------------------------------------- -->

<div class="row">
    <div class="col-xs-8">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>
                        <span class="glyphicon glyphicon-user"></span> 
                        Name
                    </th>   
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $user->name }}             
                    </td>                  
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ------------------------------------------------------------------- -->


@endsection