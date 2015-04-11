<!-- File: /app/View/Signup/account.ctp -->
<?php
   if (empty($email))
     $email = '';
    
   $hasError = true;   
   if (empty ($error))
     $hasError = false;

?>
<script>
  $(document).ready(function(){
    $("#frm_account").validationEngine();
  });
</script>
<h2 class="titel">ĐĂNG KÝ THÀNH VIÊN</h2>
<?php if ($hasError){ ?>
<div class="error"><?php echo $error ?></div>
<?php
} ?>
<div class="registered-users-left">
    <div class="login-successful">
        <form id="frm_account" name="frm_account" method="post" action="<?php echo $this->Html->url('/') ?>signup/account" class="login-subscription">
            <fieldset>
                <ul class="successful">
                    <li>
                        <label>Email</label>
                        <input type="text" name="data[Account][email]" title="" value="<?php echo $email ?>" id="is_Email1" class="validate[required,custom[email]] text"/>
                    </li> 
                    <li class="general">
                        <label>Mật khẩu</label>
                        <input type="password" name="data[Account][password]" title="" value="" id="password" class="validate[required,minSize[6]] text"/>
                        <span>(Mật khẩu phải ít nhất 6 ký tự)</span>
                    </li>
                    <li class="general">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="password2" title="" value="" id="password2" class="validate[required,equals[password]] text"/>
                    </li>   
                    <li class="confirmation">
                        <!--<img src="<?php echo $this->Html->url('/') ?>files/captcha/generate.php" />-->
                        <img src="<?php echo $this->Html->url(array('controller' => 'Signup', 'action'=>'captcha_image')); ?>" />
                        <input type="text" name="data[Account][captcha_code]" title="" value="" id="is_securimage" class="validate[required] text"/> 
                        <input type="checkbox" checked="checked" name="is_free" title="" value="" id="is_free" class="validate[required] checkbox"/>
                        <label>Tôi đã đọc và đồng ý với  <a target="_blank" href="<?php echo $this->Html->url(array('controller' => 'Home', 'action' => 'terms' )); ?>" style="color: black; font-weight: bolder;"> các điều khoản của  thamgia.net</a></label>
                    </li>
                    <li class="button">
                        <button class="button-1" title="Tham Gia" type="submit"><span>Tham Gia</span></button>
                        <button class="button-2" title="Tham Gia" onclick="$('#frm_account')[0].reset(); return false;" type="submit"><span>Hoàn tất</span></button>
                    </li>
                </ul>    
            </fieldset>   
        </form>
    </div>
</div>
<div class="registered-users-right">
    <ul>
        <li class="active">
            <p><span>1</span>Thông tin tài khoản</p>
            <div class="border-left-01"></div>
        </li>
        <li>
            <p><span>2</span>Thông tin cá nhân</p>
            <div class="border-left-01"></div>
        </li>
        <li>
            <p><span>3</span>Hoàn tất</p>
            <div class="border-left-02"></div>
        </li>
        
    </ul>
</div>