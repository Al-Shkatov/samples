<div class="toolbar">
    <a href="<?php echo $this->base_url ?>admin/mail_service/addCampaign">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('add_campaign'); ?> </div>
        </button>
    </a>
    <a href="<?php echo $this->base_url ?>admin/mail_service/subscribers">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('subscribers'); ?> </div>
        </button>
    </a>
</div>
<?php if (!empty($this->campaigns)): ?>
    <div class="table_content_cont" id="art_table">
        <div class="table_content_inner">
            <div class="table_header">
                <div class="name">
                    <?php echo _t('name'); ?>
                </div>
                <div class="added"><?php echo _t('added'); ?></div>
                <div class="activity"></div>
            </div>
            <table>
                <?php foreach ($this->campaigns as $campaign) : ?>
                    <tr rel="<?php echo $this->base_url ?>admin/mail_service/editCampaign/<?php echo $campaign['id'] ?>">
                        <td class="marker"></td>
                        <td class="name"><?php echo $campaign['name'] ?></td>
                        <td class="added"><?php echo $campaign['created_at'] ?></td>
                        <td class="category"></td>
                        <td class="activity"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
<?php else: ?>
    <h4><?php echo _t('empty_campaigns'); ?></h4>
<?php endif; ?>
<div class="toolbar">
    <a href="<?php echo $this->base_url ?>admin/mail_service/addCampaign">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('add_campaign'); ?> </div>
        </button>
    </a>
    <a href="<?php echo $this->base_url ?>admin/mail_service/subscribers">
        <button class="content_btn">
            <div class="btn_img"><img src="<?php echo $this->base_url ?>admin/images/icons/write_article.png"/></div>
            <div class="btn_text"><?php echo _t('subscribers'); ?> </div>
        </button>
    </a>
</div>