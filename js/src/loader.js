function include(src, id, callback) {
    var d = document;
    var s = d.createElement('script');
    s.id = 'script-' + id;
    if (typeof callback === 'function') {
        s.onload = function (e) {
            callback(e);
            if (typeof EventListener === 'object') {
                EventListener.trigger('script-' + id + '-loaded', e);
            }
        }
    }
    s.src = (base_url ? base_url : '') + src + '.js';
    var script = d.getElementsByTagName('script')[0];
    script.parentNode.appendChild(s);
}
include('src/config', 'config');
include('src/core/cache', 'cache');
include('src/core/utils/utilities', 'utilities');
include('src/core/game', 'game');
include('src/core/language', 'language',function(){
    Language.default = 'ru';
    Language.loaded.push('ru');
});
include('src/langs/ru', 'lang-ru');
include('src/core/core', 'core', function () {
    include('src/core/evt/event_listener', 'event_listener');
    include('src/core/scene/layer', 'layer');
    include('src/core/scene/scene', 'scene', function () {
        include('src/core/map', 'map');
        include('src/core/field', 'field');
        include('src/core/interactive', 'interactive');
        include('src/core/level', 'level',function(){
            Level.load(0);
        });
        include('src/core/menu', 'menu');
        include('src/core/start_page', 'start_page', function () {
            Game.init();
        });
    });
    include('src/core/resource', 'resource', function () {
        Resource.register('src/resources/images/towers/archer.png');
        Resource.register('src/resources/images/towers/warrior.png');
        Resource.register('src/resources/images/earth.jpg');
        Resource.register('src/resources/images/water.jpg');
        Resource.register('src/resources/images/road.jpg');
        Resource.register('src/resources/images/stone.png');
        Resource.preload();
    });

});