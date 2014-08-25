/**
 * Стартовая страница
 */
function StartPage() {
    Scene.add_layer('start_page');
    this.show = function () {
        var ctx = Scene.get_layer('start_page').ctx;
        var menu = [
            'start_game',
            'settings'
        ];
        ctx.textAlign = 'center';
        ctx.font = Config.Styles.Heading.Font;
        var clicked_zones = {};
        var draw_menu = function (active) {
            ctx.clearRect(0, 0, Scene.width(), Scene.height());
            ctx.fillStyle = Config.Styles.Heading.Color;
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
                var x = floor(Scene.width() / 2);
                var y = floor(Scene.height() / 2 - 50 + i * 50);
                var width = floor(ctx.measureText(_(txt)).width / 2);
                clicked_zones[txt] = {x1: x - width, y1: y - 30, x2: x + width, y2: y};
                ctx.fillText(_(txt), x, y);
            });
        };
        draw_menu();
        var active = false;
        var cursor = '';
        Scene.mouse_move('start_page', function (e) {
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
        Scene.click('start_page', function (e) {
            var click_on = in_zone(Scene.get_click_position(e));
            if (click_on) {
                Scene.un_mouse_move('start_page');
                Scene.unclick('start_page');
                css(Scene.canvas, {cursor: 'auto'});
            }
            switch (click_on) {
                case'start_game':
                    EventListener.trigger('start.game');
                    break;
                case'settings':
                    EventListener.trigger('open.settings');
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
        Scene.render_layers('start_page');
    }
}
StartPage = new StartPage();