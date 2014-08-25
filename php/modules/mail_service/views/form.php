<div class="subscribe_wrap">
    <span class="subscribe_button"><?php echo _t('subscribe'); ?></span>
    <?php ?>
    <script type="text/javascript">
        $(document).ready(function () {

            <?php if(Flash::get() == 'email_succ'):?>
            $(".subscribe_button").fancybox({
                overlayShow: true,
                content: $('#popup_succ').html()
            });
            $(".subscribe_button").click();
            <?php else:?>
            $(".subscribe_button").fancybox({
                overlayShow: true,
                content: $('#popup_banner_content').html(),
                beforeShow:function(){
                    console.log(12312);
                    Cufon.replace(".subscribe_form h2",{
                        fontFamily:"NatGrotesk-Nar-Light",
                        fontSize:'22',
                        color:'#75b801'
                    });
                }
            });

            <?php endif;?>
            $('.fancybox-inner #subscribe-form').submit(function () {

            });
        });
        function validate(form) {
            var inp = $(form).find('#email-subscribe');
            if (inp.val().match(/[A-za-z\-_]{2,25}@[A-za-z\-_]{2,25}\.\w{2,7}/) === null) {
                inp.css({borderColor: 'red'});
                return false;
            } else {
                inp.css({borderColor: 'green'});
                return true;
            }
        }


    </script>

    <div style="display: none;" id="popup_banner_content">
        <div class="subscribe_form">
            <h2>Форма підписки на розсилку</h2>

            <form action="" method="POST" id="subscribe-form" onsubmit="return validate(this);">
                <div class="row">
                    <label for="email-subscribe">Введіть Вашу електронну адресу нижче</label>
                    <input type="text" id="email-subscribe" name="email-subscribe"/>
                </div>
                <div class="row">
                    <input type="submit" class="submit_btn" value="Підписатись"/>
                </div>
            </form>
        </div>
    </div>
    <div style="display:none" id="popup_succ">
        <div class="subscribe_form">
            <h2>Ви успішно підписались на розсилку</h2>
        </div>
    </div>
</div>