<?php
$flag = isset($this->campaign);
?>

<form action="<?php echo Factory::getURI()->base() ?>admin/mail_service/saveCampaign" method="post" class="">
    <span class="form_header"><?php echo $flag ? _t('edit_campaign') : _t('add_campaign') ?></span>

    <div class="edit_cont">
        <div class="edit_cont_inner">
            <input type="hidden" name="campaign[id]" value="<?php echo $flag ? $this->campaign['id'] : '' ?>"/>
            <div class="input_cont">
                <label><?php echo _t('name'); ?></label>
                <input type="text" name="campaign[name]" value="<?php echo $flag ? $this->campaign['name'] : '' ?>"/>
            </div>
            <div class="input_cont">
                <label><?php echo _t('subject'); ?></label>
                <input type="text" name="campaign[subject]" value="<?php echo $flag ? $this->campaign['subject'] : '' ?>"/>
            </div>
            <div class="input_cont last_input_cont">
                <label><?php echo _t('status'); ?></label>
                <select name="campaign[status]" id="status" class="styled">
                    <option
                        value="active" <?php if ($flag && $this->campaign['status'] == 'active') echo 'selected'; ?>><?php echo _t('active'); ?></option>
                    <option
                        value="unactive" <?php if ($flag && $this->campaign['status'] == 'unactive') echo 'selected'; ?>><?php echo _t('unactive'); ?></option>
                    </select>
            </div>
            <div class="input_cont input_cont_100">
                <label><?php echo _t('html'); ?></label>
                <textarea name="campaign[html]"
                          id="html"><?php echo $flag ? stripslashes($this->campaign['html']) : '' ?></textarea>
                <?php $this->ckeditor('html');?>
            </div>
            <div class="input_cont input_cont_100">
                <label><?php echo _t('text'); ?></label>
                <textarea name="campaign[text]" style="height: 150px"
                          id="text"><?php echo $flag ? stripslashes($this->campaign['text']) : '' ?></textarea>
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

