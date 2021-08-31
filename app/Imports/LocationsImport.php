<?php
  
namespace App\Imports;
  
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;
use Uuid;
  
class LocationsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {



        $uuid = Uuid::generate(4);
        $states = DB::table("states")->where('name',$row[0])->orderby('id','desc')->first();
        if(empty($states)){
            $stateId = DB::table('states')->insertGetId(
                [ 'uuid' => $uuid, 'name' => $row[0], 'country_id' => '1', 'status' => '1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );

        }else{
            $stateId = $states->id;
        }


        $cities = DB::table("cities")->where('name',$row[1])->orderby('id','desc')->first();
        if(empty($cities)){
            $cityId = DB::table('cities')->insertGetId(
                [ 'uuid' => $uuid, 'name' => $row[1], 'state_id' => $stateId, 'status' => '1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );

        }else{
            $cityId = $cities->id;
        }


        $areas = DB::table("areas")->where('name',$row[2])->orderby('id','desc')->first();
        if(empty($areas)){
            $areasId = DB::table('areas')->insertGetId(
                [ 'name' => $row[2], 'city_id' => $cityId, 'pincode' => $row[3], 'status' => '1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );

        }else{
            $areasId = $areas->id;
        }


        /*return new User([
            'name'     => $row[0],
            'email'    => $row[1], 
            'password' => \Hash::make('123456'),
        ]);*/
    }
}