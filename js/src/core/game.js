/**
 * Основная логика игры
 * @constructor
 */
function Game(){
    this.sound = true;
    this.history = [];
    this.init = function(){
        EventListener.on('start.game',function(){
            Game.start_game();
        });
        EventListener.on('open.settings',function(){
            Game.settings();
        });
        EventListener.on('sound.toggle',function(){
            Game.sound_toggle();
        });
        EventListener.on('back',function(){
            Game.prev_page();
        });
        this.start_page();
    };
    this.win = function(){

    };
    this.loose = function(){

    };
    this.pause = function(){

    };
    this.resume = function(){

    };
    this.start_page = function(){
        this.history.push('start_page');
        StartPage.show();
    };
    this.prev_page = function(){
        var cls = this.history.pop();
        if(cls && this[cls]){
            this[cls]();
        }
    };
    this.sound_toggle = function(){
        this.sound = !this.sound;
    };
    this.start_game = function(){
        Level.show();
    };
    this.settings = function(){
        Menu.show();
    };

}
Game = new Game();