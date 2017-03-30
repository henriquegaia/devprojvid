<td>
    <div class=" col-md-6">
        <a href=" {{ URL::to('project/' . $value->id) }} " >
            {{ $value ->name }}
        </a>
    </div>
</td>
<td>
    <div class=" col-md-6">
        <span class="label label-default">
            {{ $value ->language }}
        </span>
    </div>
</td>