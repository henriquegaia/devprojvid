<!-- -----------------------------------------------------------------------
REQUEST CREATE
------------------------------------------------------------------------ -->

@extends ('layouts.app')
@section ('content')

<?php
$languagesArr = include $languagesArrFile;
?>

<div class='row'>
    <div class='col-md-8 col-md-offset-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                Request a Developer
            </div>
            <div class='panel-body'>

                @if (session()->has('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
                @elseif(session()->has('unsuccess'))
                <div class='alert alert-danger'>
                    {{session('unsuccess')}}
                </div>
                @endif


                @if(count($errors) > 0)
                <div class=' alert alert-danger'>
                    {{Html::ul($errors->all())}}
                </div>
                @endif

                {{Form::open(array('url'=>'request'))}}
                <div class='form-group'>
                    <label>
                        Language
                    </label>
                    {{ Form::select('language', $languagesArr , Input::old('language')) }}
                </div>
                {{Form::submit('Create', array('class'=>'btn btn-primary'))}}
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>

@endsection
