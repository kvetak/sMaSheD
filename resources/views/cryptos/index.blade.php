@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Cryptos: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Cryptocurrency successfully created!</div>
    @endif
    <h2>Create</h2>
    <p>Add cryptocurrency entry:</p>

    @include('cryptos.update', array( 'url' => url('crypto'),
                                      'method' => 'POST',
                                      'cr_abbr' => old('abbreviation'),
                                      'cr_name' => old('name'),
                                      'cr_url' => old('url'),
                                      'buttext' => 'Create',
                                      'errs' => $errors
                                      ))

    <div class="clearfix"></div>
    <hr/>
    <h2>List</h2>
    <p>All currently recognized cryptocurrencies in system.</p>

    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Abbreviation</th>
                <th>Name</th>
                <th>URL</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
                @foreach($cryptos as $crypto)
                    <tr>
                        <td class="col-md-1"><a href="{{ url('crypto', $crypto->id ) }}">{{ $crypto->id }}</a></td>
                        <td class="col-md-1">{{ $crypto->abbreviation }}</td>
                        <td class="col-md-3">{{ $crypto->name }}</td>
                        <td class="col-md-5"><a href="{{ $crypto->url }}" target="_blank">{{ $crypto->url }}</a></td>
                        <td class="col-md-1">
                            <a class="btn btn-link" href="{{ url('crypto/' . $crypto->id . '/edit' ) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                        </td>
                        <td class="col-md-1">
                            @include('utils.delete', array( 'url' => url('crypto/' . $crypto->id . '/destroy'),
                                                            'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'))
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection