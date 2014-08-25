function Interactive() {
    this.towermenu = function (x, y, width, height, field) {

        var ctx = Scene.get_layer('interactive').ctx;
        var current_zone = {
            x1: x,
            x2: x + width,
            y1: y,
            y2: y + height
        };
        var zones = {};
        var towers = ['archer', 'warrior'];
        ctx.clearRect(0, 0, Scene.width(), Scene.height());
        ctx.fillStyle = 'rgba(255,255,255,0.7)';
        ctx.strokeStyle = '#000000';
        ctx.beginPath();
        ctx.moveTo(x, y);
        ctx.lineTo(current_zone.x2, y);
        ctx.lineTo(current_zone.x2, current_zone.y2);
        ctx.lineTo(x, current_zone.y2);
        ctx.lineTo(x, y);
        ctx.fill();
        ctx.stroke();
        ctx.closePath();
        var s_x = x + 5;
        var s_y = y + 5;
        var w = 45;
        var h = 45;
        var j = 0;
        for (var i = 0, len = towers.length; i < len; i++) {
            if (i * w + s_x >= current_zone.x2) {
                j++;
            }
            ctx.drawImage(Resource.get('src/resources/images/towers/' + towers[i] + '.png'), i * w + s_x, j * h + s_y, w, h);
            zones[towers[i]] = {
                x1: i * w + s_x,
                x2: i * w + s_x + w,
                y1: j * h + s_y,
                y2: j * h + s_y + h
            }
        }

        ctx.lineWidth = '0.5';
        var [tmp_c,tmp_ctx] = create_ctx();
        tmp_ctx.putImageData(ctx.getImageData(0, 0, Scene.width(), Scene.height()), 0, 0);
        Scene.mouse_move('interactive', function (e) {
            var mouse_pos = Scene.get_click_position(e);
            if (in_zone(mouse_pos, current_zone)) {
                ctx.clearRect(0, 0, Scene.width(), Scene.height());
                ctx.drawImage(tmp_c, 0, 0);
                for (var t in zones) {
                    if (zones.hasOwnProperty(t)) {
                        if (in_zone(mouse_pos, zones[t])) {
                            ctx.beginPath();
                            var x1 = zones[t].x1 - 2;
                            var y1 = zones[t].y1 - 2;
                            var x2 = zones[t].x2 + 2;
                            var y2 = zones[t].y2 + 2;
                            ctx.moveTo(x1, y1);
                            ctx.lineTo(x2, y1);
                            ctx.lineTo(x2, y2);
                            ctx.lineTo(x1, y2);
                            ctx.lineTo(x1, y1);
                            ctx.stroke();
                            ctx.closePath();
                        }
                    }
                }
                return false;
            }
        });
        Scene.click('interactive', function (e) {
            var mouse_pos = Scene.get_click_position(e);
            if (in_zone(mouse_pos, current_zone)) {
                for (var t in zones) {
                    if (zones.hasOwnProperty(t)) {
                        if (in_zone(mouse_pos, zones[t])) {
                            Map.tower_build(t,field);
                            Interactive.remove();
                        }
                    }
                }
                return false;
            }
        });
        Scene.key_press(27,function(){
            Scene.un_key_press(27);
            Interactive.remove();
        });
    };
    this.remove = function(){
        Scene.un_mouse_move('interactive');
        Scene.unclick('interactive');
        Scene.get_layer('interactive').ctx.clearRect(0, 0, Scene.width(), Scene.height());
    }
}
Interactive = new Interactive();
