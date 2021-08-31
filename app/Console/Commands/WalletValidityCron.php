<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Mail\WalletReport;
use Illuminate\Support\Facades\Mail;
use App\Exports\WalletExport;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use App\User;
use App\Freepoints;
class WalletValidityCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walletvalidity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'walletvalidity is lapsed';

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
        /*$users = DB::table('users')
                       ->select('users.*','roles.name as role_name')
                       ->leftJoin('roles','roles.id','=','users.role_id')
                       ->whereIn('role_id',['2'])
                       ->where('email_verified_at','!=',null)
                       ->where('phone_verified_at','!=',null)
                       ->get();
        echo "<pre>";print_r($users);die;
        
        $now=date('Y-m-d H:i:s');
        
        foreach ($users as $key => $user) {
                $point=0;
                $freepoints = DB::table('freepoints')
                    ->where('user_id',$user->id)->where('expire_date','!=',null)->whereDate('expire_date','>=',$now)->where('status','1')->where('used','0')->get();
                foreach ($freepoints as $key => $points) {
                    $point=$point+$points->point;
                }
                $userdata= User::find($user->id);
                $userdata->wallet_point=$point;
                $userdata->save();
        }
        \Log::info("walletvalidity:cron is working done!");
        $this->info('walletvalidity:cron Cummand Run successfully!');*/


        $now=date('Y-m-d H:i:s');
        $freepoints = DB::table('freepoints')->where('expire_date','!=',null)->where('expire_date','<=',$now)->where('status','1')->where('used','0')->get();
        //echo "<pre>";print_r($freepoints);die;

        foreach ($freepoints as $key => $points) {
            //echo "<pre>";print_r($points);

            $userdata= User::find($points->user_id);
            if($userdata->wallet_point >= $points->point){
            //echo "<pre>";print_r($userdata);die;
                $userdata->wallet_point=($userdata->wallet_point - $points->point);
                $userdata->save();
                DB::table('freepoints')->where('id',$points->id)->update(['used'=>'1']);


                $freepoint= new Freepoints;
                $freepoint->order_id = generateRandomString();
                $freepoint->user_id=$points->user_id;
                $freepoint->description="Ojaak points expired,it is not used / Redeemed for 180 days. Transaction Id: ".$points->order_id;
                $freepoint->point=$points->point;
                $freepoint->ads_id=null;
                $freepoint->status=0;
                $freepoint->used=1;
                $freepoint->expire_date=null;
                $freepoint->save();

            }

        }
        \Log::info("Cron Run : walletvalidity expired update");
        //$this->info('walletvalidity:cron Command Run successfully!');

    }
}
