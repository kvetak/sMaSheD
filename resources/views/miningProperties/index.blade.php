@extends('layout')

@section('content')
    <div class="page-header">
        <h1>
            Mining Properties: Index
            @if( Auth::check()) <a class="btn btn-danger" href="{{ 'miningProp/refresh' }}">Dispatch probing</a> @endif
        </h1>
    </div>

    @if ( isset($dispatch) && $dispatch )
        <div class="alert alert-success">Mining probes dispatched</div>
    @endif

    <h2>List @if( Auth::check()) <a class="btn btn-info" href="miningProp/json">JSON</a> @endif</h2>

    <p>All currently recognized mining properties in system.</p>
    @if ( Auth::check() )
        @include('miningProperties.authlist', array( 'miningProps' => $miningProps ))
    @else
        @include('miningProperties.guestlist', array( 'miningProps' => $miningProps ))
    @endif

@endsection
