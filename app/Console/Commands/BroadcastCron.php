<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Mail\BroadcastSend;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Broadcast;
use Carbon\Carbon;
use Auth;
class BroadcastCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Broadcast:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Announcement Send Verified user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $broadcast = Broadcast::whereDate('date',date('Y-m-d'))->Where('status','0')->get();
        if(!empty($broadcast)){
            //\Log::info("is working done!");
                
            /*$broadcast->status=1;
            $broadcast->save();*/

            //$data['message']=$broadcast->message;
            
            $users = DB::table('users')
                       ->select('users.*','roles.name as role_name')
                       ->leftJoin('roles','roles.id','=','users.role_id')
                       ->whereIn('role_id',['2']);
            $users=$users->Where(function ($query){
                        $query->orWhere('email_verified_at','!=',null);
                        $query->orWhere('phone_verified_at','!=',null);
                    });
                    
            $users=$users->get();
            foreach ($broadcast as $key1 => $broadcasts) {
                $data['message']=$broadcasts->message;
                
                foreach ($users as $key => $user) {
                    $data['name']=$user->name;
                    $sendBroadcast = Mail::to($user->email)->send(new BroadcastSend($data));
                    //\Log::info("$broadcasts->id $user->name is working done!");
                }
                $broad=Broadcast::find($broadcasts->id);
                $broad->status=1;
                $broad->save();
            }
            
            \Log::info("Broadcast:send is working done!");
        }else{
            \Log::info("Broadcast:send is working failed!");
        }
        
        
        $this->info('Broadcast:send Cummand Run successfully!');
    }
}
