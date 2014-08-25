function Field(x, y, width, height, type) {
    this.init(x, y, width, height, type);
}
Field.prototype = {
    occupied: false,//set up true if tower builded
    obstacle: false,//set up true if not movable, and cant build
    init: function (x, y, width, height, type, color) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.position = {
            x: x * width,
            y: y * height
        };
        this.subtype = 'default';
        if(type === 'ex' || type === 'st' || type.length===1) {
            this.type = Config.Field.Types[type];
        }else{
            this.type = Config.Field.Types[type[0]];
            if(type[1]-0!=type[1]) {
                this.subtype = Config.Field.Types[type[1]];
            }
        }
        this.color = Config.Field.Colors[this.type];
        if (Config.Field.CanBuildTypes[type] === -1 && Config.Field.MovableTypes[type] === -1) {
            this.obstacle = true;
        }
    },
    can_move: function(){
        return Config.Field.MovableTypes.indexOf(this.type) !== -1
    },
    can_build: function () {
        return !this.occupied && Config.Field.CanBuildTypes.indexOf(this.type) !== -1
    }

};