@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Pools: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Pool successfully created!</div>
    @endif
    <h2>Create</h2>
    <p>Add pool entry:</p>

    @include('pools.update', array( 'url' => url('pool'),
                                      'method' => 'POST',
                                      'cr_name' => old('name'),
                                      'cr_url' => old('url'),
                                      'buttext' => 'Create',
                                      'errs' => $errors
                                      ))

    <div class="clearfix"></div>
    <hr/>
    <h2>List</h2>
    <p>All currently recognized pools in system.</p>

    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>URL</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
                @foreach($pools as $pool)
                    <tr>
                        <td class="col-md-1"><a href="{{ url('pool', $pool->id ) }}">{{ $pool->id }}</a></td>
                        <td class="col-md-3">{{ $pool->name }}</td>
                        <td class="col-md-5"><a href="{{ $pool->url }}" target="_blank">{{ $pool->url }}</a></td>
                        <td class="col-md-1">
                            <a class="btn btn-link" href="{{ url('pool/' . $pool->id . '/edit' ) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                        </td>
                        <td class="col-md-1">
                            @include('utils.delete', array( 'url' => url('pool/' . $pool->id . '/destroy'),
                                                            'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'))
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection