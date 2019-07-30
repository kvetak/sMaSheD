@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Address: Show</h1>
    </div>
    @if( !isset($address) )
        <div class="alert alert-danger" role="alert">Requested address does not exist!</div>
    @else
        <h2>{{ $address->name }}</h2>

        <div class="row">
            <div class="col-sm-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Database detail</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Identifier</th>
                                <td>{{ $address->id }}</td>
                            </tr>
                            <tr>
                                <th>IP</th>
                                <td>{{ $address->address }}</td>
                            </tr>
                            <tr>
                                <th>Server</th>
                                <td>{{ $address->server->fqdn }}</td>
                            </tr>
                            <tr>
                                <th>Timestamps</th>
                                <td>{{ $address->created_at }} <br> {{ $address->updated_at }}</td>
                            </tr>
                            <tr>
                                <th>Actions</th>
                                <td>                                    <a class="btn btn-info" href="{{ url('crypto/' . $address->id . '/edit') }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                                    <br>
                                    @include('utils.delete', array( 'url' => url('crypto/' . $address->id . '/destroy'),
                                                                    'class' => 'btn btn-danger',
                                                                    'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete'))
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection