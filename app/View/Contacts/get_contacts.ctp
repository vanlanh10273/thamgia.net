<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Tham Gia</title>
        <?php 
            echo $this->Html->css('loantin');
            echo $this->Html->script('jquery.min');
            echo $this->Html->script('jquery.tinyscrollbar.min');
        ?>
    </head>
    <body style="background-color:#303233">
        <div id="loantin">
            <div class="loantin-header">
                <a href="#"></a>
            </div>
            <form id="frm_contact" name="frm_contact" method="post" action="<?php echo $this->Html->url(array('controller' => 'Contacts','action' => 'sendMailToCantacts', $event_id_contact)) ?>">
                <div class="loantin-content">
                    <div class="comment">
                        <div class="comment-text">
                            <div class="wd-input">
                                <label for="skills">LỜI NHẮN</label>
                                <textarea rows="3" cols="30" id="skills" name="data[Contact][message]"></textarea>
                            </div>
                        </div>
                        <div class="select">
                            <h2>GỬI ĐẾN</h2>
                            <div class="select-01">
                                <div class="wd-input">
                                    <input id="checkAllAuto" type="checkbox" name="remeber">
                                    <label for="remember">Chọn tất cả</label>
                                </div>
                            </div>
                            <div class="select-02">
                                <div id="scrollbar1">
                                        <div class="scrollbar">
                                            <div class="track">
                                                <div class="thumb">
                                                    <div class="end"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="viewport">
                                             <div class="overview">
                                             <?php foreach($emails as $item){ ?>
                                                <div class="wd-input">
                                                    <input id="emails" type="checkbox" name="data[Contact][emails][]" value="<?php echo $item ?>" />
                                                    <label for="remember"><?php echo $item ?></label>
                                                </div>
                                             <?php } ?>
                                             </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="footer">
                    <div class="wd-submit">
                        <input type="submit" value="Send" class="wd-send" />
                        <input type="reset" onclick="javascript:window.close();" value="Reset" class="wd-reset" />
                    </div>
                </div> 
            </form>       
        </div>
    </body>
</html>
<script>
    $(document).ready(function() {
        $("#frm_contact").submit(function() {
            //$(this).ajaxSubmit(options);
            $.post("<?php echo $this->Html->url(array('controller' => 'Contacts','action' => 'sendMailToCantacts', $event_id_contact)) ?>"
                    ,$("#frm_contact").serialize()
            );
            alert('Bạn vừa loan tin sự kiện đến bạn bè!');
            window.close();
            return false;
        });
        /*$('#scrollbar1').tinyscrollbar({ size: 190 });*/
        $('#scrollbar1').tinyscrollbar({size:120});
        
        $('#checkAllAuto').click(
            function()
            {
                $("INPUT[type='checkbox']").attr('checked', $('#checkAllAuto').is(':checked'));    
            }
        )
    });
</script>
