<script type="text/javascript">
    function show_hide_voucher() {
        if(document.getElementById("full").style.display!='block') {
            document.getElementById("full").style.display = 'block';
            document.getElementById("link").innerHTML = '';
            document.getElementById("link").className = 'close';
        }
        else {
            document.getElementById("full").style.display = 'none';
            document.getElementById("link").innerHTML = '';
            document.getElementById("link").className = 'open';
        }
        return false;
    }
</script>
<?php $dataVoucher =  $this->requestAction(array('controller' => 'Events', 'action' => 'getVoucherInformation', $event_id)); ?>
<div class="block-events-00">
    <h3 class="titel">Voucher</h3>
    <div id="full">
        <div class="voucher">
            <div class="voucher-01">
                <h3><span>DAILY COUPON</span> - <?php echo $dataVoucher['title']; ?></h3>
                <p class="day"><?php echo $dataVoucher['summary']; ?></p>
                <div class="communication-voucher">
                    <span class="name"><?php echo $users_name; ?></span>
                    <span class="phone"><?php echo isset($default_user_mobile) ? $default_user_mobile : ''; ?></span>
                    <span class="ms"><?php echo $dataVoucher['code']; ?></span>
                </div>
                <span class="promotion"><?php echo $dataVoucher['fee']; ?></span>
                <div class="communication-voucher-01">
                    <div class="communication-left">
                        <p>Áp dụng: từ ngày <span><?php echo $dataVoucher['start'] ?></span> đến ngày<span><?php echo $dataVoucher['end']; ?></span></p>
                        <p>Địa điểm: <?php echo $dataVoucher['address']; ?></p>
                    </div>
                    <div class="communication-right">
                        <span>* Vui lòng xuất trình Voucher hoặc Mã sô tại địa điểm Khuyến Mãi để nhận những ưu đãi đặc biệt dành cho thành viên thamgia.net</span>
                    </div>
                </div>
            </div>
            <div class="voucher-02">
                <a href="<?php echo $this->Html->url(array('action'=>'getPdfVoucher', 'controller'=>'Events', 'ext'=>'pdf', $dataVoucher['id'] )); ?>" target="_blank" class="download"></a>
                <a href="<?php echo $this->Html->url(array('action'=>'printVoucher', 'controller'=>'Events', $dataVoucher['id'] )); ?>" target="_blank" class="in" ></a>
            </div>
        </div>
    </div>
    <div class="view-more see-more">
        <span></span><a id="link" onclick="return show_hide_voucher()" href="#" class="open"></a>
    </div>
</div>