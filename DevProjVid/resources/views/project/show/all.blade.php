<div class="row">
    <div class="col-xs-12">

        <!-- ------------------------------------------------------------------- -->

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>
                        <span class="glyphicon glyphicon-folder-close"></span> 
                        Name
                    </th>

                    <!-- ------------------------------------------------------------------- -->

                    <th>
                        <span class="glyphicon glyphicon-chevron-left"></span> 
                        <span class="glyphicon glyphicon-chevron-right"></span> 
                        Language
                    </th>

                    <!-- ------------------------------------------------------------------- -->

                    @if(Auth::check())
                    @if ($user->id != Auth::user()->id)
                    <th>
                        <span class="glyphicon glyphicon-user"></span> 
                        Created By
                    </th> 
                    @endif
                    @endif

                    <!-- ------------------------------------------------------------------- -->

                    @if(Auth::check())
                    @if ($user->id == Auth::user()->id)
                    <th></th>
                    @endif
                    @endif

                    <!-- ------------------------------------------------------------------- -->

                </tr>
            </thead>
            <tbody>
                <tr>

                    <!-- ------------------------------------------------------------------- -->

                    <td>
                        <div class=" col-md-6">
                            {{ $project->name }}
                        </div>
                    </td>

                    <!-- ------------------------------------------------------------------- -->

                    <td>
                        <div class=" col-md-6">
                            <span class="label label-default">
                                {{ $project ->language }}
                            </span>
                        </div>
                    </td>

                    <!-- ------------------------------------------------------------------- -->

                    @if(Auth::check())
                    @if ($user->id != Auth::user()->id)
                    <td>
                        <?php
                        $id = json_decode($user->id);
                        $user_href = "user/$id";
                        ?>
                        <a href="{{ url('user/'. $user->id) }}">
                            {{ $user->name }}
                        </a>                
                    </td>  

                    @endif
                    @endif
                    <!-- ------------------------------------------------------------------- -->

                    @if(Auth::check())
                    @if ($user->id == Auth::user()->id)

                    <td>
                        <!-- edit (uses the edit method found at GET /project/{id}/edit -->
                        <span class=" glyphicon glyphicon-pencil"></span>
                        <a href="{{ URL::to('project/' . $project->id . '/edit') }}">
                            Edit
                        </a>
                    </td>
                    @endif
                    @endif
                    <!-- ------------------------------------------------------------------- -->

                </tr>
            </tbody>
        </table>
    </div>
</div>