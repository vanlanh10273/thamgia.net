<?php echo $this->Html->script('jquery.charsleft-0.1.js'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fullname").charsLeft({
            maxChars: 50,
            attachment: "after",
            charPrefix: "(Còn lại",
            charSuffix: "ký tự)"
        });
        $("#frm_profile").validationEngine();
    });
</script>
<div class="content-event">
    <div class="content-titel">
        <h2 class="titel">Thông tin cá nhân</h2>
    </div>
    <div class="subscription">
        <?php if (isset($error)){?>
        <div class="error"><?php echo $error ?></div>
        <?php } ?>
        <form id="frm_profile" name="frm_profile" method="post" action="<?php echo $this->Html->url(array('controller' =>'Users', 'action' => 'editProfile'))?>" class="login-subscription" enctype="multipart/form-data">
            <fieldset>
                <ul>
                    <li>
                        <label>Họ và tên </label>
                        <input type="text" name="data[User][fullname]" title="" value="<?php echo isset($data['User']['fullname']) ? $data['User']['fullname'] : ''; ?>" id="fullname" class="validate[required] text"/>
                    </li>
                    <li>
                        <label>Đến từ</label>
                        <div class="select-1">
                            <select id="city" name="data[User][cityid]">
                                <?php foreach ($cities as $city) {?>
                                <option value="<?php echo $city['City']['id']; ?>"  <?php if ($city['City']['id'] == $data['User']['cityid']) echo 'selected="selected"'; ?>">
                                    <?php echo $city['City']['name']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <label>Lĩnh vực</label>
                        <div class="select-1">
                            <select id="city" name="data[User][careerid]">
                                <?php foreach ($careers as $career) {?>
                                <option value="<?php echo $career['Career']['id']; ?>"  <?php if ($career['Career']['id'] == $data['User']['careerid']) echo 'selected="selected"'; ?>">
                                    <?php echo $career['Career']['name']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <label>Email</label>
                        <div class="select-1">
                            <input type="text" name="data[User][email]" title="" value="<?php echo isset($data['User']['email']) ? $data['User']['email'] : ''; ?>" id="title" readonly="readonly" class="text"/>
                        </div>
                    </li>
                    <li>
                        <label>Avatar</label>
                        <div class="information-users-left">
                            <img alt="" style="float:left;" width="129" height="128" src="<?php echo General::getUrlImage($data['User']['avatar_url']);  ?>" />
                        </div>
                        <div>
                            <input type="file" name="data[User][avatar]"  id="FileImage" />
                        </div>
                    </li>
                    <li >
                        <button class="button-update" title="Cập Nhật" type="submit"><span>Cập Nhật</span></button>
                    </li>
                </ul>
            </fieldset>    
        </form>
    </div>
</div>