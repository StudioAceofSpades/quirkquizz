//Local storage cookie fallback function based on https://gist.github.com/Fluidbyte/4718380
var store = function store(key, value) {
    var lsSupport = false;

    //Check for localstorage support
    if (storageAvailable('localStorage')) {
        lsSupport = true;
    }

    //check to see if we are setting a new value
    if (typeof value !== "undefined" && value !== null) {
        //Converting object values to json
        if(typeof value === 'object') {
            value = JSON.stringify(value);
        }
        //set the store
        if(lsSupport) {
            data = localStorage.setItem(key, value);
        } else {
            createCookie(key, value, 30);
        }
    }

    //Getting the value if we arent setting
    if (typeof value === "undefined") {
        //Returning value from localStorage
        if(lsSupport) {
            data = localStorage.getItem(key);
        } else { //Returning value from cookie
            data = readCookie(key);
        }

        // Try to parse json for objects
        try {
            data = JSON.parse(data);
        } catch(e) {
            //nothing to see here
        }

        return data;
    }

    //if null is specified for value, we will delete the stored value
    if (value === null) {
        if (lsSupport) {
            localStorage.removeItem(key);
        } else {
            createCookie(key, '', -1);
        }
    }

    //Creates a new cookie with specified key, value, and expiration, expiration defaults to 30.
    function createCookie(key, value, exp=30) {
        var date = new Date();
        date.setTime(date.getTime() + (exp * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
        document.cookie = key + "=" + value + expires + "; path=/";
    }

    //gets cookie val based on key
    function readCookie(key) {
        var cookie = document.cookie.match('(^|[^;]+)\\s*' + key + '\\s*=\\s*([^;]+)');
        return cookie ? cookie.pop() : '';
    }

    //Fix for private browsing on safari
    function storageAvailable(type) {
        try {
            var storage = window[type],
                x = '__storage_test__';
            storage.setItem(x, x);
            storage.removeItem(x);
            return true;
        }
        catch(e) {
            return false;
        }
    }
}