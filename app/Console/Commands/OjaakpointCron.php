<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Notification;
use App\Ads;
use App\setting;
class OjaakpointCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ojaakpoint:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ojaakpoint Low Notify';

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
    {   $setting=Setting::first();
        $ads=Ads::where('point','<',$setting->ads_view_point)->where('status','1')->get();
        //echo "<pre>";print_r($ads);die;
        foreach ($ads as $key => $ad) {
            //$checkNotification = Notification::where('user_id',$ad->seller_id)->whereDate('created_at',date('Y-m-d'))->first();
            $checkNotification = Notification::where('message',"Your Ads(Ads ID: $ad->ads_ep_id , Ads Title : $ad->title) have a Low Ads Balance")->first();
            if(empty($checkNotification)){
                $notification= Notification::Create([
                    'user_id' => $ad->seller_id,
                    'message' =>"Your Ads(Ads ID: $ad->ads_ep_id , Ads Title : $ad->title) have a Low Ads Balance",
                    ]);
            }
        }
        \Log::info("Ojaak point Low Notify worked!");
        $this->info('Ojaak point Low Notify worked!');
    }
}
