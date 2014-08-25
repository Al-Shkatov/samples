/**
 * Used to manipulate loading resources
 * @todo add another type of resources instead a image
 * @returns {Resource}
 * @constructor
 */
function Resource() {
    var cache = {
        registered: [],
        loaded: []
    };
    var images = {};
    /**
     * Register resource to preload
     * @param path
     */
    this.register = function (path) {
        cache.registered.push(path);
    };
    /**
     * Preload all registered resource, trigger('resources.loaded') after all resources loaded
     */
    this.preload = function () {
        cache.registered.forEach(function (path, index) {
            var image = new Image();
            image.onload = function () {
                delete cache.registered[index];
                cache.registered[index] = null;
                cache.loaded.push(path);
                images[path] = image;
                if (cache.loaded.length == cache.registered.length) {
                    EventListener.trigger('resources.loaded');
                }
            };
            image.src = (base_url ? base_url : '') + path;
        });
    };
    /**
     * Load simple resource
     * @param path
     * @param callback
     */
    this.load = function (path, callback) {
        var image = new Image();
        if (cache.loaded.indexOf(path) !== -1) {
            cache.loaded.push(path);
            images[path] = image;
        }
        image.onload = callback;
        image.src = (base_url ? base_url : '') + path;
    };

    this.get = function (path) {
        if (images.hasOwnProperty(path)) {
            return images[path];
        }
        return null;

    }
    return this;
}

Resource = new Resource();