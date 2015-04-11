<html>
    
<head>
    <?php 
        echo $this->Html->charset(); 
    ?>

<style type="text/css">
.voucher {padding:8px; border:1px solid #e1e1e1;background-color:#f9f9f9;font-family: TimeNewRoman; }
.voucher-01 {position:relative;background:url(./img/bg-voucher.png) no-repeat center;width:716px;height:326px;overflow:hidden; }
.voucher h3 {font-size:22px; text-align:center; color:#000;margin-top:83px; margin-bottom:0px;}
.voucher h3 span {color:#70b700;}
.voucher p {color:#FFF; text-align:center}
.voucher p.day { font-size:15px; color:#000;margin:10px 0 0 0}
.voucher p.day span {color:#0099cc}
.communication-voucher {background:url(./img/bg-voucher-01.png) no-repeat center; width:424px; height:90px;margin-left: 159px; position:relative;margin-top:18px}
.communication-voucher span { font-size:18px; color:#fff; text-align:left; height:25px; position:absolute}
.communication-voucher span.name {margin-top: 10px; margin-left:203px;}
.communication-voucher span.phone {margin-top: 37px; margin-left:203px;}
.communication-voucher span.ms {margin-top: 59px; margin-left:203px;}
.voucher p.note { text-align:left; padding: 3px 0 12px 60px; background:url(./img/bg-shared.png) no-repeat -881px -2529px; font-size:12px; color:#fffe96}
.qr-code {position:absolute; top:165px; right:58px;}
.qr-code img {width:95px; height:87px;}

span.promotion { 
    position:absolute; top: 127px; left:128px; width:142px; 
    background:url(./img/bg_saleoff_big.png) no-repeat center; 
    font-size:29px; 
    text-align:center;
    display:block; padding:60px 0;
    color:#000;
}

.communication-voucher-01 { position:absolute; top:257px; left:20px; width: 100%;}
.communication-voucher-01 .communication-left{position: absolute; left:0px; width: 40%; text-align: left;}
.communication-voucher-01 .communication-left p {font-size:12px; color:#000; font-weight:bold; line-height:19px;margin:0px}
.communication-voucher-01 .communication-left p span {color:#008cc7}
.communication-voucher-01 .communication-right {position: absolute; left:320px; width:55%;}
.communication-voucher-01 .communication-right span{font-size:11px; color:#000; line-height:17px;margin:0px; text-align:left;font-style: italic;font-weight:bold;}


.voucher .login-subscription ul li label {color:#FFF; width:98px;color:#FFF}
p.file-in {position:absolute; bottom:18px; right:15px}
a.file { display:block; width:50px; height:44px;background:url(./img/bg-shared.png) no-repeat right -2575px;float:left; margin-right:10px; }
a.in { display:block; width:45px; height:44px;background:url(./img/bg-shared.png) no-repeat right -2651px;float:left }

</style>
</head>
<body>
    <div id="fancybox-voucher" class="voucher">
        <div class="voucher-01">
            <h3><span> <?php echo $daily_coupon_title; ?></span></h3>
            <!--<p class="day"><?php echo $daily_coupon_summary; ?></p>-->
            <div class="communication-voucher">
                <span class="name"><?php echo $users_name; ?></span>
                <span class="phone"><?php echo isset($default_user_mobile) ? $default_user_mobile : ''; ?></span>
                <span class="ms"><?php echo $voucher_code; ?></span>
            </div>
            <span style="color:#fff;" class="promotion"><?php echo $daily_coupon_discount; ?></span>
            <div class="communication-voucher-01">
                <div class="communication-left">
                    <p>Áp dụng: từ ngày <span><?php echo $daily_coupon_start ?></span> đến ngày<span><?php echo $daily_coupon_end; ?></span></p>
                    <p>Địa điểm: <?php echo $daily_coupon_address; ?></p>
                </div>
                <div class="communication-right">
                    <span>* Vui lòng xuất trình Voucher hoặc Mã sô tại địa điểm Khuyến Mãi để nhận những ưu đãi đặc biệt dành cho thành viên thamgia.net</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


