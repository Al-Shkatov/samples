function Layer(params) {
    this.name = params.name;
    var [c,ctx]=create_ctx();
    this.canvas = c;
    this.ctx = ctx;

    this.draw = function (image, x, y) {
        this.ctx.drawImage(image, x, y);
    }
}