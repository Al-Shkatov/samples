function Scene(id) {
    var container = document.getElementById(id),
        c = document.createElement('canvas'),
        layer_names = {},
        layers_to_render = [],
        click_handlers = {},
        click_h_order = [],
        move_handlers = {},
        move_h_order = [],
        key_press_handlers = {};
    c.setAttribute('tabindex', '1');
    this.canvas = container.appendChild(c);
    this.ctx = this.canvas.getContext('2d');
    css(this.canvas, {width: '100%', height: '100%'});
    this.canvas.width = this.canvas.offsetWidth;
    this.canvas.height = this.canvas.offsetHeight;
    this.debug = null;
    this.canvas.onkeydown = function (e) {
        if (e.keyCode === 17) {
            var tmp = layers_to_render.slice(0);
            layers_to_render = [];
            for (var i = 0, len = tmp.length; i < len; i++) {
                if (tmp[i] !== 'debug') {
                    layers_to_render[layers_to_render.length] = tmp[i];
                }
            }
            layers_to_render[layers_to_render.length] = 'debug';
        }
    };
    this.canvas.onkeyup = function (e) {
        if (e.keyCode === 17) {
            var tmp = layers_to_render.slice(0);
            layers_to_render = [];
            for (var i = 0, len = tmp.length; i < len; i++) {
                if (tmp[i] !== 'debug') {
                    layers_to_render[layers_to_render.length] = tmp[i];
                }
            }
        }
    };
    this.canvas.onkeypress = function (e) {
        if (key_press_handlers.hasOwnProperty(e.keyCode)) {
            key_press_handlers[e.keyCode](e);
        }
    };
    this.canvas.onclick = function (e) {
        for (var j = click_h_order.length; j >= 0; j--) {
            var i = click_h_order[j];
            if (click_handlers.hasOwnProperty(i) && layers_to_render.indexOf(i) !== -1) {
                if (false === click_handlers[i](e)) {
                    return;
                }
            }
        }
    };
    this.canvas.onmousemove = function (e) {
        for (var j = move_h_order.length; j >= 0; j--) {
            var i = move_h_order[j];
            if (move_handlers.hasOwnProperty(i) && layers_to_render.indexOf(i) !== -1) {
                if (false === move_handlers[i](e)) {
                    return;
                }
            }
        }
    };

    this.width = function () {
        return this.canvas.width;
    };
    this.height = function () {
        return this.canvas.height;
    };
    this.render = function () {
        var c = Scene.canvas;
        var ctx = Scene.ctx;
        ctx.clearRect(0, 0, c.width, c.height);
        for (var i = 0, len = layers_to_render.length; i < len; i++) {
            ctx.drawImage(layer_names[layers_to_render[i]].canvas, 0, 0);
        }
        main_loop(Scene.render, Scene.canvas);
    };
    this.render_layers = function (layers) {
        layers_to_render = [];
        if (typeof layers === 'object') {
            layers.forEach(function (layer_name) {
                if (layer_names.hasOwnProperty(layer_name)) {
                    layers_to_render[layers_to_render.length] = layer_name;
                }
            });
        } else if (typeof layers === 'string') {
            if (layer_names.hasOwnProperty(layers)) {
                layers_to_render[layers_to_render.length] = layers;
            }
        }
    };
    this.add_layer = function (name, image_data) {
        layer_names[name] = new Layer({name: name, width: this.width(), height: this.height()});
        if (image_data) {
            layer_names[name].draw(image_data, 0, 0);
        }
    };
    this.get_layer = function (name) {
        return layer_names[name];
    };
    this.click = function (id, callback) {
        if (layer_names.hasOwnProperty(id)) {
            click_handlers[id] = callback;
            click_h_order[click_h_order.length] = id;
        }
    };
    this.unclick = function (id) {
        click_handlers[id] = null;
        delete click_handlers[id];
        var index = click_h_order.indexOf(id);
        if (index !== -1) {
            click_h_order = click_h_order.slice(index-1, index);
        }
    };
    this.mouse_move = function (id, callback) {
        if (layer_names.hasOwnProperty(id)) {
            move_handlers[id] = callback;
            move_h_order[move_h_order.length] = id;
        }
    };
    this.un_mouse_move = function (id) {
        move_handlers[id] = null;
        delete move_handlers[id];
        var index = move_h_order.indexOf(id);
        if (index !== -1) {
            move_h_order = move_h_order.slice(index-1, index);
        }
    };
    this.key_press = function (key, callback) {
        key_press_handlers[key] = callback;
    };
    this.un_key_press = function (key) {
        key_press_handlers[key] = null;
        delete key_press_handlers[key];
    };
    this.get_click_position = function (e) {
        return {x: e.pageX - this.canvas.offsetLeft, y: e.pageY - this.canvas.offsetTop}
    }
}
main_loop = (function () {
    return  window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function (callback, element) {
            return setTimeout(callback, 1000 / 60);
        };
})();


Scene = new Scene('scene');
Scene.render();
Scene.add_layer('debug');
Scene.debug = Scene.get_layer('debug');