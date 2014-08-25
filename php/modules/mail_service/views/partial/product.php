<?php if ($this->product): ?>
    <div style="text-align: center">
        <img style="margin: 10px" height="145"
             src="<?php echo URI::base(true) . Image::resize($this->product['image'], 175, 145) ?>">

        <div style="height:30px;text-align: left; padding: 10px">
            <span
                style="font-size:14px;font-family:Arial, Geneva, sans-serif;color:#006230"><?php echo $this->product['name']; ?></span>
        </div>
        <div style="margin:5px;text-align:left">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:106px">
                        <?php if ($this->product['discount']): ?>
                            <img style="margin: 2px"
                                 src="<?php echo URI::base(true) . Image::resize($this->product['discount_link'], 30, 30) ?>">
                        <?php endif; ?>
                        <?php if (isset($this->product['params']['hit']) && $this->product['params']['hit']): ?>
                            <img style="margin: 2px"
                                 src="<?php echo URI::base(true) . Image::resize('modules/store/content/hit_2.png', 30, 30) ?>">
                        <?php endif; ?>
                        <?php if (isset($this->product['params']['sticers_status']) && $this->product['params']['sticers_status']): ?>
                            <img style="margin: 2px"
                                 src="<?php echo URI::base(true) . Image::resize($this->product['params']['sticers_link'], 25, 25) ?>">
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right;width:80px">
                        <?php list($cPrice, $lPrice) = explode('.', sprintf('%.2f', $this->product['price'])) ?>
                        <span
                            style="font-size:36px;font-family:Arial, Geneva, sans-serif;color:#e01b1f"><?php echo trim($cPrice); ?><sup style="font-size:22px;vertical-align: top"><?php echo trim($lPrice); ?></sup></span>
                    </td>
                </tr>
            </table>

        </div>
    </div>
<?php else: ?>
    &nbsp;
<?php endif; ?>