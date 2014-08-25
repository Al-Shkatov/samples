/**
 * Simple Events functional
 * @returns {EventListener}
 * @constructor
 */
function EventListener() {
    var events = [];
    /**
     * Used to attach custom function to evt.name
     * @param type event name
     * @param calback function was called on trigger(evt.name)
     */
    this.on = function (type, calback) {
        events[type] = events[type] || [];
        events[type].push(calback);
    };
    /**
     * Used to execute all custom functions attached to evt.name
     * @param type event name
     * @param [data] optional data send to each callback function assigned to evt.name
     */
    this.trigger = function (type, data) {
        if (events[type] && events[type].length) {
            events[type].forEach(function (fn) {
                setTimeout(function () {
                    fn(data)
                }, 10);
            });
        }
    };
    return this;
}

EventListener = new EventListener();