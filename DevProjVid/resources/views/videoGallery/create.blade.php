<!-- -----------------------------------------------------------------------
VIDEO GALLERY
------------------------------------------------------------------------ -->

@extends ('layouts.app')
@section ('content')

<!-- ------------------------------------------------------------------- -->

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                Create Video Gallery
            </div>
            <div class="panel-body">    
                <!-- ------------------------------------------------------------------- -->

                <div class="row">
                    <div class="col-xs-8">
                        <a href="{{ url('projects/user/'. Auth::user()->id) }}">
                            Projects
                        </a>
                        / 
                        <a href="{{ url('project/'. $project->id) }}">
                            {{ $project->name}}
                        </a>
                    </div>
                </div>

                <!-- ------------------------------------------------------------------- -->

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    {{ Html::ul($errors->all()) }}
                </div>
                @endif

                <!-- ------------------------------------------------------------------- -->

                <?php
                if (session()->has('unsuccess')) {
                    ?>
                    <div class="alert alert-danger">
                        <?php
                        echo session('unsuccess');
                        ?>
                    </div>
                    <?php
                } else if (session()->has('success')) {
                    ?>
                    <div class="alert alert-success">
                        <?php
                        echo session('success');
                        ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <hr>
                    <p>
                        <span class="label label-primary">
                            Please choose 1-{{$maxVideosGallery}} videos.
                        </span>
                    </p>
                    <p>
                        <span class="label label-warning">
                            Size limit for each video: {{ $maxVideoSize }} MB
                        </span>
                    </p>
                    <?php
                }
                ?>
                <hr>

                <!-- ------------------------------------------------------------------- -->

                <form 
                    method="post" 
                    action="{{ url('videoGallery')}}" 
                    enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="control-label">
                            Gallery Name
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    @for ($i = 1; $i <= $maxVideosGallery; $i++)
                    <div>
                        <p>
                            <label class="control-label">
                                {{ $videoPrefix . ' ' . $i }}
                            </label>
                        </p>
                        <p>
                            <input type="file" name="{{ $videoPrefix . $i }}" id="{{ $videoPrefix . $i }}" class="file">
                        </p>
                        <hr>
                    </div>
                    @endfor
                    <input type="hidden" name="projectId" value="{{$project->id}}">
                    <input 
                        class="btn btn-primary"
                        type="submit" 
                        value="Create"/>
                </form>

                <!-- --------------------------------------------------------------- -->
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------- -->

@endsection