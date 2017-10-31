<div class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>URL</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pools as $pool)
                <tr>
                    <td class="col-md-1"><a href="{{ url('pool', $pool->id ) }}">{{ $pool->id }}</a></td>
                    <td class="col-md-3">{{ $pool->name }}</td>
                    <td class="col-md-5"><a href="{{ $pool->url }}" target="_blank">{{ $pool->url }}</a></td>
                    <td class="col-md-1">
                        <a class="btn btn-link" href="{{ url('pool/' . $pool->id . '/edit' ) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                    </td>
                    <td class="col-md-1">
                        @include('utils.delete', array( 'url' => url('pool/' . $pool->id . '/destroy'),
                                                        'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'))
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
