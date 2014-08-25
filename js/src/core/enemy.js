function Enemy() {

}
Enemy.prototype = {
    name: null,
    hp: null,
    mp: null,
    speed: null,
    /**
     * Я думаю стоит использовать простой показатель армора
     * 0-100 в процентах и у моба максимум 99 армора
     */
    armor: null,
    resistance: null,
    killed_by: null,
    damaged_by: {},
    experience: null,
    x:null,
    y:null,
    move: function () {

    },
    hit: function (tower) {
        var dmg = tower.get_damage() * this.armor / 100;
        if (dmg > this.hp) {
            this.kill_by(tower);
        } else {
            this.damage_by(tower, dmg);
        }
    },
    damage_by: function (tower, dmg) {
        this.hp -= dmg;
        this.damaged_by[tower.name + tower.x + tower.y] =
            this.damaged_by[tower.name + tower.x + tower.y] ?
                this.damaged_by[tower.name + tower.x + tower.y] + dmg :
                dmg
    },
    kill_by: function (tower) {
        this.killed_by = tower;
        this.speed = 0;
    }
};