<!-- ----------------------------------------------------------------------- -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                All Projects
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
                            <th>
                                <span class="glyphicon glyphicon-user"></span> 
                                Created By
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($projects as $key => $value)
                        <tr>
                            @include('project._partials._tableData')
                            <td>
                                @foreach ($users as $user)
                                @if ($user->id == $value->created_by)
                                <a href="{{ url('user/'.$user->id)}}">
                                    {{ $user->name }}
                                </a>                
                                @endif
                                @endforeach
                            </td>
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

