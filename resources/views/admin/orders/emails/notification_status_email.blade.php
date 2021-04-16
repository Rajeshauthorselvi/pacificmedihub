<!DOCTYPE html>
<html>
<head>
	<title>
     {{isset($order_details->statusName->subject)?$order_details->statusName->subject:'Status Notification'}}   
    </title>
<style type="text/css">
    @font-face {font-family:'SourceSansPro';src: url("{{asset('fonts/SourceSansPro-Regular.otf')}}") format('otf');font-style: 'normal';}
    body{font-family: 'SourceSansPro', sans-serif;}
    .container {width: 80%;margin: auto;}
    .header-logo {text-align: center;}
    .container h3 {background: #eee;padding: 20px 0 20px 20px;text-align: center;}
    .mail-content {padding-left: 30px;}
    .mail-content p, .unsub span {color: #3e72b1;}
    footer {margin-top: 50px;text-align: center;background: #eee;padding: 25px 0;}
    .footer-social {list-style: none;padding: 0;display: inline-flex;}
    .footer-social li {padding: 0 10px;}
    #store_images {display: inline-flex;}
    #store_images .images {padding: 0 5px;}
</style>
</head>
<body>
<div class="container">
    <div class="header-logo"><img src="{{ asset('theme/images/logo_mtcu.png') }}"></div>
    <h3>Pacific Medihub</h3>
    <div class="mail-content">
        @if (isset($order_details->statusName->email_content))
            {!!  str_replace("~order_no~",'<b>'.$order_details->order_no.'</b>',$order_details->statusName->email_content)  !!}
        @else
            <div>Your order status has been changed to <b>{{ $order_details->statusName->status_name }}</b></div>
        @endif
         <br/>
         <br/>
        Questions? Need help getting set up? Simply send an email <b>admin@medihub.com</b><br/>
    		<p>- Team Pacific Medihub</p>    
    </div>
    <footer>
        <div class="social-links">
            <ul class="footer-social">
                <li><a href="javascript:void(0);"><img src="{{ asset('theme/images/social/fb-b.png') }}"></a></li>
                <li><a href="javascript:void(0);"><img src="{{ asset('theme/images/social/tw-b.png') }}"></a></li>
                <li><a href="javascript:void(0);"><img src="{{ asset('theme/images/social/in-b.png') }}"></a></li>
                <li><a href="javascript:void(0);"><img src="{{ asset('theme/images/social/yt-b.png') }}"></a></li>
            </ul>
        </div>
        <div class="cpy-ryt">&#169;{{date('Y')}}&nbsp;<b>Pacific Medihub</b>. All Rights Reserved.</div>
    </footer>
</div>
</body>
</html>

<?php //exit(); ?>