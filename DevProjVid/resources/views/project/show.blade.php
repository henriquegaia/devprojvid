<!-- -----------------------------------------------------------------------
PROJECT
------------------------------------------------------------------------ -->

@extends ('layouts.app')
@section ('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">

            <!-- ------------------------------------------------------------------- -->

            <div class="panel-heading">
                Overview
            </div>

            <div class="panel-body">

                @if (Auth::check())
                @if ($user->id == Auth::user()->id)
                <div class="row">
                    <div class="col-xs-8">
                        <a href="{{ url('projects/user/'.Auth::user()->id) }}">
                            Projects
                        </a>
                        / {{ $project->name }}
                    </div>
                </div>

                @else

                <div class="row">
                    <div class="col-xs-8">
                        <a href="{{ url('project')}}">
                            Back To List
                        </a>
                    </div>
                </div>

                @endif
                @endif

                <p></p>

                <!-- ------------------------------------------------------------------- -->
                @include('project.show.all')
                @include ('project.show.rate')
                <!-- ------------------------------------------------------------------- -->

                <hr>
                <h3>
                    Video Galleries
                </h3>

                <!-- ------------------------------------------------------------------- -->
                <?php $n = 1; ?>

                @if ($videoGalleries->isEmpty())
                empty
                @endif

                @foreach($videoGalleries as $videoGallery)

                <?php
                $numberOfVideos = sizeof($videos[$videoGallery->id]);
                ?>
                <table class="table table-bordered">
                    <tr>
                        <td colspan="2" class="td-backgroung-color">
                            {{$n}}
                        </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{ $videoGallery->name }}</td>
                    </tr>
                    <tr>
                        <td>Videos</td>
                        <td>{{ $numberOfVideos}}</td>
                    </tr>
                    @if (Auth::check())
                    @if ($user->id == Auth::user()->id)
                    <tr>
                        <td>Options</td>
                        <td>
                            <a href="{{ url('videoGallery/'.$videoGallery->id .'/edit') }}">
                                Edit
                            </a>
                            &nbsp;|&nbsp;

                            <?php $idDelete = "#modal_delete_video_gallery_$n"; ?>

                            <a href="#" data-toggle="modal" data-target="<?php echo $idDelete; ?>">
                                Delete
                            </a>
                        </td>
                    </tr>
                    @endif
                    @endif
                    <?php ++$n; ?>
                </table>

                <!-- ------------------------------------------------------------------- -->

                <div class="text-center">
                    <div class="row">
                        <div class="col-md-12">

                            @foreach ($videos as $result)

                            <!-- ------------------------------------------------------------------- -->

                            @foreach ($result as $video)

                            @if ($video->video_gallery_id == $videoGallery->id)

                            <div class="video_in_gallery width-30-perc">
                                <b>
                                    <?php
                                    $videoName = $video->name;
                                    $videoNameArr = explode('.', $videoName);
                                    $videoNameNoExt = $videoNameArr[0];
                                    ?>
                                    {{$videoNameNoExt}}
                                </b>
                                <hr>
                                <?php
                                $videoId = "video_" . $video->id . "_gallery_" . $videoGallery->id;
                                ?>
                                <video 
                                    id="<?php echo $videoId; ?>"
                                    height="50%" controls>
                                        <?php
                                        $videoGalleryId = $videoGallery->id;
                                        $videoPath = "/database/uploads/video_gallery/$videoGalleryId/$videoName";
                                        ?>
                                    <source src="<?php echo getBaseUrl() . $videoPath; ?>" type="video/{{$video->extension}}">
                                </video>
                            </div>
                            @endif 

                            @endforeach 
                            @endforeach 
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- ------------------------------------------------------------------- -->

                @if(Auth::check())
                @if ($user->id == Auth::user()->id)
                <hr>
                <h3>
                    Options
                </h3>

                <table class="table">
                    <tr>
                        <td>
                            <a href="{{ url('videoGallery/create/'. $project->id)}}">
                                <span class="glyphicon glyphicon-film text-capitalize"></span>
                                Create a video gallery for this project
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#modal_delete_project">
                                <span class="glyphicon glyphicon-remove text-capitalize"></span>
                                Remove Project
                            </a>
                        </td>
                    </tr>
                </table>

                @endif 
                @endif 
                
                <!-- ------------------------------------------------------- -->

                <!-- Modal - Delete Project -------------------------------- -->

                <div class="modal fade" id="modal_delete_project" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Do you really want to remove Project?</h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="{{ url('project/' . $project->id)}}">
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

                <!-- ------------------------------------------------------- -->

                <!-- Modal - Delete Videos --------------------------------- -->
                <?php $m = 1; ?>

                @foreach($videoGalleries as $videoGallery)

                <?php $id = "modal_delete_video_gallery_$m"; ?>

                <div class="modal fade" id="<?php echo $id; ?>" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Do you really want to remove the video gallery {{$videoGallery->name}}?</h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="{{ url('videoGallery/' . $videoGallery->id)}}">
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

                <?php
                ++$m;
                ?>

                @endforeach

                <!-- ------------------------------------------------------------------- -->
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------- -->

@endsection
