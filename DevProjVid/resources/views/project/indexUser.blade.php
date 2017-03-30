<!-- -----------------------------------------------------------------------
PROJECT
------------------------------------------------------------------------ -->

@extends ('layouts.app')
@section('content')

<!-- ----------------------------------------------------------------------- -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                @if(Auth::check())
                @if(Auth::user()->id == $user->id)
                My Projects
                @else
                {{ $user->name }}'s Projects
                @endif
                @endif

            </div>
            <div class="panel-body">
                <!-- ------------------------------------------------------------------- -->

                @include('_partials.sessionSuccessMessage')

                <!-- ----------------------------------------------------------------------- -->

                <p>
                    <a href="{{ url('project/create') }}">
                        <span class="glyphicon glyphicon-plus"></span> 
                        Create Project
                    </a>
                </p>

                <!-- ----------------------------------------------------------------------- -->

                <p>
                    Total of projects: 
                    <span class="label label-info">
                        {{ count($projects) }}
                    </span>
                </p>
                @if(!$projects->isEmpty())

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            @include('project._partials._tableHeaders')
                            @if(Auth::check())
                            @if(Auth::user()->id == $user->id)
                            <th></th>
                            @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($projects as $key => $value)
                        <tr>
                            @include('project._partials._tableData')

                            @if(Auth::check())
                            @if(Auth::user()->id == $user->id)
                            <!-- we will also add show, edit, and delete buttons -->
                            <td>                     
                                <!-- edit (uses the edit method found at GET /project/{id}/edit -->
                                <span class=" glyphicon glyphicon-pencil"></span>
                                <a href="{{ URL::to('project/' . $value->id . '/edit') }}">
                                    Edit
                                </a>

                            </td>
                            @endif
                            @endif
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ----------------------------------------------------------------------- -->

@endif
@endsection

