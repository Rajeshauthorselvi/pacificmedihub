<head>
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
<div class="container">
    <div class="header-logo"><img src="{{ asset('theme/images/logo_mtcu.png') }}"></div>
    <h3>MTC U TRADING</h3>
    <div class="mail-content">
        Your account password rest Code has been in the following:<br/>
        
        <strong>Your Password Rest Code:</strong> <?php echo $code; ?> <br/><br/>

        Questions? Need help getting set up? Simply send an email <b>admin@mtcu.com</b><br/>
    		<p>- Team MTC U TRADING</p>    
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
        <div class="cpy-ryt">&#169;{{date('Y')}}&nbsp;<b>MTC U TRADING</b>. All Rights Reserved.</div>
    </footer>
</div>