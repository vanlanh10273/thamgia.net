<script type="text/javascript">
    $(document).ready(function(){
        $("#frm_login").validationEngine();
    });
</script>
<div class="content-event">
    <div class="content-titel">
        <ul class="menu-content">
            <li class="last">
                <a class="active" href="#">Đổi mật khẩu</a>
            </li>
        </ul>
    </div>
    <div class="login-successful">
        <form id="frm_login" name="frm_login" method="post" action="<?php echo $this->Html->url(array("controller" => "Users", "action" => "changePassword")) ?>" class="login-subscription">
            <fieldset>
                <ul >
                    <li class="general">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="data[User][current_password] title="" value="" id="is_current_password" class="validate[required] text"/>
                    </li>
                    <li class="general">
                        <label>Mật khẩu</label>
                        <input type="password" name="data[User][password]" title="" value="" id="password" class="validate[required] text"/>
                    </li>
                    <li class="general">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="data[User][password2]" title="" value="" id="password2" class="validate[required,equals[password]] text"/>
                    </li>
                    <li >
                        <button class="button-update" title="Đăng Nhập" type="submit"><span>Đổi mật khẩu</span></button>
                    </li>
                </ul>
            </fieldset>
        </form>
    </div>
</div>