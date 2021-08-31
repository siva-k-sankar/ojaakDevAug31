<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Mail\WalletReport;
use Illuminate\Support\Facades\Mail;
use App\Exports\WalletExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Facades\Storage;

class WalletCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'OJAAK Wallet Report Generated';

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
        $Months = date('m');
        $previousYear = date('Y');
        $previousMonth = $Months-1;
        if($previousMonth == 1){
            $previousYear = $previousYear-1;
        }
        $walletdatas = DB::table('freepoints')
                    ->select('users.name','users.email','users.uuid','users.id','users.phone_no')
                    ->leftJoin('users','users.id','=','freepoints.user_id')
                    ->whereRaw('YEAR(freepoints.created_at) = ?',[$previousYear])
                    ->whereRaw('MONTH(freepoints.created_at) = ?',[$previousMonth])
                    //->whereRaw('MONTH(freepoints.created_at) = ?',[$currentMonth])
                    ->groupBy('users.id')
                    ->get();
        
        foreach ($walletdatas as $key => $wallet) {


        $walletdatass = DB::table('freepoints')
                    ->select('freepoints.description','freepoints.point','ads.title','ads.ads_ep_id','freepoints.created_at','freepoints.order_id')
                    ->leftJoin('users','users.id','=','freepoints.user_id')
                    ->leftJoin('ads','ads.id','=','freepoints.ads_id')
                    ->whereRaw('YEAR(freepoints.created_at) = ?',[$previousYear])
                    ->whereRaw('MONTH(freepoints.created_at) = ?',[$previousMonth])
                    ->where('users.uuid',$wallet->uuid)
                    ->get();
            $datass['walletdatass'] = $walletdatass;
            $datass['wallet'] = $wallet;

            $filenamePDF = 'WalletReport-'.$wallet->id.'-'.$previousYear.'-'.$previousMonth.'.pdf';
            $pdf = PDF::loadView('bought_billing.walletReport', $datass);

            Storage::put($filenamePDF, $pdf->output());
            //$pdf->download('invoice.pdf');


            $filename = 'WalletReport-'.$wallet->id.'-'.$previousYear.'-'.$previousMonth.'.csv';
            Excel::store(new WalletExport($wallet->uuid,$previousYear,$previousMonth), $filename);
            $filepath='/app/'.$filename;
            $filepathPDF='/app/'.$filenamePDF;
            $data['document'] = storage_path($filepath);
            $data['filenamePDF'] = storage_path($filepathPDF);
            $data['name']=$wallet->name;
            $data['line']='Your Last Month OJAAK Wallet Report Generated. Attached below';
            $sendmail = Mail::to($wallet->email)->send(new WalletReport($data));
            //die;
        }
        \Log::info("wallet:cron Last Month OJAAK Wallet Report Generated!");
        $this->info('wallet:cron Last Month OJAAK Wallet Report Generated!');
    }
}
