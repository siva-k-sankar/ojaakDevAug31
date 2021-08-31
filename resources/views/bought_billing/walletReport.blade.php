<!DOCTYPE html>
<html>
<head>
	<title>Ojaak Billing</title>
	<meta charset="utf-8">
	<style type="text/css">
		@page { margin: 0px; }
        body { margin: 0px; }
        html { margin: 0px} 
		/*Font family*/
		@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap');
		/**/
		table, th, td, h3, h4, h5, h6, span, strong, p { font-family: 'Open Sans', sans-serif; }
		.invoice_outer_wrap {width: 800px; margin: 0 auto; padding: 0px 0px; }
		.billing_logo_img_wrap {width: 220px; height: 90px; margin-left: 21px; }
		.billing_logo_img_wrap img {width: 100%; height: 100%; object-fit: contain; }
		.customer_info_wrap {width: 100%; }
        td.customer_info_logo_wrap {width: 63%; }
        table.customer_info_wrap td h5 {text-align: right; font-size:15px ;margin: 0; margin-bottom:3px; }
        table.table_header_bg {background: rgb(81 170 80 / 18%); padding: 5px 30px 10px 10px; }
        td.tax_invoice_title_inner_wrap h3 {text-transform: capitalize; padding: 10px 35px; font-size: 17px; }
        td.tax_invoice_title_inner_wrap h3 span {color: #888; padding-right: 35px; }
        td.opening_balance {border: 1px solid rgb(0 0 0 / 25%); width: 26%; padding: 25px; }
        td.opening_center_table_wrap table {width: 100%; padding: 0 !important; }
        td.opening_center_table_wrap table tr td {width: 50%; border: 1px solid rgb(0 0 0 / 25%); padding: 15px 20px; }
        td.opening_balance h4 {font-size: 16px; margin: 0; font-weight: 600; }
        td.opening_balance p {margin-top: 4px; font-size: 14px; text-transform: capitalize; }
        td.opening_balance span {font-size: 14px; color: #888; }
        td.opening_center_table_wrap h4 {margin: 0; }
        td.opening_center_table_wrap span {font-size: 14px; color: #888; text-transform: capitalize; }
        .closing_balance {background: #2ccf2a; }
        .closing_balance h4, .closing_balance p, .closing_balance span { color: #fff !important; }
        tr.border_line_wrap td h4 {font-size: 0; height: 7px; background: #51aa50; margin-top: 40px; }
        table.balance_table_wrap {margin: 0 auto !important; }
        table.transaction_table_wrap {margin:0 auto 45px; padding-top: 15px; }
        table.transaction_table_wrap thead tr th {border-bottom: 1px solid rgb(0 0 0 / 13%); padding-bottom: 12px; }
        table.transaction_table_wrap td {border-bottom: 1px solid rgb(0 0 0 / 11%); padding-bottom: 13px; }
        .left_align_head {text-align: left; }
        .center_align_head {text-align: center; }
        .right_align_head {text-align: end; }
        td.available_bala_wrap {text-align: end; color: #555; }
        table.transaction_table_wrap td h5 {font-size: 15px; margin: 8px 0px 0px; text-transform: uppercase; }
        table.transaction_table_wrap td span {font-size: 13px; color: #888; }


</style>
</head>
<body>
	<div class="invoice_outer_wrap">
		<table style="width:100%;" class="table_header_bg" >
            <tr>
                <td colspan="2" class="customer_info_logo_wrap">
                	<div class="billing_logo_img_wrap">
                    	<img src="{{ asset('public/frontdesign/assets/img/ojaak_logo_non_trans.jpg') }}">
                    </div>
                </td>
                <td class="customer_info_outer_wrap">
                    <table class="customer_info_wrap">
                    	<tr>
                    		<td style="font-size:18px; font-weight:600;text-transform:uppercase;"><h5>{{$wallet->email}}</h5></td>
                    	</tr>
                    	<tr>
                    		<td style="font-size:14px; font-weight:400;"><h5>{{$wallet->phone_no}}</h5></td>
                    	</tr>
                    	<tr>
                    		<td style="font-size:14px; font-weight:400;"><h5>{{$wallet->email}}</h5></td>
                    	</tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width:100%;" >
        	<tr class="tax_invoice_title_wrap">
            	<td class="tax_invoice_title_inner_wrap">
                    <h3><span>Wallet statement for</span>{{date('01',strtotime('last month'))}} {{date("F", strtotime("-1 months"))}} {{date('Y')}} to {{date('t',strtotime('last month'))}} {{date("F", strtotime("-1 months"))}} {{date('Y')}}</h3>
            	</td>
            </tr>
        </table>
        <table style="width:100%;" >
            <tr class="border_line_wrap">
                <td><h4>hai</h4></td>
            </tr>
        </table>
        <table class="transaction_table_wrap" style="width:80% !important;" >
                <tr>
                    <td class="left_align_head"><strong>Date & Time</strong></td>
                    <td class="left_align_head"><strong>Transaction Details</strong></td>
                    <td class="center_align_head"><strong>Amount</strong></td>
                </tr>
                @if(!empty($walletdatass))
                    @foreach($walletdatass as $walletdatas)
                    <tr>
                        <td>
                            <h5>{{date("d M Y",strtotime($walletdatas->created_at))}}</h5>
                            <span>{{date("h A",strtotime($walletdatas->created_at))}}</span>
                        </td>
                        <td>
                            <h5>{{$walletdatas->description}}</h5>
                            <span>Transaction ID : {{$walletdatas->order_id}}</span>
                        </td>
                        <td class="center_align_head">
                            <h5>Rs.{{$walletdatas->point}}</h5>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>