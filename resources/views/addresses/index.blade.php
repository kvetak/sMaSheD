@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Address: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Address successfully created!</div>
    @endif
    <h2>Create</h2>
    <p>Add address entry:</p>

    @include('addresses.update', array( 'url' => url('address'),
                              'method' => 'POST',
                              'address' => old('address'),
                              'server_id' => old('server_id'),
                              'server_fqdn' => null,
                              'servers'=> $servers,
                              'buttext' => 'Create',
                              'errs' => $errors
                              ))

    <div class="clearfix"></div>
    <hr/>
    <h2>List</h2>
    <p>All currently recognized addresses in system.</p>

    @include('addresses.list', array( 'addresses' => $addresses) )

@endsection