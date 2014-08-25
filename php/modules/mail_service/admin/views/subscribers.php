<div class="toolbar">
    <a href="<?php echo $this->base_url ?>admin/mail_service/addSubscriber">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('add_subscriber'); ?> </div>
        </button>
    </a>
    <a href="<?php echo $this->base_url ?>admin/mail_service">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('campaigns'); ?> </div>
        </button>
    </a>
</div>
<?php if (!empty($this->subscribers)): ?>
    <div class="table_content_cont" id="art_table">
        <div class="table_content_inner">
            <div class="table_header">
                <div class="marker"></div>
                <div class="name">
                    <?php echo _t('email'); ?>
                </div>
                <div class="category"><?php echo _t('added'); ?></div>
                <div class="added"><?php echo _t('status'); ?></div>
                <div class="activity"></div>
            </div>
            <table>
                <?php foreach ($this->subscribers as $subscriber) : ?>
                    <tr rel="<?php echo $this->base_url ?>admin/mail_service/editSubscriber/<?php echo $subscriber['id'] ?>">
                        <td class="marker"></td>
                        <td class="name"><?php echo $subscriber['email'] ?></td>
                        <td class="category"><?php echo $subscriber['created_at'] ?></td>
                        <td class="added"><?php echo _t($subscriber['status']); ?></td>
                        <td class="activity"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
<?php else: ?>
    <h4><?php echo _t('empty_subscribers'); ?></h4>
<?php endif; ?>
<div class="toolbar">
    <a href="<?php echo $this->base_url ?>admin/mail_service/addSubscriber">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('add_subscriber'); ?> </div>
        </button>
    </a>
    <a href="<?php echo $this->base_url ?>admin/mail_service">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('campaigns'); ?> </div>
        </button>
    </a>
</div>