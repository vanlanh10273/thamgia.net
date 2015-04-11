<?php 
    echo $this->Html->script('jquery-1.7.1.min');
    echo $this->Html->script('cufon-yui');
    echo $this->Html->script('UTM_Nokia_StandardB_700.font');
    echo $this->Html->script('UTM_NOKIA_STANDARD_400.font');
?>
<style type="text/css">
.voucher {padding:8px; border:1px solid #e1e1e1;background-color:#f9f9f9 }
.voucher-01 {position:relative;background:url(<?php echo $this->Html->url('/'); ?>img/bg-voucher.png) no-repeat center;width:716px;height:326px;overflow:hidden; }
.voucher h3 {font-size:22px; text-align:center; color:#000;margin-top:83px; margin-bottom:0px;}
.voucher h3 span {color:#70b700;}
.voucher p {color:#FFF; text-align:center}
.voucher p.day { font-size:15px; color:#000;margin:10px 0 0 0}
.voucher p.day span {color:#0099cc}
.communication-voucher {background:url(<?php echo $this->Html->url('/'); ?>img/bg-voucher-01.png) no-repeat center; width:424px; height:90px;margin-left: 159px; position:relative;margin-top:0px;}
.communication-voucher span { font-size:18px; color:#fff; text-align:left; height:25px; position:absolute}
.communication-voucher span.name {top: 10px; left:203px;}
.communication-voucher span.phone {top: 37px; left:203px;}
.communication-voucher span.ms {top: 59px; left:203px;}
.voucher p.note { text-align:left; padding: 3px 0 12px 60px; background:url(<?php echo $this->Html->url('/'); ?>img/bg-shared.png) no-repeat -881px -2529px; font-size:12px; color:#fffe96}
.qr-code {position:absolute; top:165px; right:58px;}
.qr-code img {width:95px; height:87px;}

.communication-voucher-01 {overflow:hidden; position:absolute;left:20px; bottom:23px}
.communication-voucher-01 .communication-left{float:left}
.communication-voucher-01 .communication-left p {font-size:12px; color:#000; font-weight:bold; line-height:19px;margin:0px}
.communication-voucher-01 .communication-left p span {color:#008cc7}
.communication-voucher-01 .communication-right {float:right;width:363px;margin-left:20px;}
.communication-voucher-01 .communication-right span{font-size:11px; color:#000; line-height:17px;margin:0px; text-align:left;font-style: italic;font-weight:bold;}
.voucher-02 {overflow:hidden;margin-top:10px}
.voucher-02 a {float:left}
.voucher-02 a.download {width:161px; height:35px; display:block;background:url(<?php echo $this->Html->url('/'); ?>img/download.png) no-repeat; margin-left:205px;margin-right:10px}
.voucher-02 a.download:hover {background:url(<?php echo $this->Html->url('/'); ?>img/download-hover.png) no-repeat;}
.voucher-02 a.in {width:161px; height:35px; display:block;background:url(<?php echo $this->Html->url('/'); ?>img/in.png) no-repeat;}
.voucher-02 a.in:hover {background:url(<?php echo $this->Html->url('/'); ?>img/in-hover.png) no-repeat;}


.voucher .login-subscription ul li label {color:#FFF; width:98px;color:#FFF}
p.file-in {position:absolute; bottom:18px; right:15px}
a.file { display:block; width:50px; height:44px;background:url(<?php echo $this->Html->url('/'); ?>img/bg-shared.png) no-repeat right -2575px;float:left; margin-right:10px; }
a.in { display:block; width:45px; height:44px;background:url(<?php echo $this->Html->url('/'); ?>img/bg-shared.png) no-repeat right -2651px;float:left;margin-left: 292px;}
span.promotion { position:absolute;top:132px; left:128px; width:142px; background:url(<?php echo $this->Html->url('/'); ?>img/bg_saleoff_big.png) no-repeat center; font-size:29px; text-align:center;display:block; padding:60px 0;color:#FFF}

</style>


<div class="voucher">
    <div class="voucher-01">
        <h3><span>DAILY COUPON</span> - <?php echo $daily_coupon_title; ?></h3>
        <p class="day"><?php echo $daily_coupon_summary;?></p>
        <div class="communication-voucher">
            <span class="name"><?php echo $users_name; ?></span>
            <span class="phone"><?php echo isset($default_user_mobile) ? $default_user_mobile : ''; ?></span>
            <span class="ms"><?php echo $voucher_code ?></span>
        </div>
        <span class="promotion"><?php echo $daily_coupon_discount ?></span>
        <div class="communication-voucher-01">
            <div class="communication-left">
                <p>Áp dụng: từ ngày <span><?php echo $daily_coupon_start ?></span> đến ngày<span><?php echo $daily_coupon_end ?></span></p>
                <p><?php echo $daily_coupon_address ?></p>
            </div>
            <div class="communication-right">
                <span>* Vui lòng xuất trình Voucher hoặc Mã sô tại địa điểm Khuyến Mãi để nhận những ưu đãi đặc biệt dành cho thành viên thamgia.net</span>
            </div>
            
        </div>
    </div>
</div>

<script>
  $(document).ready(function(){
        Cufon.replace('.voucher h3,.voucher p.day span,span.promotion,.voucher',{
            fontFamily: 'UTM Nokia StandardB'
        });
        window.print();
  });
</script>