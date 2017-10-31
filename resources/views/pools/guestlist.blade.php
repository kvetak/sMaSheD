<div class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pools as $pool)
                <tr>
                    <td class="col-md-1">{{ $pool->id }}</td>
                    <td class="col-md-2">{{ $pool->name }}</td>
                    <td class="col-md-5"><a href="{{ $pool->url }}" target="_blank">{{ $pool->url }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
