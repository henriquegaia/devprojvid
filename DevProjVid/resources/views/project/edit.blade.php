<!-- -----------------------------------------------------------------------
PROJECT
------------------------------------------------------------------------ -->

<!-- ------------------------------------------------------------------- -->

@extends ('layouts.app')
@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <!-- ----------------------------------------------------------- -->

            <div class="panel-heading">
                Edit
            </div>

            <div class="panel-body">
                <!-- ----------------------------------------------------------- -->


                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('projects/user/'.Auth::user()->id) }}">
                            Projects
                        </a>
                        / {{ $project->name }}
                    </div>
                </div>

                <p></p>

                <!-- ------------------------------------------------------------------- -->

                <div class="row">
                    <div class="col-md-12">

                        <!-- ------------------------------------------------------------------- -->

                        @include('_partials.sessionSuccessMessage')

                        <!-- ------------------------------------------------------------------- -->

                        @include('_partials.errorsList')

                        <!-- ------------------------------------------------------------------- -->

                        {{ Form::model($project, array('route' => array('project.update', $project->id), 'method' => 'PUT')) }}

                        <div class="form-group">
                            {{ Form::label('name', 'Name') }}
                            {{ Form::text('name', null, array('class' => 'form-control')) }}
                        </div>

                        <!-- ------------------------------------------------------------------- -->

                        <?php
                        $languagesArr = include $languagesArrFile;
                        ?>

                        <div class="form-group">
                            {{ Form::label('language', 'Language') }}
                            <div class="form-group">
                                {{ Form::select('language', $languagesArr , Input::old('language')) }}
                            </div>
                        </div>

                        <!-- ------------------------------------------------------------------- -->

                        {{ Form::submit('Edit !', array('class' => 'btn btn-primary')) }}

                        {{ Form::close() }}

                        <!-- ------------------------------------------------------------------- -->

                    </div>   
                </div>

                <!-- ------------------------------------------------------------------- -->
            </div>
        </div>
    </div>   
</div>
<!-- ------------------------------------------------------------------- -->

@endsection
