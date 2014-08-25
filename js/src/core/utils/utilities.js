/**
 * simple proxy to add some styles to element
 * @param element
 * @param styles
 */
function css(element, styles) {
    for (var rule in styles) {
        if (styles.hasOwnProperty(rule)) {
            element.style[rule] = styles[rule];
        }
    }
}
/**
 * Use this careful, not check types.
 * @param number
 * @returns {*}
 */
function floor(number) {
    return number | 0
}

/**
 *
 * @returns current files and functions stack
 */
function stack() {
    try {
        throw new Error();
    } catch (e) {
        var rows = e.stack.split("\n");
        var tmp = [];
        for (var i = 0, len = rows.length; i < len; i++) {
            if(rows[i]) {
                var t = rows[i].split('@');
                var fn = t[0];
                var f = t[1].split(':');
                var file = f[1];
                var line = f[2];
                var c = fn.split('/');
                var cl = c.length == 1 ? 'GLOBAL' : c[0];
                var fn = c.length > 1 ? c[1] : fn;
                tmp[tmp.length] = {'Class': cl, 'Method': fn, 'File': 'http:'+file, 'Line': line}
            }
        }
        return tmp;
    }
}

function color_lum(hex,lum){
    if(hex[0] === '#'){
        hex = hex.substring(1);
    }
    var rgb = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i*2,2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
        rgb += ("00"+c).substr(c.length);
    }
    return rgb;
}

function in_zone(position , zone){
    return position.x > zone.x1 && position.x < zone.x2
        && position.y > zone.y1 && position.y < zone.y2;
}