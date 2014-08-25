/**
 *
 * @param msg send to console
 * @param [msg1],[msg2],[msg3] optionals send to grouped
 */
function dbg(msg) {
    var args = arguments;
    if (args.length > 1) {
        console.groupCollapsed(msg);
        for (var i = 1; i < args.length; i++) {
            console.log(args[i]);
        }
        console.groupEnd();
    } else {
        console.log(msg);
    }
}

function create_ctx(){
    var c = document.createElement('canvas');
    c.width = Scene.width();
    c.height = Scene.height();
    var ctx = c.getContext('2d');
    return [c,ctx];
}

function _(word){
    return Language.translate(word);
}