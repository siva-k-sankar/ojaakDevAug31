<!DOCTYPE html>
<!-- saved from url=(0051)https://www.sparksuite.com/open-source/invoice.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>OJAAK Invoice</title>
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/frontdesign/assets/img/favicon-32x32.png') }}">
<style>
	
	body{
		font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
		text-align:center;
		color:#777;
	}
	
	body h1{
		font-weight:300;
		margin-bottom:0px;
		padding-bottom:0px;
		color:#000;
	}
	
	body h3{
		font-weight:300;
		margin-top:10px;
		margin-bottom:20px;
		font-style:italic;
		color:#555;
	}
	
	body a{
		color:#06F;
	}
	
	.invoice-box{
		max-width:800px;
		margin:auto;
		padding:30px;
		border:1px solid #eee;
		box-shadow:0 0 10px rgba(0, 0, 0, .15);
		font-size:16px;
		line-height:24px;
		font-family:'Helvetica Neue', 'Helvetica', 'Helvetica', 'Arial', 'sans-serif';
		color:#555;
	}
	
	.invoice-box table{
		width:100%;
		line-height:inherit;
		text-align:left;
	}
	
	.invoice-box table td{
		padding:5px;
		vertical-align:top;
	}
	
	.invoice-box table tr td:nth-child(2){
		text-align:right;
	}
	
	.invoice-box table tr.top table td{
		padding-bottom:20px;
	}
	
	.invoice-box table tr.top table td.title{
		font-size:45px;
		line-height:45px;
		color:#333;
	}
	
	.invoice-box table tr.information table td{
		padding-bottom:40px;
	}
	
	.invoice-box table tr.heading td{
		background:#eee;
		border-bottom:1px solid #ddd;
		font-weight:bold;
	}
	
	.invoice-box table tr.details td{
		padding-bottom:20px;
	}
	
	.invoice-box table tr.item td{
		border-bottom:1px solid #eee;
	}
	
	.invoice-box table tr.item.last td{
		border-bottom:none;
	}
	
	.invoice-box table tr.total td:nth-child(2){
		border-top:2px solid #eee;
		font-weight:bold;
	}
	
	@media only screen and (max-width: 600px) {
		.invoice-box table tr.top table td{
			width:100%;
			display:block;
			text-align:center;
		}
		
		.invoice-box table tr.information table td{
			width:100%;
			display:block;
			text-align:center;
		}
	}
	</style>
</head>
<body>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr class="top">
					<td colspan="2">
						<table>
						<tbody>
							<tr>
								<td class="title">
									<img src="{{ asset('public/frontdesign/assets/img/ojaak_logo.png') }}" style="width:150px;">
								</td>
								<td>
								Invoice #: {{$invoiceid}}<br>
								Created: {{$created_at}}<br>
								Due: {{$expire}}<br>
								GST NO: {{$gst}}
								</td>
							</tr>
						</tbody>
						</table>
					</td>
				</tr>
				<tr class="details">
					<td colspan="2">
						<table>
							<tbody>
								<tr>
									<td>
									{{$businessname}}.<br>
									{{$addr1}}<br>
									{{$addr2}}<br>
									{{$city}}<br>
									{{$state}}<br>
									</td>
									<td>
									{{$customername}}<br>
									{{$email}}<br>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr class="heading">
					<td> Item </td>
					<td> Price </td>
				</tr>
				
				<tr class="item last">
					<td> {{$information}} </td>
					<td style="font-family: 'DejaVu Sans','sans-serif';"> &#8377; {{$payment}} </td>
				</tr>
				<tr class="total">
					<td></td>
					<td style="font-family: 'DejaVu Sans','sans-serif';"> Total: &#8377; {{$payment}} </td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>