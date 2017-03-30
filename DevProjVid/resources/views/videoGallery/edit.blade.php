<!-- ---------------------------------------------------------------------------
VIDEO GALLERY
---------------------------------------------------------------------------- -->

@extends ('layouts.app')
@section ('content')

<!-- ----------------------------------------------------------------------- -->

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Video Gallery
            </div>
            <div class="panel-body">


                <!-- ------------------------------------------------------- -->

                <a href="{{ url('projects/user/'. Auth::user()->id) }}">
                    Projects
                </a>
                &nbsp/&nbsp
                <a href="{{ url('project/'. $project->id) }}">
                    {{ $project->name}}
                </a>
                &nbsp/&nbsp               
                {{$videoGallery->name }}
                <br>

                <!-- ------------------------------------------------------- -->

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    {{ Html::ul($errors->all()) }}
                </div>
                @endif

                <!-- ------------------------------------------------------- -->

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

                <!-- --------------------------------------------------------------- -->

                <?php
                $action = "videoGallery/$videoGallery->id";
                ?>

                {{ Form::model($videoGallery, array('route' => array('videoGallery.update', $videoGallery->id), 'method' => 'PUT')) }}
                <div class="form-group">
                    <label class="control-label">
                        Gallery Name
                    </label>
                    <input type="text" name="name" class="form-control" value="{{$videoGallery->name }}">
                </div>
                {{ Form::submit('Edit Gallery Name!', array('class' => 'btn btn-primary')) }}
                {{ Form::close() }}

                <!-- --------------------------------------------------------------- -->
                <hr>
                <!-- --------------------------------------------------------------- -->

                <label class="control-label">
                    Remove Videos
                </label>

                <?php $i = 0; ?>
                <table class="table table-bordered">
                    <tbody>
                        @foreach($videos as $video)
                        <?php ++$i; ?>
                        <tr>

                            <?php $id = "#modal_video_$i"; ?>
                            <?php $idDelete = "#modal_delete_video_$i"; ?>
                            <td>
                                <label class="badge"><?php echo $i; ?></label>
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="<?php echo $id; ?>">
                                    {{$video->name }}
                                </a>
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="<?php echo $idDelete; ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ------------------------------------------------------- -->

                @if($i != $maxVideosGallery)

                <hr>

                <label class="control-label">
                    Add Videos
                </label>

                <form method="POST" action="{{ url('videoGallery/' . $videoGallery->id . '/updateVideos')}}">
                    {{ csrf_field() }}
                    <table class="table table-bordered">
                        <tbody>
                            @for ($k = $i + 1; $k <= $maxVideosGallery; $k++)
                            <tr>
                                <td>
                                    <label class="badge"><?php echo $k; ?></label>
                                </td>
                                <td>
                                    <input type="file" name="{{ $videoPrefix . $k }}" id="{{ $videoPrefix . $k }}" class="file">
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                    <input type="submit" value="Add Videos" class="btn btn-primary">
                </form>     
                @endif
                
                <!-- ------------------------------------------------------- -->

            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------- -->

<!-- Modal - Videos ------------------------------------------------ -->

<?php $j = 1; ?>

@foreach($videos as $video)

<?php $id = "modal_video_$j"; ?>

<div class="modal fade" id="<?php echo $id; ?>" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{$video->name}}</h4>
            </div>
            <div class="modal-body">
                <video height="50%" controls>
                    <?php
                    $videoName = $video->name;
                    $videoNameArr = explode('.', $videoName);
                    $videoNameNoExt = $videoNameArr[0];
                    $videoGalleryId = $videoGallery->id;
                    $videoPath = "/database/uploads/video_gallery/$videoGalleryId/$videoName";
                    ?>
                    <source src="<?php echo getBaseUrl() . $videoPath; ?>" type="video/{{$video->extension}}">
                </video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<?php
++$j;
?>

@endforeach

<!-- Modal - Delete Videos ------------------------------------------------- -->
<?php $m = 1; ?>

@foreach($videos as $video)

<?php $id = "modal_delete_video_$m"; ?>

<div class="modal fade" id="<?php echo $id; ?>" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Do you really want to remove the video {{$video->name}}?</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('video/' . $video->id)}}">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ----------------------------------------------------------------------- -->

<?php
++$m;
?>

@endforeach
<!-- ----------------------------------------------------------------------- -->


@endsection