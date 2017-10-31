<div class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Abbreviation</th>
                <th>Name</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cryptos as $crypto)
                <tr>
                    <td class="col-md-1">{{ $crypto->id }}</td>
                    <td class="col-md-2">{{ $crypto->abbreviation }}</td>
                    <td class="col-md-4">{{ $crypto->name }}</td>
                    <td class="col-md-5"><a href="{{ $crypto->url }}" target="_blank">{{ $crypto->url }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
