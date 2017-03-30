@extends ('layouts.app')

@section ('content')

<?php
$modelId = "modal_delete_request_" . $request->id;
?>

<div class='row'>
    <div class='col-md-8 col-md-offset-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                Request
            </div>
            <div class='panel-body'>

                <p>
                    <a href="{{ url('request/company/' . $companyId) }}">
                        Requests from {{ $companyName }}
                    </a>
                    &nbsp/&nbsp
                    <b>
                        {{ $request->id }}
                    </b>
                </p>

                <table class='table table-striped table-bordered'>
                    <tr>
                        <th>id</th>
                        <th>company id</th>
                        <th>language</th>
                        <th>created at</th>
                        @if($userOwnsRequest)
                        <th>Options</th>
                        @endif
                    </tr>
                    <tr>
                        <td>{{$request->id}}</td>
                        <td>
                            <a href="{{ url('user/' . $userId)}}">
                                {{$request->company_id}}
                            </a>
                        </td>
                        <td>{{$request->language}}</td>
                        <td>{{$request->created_at}}</td>
                        @if($userOwnsRequest)
                        <td>
                            <a href='{{ url('request/' . $request->id . '/edit')}}'>
                                <span class='glyphicon glyphicon-edit'></span>
                                Edit 
                            </a>
                            &nbsp|&nbsp
                            <a href='#' data-toggle="modal" data-target="#{{$modelId}}">
                                <span class='glyphicon glyphicon-remove'></span>
                                Delete  
                            </a>
                        </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="{{$modelId}}" role="dialog">
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

@endsection
