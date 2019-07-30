@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Address: Edit</h1>
    </div>

    @if( !isset($address))
        <div class="alert alert-danger" role="alert">Requested address does not exist!</div>
    @else
        @if ( isset($isChanged) )
            <div class="alert alert-success">Address successfully updated!</div>
        @endif

        <h2>{{ $address->address }} of {{ $address->server->fqdn }}</h2>
        <p>Update Address entry:</p>

        @include('addresses.update', array( 'url' => url('address/'.$address->id),
                                  'method' => 'PATCH',

                                  'address' => $address->address,
                                  'server_id' => $address->server->id,
                                  'server_fqdn' => null,
                                  'servers'=> $servers,

                                  'buttext' => 'Update',
                                  'errs' => $errors
                                  ))


    @endif

@endsection