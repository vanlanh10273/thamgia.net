<script type="text/javascript">
    $(document).ready(function(){
        $("#frm_login").validationEngine();
    });
</script>
<div class="content-event">
    <div class="content-titel">
        <ul class="menu-content">
            <li class="last">
                <a class="active" href="#">Đăng Nhập</a>
            </li>
        </ul>
    </div>
    <div class="login-successful">
        <form id="frm_login" name="frm_login" method="post" action="<?php echo $this->Html->url(array("controller" => "users", "action" => "login")) ?>" class="login-subscription">
            <fieldset>
                <ul class="successful">
                    <li>
                        <label>Email</label>
                        <input type="text" name="data[User][email] title="" value="" id="is_Email1" class="validate[required] text"/>
                    </li>
                    <li class="general">
                        <label>Mật khẩu</label>
                        <input type="password" name="data[User][password]" title="" value="" id="is_password2" class="validate[required] text"/>
                    </li>
                    <li class="button">
                        <button class="login" title="Đăng Nhập" type="submit"><span>Đăng Nhập</span></button>
                        <a style="margin-top:7px;" href="javascript:" onclick="loginFacebook()" class="facebook">facebook</a>
                    </li>
                </ul>
            </fieldset>
        </form>
    </div>
</div>