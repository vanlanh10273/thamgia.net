<article class="module width_full">
    <header><h3>Send Emails</h3></header>
        <div class="module_content">
            <form id="frm_emails" name="frm_emails" method="post" action="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' =>'sendEmails')) ?>" class="login-subscription" enctype="multipart/form-data">
                <fieldset>
                    <label>Subject *</label>
                    <input name="data[Email][subject]" type="text">
                </fieldset>
                <fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
                    <label>To Emails *</label>
                    <textarea rows="5" name="data[Email][toEmails]"></textarea>
                </fieldset>
                <div>
                    <?php echo $this->fck->ckeditor(array('Email', 'content'), $this->webroot, ''); ?>
                </div>
                <input class="submit_link" type="submit" value="Send" />
                <div class="clear"></div>
                
            </form>
        </div>
    <footer>
        
    </footer>
</article>