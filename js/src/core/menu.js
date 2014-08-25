/**
 * Думаю стоит сделать отдельный класс
 * для отображения страницы меню.
 */
function Menu(){
    Scene.add_layer('menu');
    this.show = function(){
        var ctx = Scene.get_layer('menu').ctx;
        var menu = [
            'sound',
            'back'
        ];
        ctx.textAlign = 'center';
        ctx.font = Config.Styles.Heading.Font;
        var clicked_zones = {};
        var draw_menu = function (active) {
            ctx.clearRect(0, 0, Scene.width(), Scene.height());
            ctx.fillStyle = Config.Styles.Heading.Color;
            var t_txt;
            menu.forEach(function (txt, i) {
                if (txt === active) {
                    ctx.shadowColor = 'rgba(255,100,0,1)';
                    ctx.shadowOffsetX = 0;
                    ctx.shadowOffsetY = 0;
                    ctx.shadowBlur = 10;
                } else {
                    ctx.shadowColor = null;
                    ctx.shadowOffsetX = 0;
                    ctx.shadowOffsetY = 0;
                    ctx.shadowBlur = 0;
                }
                t_txt = _(txt);
                if(txt === 'sound'){
                    t_txt = _(txt)+' '+_(Game.sound?'s_on':'s_off');
                }
                var x = floor(Scene.width() / 2);
                var y = floor(Scene.height() / 2 - 50 + i * 50);
                var width = floor(ctx.measureText(t_txt).width / 2);
                clicked_zones[txt] = {x1: x - width, y1: y - 30, x2: x + width, y2: y};
                ctx.fillText(t_txt, x, y);
            });
        };
        draw_menu();
        var active = false;
        var cursor = '';
        Scene.mouse_move('menu', function (e) {
            var txt = in_zone(Scene.get_click_position(e));
            if (null !== txt) {
                if (active !== txt) {
                    active = txt;
                    draw_menu(active);
                }
                if (cursor !== 'pointer') {
                    cursor = 'pointer';
                    css(Scene.canvas, {cursor: 'pointer'});
                }
            }
            else {
                if (active != false) {
                    active = false;
                    draw_menu();
                }
                if (cursor !== 'auto') {
                    cursor = 'auto';
                    css(Scene.canvas, {cursor: 'auto'});
                }
            }
        });
        Scene.click('menu', function (e) {
            var click_on = in_zone(Scene.get_click_position(e));
            switch (click_on) {
                case'sound':
                    EventListener.trigger('sound.toggle');
                    draw_menu();
                    break;
                case'back':
                    Scene.unclick('menu');
                    EventListener.trigger('back');
                    draw_menu();
                    break;
            }
        });
        var in_zone = function (pos) {
            var click_on = null;
            for (var txt in clicked_zones) {
                if(clicked_zones.hasOwnProperty(txt)) {
                    if (pos.x > clicked_zones[txt].x1
                        && pos.x < clicked_zones[txt].x2
                        && pos.y > clicked_zones[txt].y1
                        && pos.y < clicked_zones[txt].y2) {
                        click_on = txt;
                    }
                }
            }
            return click_on;
        };
        Scene.render_layers('menu');
    }
}
Menu = new Menu();