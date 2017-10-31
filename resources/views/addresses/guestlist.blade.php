<div class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>IP address</th>
                <th>Server</th>
            </tr>
        </thead>
        <tbody>
            @foreach($addresses as $addr)
                <tr>
                    <td class="col-md-2">{{ $addr->id }}</td>
                    <td class="col-md-3">{{ $addr->address }}</td>
                    <td class="col-md-3">{{ $addr->server->fqdn }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
