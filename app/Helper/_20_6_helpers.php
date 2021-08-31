<?php

    function user_profile_img($path,$base64_image){
    	$binary = base64_decode($base64_image);
        header('Content-Type: bitmap; charset=utf-8');
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $binary, FILEINFO_MIME_TYPE);
        $mime_type = str_ireplace('image/', '', $mime_type);
        $filename = md5(\Carbon\Carbon::now()) . '.' . $mime_type;
        $file = fopen($path . $filename, 'wb');
        if (fwrite($file, $binary)) {
                $destinationPath = public_path('uploads/profile/small');
                $img = Image::make($path.'/'.$filename)->save($destinationPath.'/'.$filename);
                $img->resize(100, 100, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.'/'.$filename);
                $destinationPath = public_path('uploads/profile/mid');
                $img = Image::make($path.'/'.$filename)->save($destinationPath.'/'.$filename);
                $img->resize(250, 250, function ($constraint) {$constraint->aspectRatio();
                })->save($destinationPath.'/'.$filename);           
        }
        fclose($file);

        return $filename;
    }
    function getIndianCurrency($number){
        //echo $number;die;
        //$number = 19;
        $no = round($number);
        $decimal = round($number - ($no = floor($number)), 2) * 100;    
        $digits_length = strlen($no);    
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
                $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
            } else {
                $str [] = null;
            }  
        }
        
        $Rupees = implode(' ', array_reverse($str));
        $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
        return ($Rupees ? '' . $Rupees : '') . $paise . " Only";
        //return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
    }
    function AdsBalanceChecker($id){
        $getads = DB::table('ads')
                ->select('ads.*')
                ->where('id',$id)
                ->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'))
                ->where('ads.point_expire_date','>',date('Y-m-d H:s:i'))->first();
        $settings= DB::table('settings')->first();
        if(!empty($getads)){
            if($getads->point >= $settings->ads_view_point){
                return $getads->point; 
            }else{
                return 0;
            }
           
        }
        return 0;
    }
    function plancategoryChecker($id,$value){
        $data= DB::table('premiumadsplans')->where('id',$id)->first();
        $plancategory = explode(",", $data->category);
        foreach ($plancategory as $cate) {
            if($cate==$value){
                return 1;
            }
        }
        return 0;
    }
    function plancategorychoose($id){

        $data= DB::table('premiumadsplans')->get();
        $datavalue = array();
        foreach ($data as $value) {
            $plancategory = explode(",", $value->category);
            foreach ($plancategory as $value1) {
                if($id==$value1){
                    array_push($datavalue,$value->id);
                }
            }
            
            //array_push($datavalue,$value->id);
        }
        return $datavalue;
    }
    function activecategory(){

        $data= DB::table('parent_categories')->where('status',1)->get();
        $datavalue = array();
        foreach ($data as $value) {
            array_push($datavalue,$value->id);
        }
        return $datavalue;
    }
    function plancategoryName($id){
        //return $id;
        $cate=explode(",", $id);
        $datavalue = array();
        $catelist=DB::table('parent_categories')->where('status',1)->whereIN('id',$cate)->get();
        foreach ($catelist as $value) {
            array_push($datavalue,$value->name);
        }
        return $datavalue;
        
    }
    function PurchaseToPost($planid,$adsid){
        //echo"<pre>";print_r($planid); die;
        $toastrwarning=0;
        $plandetails= App\PlansPurchase::where('uuid',$planid)->where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->first();
        if(!empty($plandetails)){
            $paidads= App\Premiumadsplan::where('id',$plandetails->plan_id)->first();
            if(!empty($paidads)){
                //$walletpoint=$paidads->ads_points;
                
                
                
                $plan_id=$paidads->id;
                //$walletpoint=$paidads->ads_points;
                //$type=paidorfree($plan_id);
                $findads= App\AdsTemp::find($adsid);
                if(!empty($paidads)){
                    $walletpoint=$paidads->ads_points;
                    if($plandetails->plan_id==1){

                    $postcount=App\PlansPurchase::where('user_id',Auth::user()->id)->where('type','0')->where('plan_id','1')->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30)->toDateString())->get()->count();
                    $settings= DB::table('settings')->first();
                        if($postcount<=$settings->no_free_ads_point_per_month){
                            $walletpoint=$paidads->ads_points;
                            $toastrwarning=1;
                        }else{
                            $walletpoint=0;
                            $toastrwarning=0;
                        }
                    }


                    $plan_id=$paidads->id;
                    //$walletpoint=$paidads->ads_points;
                    $type=paidorfree($plan_id);
                    $findads= App\AdsTemp::find($adsid);
                    
                    if(!empty($findads)){
                        
                        $findads->type=$type;
                        $findads->point = $walletpoint;
                        $findads->plan_id = $plan_id;
                        $findads->purchase_id = $plandetails->id;
                        $findads->save();

                        $ads =  new App\Ads;
                        $ads->uuid = $findads->uuid;
                        $ads->categories = $findads->categories;
                        $ads->sub_categories = $findads->sub_categories;
                        $ads->title = $findads->title;
                        $ads->cities = $findads->cities;
                        $ads->area_id = $findads->area_id;
                        $ads->pincode = $findads->pincode;
                        $ads->price = $findads->price;
                        $ads->description = $findads->description;
                        $ads->tags = $ads->findads;
                        $ads->seller_id = $findads->seller_id;
                        $ads->plan_id = $findads->plan_id;
                        $ads->purchase_id=$findads->purchase_id;
                        $ads->type = $findads->type;
                        $ads->point = $findads->point;
                        $ads->ads_ep_id=$findads->ads_ep_id;
                        $ads->seller_information = $findads->seller_information;
                        $ads->phone_no=$findads->phone_no;
                        $ads->name= $findads->name;
                        $ads->latitude = $findads->latitude;
                        $ads->longitude =  $findads->longitude;
                        $ads->brand_id =  $findads->brand_id;
                        $ads->model_id =  $findads->model_id;
                        $ads->save();

                        session()->put('adsuuid', $ads->uuid);


                        //Notificaiton Admin
                        $randomuuid = Uuid::generate(4);
                        
                        $adsnewid = $ads->id;

                        $adminNotification = DB::table('notification_admin')->insert(['user_id' => Auth::user()->id,'uuid' =>$randomuuid,'adsid' => $adsnewid,'message'=>Auth::user()->name." has added the new ad",'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                        //Notificaiton Admin

                        $adsimagedata= App\AdsimageTemp::where('ads_id',$adsid)->get();
                        if(!empty($adsimagedata)){
                            foreach ($adsimagedata as $adimage) {
                                $adsimage =  new App\Adsimage;
                                $adsimage->ads_id = $ads->id;
                                $adsimage->uuid = $adimage->uuid;
                                $adsimage->image = $adimage->image;
                                $adsimage->save();
                            }
                        }
                                                
                        $postvaluedata= App\PostValuesTemp::where('post_id',$adsid)->get();
                        if(!empty($postvaluedata)){
                            foreach ($postvaluedata as $postvalue) {
                                $postdata =  new App\PostValues;
                                $postdata->post_id = $ads->id;
                                $postdata->uuid = $postvalue->uuid;
                                $postdata->field_id = $postvalue->field_id;
                                $postdata->option_id = $postvalue->option_id;
                                $postdata->value = $postvalue->value;
                                $postdata->save();
                            }
                        }
                        
                        $PlansLimit = App\PlansPurchase::where('uuid',$planid)->first();
                        $PlansLimit->ads_count=$PlansLimit->ads_count-1;
                        $PlansLimit->save();

                        $adsdelete = App\AdsTemp::where('id',$adsid)->delete();
                        $adsimagedelete = App\AdsimageTemp::where('ads_id',$adsid)->delete();
                        $postvaluedelete = App\PostValuesTemp::where('post_id',$adsid)->delete();
                        
                        if($toastrwarning == 0){
                            return 2;
                        }else{
                            return 1;
                        }

                    }else{
                        return 0;
                    }   
                }else{
                        return 0;
                } 
            }else{
                    return 0;
                } 
        }else{
            return 0;
        } 
    }


    function PurchaseToPostPlatinam($planid,$adsuuid){
        //echo"<pre>";print_r($adsuuid); die;
        $toastrwarning=0;
        $plandetails= App\PlansPurchase::where('uuid',$planid)->where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->first();
        if(!empty($plandetails)){
            $paidads= App\Premiumadsplan::where('id',$plandetails->plan_id)->first();
            if(!empty($paidads)){
                //$walletpoint=$paidads->ads_points;
                
                
                
                $plan_id=$paidads->id;
                //$walletpoint=$paidads->ads_points;
                //$type=paidorfree($plan_id);
                //$findads= App\AdsTemp::find($adsid);
                if(!empty($paidads)){
                    $walletpoint=$paidads->ads_points;


                    $plan_id=$paidads->id;
                    //$walletpoint=$paidads->ads_points;
                    $type=paidorfree($plan_id);

                    $date = new DateTime('now');
                    $date->modify('+30 day'); 
                    $date = $date->format('Y-m-d H:i:s');

                    $findads =  App\Ads::where('uuid', $adsuuid)
                      ->update(['type' => $type,'plan_id' => $plan_id,'purchase_id' => $plandetails->id,'point_expire_date' => $date,'ads_expire_date' => $date]);

                    $findadsone =  App\Ads::where('uuid', $adsuuid)->increment('point',$walletpoint);

                    session()->put('adsuuid', $adsuuid);
                    
                    $PlansLimit = App\PlansPurchase::where('uuid',$planid)->first();
                    $PlansLimit->ads_count=$PlansLimit->ads_count-1;
                    $PlansLimit->save();
                    
                    
                   
                }else{
                    return 0;
                } 
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    function userstarratingPoint($val){
        switch ($val) {
            case 'Perfect':
                return 5;
                break;
            case 'Good':
                return 4;
                break;
            case 'Medium':
                return 3;
                break;
            case 'Poor':
                return 2;
                break;
            case 'Bad':
                return 1;
                break;
            default:
                return 1;
                break;
        }
    }
    function PlanidToGetads($id=null){
        return $id;
        $ads = DB::table('ads')->where('plan_id',$id)->get();
        $details='';
        if(!empty($ads)){ 
            foreach ($ads as $key => $value) {
               $details.="<li class='list-group-item'><a href='".route('adsview.getads',$value->uuid)."' style='text-decoration:none'>$value->title</a></li>";
            }
        }else{
            $details.="<li class='list-group-item'>NO Used Ads</li>";
        } 
        return $details;
    }
    function userstarrating($id){
        $userrating = DB::table('users_ratings')->where('user_id',$id)->get();
        if(!empty($userrating)){
            $userratingcount=$userrating->count();
            $sumofvalue=DB::table('users_ratings')->select(DB::raw("SUM(rating) as sum"))->where('user_id',$id)->get();
            //return $sumofvalue[0]->sum;
            if(!empty($sumofvalue) && $sumofvalue[0]->sum != 0){
                $rating= $sumofvalue[0]->sum / $userratingcount;
                return $rating;
            }else{
                return 0;
            }
            
        }
        return 0;

    }
    function userstarratingcheck($id,$fromuserid){
        $userrating = DB::table('users_ratings')->select('rating')->where('user_id',$id)->where('rating_from_user_id',$fromuserid)->first();
        if(!empty($userrating)){
           return $userrating->rating;
        }
        return 0;
    }
    function getUserUuid($id){
        $getUuid = DB::table('users')->select('uuid')->where('id',$id)->first();
        if(!empty($getUuid)){
            return $getUuid->uuid;
        }
    }

    // function getUserPhoto($id){
    //     $getUuid = DB::table('users')->select('photo')->where('uuid',$id)->first();
    //     if(!empty($getUuid)){
    //         return $getUuid->photo;
    //     }
    // }
    
    function getUserId($id){
        $getid = DB::table('users')->select('id')->where('uuid',$id)->first();
        if(!empty($getid)){
            return $getid->id;
        }
    }
    function getUserEmail($id){
        $getid = DB::table('users')->select('email')->where('id',$id)->first();
        if(!empty($getid)){
            return $getid->email;
        }
    }
    function getAdsUuid($id){
        $getUuid = DB::table('ads')->select('uuid')->where('id',$id)->first();
        if(!empty($getUuid)){
            return $getUuid->uuid;
        }
    }
    function getAdsTitle($id){
        $getUuid = DB::table('ads')->select('title')->where('id',$id)->first();
        if(!empty($getUuid)){
            return $getUuid->title;
        }
    }
    function getTempAdsUuid($id){
        $getUuid = DB::table('ads_temp')->select('uuid')->where('id',$id)->first();
        if(!empty($getUuid)){
            return $getUuid->uuid;
        }
    }
    function getAdsId($id){
        $getid = DB::table('ads')->select('id')->where('uuid',$id)->first();
        if(!empty($getid)){
            return $getid->id;
        }
    }
    function otp($mobile){
        $otp=env('SMS_OTP');
        //$otp="123456";
        //echo"<pre>";print_r($otp); die;
        return $otp;
    }

    function verify($mobile){
        $otp=env('SMS_OTP');
        //$otp="123456";
        //echo"<pre>";print_r($otp); die;
        return $otp;
    }

    function referral(){
        $strcode =Str::random(6);
        $check=DB::table('users')->where('referral_code',$strcode)->first();
        if(!empty($check)){
            referral();
        }else{
            $referral=$strcode;
            return $referral;
        }
        
    }


    function referralpoints($userid,$referalcode){

        $loginUser=DB::table('users')->where('id',$userid)->where('email_verified_at','!=',null)->where('phone_verified_at','!=',null)->first();
        if($loginUser){
            $referralcheck=DB::table('users')->where('referral_code',$referalcode)->where('email_verified_at','!=',null)->where('phone_verified_at','!=',null)->first();
            if(empty($referralcheck))
            {
               return "Not Verified User";
            }                
            $slug="referral_point";
            if(!empty($referralcheck)){
                $verifiedinfo= DB::table('verifiedlist')->where('name','=',$slug)->first();
                $point = DB::table('settings')->select($slug)->first();
                
                $refferral_table  = App\Referral::firstOrCreate(['user_id' => $userid,
                'referal_user_id' =>$referralcheck->id],[
                'user_id' => $userid,
                'referal_user_id' =>$referralcheck->id,
                'referral_point'=>$point->$slug,
                ]);
                if(!empty($refferral_table)){
                    $date = new DateTime();
                    $date->modify('+180 day');
                    $expire_date = $date->format('Y-m-d H:i:s');
                    $freepoint= App\Freepoints::Create([
                    'order_id' => generateRandomString(),
                    'user_id' => $referralcheck->id,
                    'description' =>$verifiedinfo->description,
                    'ads_id'=> null,
                    'point'=>$point->$slug,
                    'status'=> 1,
                    'used'=> 0,
                    'expire_date'=>$expire_date,
                    ]);
                    $verification= App\Verification::Create([
                    'user_id' => $referralcheck->id,
                    'verified_id' =>$verifiedinfo->id,
                    'status'=>$verifiedinfo->status,
                    ]);
                    $user= App\User::find($referralcheck->id);
                    $user->wallet_point=$user->wallet_point+$point->$slug;
                    $user->save();
                }
                
            }
        }
    }

    function verified_profile($id){
        $badge="";
        $verifycount= DB::table('verifiedlist')->where('status',0)->count();
        $pointverifycount= DB::table('verifications')->where('status',0)->where('user_id',$id)->count();
        /*$verifydesc= DB::table('verifiedlist')->select('description as des')->get();
        $convertjson = json_decode(json_encode($verifydesc),true);
        $verifyarray = array_column($convertjson, "des");
        $pointverifycount = DB::table('freepoints')
                    ->where('user_id',$id)
                    ->whereIn('description',$verifyarray)->count();*/
        //echo"<pre>";print_r($pointverifycount);die;
        if($verifycount <= $pointverifycount )
        {
            $badge="1";
        }else{
            $badge="0";  
        }  
        return $badge;    
    }

    function addpoint($id,$slug,$adid=null){
        $usercheck=DB::table('users')->where('id','=',$id)->where('email_verified_at','!=',null)
                       ->where('phone_verified_at','!=',null)->first();
        if(empty($usercheck))
        {
           return "Not Verified User";
        }                
        $verifiedinfo= DB::table('verifiedlist')->where('name','=',$slug)->first();
        $point = DB::table('settings')->select($slug)->first();
        if(isset($point) && !empty($point)){

            /*if($slug == 'mail_point' || $slug == 'mobile_point'){
                $amountToBeRedueced = 0;
                if(isset($point->mail_point)){
                    $amountToBeRedueced = $point->mail_point;
                }else{
                    $amountToBeRedueced = $point->mobile_point;                
                }
                $reducewallet = DB::table('wallets')->where('id',1)->first();
                if($reducewallet->wallet1 >= $amountToBeRedueced){
                    DB::table('wallets')->where('id',1)->decrement('wallet1', $amountToBeRedueced); 
                }
            }else if($slug == 'ads_post_point'){
                $amountToBeRedueced = 0;
                if(isset($point->ads_post_point)){
                    $amountToBeRedueced = $point->ads_post_point;
                }

                $reducewallet = DB::table('wallets')->where('id',1)->first();
                if($reducewallet->wallet2 >= $amountToBeRedueced){
                    DB::table('wallets')->where('id',1)->decrement('wallet2', $amountToBeRedueced);
                }
            }else if($slug == 'ads_view_point'){
                $amountToBeRedueced = 0;
                if(isset($point->ads_view_point)){
                    $amountToBeRedueced = $point->ads_view_point;
                }

                $reducewallet = DB::table('wallets')->where('id',1)->first();
                if($reducewallet->wallet3 >= $amountToBeRedueced){
                    DB::table('wallets')->where('id',1)->decrement('wallet3', $amountToBeRedueced);
                }

            }else if($slug == 'facebook_point' || $slug == 'google_point' || $slug == 'work_mail_point' || $slug == 'profile_upload_point'  || $slug == 'govt_id_point'  || $slug == 'referral_point' || $slug == 'feature_ads_point' || $slug == 'user_buys_product_point'){

                $amountToBeRedueced = 0;
                if(isset($point->facebook_point)){
                    $amountToBeRedueced = $point->facebook_point;
                }else if(isset($point->google_point)){
                    $amountToBeRedueced = $point->google_point;                
                }else if(isset($point->work_mail_point)){
                    $amountToBeRedueced = $point->work_mail_point;                
                }else if(isset($point->profile_upload_point)){
                    $amountToBeRedueced = $point->profile_upload_point;                
                }else if(isset($point->govt_id_point)){
                    $amountToBeRedueced = $point->govt_id_point;                
                }else if(isset($point->referral_point)){
                    $amountToBeRedueced = $point->referral_point;                
                }else if(isset($point->feature_ads_point)){
                    $amountToBeRedueced = $point->feature_ads_point;                
                }else if(isset($point->user_buys_product_point)){
                    $amountToBeRedueced = $point->user_buys_product_point;                
                }

                $reducewallet = DB::table('wallets')->where('id',1)->first();
                if($reducewallet->wallet4 >= $amountToBeRedueced){
                    DB::table('wallets')->where('id',1)->decrement('wallet4', $amountToBeRedueced);
                }
            }*/
            $freepointcheck=DB::table('freepoints')->where('user_id','=',$id)->where('description','=',$verifiedinfo->description)->where('ads_id','=',$adid)->first();
            if(empty($freepointcheck)){
                
                $date = new DateTime();
                $date->modify('+180 day');
                $expire_date = $date->format('Y-m-d H:i:s');

                $freepoint= new App\Freepoints;
                $freepoint->order_id = generateRandomString();
                $freepoint->user_id=$id;
                $freepoint->description=$verifiedinfo->description;
                $freepoint->point=$point->$slug;
                $freepoint->ads_id=$adid;
                $freepoint->status=1;
                $freepoint->used=0;
                $freepoint->expire_date=$expire_date;
                $freepoint->save();

                $verification= new App\Verification;
                $verification->user_id=$id;
                $verification->verified_id=$verifiedinfo->id;
                $verification->ads_id=$adid;
                $verification->status=$verifiedinfo->status;
                $verification->save();

                $user= App\User::find($id);
                $user->wallet_point=$user->wallet_point+$point->$slug;
                $user->save();
            }
        }
        
        

    }
    function addviewpoint($id,$slug,$adid){

        $point = DB::table('settings')->select($slug)->first();
        $usercheck=DB::table('users')->where('id','=',$id)->where('email_verified_at','!=',null)
                       ->where('phone_verified_at','!=',null)->first();
        if(empty($usercheck))
        {
           return "Not Verified User";
        } 
        /*if($slug == 'ads_view_point'){
            $amountToBeRedueced = 0;
            if(isset($point->ads_view_point)){
                $amountToBeRedueced = $point->ads_view_point;
            }
            $reducewallet = DB::table('wallets')->where('id',1)->first();
            if($reducewallet->wallet3 >= $amountToBeRedueced){
                DB::table('wallets')->where('id',1)->decrement('wallet3', $amountToBeRedueced);
            }
        }*/
        
        $verifiedinfo= DB::table('verifiedlist')->where('name','=',$slug)->first();
        $point = DB::table('settings')->select($slug)->first();
        $freepointcheck=DB::table('freepoints')->where('user_id','=',$id)->where('description','=',$verifiedinfo->description)->where('ads_id','=',$adid)->first();
            if(empty($freepointcheck)){
                
                $date = new DateTime();
                $date->modify('+180 day');
                $expire_date = $date->format('Y-m-d H:i:s');

                $freepoint= new App\Freepoints;
                $freepoint->order_id = generateRandomString();
                $freepoint->user_id=$id;
                $freepoint->description=$verifiedinfo->description;
                $freepoint->point=$point->$slug;
                $freepoint->status=1;
                $freepoint->ads_id=$adid;
                $freepoint->used=0;
                $freepoint->expire_date=$expire_date;
                $freepoint->save();

                $verification= new App\Verification;
                $verification->user_id=$id;
                $verification->verified_id=$verifiedinfo->id;
                $verification->ads_id=$adid;
                $verification->status=$verifiedinfo->status;
                $verification->save();

                $user= App\User::find($id);
                $user->wallet_point=$user->wallet_point+$point->$slug;
                $user->save();
            }
    }

    function age($date){
        $dobdate = strtotime($date);  
        $currentdate = strtotime(date('Y-m-d'));
        $diff = abs($currentdate  - $dobdate);
        $years = floor($diff / (365*60*60*24));
        return $years;
    }
    function get_name($id)
    {   
        $responses= DB::table('users')->where('id',$id)->first();
        if(!empty($responses)){
            $name=$responses->name;
            //echo"<pre>";print_r( $name); die;
            return ucwords($name);
        }
        
    }
    function get_mobile($id)
    {   
        $responses= DB::table('users')->where('id',$id)->first();
        if(!empty($responses)){
            $name=$responses->phone_no;
            //echo"<pre>";print_r( $name); die;
            return $name;
        }
        
    }
    function get_fieldname($id)
    {   
        $responses= DB::table('fields')->where('id',$id)->first();
        if(!empty($responses)){
            $name=$responses->name;
            //echo"<pre>";print_r( $responses); die;
            return ucwords($name);
        }
        
    }

    function get_fielddata($fieldid=null,$value=null)
    {   
        $responses= DB::table('fields')->where('id',$fieldid)->first();
        if(!empty($responses)){
            if($responses->type=='select'){
                $data =DB::table('fields_options')->where('field_id',$responses->id)->where('id',$value)->first();
                if(!empty($data)){
                    return $data->value;
                }else{
                    $value="";
                    return $value;
                }
            }else if($responses->type=='checkbox'){
                if($value==1){
                    $value="on";
                    return $value;
                }else{
                    $value="off";
                    return $value;
                }

            }else if($responses->type=='radio'){
                $data =DB::table('fields_options')->where('field_id',$responses->id)->where('id',$value)->first();
                if(!empty($data)){
                    return $data->value;
                }else{
                    $value="";
                    return $value;
                }

            }else if($responses->type=='checkbox_multiple'){
                $data =DB::table('fields_options')->where('field_id',$responses->id)->where('id',$value)->first();
                if(!empty($data)){
                    return $data->value;
                }else{
                    $value="";
                    return $value;
                }
            }else{
                return $value;
            }
        }
        
    }
    function get_P_Cate_Name($id){
        $getid = DB::table('parent_categories')->select('name')->where('id',$id)->first();
        if(!empty($getid)){
            return ucwords($getid->name);
        }
    }
    function get_S_Cate_Name($id){
        $getid = DB::table('sub_categories')->select('name')->where('id',$id)->first();
        if(!empty($getid)){
            return ucwords($getid->name);
        }
    }

    function get_cityname($id)
    {   
        $responses= DB::table('cities')->where('id',$id)->first();
        if(!empty($responses)){
            $name=$responses->name;
            //echo"<pre>";print_r( $responses); die;
            return ucwords($name);
        }
        
    }
    function get_state($id)
    {   
        $responses= DB::table('cities')->where('id',$id)->first();
        if(!empty($responses)){
            $state= DB::table('states')->where('id',$responses->state_id)->first();
            $name=$state->name;
            //echo"<pre>";print_r( $responses); die;
            return ucwords($name);
        }
        
    }
    function get_areaname($id)
    {   
        $responses= DB::table('areas')->where('id',$id)->first();
        if(!empty($responses)){
            $name=$responses->name;
            //echo"<pre>";print_r( $responses); die;
            return ucwords($name);
        }
        
    }
    /**/

    function get_mail($id)
    {   
        $responses= DB::table('users')->where('id',$id)->first();
        if(!empty($responses)){
            $email=$responses->email;
            //echo"<pre>";print_r( $name); die;
            return ucwords($email);
        }
        
    }
    function get_prooflist($proof_id='')
    {
        $responses = array(
            '1'=>strtoupper('aadhar card'),
            '2'=>strtoupper('driving license'),
            '3'=>strtoupper('pan card'),
            '4'=>strtoupper('voter id'),
        );

        if(!empty($proof_id)){
            return $responses[$proof_id];
        }
        return $responses;
    }
    function get_proofcheck($proofid='')
    {
        $proofid= DB::table('proofs')->where('proof',$proofid)->where('user_id',Auth::id())->where('verified','=','1')->first();
        if(!empty($proofid)){
            return 1;
        }else{
            return 0;
        }
    }
    function old_proofdelete($proofid='')
    {
        $proofid= DB::table('proofs')->where('proof',$proofid)->where('user_id',Auth::id())->first();
        if(!empty($proofid)){
            if(!empty($proofid->image) && file_exists(public_path('/uploads/proof/'.$proofid->image)))
            {
                unlink(public_path('uploads/proof/'.$proofid->image));
            }

            DB::table('proofs')->where('id',$proofid->id)->where('user_id',Auth::id())->delete();
        }
    }
    function get_username($id)
    {   
        $responses= DB::table('users')->select('name')->where('id',$id)->first();
        if(!empty($responses)){
            if($responses->name != ""){
                return $responses->name;
            }else{
                return "OJAAK USER";
            }
            
        }
        
    }
    function get_adsname($id)
    {   
        $responses= DB::table('ads')->select('title')->where('id',$id)->first();
        if(!empty($responses)){
            return $responses->title;
        }
        
    }
    function get_chatTime($userid,$adid)
    {   
        $unique_chats_id=Auth::user()->id."_".$userid."_".$adid;
        $ulternative_unique_chats_id=$userid."_".Auth::user()->id."_".$adid;
        $responses= DB::table('chats')->select('created_at')->where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
        if(!empty($responses)){
            return $responses->created_at;
        }
    }
    function get_adminchatTime($admin)
    {   
        $unique_chats_id=Auth::user()->id."_".$admin;
        $ulternative_unique_chats_id=$admin."_".Auth::user()->id;
        $responses= DB::table('chats')->select('created_at')->where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
        if(!empty($responses)){
            return $responses->created_at;
        }
    }
    function get_adsprice($id)
    {   
        $responses= DB::table('ads')->select('price')->where('id',$id)->first();
        if(!empty($responses)){
            return $responses->price;
        }
        
    }
    function get_userphoto($id)
    {   
        $responses= DB::table('users')->select('photo')->where('id',$id)->first();
        if(!empty($responses)){
            return $responses->photo;
        }
        
    }
    function get_adsphoto($id)
    {   $responses = DB::table('ads')
            ->leftJoin('ads_image', 'ads_image.ads_id', '=', 'ads.id')
            ->where('ads.id',$id)
            ->select('ads.*','ads_image.id as adsimgid','ads_image.image')
            ->groupBy("ads.id")
            ->pluck('ads_image.image');
        if(!empty($responses)){
            return $responses[0];
        }
        
    }
    function predefined_privacy_table($id)
    {   
        $privacy=DB::table('privacy')->where('user_id',$id)->first();
        if(empty($privacy)){
            $privacy=App\Privacy::create([
                'user_id' => $id,
            ]);
        }
        
    }
    function plan_history($uuid,$plan_id,$plan_name,$plan_price,$plan_limit,$admin_id,$status)
    {   
        $plan_history= new App\Plan_history;
        $plan_history->uuid=$uuid;
        $plan_history->plan_id=$plan_id;
        $plan_history->modified_name=$plan_name;
        $plan_history->modified_price=$plan_price;
        $plan_history->modified_limit=$plan_limit;
        $plan_history->modified_admin_id=$admin_id;
        $plan_history->modified_date=date("Y-m-d h:m:s");
        $plan_history->status=$status;
        $plan_history->save();
        
    }
    
    function faq_data($uuid,$questions,$answers)
    {   
        $faq_data= new App\Faq_data;
        $faq_data->uuid=$uuid;
        // $faq_data->id=$id;
        $faq_data->questions=$questions;
        $faq_data->answers=$answers;
        $faq_data->save();
    }
    
    function getOTP()
    {
        return '121314';
    }

    
    function adsLimitcheck()
    {
        $setting=App\Setting::first();
        if(!empty($setting)){
            $useradslimits=App\AdsLimits::updateOrCreate(['user_id' => Auth::id()],['free' => ($setting->freeadslimit), 'paid' => 0]);

            if($setting->infinitefreelimit =='1'){
                return 1;
            } 
        }

        $getadslimit = App\AdsLimits::where('user_id',Auth::id())->first();

        if(empty($getadslimit)){
            //return redirect('plans');
            return 0;
        }else if($getadslimit->free <= 0 && $getadslimit->paid <= 0){
            return 0;
        }
        return 1;
    }
    

    function time_elapsed_string($datetime, $full = false) {
        //echo '<pre>';print_r($datetime);die;
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'Just now';
    }
    function ads_display_time_elapsed_string($datetime, $full = false) {
        //echo '<pre>';print_r($datetime);die;

        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        //return $datetime;
        //echo '<pre>';print_r($diff->days);die;
        if($diff->days < 1){
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'Just now';
        }else{
            $createddate=$ago->format('d-M-Y');
            return $createddate;
        }
    }
    function BlockedUserChat($id,$opponentid)
    {
        
        $block_user_level_check_1= DB::table('customer_manage_user')->where('user_id',$id)->where('block_user_id',$opponentid)->first();
        $block_user_level_check_2= DB::table('customer_manage_user')->where('user_id',$opponentid)->where('block_user_id',$id)->first();
        //echo '<pre>';print_r($block_user_level1);die;
        if (!empty($block_user_level_check_1) && !empty($block_user_level_check_2)) {
                $block['ref']=3;
                $block['msg']="Both Block";
                return $block;
           
        }else {
            $block_user_level1= DB::table('customer_manage_user')->where('user_id',$id)->where('block_user_id',$opponentid)->first();
            if (!empty($block_user_level1)) {
                $block['ref']=0;
                $block['msg']="You Blocked this User";
                return $block;
            }else {
                $block_user_level2= DB::table('customer_manage_user')->where('block_user_id',$id)->where('user_id',$opponentid)->first();
                if (!empty($block_user_level2)) {
                    $block['ref']=1;
                    $block['msg']="This User Blocked You";
                    return $block;
                } else {
                    $block['ref']=2;
                    $block['msg']="Not Blocking";
                    return $block;
                }
            }
            
        }
    }
    

    function getaddress($lat='',$lang='')
    {
        $geolocation = $lat.','.$lang;
        //$requestedg = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false';
        $requestedg = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4
&sensor=false';

        $file_contents = file_get_contents($requestedg);
        $json_decode = json_decode($file_contents);
        $area = '';$city = '';$state = '';$country = '';
        //echo '<pre>';print_r( $json_decode->results[0] );die; 
        if(isset($json_decode->results[0])) {
            $response = array();
            foreach($json_decode->results[0]->address_components as $addressComponet) {
                if(in_array('sublocality_level_1', $addressComponet->types)){
                        $area = $addressComponet->long_name;             
                }else if(in_array('sublocality_level_2', $addressComponet->types)) {
                        $area = $addressComponet->long_name; 
                }else if(in_array('neighborhood', $addressComponet->types)) {
                        $area = $addressComponet->long_name; 
                }else if(in_array('route', $addressComponet->types)) {
                        $area = $addressComponet->long_name; 
                }

                if(in_array('locality', $addressComponet->types)){
                        $city = $addressComponet->long_name;             
                }else if(in_array('administrative_area_level_2', $addressComponet->types)){
                        $city = $addressComponet->long_name;             
                }
                if(in_array('administrative_area_level_1', $addressComponet->types)) {
                        $state = $addressComponet->long_name; 
                }
                if(in_array('country', $addressComponet->types)) {
                        $country = $addressComponet->long_name; 
                }
            }
        }
        /*echo '<pre>';print_r( $area );
        echo '<pre>';print_r( $city );
        echo '<pre>';print_r( $state );
        echo '<pre>';print_r( $country );die; */

        $uuid = Uuid::generate(4);
        if($country != ''){
            $countries = DB::table('countries')->where('name',$country)->first();
            if(empty($countries)){
                $country_id = DB::table('countries')->insertGetId(
                    ['uuid' => $uuid, 'sortname'=>$country, 'name' => $country, 'status' => 1]
                );
            }else{
                $country_id = $countries->id;
            }
        }
        if($state != ''){
            $states = DB::table('states')->where('name',$state)->first();
            if(empty($states)){
                $state_id = DB::table('states')->insertGetId(
                    ['uuid' => $uuid, 'country_id' => $country_id, 'name' => $state, 'status' => 1]
                );
            }else{
                $state_id = $states->id;
            }
        }
        if($city != ''){
            $cities = DB::table('cities')->where('name',$city)->first();
            if(empty($cities)){
                $city_id = DB::table('cities')->insertGetId(
                    ['uuid' => $uuid, 'state_id' => $state_id, 'name' => $city, 'status' => 1]
                );
            }else{
                $city_id = $cities->id;
            }
        }else{
            return 0;
        }
        if($area != ''){
            $areas = DB::table('areas')->where('name',$area)->first();
            if(empty($areas)){
                $area_id = DB::table('areas')->insertGetId(
                    ['city_id' => $city_id, 'name' => $area, 'status' => 1]
                );
            }else{
                $area_id = $areas->id;
            }
        }else{
            return 0;
        }
        /*echo '<pre>';print_r( $area_id );
        echo '<pre>';print_r( $city_id );
        echo '<pre>';print_r( $state_id );
        echo '<pre>';print_r( $country_id );die;*/      
        
        $location_ids = array('area_id'=>$area_id,'city_id'=>$city_id,'state_id'=>$state_id,'country_id'=>$country_id);
        return $location_ids;
        


        
    }

    function getfulladress($area_id='')
    {

        $areas = DB::table('areas')->where('id',$area_id)->first();
        $cities = DB::table('cities')->where('id',$areas->city_id)->first();
        $states = DB::table('states')->where('id',$cities->state_id)->first();
        $countries = DB::table('countries')->where('id',$states->country_id)->first();
        $fulladdress = $areas->name.','.$cities->name.','.$states->name.','.$countries->name;
        return $fulladdress;
    }
    function getStaticMapKey()
    {
         $mapapikey="AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4";
        return $mapapikey;
    }

    if (! function_exists('getSitePages')) {
       function getSitePages() 
       {
            return [  
                '1'=>'Dashboard',
                '2'=>'Ads',
                '3'=>'Plans',
                '4'=>'Users',
                '5'=>'FAQ',
                '6'=>'User Complaints',
                '7'=>'Ads Complaints',
                '8'=>'Sites Settings',
                '9'=>'Wallet Settings',
                '10'=>'Point Settings',
                '11'=>'Proof Verification',
                '12'=>'Logs',
                '13'=>'Categories',
                '14'=>'Custom Field',
                '15'=>'Redeem Amount',
                '16'=>'Contact Us',
                '17'=>'Profile Photo Verification',
                '18'=>'Plan Purchase',
                '19'=>'Location',
                '20'=>'Purchase Bill',
                '21'=>'Announcement',
                '22'=>'Roles',
                '23'=>'Admin Notification',
            ];
       }
    }
    function getpaidplanname($id){
        $getid = DB::table('premiumadsplans')->select('plan_name')->where('id',$id)->first();
        if(!empty($getid)){
            return $getid->plan_name;
        }
        return "None";
    }
    function paidorfree($id){
        $getid = DB::table('premiumadsplans')->where('id',$id)->first();
        //echo '<pre>';print_r($getid);die;
        if(!empty($getid)){
            if($getid->id==1){
                $free='0';
                return $free;
            }else{
                $free='1';
                return $free;
            }
            
        }
    }
    function getTopPlanName($id){
        $getid = DB::table('top_ads_plan')->select('name')->where('id',$id)->first();
        if(!empty($getid)){
            return $getid->name;
        }
    }
    function formatMoney($number, $fractional=false) {
        if ($fractional) {
            $number = sprintf('%.2f', $number);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
            if ($replaced != $number) {
                $number = $replaced;
            } else {
                break;
            }
        }
        return $number;
    }
    function Billing_Information_Check(){
        $bill=DB::table('billing_information')->where('user_id',Auth::user()->id)->first();
        if(!empty($bill)){
            if($bill->addr1!='' && $bill->state!='' && $bill->city!='' ){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0; 
        }
    }
    function Purchase_Bill_Address($purchaseplanid){
        $bill=DB::table('purchase_billing')->where('plan_id',$purchaseplanid)->first();
        if(empty($bill)){
            $billinfo=DB::table('billing_information')->where('user_id',Auth::user()->id)->first();
            $purchasebilling = App\PurchaseBilling::updateOrCreate(
            ['plan_id' =>$purchaseplanid],
            [
                'plan_id' =>$purchaseplanid,
                'username' => ucwords($billinfo->username),
                'email' => ucwords($billinfo->email),
                'businessname' => ucwords($billinfo->businessname), 
                'gst' => $billinfo->gst,
                'gstquestion' => $billinfo->gstquestion,
                'addr1' => ucwords($billinfo->addr1),
                'addr2' => ucwords($billinfo->addr2),
                'state' => ucwords($billinfo->state),
                'city' => ucwords($billinfo->city)
            ]);
            return "saved";
        }else{
            return "already Saved";
        }
    }
    function redeemWallet($beneficiaryPhoneNo,$amount)
    {  

        //echo '<pre>';print_r( $OrderId );die;
        require_once(app_path() . '/Helper/paytm_checksum/lib/encdec_paytm.php');



        /* initialize an array */
        $paytmParams = array();

        /* Find Sub Wallet GUID in your Paytm Dashboard at https://dashboard.paytm.com */
        $paytmParams["subwalletGuid"] = "252f7eb4-3cd6-11ea-8708-fa163e429e83";
        // $paytmParams["subwalletGuid"] = "abc1a71f-398b-489e-9b54-5c10443809ca";
        //$paytmParams["subwalletGuid"] = "59e08f33-8f72-46e0-9e1f-7841a05f78c0";

        /* Enter your unique order id, this should be unique for every disbursal */
        //$OrderId = time().rand(10,100);
        $OrderId = round(microtime(true) * 1000);
        //$paytmParams["orderId"] = "ORDER105";
        $paytmParams["orderId"] = $OrderId;
           
        /* Enter Beneficiary Phone Number against which the disbursal needs to be made */
        //$paytmParams["beneficiaryPhoneNo"] = "7777777777";
        $paytmParams["beneficiaryPhoneNo"] = $beneficiaryPhoneNo;

        /* Amount in INR payable to beneficiary */
        //$paytmParams["amount"] = "1";
        $paytmParams["amount"] = $amount;

        /* prepare JSON string for request body */
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        /**
        * Generate checksum by parameters we have in body
        * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
        */
        $checksum = getChecksumFromString($post_data, "vAYBeZTMeY8aCesw");
        //echo '<pre>';print_r( $checksum );die;

        /* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
        $x_mid = "OjaakI02159452260228";
        //$x_mid = "OjaakI23317210722554";

        /* put generated checksum value here */
        $x_checksum = $checksum;

        /* Solutions offered are: food, gift, gratification, loyalty, allowance, communication */

        /* for Staging */
        $url = "https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/wallet/gratification";

        /* for Production */
        // $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/wallet/gratification";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
       
        $responses = json_decode($response, true);
        if(!empty($responses)){
            if($responses['statusCode'] == 'DE_001' || $responses['statusCode'] == 'DE_002'){
                    //for Staging
                    $urlstatuscheck = "https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/query";
                    //for Production
                    // $urlstatuscheck = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/query";

                    $ch = curl_init($urlstatuscheck);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $responsestatus = curl_exec($ch);
                    $responsestatuss = json_decode($responsestatus, true);

                    if(!empty($responsestatuss)){
                        if($responsestatuss['statusCode'] == 'DE_001' || $responsestatuss['statusCode'] == 'DE_101'){
                            return $responsestatuss;
                        }else{
                            return 0;
                        }
                    }else{
                        return 0;
                    }

            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    function generateRandomString($length = 10) {

        $characters = '0123456789012345678901234567890123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $checkOrderId=DB::table('freepoints')->where('order_id',$randomString)->first();
        if(empty($checkOrderId)){
            return $randomString;
        }else{
            generateRandomString();
        }
    }



    function getarrays($arrayvalues) {
        $newarrayvalues = array();
        foreach ($arrayvalues as $key => $cffvla) {
            $customfieldfiltersValues = explode('=',$cffvla);
            $newarrayvalues[$customfieldfiltersValues[0]] = $customfieldfiltersValues[1];
        }
        //echo"<pre>";print_r($newarrayvalues);die;
        return $newarrayvalues;
    }

    
?>


