define([], function() {
    var QueryString = (function () {
        // This function is anonymous, is executed immediately and
        // the return value is assigned to QueryString!
        var query_string = {};
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            // If first entry with this name
            if (typeof query_string[pair[0]] === "undefined") {
                query_string[pair[0]] = pair[1];
                // If second entry with this name
            } else if (typeof query_string[pair[0]] === "string") {
                query_string[pair[0]] = [ query_string[pair[0]], pair[1] ];
                // If third or later entry with this name
            } else {
                query_string[pair[0]].push(pair[1]);
            }
        }
        return query_string;
    })();

    /**
     * http://erlycoder.com/49/javascript-hash-functions-to-convert-string-into-integer-hash-
     */
    function hashCode(str)
    {
        var hash = 0;
        if (str.length == 0) return hash;
        for (i = 0; i < str.length; i++) {
            char = str.charCodeAt(i);
            hash = ((hash<<5)-hash)+char;
            hash = hash & hash; // Convert to 32bit integer
        }
        return hash;
    }

    function count(object)
    {
        var n = 0;
        if (object) {
            for (var i in object) {
                if (object.hasOwnProperty(i)) {
                    n++;
                }
            }
        }
        return n;
    }

    return {
        hashCode: hashCode,
        count: count,
        queryParam: QueryString
    };
});