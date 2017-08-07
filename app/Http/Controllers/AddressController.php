<?php

namespace App\Http\Controllers;

use App\Address;
use App\Server;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = Address::all();
        $servers = Server::all();
        return view('addresses.index', compact('addresses','servers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $servers = Server::all();
        return view('addresses.create', compact('servers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'address' => 'required',
            'server_id' => 'required'
        ]);

        $address = new Address();

        $isChanged = false;
        if ( isset($address)) {
            $this->assignFromRequest($request, $address);

            $address->save();
            $isChanged = true;
        }

        //return view('ports.edit', compact('port','isChanged'));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address = Address::find($id);
        return view('addresses.show', compact('address'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $address = Address::find($id);
        $servers = Server::all();
        return view('addresses.edit', compact('address','servers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'address' => 'required',
            'server_id' => 'required'
        ]);

        $address = Address::find($id);

        $isChanged = false;
        if ( isset($address)) {
            $this->assignFromRequest($request, $address);

            $address->save();
            $isChanged = true;
            $servers = Server::all();
        }

        return view('addresses.edit', compact('address','servers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Address::destroy($id);
        return back();
    }

    /**
     * @param Request $request
     * @param $address
     */
    protected function assignFromRequest(Request $request, $address)
    {
        $address->address = $request->address;
        $address->server_id = $request->server_id;
    }
}
