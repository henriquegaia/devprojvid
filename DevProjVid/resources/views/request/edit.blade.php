@extends ('layouts.app')
@section ('content')

<?php
$languagesArrFile = languagesFile();
$languagesArr = include $languagesArrFile;
?>

<div class='row'>
    <div class='col-md-8 col-md-offset-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'> 
                Edit Request 
            </div>
            <div class='panel-body'>

                @if(session()->has('success'))
                <div class='alert alert-success'>
                    {{ session('success') }}
                </div>
                @endif

                @if(session()->has('unsuccess'))
                <div class='alert alert-danger'>
                     {{ session('unsuccess') }}
                </div>
                @endif

                @if(count($errors)>0)
                <div class='alert alert-danger'>
                    {{ Html::ul($errors->all()) }}
                </div>
                @endif

                {{ Form::model($request, [
                    'route'=>[
                        'request.update',
                        $request->id
                    ],
                    'method'=> 'PUT']) }}
                <div class='form-group'>
                    {{ Form::label('Language') }}
                    {{ Form::select(
                            'language',
                            $languagesArr,
                            $request->language,
                            ['class' => 'form-control'])}}
                </div>
                <div class='form-group'>
                    {{ Form::submit('Edit',['class' => 'btn btn-primary']) }}
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>

@endsection