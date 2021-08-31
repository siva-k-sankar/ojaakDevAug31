<?php

namespace App\Exports;

use App\Freepoints;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
class WalletExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $userid;
    private $year;
    private $month;

    public function __construct(string $userid,int $year,int $month)
    {
        $this->userid = $userid;
        $this->year = $year;
        $this->month = $month;
        /*$Months = date('m');
        $previousYear = date('Y');
        $previousMonth = $Months-1;;
        if($previousMonth == 1){
            $previousYear = $previousYear-1;
        }
        //$this->userid = 'wsxafewf34f43fcsdc';
        $this->userid = $userid;
        $this->year = $previousYear;
        $this->month = $previousMonth;*/

    }
    public function collection()
    {	
        //echo '<pre>';print_r( $this->year );die;
    	$walletdatas = DB::table('freepoints')
                    ->select('freepoints.description','freepoints.point','ads.title','ads.ads_ep_id','freepoints.created_at')
                    ->leftJoin('users','users.id','=','freepoints.user_id')
                    ->leftJoin('ads','ads.id','=','freepoints.ads_id')
                    ->whereRaw('YEAR(freepoints.created_at) = ?',[$this->year])
                    ->whereRaw('MONTH(freepoints.created_at) = ?',[$this->month])
                    ->where('users.uuid',$this->userid)
                    ->get();
                    //return $this->userid; 
        return $walletdatas;
        
    }
    public function headings(): array
    {
        return [
            'Description',
            'Point',
            'Ads Title',
            'Ads Id',
            'Created Date',
        ];
    }
}
