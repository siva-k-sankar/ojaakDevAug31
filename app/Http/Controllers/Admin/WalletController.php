<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Uuid;

use App\Wallet;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = Wallet::find(1);
        //echo '<pre>';print_r( $wallets );die;
        return view('back.wallets.list',compact('wallets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input=$request->all();
        //echo '<pre>';print_r( $input );die;
        //$uuid = Uuid::generate(4);
        $request->validate([
            'wallet1'          =>'required', 
            'wallet2'          =>'required',
            'wallet3'          =>'required',
            'wallet4'          =>'required',
            
        ]);
        
        //$wallets= new Wallet;
        $wallets = Wallet::find(1);
        $wallets->wallet1=$input['wallet1'];
        $wallets->wallet2=$input['wallet2'];
        $wallets->wallet3=$input['wallet3'];
        $wallets->wallet4=$input['wallet4'];
        $wallets->save();

        toastr()->success(' Wallets Updated successfully!');
        return redirect()->route('wallets');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        echo "edit";die;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
