(function(){

    var X = {};

    X.isFunction = function(variable){
        var obj = {};
        return variable && obj.toString.call(variable) === '[object Function]';
    };

    X.run = function(func, arguments) {
        if (X.isFunction(func)) {
            func.apply(null, arguments);
        }
    };

    X.setCookie = function(name, value, expire) {
        var date = new Date();
        date.setTime(date.getTime() + (expire * 24 * 60 * 60 * 1000));
        var expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + "; " + expires;
    };

    X.readableSize = function (bytes) {
        var metrics = ['b', 'kB', 'MB', 'GB'];
        var i = 0;
        while (bytes > 1024) {
            bytes = bytes / 1024;
            i++;
        }
        return Math.max(bytes, 0.1).toFixed(1) + metrics[i];
    };

    X.triggerEvent = function(element, name) {
        var event; // The custom event that will be created
        if (document.createEvent) {
            event = document.createEvent("HTMLEvents");
            event.initEvent(name, true, true);
        } else {
            event = document.createEventObject();
            event.eventType = name;
        }
        event.eventName = name;
        if (document.createEvent) {
            element.dispatchEvent(event);
        } else {
            element.fireEvent("on" + event.eventType, event);
        }
    };

    window.X = X;

})();
