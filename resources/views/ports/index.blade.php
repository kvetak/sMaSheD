@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Port: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Port successfully created!</div>
    @endif
    <h2>Create</h2>
    <p>Add port entry:</p>

    @include('ports.update', array( 'url' => url('port'),
                              'method' => 'POST',
                              'number' => old('number'),
                              'crypto_id' => old('crypto_id'),
                              'cryptos'=> $cryptos,
                              'server_id' => old('server_id'),
                              'server_fqdn' => null,
                              'servers'=> $servers,
                              'buttext' => 'Create',
                              'errs' => $errors
                              ))

    <div class="clearfix"></div>
    <hr/>
    <h2>List</h2>
    <p>All currently recognized ports in system.</p>

    @include('ports.list', array( 'ports' => $ports) )

@endsection