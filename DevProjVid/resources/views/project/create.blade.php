<!-- -----------------------------------------------------------------------
PROJECT
------------------------------------------------------------------------ -->

<!-- ------------------------------------------------------------------- -->

@extends ('layouts.app')
@section('content')

<!-- ------------------------------------------------------------------- -->

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                Create Project
            </div>
            <div class="panel-body">
                <!-- ------------------------------------------------------------------- -->

                <p>
                    <a href="{{ url('projects/user/'.Auth::user()->id)}}">
                        My Projects
                    </a>
                </p>

                <!-- ------------------------------------------------------------------- -->

                @include('_partials.sessionSuccessMessage')
                <!-- ------------------------------------------------------------------- -->

                @include('_partials.errorsList')

                <!-- ------------------------------------------------------------------- -->

                {{ Form::open(array('url' => 'project')) }}

                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
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
                {{ Form::submit('Create',['class'=>'btn btn-primary'])}}

                {{ Form::close() }}
                <!-- ------------------------------------------------------------------- -->

            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------- -->

@endsection
