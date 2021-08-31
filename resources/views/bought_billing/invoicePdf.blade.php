<!DOCTYPE html>
<html>
<head>
    <title>Ojaak Billing</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
       @page { margin: 0px; }
        body { margin: 0px; }
        html { margin: 0px} 
        /*Font family*/
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap');
        /**/
        table, th, td, h3, h4, h5, h6, span, strong, p { font-family: 'Open Sans', sans-serif; }
        .invoice_outer_wrap {width: 1050px; margin: 0 auto; padding: 5px; }
        .billing_logo_img_wrap {width: 250px; height: 90px; margin-left: 21px; }
        .billing_logo_img_wrap img {width: 100%; height: 100%; object-fit: cover; }
        .customer_info_wrap {width: 100%; }
        .tax_invoice_title_wrap td h3 {text-align: center; font-size: 22px; margin: 0; color: #f00; font-weight: 900; }
        .customer_info_wrap td {font-size: 14px; }
        .tax_invoice_title_inner_wrap {display: block; width: 171px; margin: 0 auto; }
        .tax_invoice_title_wrap h6 {margin: 0; background:#50aa50; color: #50aa50; line-height: 4px; font-size: 3px; }
        .total_amount_outer_wrap table {width: 98%; }
        .total_amount_outer_wrap h4 {font-size: 17px; font-weight: 900; color: #f00; margin: 0;}
        .billing_info_outer_wrap p i {font-weight: 900; }
        .billing_info_outer_wrap h4 {text-transform: uppercase; color: #f00; margin: 1px 0 8px; font-size: 17px; }
        .billing_info_outer_wrap p {margin-bottom: 9px; }
        .left_col_content_wrap span {color: #666; font-size: 13px; }
        td.billing_info_outer_wrap {width: 375px; }
        .right_col_content_wrap span {color: #000; font-weight: 600; font-size: 13px; }
        .total_amount_outer_wrap h5 {font-size: 17px; margin: 0; margin-bottom: 8px; font-weight: 900; color: #000; text-transform: capitalize; }
        .total_amount_outer_wrap h6 {font-size: 15px; margin: 0; margin-bottom: 8px; color: #777; text-transform: capitalize; }
        .billing_info_outer_wrap table, .estimate_right_col {width: 97%; }
        .bill_table_outer_wrap, .estimate_right_col{border-collapse: collapse; width: 100%; }
        .bill_table_outer_wrap { margin-top:15px;}
        .bill_table_outer_wrap th,.bill_table_outer_wrap td, .estimate_right_col th, .estimate_right_col td {border: 1px solid #000;}
        .bill_table_outer_wrap th {text-align: center; background-color: rgba(76, 175, 80, 0.65); color: #000; font-size: 12px; }
        .estimate_right_col thead td {  background-color: rgba(76, 175, 80, 0.65); color: #000; text-align: center; }
        .bill_table_outer_wrap th p {margin: 0; }
        .bill_table_outer_wrap td { text-align: center; }
        .bill_table_inner_wrap td {  word-wrap:break-word; overflow-wrap:break-word; font-size: 12px; }
        .bill_table_inner_wrap td span {   overflow-wrap:break-word; word-wrap: break-word }
        .estimate_left_col td { padding-bottom: 5px; text-transform: capitalize; }
        .estimate_left_col h5 {margin: 0; font-size: 13px; color: #666;  }
        .estimate_right_col tr strong {text-align: center; padding: 7px 0; }
        .estimate_right_col td {text-align: center; }
        .term_cond_left_col {width: 63%; }
        .term_cond_left_col h5 {margin: 0; font-size: 16px; padding-bottom: 9px; font-weight: 800; }
        .term_cond_left_col p {margin: 0; color: #666; padding-bottom: 10px;line-height: 20px;  font-size: 13px; }
        .term_cond_right_col h5 { font-size: 16px; margin-bottom: 0; border-bottom: 1px solid #444; width: 220px; margin-left: auto; margin-right: auto; padding-bottom: 15px; }
        .term_cond_right_col {text-align: center; }
        .term_cond_right_col p {margin-top: 10px; }
        .estimate_right_col td {border-top: none; }
        .estimate_left_col td strong {font-size: 13px; }
        td.border_line_wrap {    width: 396px; }
        td.customer_info_logo_wrap {width: 63%; }
        
    </style>
</head>
<body>
<div class="invoice_outer_wrap">
        <table style="width:100%;" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2" class="customer_info_logo_wrap">
                    <div class="billing_logo_img_wrap">
                        <img src="{{ asset('public/frontdesign/assets/img/ojaak_logo_non_trans.jpg') }}">
                    </div>
                </td>
                <td class="customer_info_outer_wrap">
                    <table class="customer_info_wrap">
                        <tr>
                            <td><strong>Customer Name</strong></td>
                            <td>{{$customername}}</td>
                        </tr>
                        <tr>
                            <td><strong>Customer GSTIN</strong></td>
                            <td>{{$gst}}</td>
                        </tr>
                        <!-- <tr>
                            <td><strong>Customer Number</strong></td>
                            <td>R_12335566</td>
                        </tr> -->
                    </table>
                </td>
            </tr>
        </table>
        <table style="width:100%;" cellpadding="0" cellspacing="0">
            <tr class="tax_invoice_title_wrap">
                <td class="border_line_wrap"><h6>hai</h6></td>
                <td class="tax_invoice_title_inner_wrap">
                    <h3>TAX INVOICE</h3>
                </td>
                <td class="border_line_wrap"><h6>hai</h6></td>
            </tr>
        </table>
        <table style="width:100%;" cellpadding="0" cellspacing="0">
            <tr>
                <td class="billing_info_outer_wrap">
                    <p><i>Bill To</i></p>
                    <h4>OKAAK CUSTOMER</h4>
                    <table>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Contact Person</span>
                            </td>
                            <td class="right_col_content_wrap">
                                <span>{{$customername}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Address</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$addr1}} {{$addr2}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>City</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$city}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>State</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$state}}</span>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td class="left_col_content_wrap">
                                <span>State code</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>06</span>
                            </td>
                        </tr> -->
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Country</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>India</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>GSTIN</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$gst}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Email</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$email}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="billing_info_outer_wrap">
                    <p><i>Ship To</i></p>
                    <h4>OKAAK CUSTOMER</h4>
                    <table>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Contact Person</span>
                            </td>
                            <td class="right_col_content_wrap">
                                <span>{{$customername}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Address</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$addr1}} {{$addr2}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>City</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$city}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>State</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$state}}</span>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td class="left_col_content_wrap">
                                <span>State code</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>06</span>
                            </td>
                        </tr> -->
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Country</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>India</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>GSTIN</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$gst}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="left_col_content_wrap">
                                <span>Email</span>
                            </td>
                            <td  class="right_col_content_wrap">
                                <span>{{$email}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="total_amount_outer_wrap">
                    <table>
                        <tr>
                            <td>
                                <h5>place of supply</h5>
                                <h6>Chennai</h6>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Invoice Number</h5>
                                <h6>{{$invoiceid}}</h6>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Invoice Date</h5>
                                <h6>{{$created_at}}</h6>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <h4>Total Amount : <span>{{$payment}}.00</span></h4>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="bill_table_outer_wrap">
            <thead>
            <tr>
                <th rowspan="2"><span>SI.No</span></th>
                <th rowspan="2"><span>HSN</span></th>
                <th style="width: 100px;" rowspan="2"><span>Description</span></th>
                <th rowspan="2"><span>Qty</span></th>
                <th rowspan="2"><span>Rate(Excl. GST)</span></th>
                <th rowspan="2"><span>Discount</span></th>
                <th rowspan="2"><span>Taxable</span><p>Value</p></th>
                <th colspan="2" scope="colgroup"><span>CGST</span></th>
                <th colspan="2" scope="colgroup"><span>SGST</span></th>
                <th rowspan="2"><span>Invoice Amount</span></th>
            </tr>
            <tr>
                <th scope="col">%</th>
                <th scope="col">Amt</th>
                <th scope="col">%</th>
                <th scope="col">Amt</th>
            </tr>
        </thead>
            <tr class="bill_table_inner_wrap">
                <td><span>1</span></td>
                <td><span>9983</span></td>
                <td><span>{{$information}}</span></td>
                <td><span>{{$ads_limit}}</span></td>
                <td><span>{{$paymentGSTdeduct}}</span></td>
                <td><span>0.0</span></td>
                <td><span>{{$paymentGSTdeduct}}</span></td>
                <td><span>9%</span></td>
                <td><span>{{$gstAmountValue}}</span></td>
                <td><span>9%</span></td>
                <td><span>{{$gstAmountValue}}</span></td>
                <td><span>{{$paymentGSTdeduct}}</span></td>
            </tr>
        </table>
        <table style="width:100%;" cellpadding="0" cellspacing="0">
            <tr>
                <td class="estimate_outer_wrap" colspan="2">
                    <table class="estimate_left_col" style="
    width: 100%;">
                        <tr>
                            <td style="width: 160px;">
                                <h5>Total in words</h5>
                            </td>
                            <td><strong>{{$totalinvoiceamut}}</strong></td>
                            
                        </tr>
                    </table>
                </td>
                <td class="estimate_outer_wrap" style="width: 294px;">
                    <table class="estimate_right_col">
                        <thead>
                            <tr>
                                <td>CGST Amount</td>
                                <td>{{$gstAmountValue}}</td>
                            </tr>
                            <tr>
                                <td>SGST Amount</td>
                                <td>{{$gstAmountValue}}</td>
                            </tr>
                            <tr>
                                <td>Transaction Value</td>
                                <td>{{$paymentGSTdeduct}}</td>
                            </tr>
                        </thead>
                        <tr>
                            <td><strong>Grand Total</strong></td>
                            <td><strong>INR <span>{{$payment}}.0</span></strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width:100%; margin-top: 10px;">
            <tr>
                <td class="term_cond_left_col" colspan="2">
                    <h5>Terms And Conditions</h5>
                    <p>OJAAK is India's classified app to buy / sell products. We passionately believe that our marketplace should provide profit for the active customers when they are ready to spend their quality time with us. Since 2019 OJAAK provides the virtual marketplace to connect the trusted buyers and sellers.</p>    
                </td>
                <td class="term_cond_right_col">
                    <!-- <h5>Customer</h5> -->
                    <p>-(This is computer generated invoice no signature required)</p>
                </td>
            </tr>
            <tr>
                <td>
                    <h6 style="margin:0; font-size: 15px;">
                        <strong>Tax Payable Under Reverse Charge : </strong>
                        <span style="color: #666">NO</span>
                    </h6>
                </td>
            </tr>
        </table>
        <table style="width:100%;  margin-top: 15px;">
            <tr>
                <td style="color:#f00; border-bottom:1px solid #000;width: 100%;font-size: 13px;
                font-weight: 700;">Thank you for your business</td>
            </tr>
        </table>
    </div>
</body>
</html>