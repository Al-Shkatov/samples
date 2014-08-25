<?php
$flag = isset($this->subscriber);
?>

<form action="<?php echo Factory::getURI()->base() ?>admin/mail_service/saveSubscriber" method="post" class="">
    <span class="form_header"><?php echo $flag ? _t('edit_subscriber') : _t('add_subscriber') ?></span>

    <div class="edit_cont">
        <div class="edit_cont_inner">
            <input type="hidden" name="subscriber[id]" value="<?php echo $flag ? $this->subscriber['id'] : '' ?>"/>
            <div class="input_cont">
                <label><?php echo _t('email'); ?></label>
                <input type="text" name="subscriber[email]" value="<?php echo $flag ? $this->subscriber['email'] : '' ?>"/>
            </div>
            <div class="clear_block"></div>
        </div>
    </div>
    <?php echo $this->backButton(); ?>
    <div class="right_block">
        <button class="content_btn" type="submit">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/save.png"/></div>
            <div class="btn_text"> <?php echo $flag ? _t('save') : _t('add'); ?></div>
        </button>
    </div>
</form>

