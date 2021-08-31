<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'appname',
        'logo',
        'email',
        'address',
        'footer',
        'phone',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'facebook_point',
        'google_point',
        'mobile_point',
        'mail_point',
        'work_mail_point',
        'profile_upload_point',
        'govt_id_point',
        'referral_point',
        'freeadslimit',
        'ads_view_point',
        'ads_post_point',
        'free_ad_view_count',
        'infinitefreelimit',
        'feature_ads_point',
        'user_buys_product_point',
        'redeemable_amount',
        'PAYTM_ENV',
        'MERCHANT_ID',
        'MERCHANT_KEY',
        'WEBSITE',
        'CHANNEL',
        'INDUSTRY_TYPE',
        'SALESWALLETGUID',
        'RAZORPAY_KEY',
        'RAZORPAY_SECRET',
        'choosepaymentgateway',
        'paymentgateway',
        'new_reg_point',
        'social_reg',
        'no_free_ads_point_per_month',
        'no_free_ads_post_per_month',
        'no_feature_ads_post_per_month',
        'minimum_ojaak_point_use_payment'
    ];


    
    
}
