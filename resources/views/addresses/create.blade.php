@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Address: Create</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Address successfully created!</div>
    @endif
    <h2>A new entry</h2>
    <p>Add cryptocurrency entry:</p>

    @include('addresses.update', array( 'url' => url('crypto'),
                                      'method' => 'POST',
                                      'cr_abbr' => old('abbreviation'),
                                      'cr_name' => old('name'),
                                      'cr_url' => old('url'),
                                      'buttext' => 'Create',
                                      'errs' => $errors
                                      ))

@endsection