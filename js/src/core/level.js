function Level() {
    this.load = function (lvl_number) {
        include('src/game/levels/level-' + lvl_number, 'lvl' + lvl_number, function (e) {
            EventListener.trigger('level.loaded');
            EventListener.trigger('level.loaded.' + lvl_number);
        });
    };
    Scene.add_layer('background');
    Scene.add_layer('towers');
    Scene.add_layer('enemies');
    Scene.add_layer('interactive');//используется для вывода разной инфы
    Scene.add_layer('interface');
    this.show = function () {
        var background = Scene.get_layer('background').ctx;
        Map.generate(level_data.map, background);
        Scene.mouse_move('background', function (e) {
            Map.hover(e);
        });
        Scene.click('background', function (e) {
            var mouse_pos = Scene.get_click_position(e);
            var field = Map.get_field(mouse_pos.x, mouse_pos.y);
            if (field.can_build()) {
                var s_x = field.position.x - field.width - 10;
                var s_y = field.position.y - field.height + 10;
                var w = field.width * 4 + 20;
                var h = field.height * 8;
                Interactive.towermenu(s_x, s_y, w, h, field);

            }
        });
        Scene.render_layers(['background', 'enemies', 'towers', 'interactive', 'interface']);
    }
}
Level = new Level();