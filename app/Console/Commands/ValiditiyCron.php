<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Notification;
use App\Ads;

class ValiditiyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validitiy:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ads Valitity Expire Notify';

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
        $date=date("Y-m-d",strtotime("tomorrow"));
        $ads=Ads::whereDate('ads_expire_date','=',$date)->get();
        foreach ($ads as $key => $ad) {
            $checkNotification = Notification::where('message',"Your Ads(Ads ID: $ad->ads_ep_id , Ads Title : $ad->title) Expire Tomorrow")->first();
            if(empty($checkNotification)){

                $notification=Notification::Create([
                    'user_id' => $ad->seller_id,
                    'message' =>"Your Ads(Ads ID: $ad->ads_ep_id , Ads Title : $ad->title) Expire Tomorrow",
                    ]);
            }
        }
        \Log::info("Ad validitiy:cron is working done!");
        $this->info('validitiy:cron Cummand Run successfully!');
    }
}
