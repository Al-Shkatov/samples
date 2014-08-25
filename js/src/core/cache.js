/**
 * Simple Cache
 */
var Cache = {
    runtime: {
        cached: {},
        put: function (index, data) {
            this.runtime.cached[index] = data;
        },
        get: function (index) {
            return this.runtime.cached[index];
        }
    },
    local: {
        put: function (index, data) {
            if (typeof data === 'string' || typeof data === 'number') {
                localStorage[index] = data;
            } else if (typeof data === 'array' || typeof data === 'object') {
                localStorage[index] = JSON.stringify(data);
            }
        },
        get: function (index) {
            var data = localStorage[index];
            try {
                data = JSON.parse(data);
            } catch (e) {
                //do nothing
            }
            return data;
        }
    },
    put: function (index, data, where) {
        var storage = this.hasOwnProperty(where) ? this[where] : this.runtime;
        storage.put.call(this, index, data)

    },
    get: function (index, where) {
        var storage = this.hasOwnProperty(where) ? this[where] : this.runtime;
        return storage.get.call(this, index)
    }
};