<script type="text/javascript">
    $(document).ready(function(){
        $("#frm_login").validationEngine();
    });
</script>
<div class="content-event">
    <div class="content-titel">
        <ul class="menu-content">
            <li class="last">
                <a class="active" href="#">Quên mật khẩu</a>
            </li>
        </ul>
    </div>
    
    <div class="login-successful">
        <div class="info">
            Vui lòng nhập địa chỉ email bạn đã đăng ký tại thamgia.net. Hệ thống sẽ gửi một email xác nhận yêu cầu Thiết lập lại mật khẩu vào email này
        </div>
        <form id="frm_login" name="frm_login" method="post" action="<?php echo $this->Html->url(array("controller" => "users", "action" => "forgotPassword")) ?>" class="login-subscription">
            <fieldset>
                <ul class="successful">
                    <li>
                        <label>Email</label>
                        <input type="text" name="data[User][email] title="" value="" id="is_Email1" class="validate[required] text"/>
                    </li>
                    <li class="button">
                        <button class="forgot-password" title="Đăng Nhập" type="submit"><span>Đăng Nhập</span></button>
                    </li>
                </ul>
            </fieldset>
        </form>
    </div>
</div>