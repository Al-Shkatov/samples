function Map() {
    this.fields = [];
    this.cached_coords = {};
    var [state_c, state_ctx] = create_ctx();
    this.generate = function (matrix, ctx) {
        this.ctx = ctx;
        var height = matrix.length;
        var width = matrix[0] && matrix[0].length;
        this.field_width = Scene.width() / width;
        this.field_height = Scene.height() / height;

        for (var y = 0; y < height; y++) {
            for (var x = 0; x < width; x++) {
                var field = new Field(x, y, this.field_width, this.field_height, matrix[y][x]);
                this.fields[x] = this.fields[x] || [];
                this.fields[x][y] = field;
                this.cached_coords[x + '_' + y] = field;
                draw_square(x, y, this.field_width, this.field_height, field.color);
            }
        }
        state_ctx.putImageData(ctx.getImageData(0, 0, Scene.width(), Scene.height()), 0, 0);
    };
    this.tower_build = function(towername, field){
        var t_layer = Scene.get_layer('towers').ctx;
        var t_i = Resource.get('src/resources/images/towers/' + towername + '.png');
        var x = field.position.x+field.width/2-t_i.width/2;
        var y = field.position.y+field.height- t_i.height;
        field.occupied = true;
        /**
         * @todo need algorithm to correction z-index by y coord
         */
        t_layer.drawImage(t_i,x,y);
    };
    var draw_square = function (x, y, width, height, color, lineWidth) {
        var ctx = Map.ctx;
        ctx.beginPath();
        var s_x = x * width;
        var s_y = y * height;
        ctx.lineWidth = '1px';
        if (lineWidth) {
            ctx.lineWidth = lineWidth;
        }
        ctx.strokeStyle = '#aaaaaa';
        ctx.moveTo(s_x, s_y);
        ctx.lineTo(s_x + width, s_y);
        ctx.lineTo(s_x + width, s_y + height);
        ctx.lineTo(s_x, s_y + height);
        ctx.lineTo(s_x, s_y);
        if (color) {
            ctx.fillStyle = color;
            ctx.fill();
        } else {
            ctx.fillStyle = null;
        }
        Scene.debug.ctx.clearRect(s_x, s_y, width, height);
        Scene.debug.ctx.font = '6pt sans';
        Scene.debug.ctx.fillStyle = '#000000';
        Scene.debug.ctx.fillText(x+', '+y, s_x+2, s_y+10);
        ctx.stroke();
        ctx.closePath();
    };
    this.hover = function (e) {
        this.ctx.drawImage(state_c, 0, 0);
        var mouse_pos = Scene.get_click_position(e);
        var field = this.get_field(mouse_pos.x, mouse_pos.y);
        draw_square(field.x, field.y, field.width, field.height, color_lum(field.color, 0.1),3);
    };
    this.get_field = function (p_x, p_y) {
        var x = floor(p_x / this.field_width);
        var y = floor(p_y / this.field_height);
        return this.fields[x][y];
    };
}
Map = new Map();