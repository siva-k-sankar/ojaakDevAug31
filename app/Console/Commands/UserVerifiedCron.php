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
use Carbon\Carbon;
class UserVerifiedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UserVerified:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'UserVerified is lapsed';

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
        $users = DB::table('users')
                       ->select('users.*','roles.name as role_name')
                       ->leftJoin('roles','roles.id','=','users.role_id')
                       ->whereIn('role_id',['2']);
            $users=$users->Where(function ($query){
                        $query->orWhere('email_verified_at','=',null);
                        $query->orWhere('phone_verified_at','=',null);
                    });
                    
            $users=$users->get();
       
        foreach ($users as $key => $user) {
            
            $date = date('Y-m-d H:i:s', strtotime($user->created_at. ' + 90 days'));
            $now=date('Y-m-d H:i:s');
            if($now>$date){
                $userdata =User::find($user->id);
                $userdata->delete();
                \Log::info("$user->email is Un verified user deleted done!");
            }
            
        }
        \Log::info("UserVerified:cron deleted after 90 days is working done!");
        $this->info('UserVerified:cron deleted after 90 days  Command Run successfully!');
    }
}
