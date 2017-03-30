@extends ('layouts.app')

@section ('content')


<div class='row'>
    <div class='col-md-8' col-md-offset-2>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                Requests From 
                <a href="{{ url('user/' . $userId) }}">
                    {{$name}}
                </a>
            </div>
            <div class='panel-body'>

                <table class='table table-striped table-bordered'>
                    <tr>
                        <th>
                            Request Id
                        </th>
                        <th>
                            Language
                        </th>
                        <th>
                            Created At
                        </th>
                        @if($userOwnsRequests)
                        <th>Options</th>
                        @endif

                    </tr>
                    @foreach($requests as $request)
                    <tr>
                        <td>
                            <a href="{{ url('request/' . $request->id)}}">
                                {{$request->id}}
                            </a>
                        </td>
                        <td>{{$request->language}}</td>
                        <td>{{$request->created_at}}</td>
                        @if($userOwnsRequests)
                        <td>
                            <a href='{{ url('request/' . $request->id . '/edit')}}'>
                                <span class='glyphicon glyphicon-edit'></span>
                                Edit 
                            </a>
                            &nbsp|&nbsp

                            <?php $modalId = "modal_delete_request_" . $request->id; ?>

                            <a href='#' data-toggle="modal" data-target="#{{$modalId}}">
                                <span class='glyphicon glyphicon-remove'></span>
                                Delete  
                            </a>

                            <!-- modal delete --------------------------------->

                            <div class="modal fade" id="{{$modalId}}" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Do you really want to remove the request?</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ url('request/' . $request->id)}}">
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

                            <!-- end modal delete ----------------------------->

                        </td>
                        @endif
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

</div>

@endsection