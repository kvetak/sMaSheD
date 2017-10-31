<div class="col-md-12">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Number</th>
            <th>Crypto</th>
            <th>Server</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ports as $port)
            <tr>
                <td class="col-md-1">{{ $port->id }}</td>
                <td class="col-md-2">{{ $port->number }}</td>
                <td class="col-md-3">{{ $port->crypto->name }}</td>
                <td class="col-md-5">{{ $port->server->fqdn }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
