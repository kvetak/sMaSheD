<div class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>FQDN</th>
                <th>Pool</th>
                <th>Ports</th>
                <th>Addresses</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servers as $server)
                <tr>
                    <td class="col-md-1">{{ $server->id }}</td>
                    <td class="col-md-3">{{ $server->fqdn }}</td>
                    <td class="col-md-3">{{ $server->pool->name }}</td>
                    <td class="col-md-2">
                        @foreach ($server->ports as $port)
                            {{ $port->number }} {{$port->crypto->abbreviation}}<br/>
                        @endforeach
                    </td>
                    <td class="col-md-2">
                        @foreach ($server->addresses as $addr)
                            {{ $addr->address}}<br/>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
