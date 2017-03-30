

<div class='row'>
    <div class='col-md-8 col-md-offset-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                All Requests For Developers               
            </div>
            <div class='panel-body'>
                <table class='table table-striped table-bordered'>
                    <tr>
                        <th>
                            Request Id
                        </th>
                        <th>
                            Company Id
                        </th>
                        <th>
                            Language
                        </th>
                        <th>
                            Created At
                        </th>
                    </tr>
                    @foreach($requests as $request)
                    <tr>
                        <td>
                            <a href="{{ url('request/' . $request->id)}}">
                                {{$request->id}}
                            </a>
                        </td>
                        <td>
                            <a href="{{ url('user/' . $companiesIdToUserId[$request->company_id]) }}">
                                {{ $request->company_id }}
                                <a href="../../../../../../php-shoppingCart/php-shoppingCart/app/view/test/interfaces.php"></a>
                            </a>
                        </td>
                        <td>{{ $request->language }}</td>
                        <td>{{ $request->created_at }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
