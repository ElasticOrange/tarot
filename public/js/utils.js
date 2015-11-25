// Prologram namespace
var Prologram = window.Prologram || {};

Prologram.Utils = {};

/**
 * Checks whether the given object is of the given interface.
 * @param {object} object The object to check
 * @param {object} interface The interface definition. Should contain two arrays: 'properties' and 'methods'.
 * @returns {Boolean}
 */
Prologram.Utils.isInterface = function(object, interface) {
    var isinterface = false;

    // Check parameters
    if (!_.isObject(object) && !_.isFunction(object)) {
        return isinterface;
    }
    if (!_.isObject(interface)) {
        return isinterface;
    }

    // Default empty properties and methods
    if (!_.isArray(interface.methods)) {
        interface.methods = [];
    }
    if (!_.isArray(interface.properties)) {
        interface.properties = [];
    }

    isinterface = true;

    // Check existence of methods
    var methodName = null;
    var hasMethod = false;
    var idx = 0;
    for (idx in interface.methods) {
        methodName = interface.methods[idx];
        hasMethod = _.isFunction(object[methodName]);
        if (!hasMethod) {
            isinterface = false;
            break;
        }
    }

    // Check properties
    var propName = null;
    var prop = null;
    var hasProp = false;
    for (idx in interface.properties) {
        propName = interface.properties[idx];
        hasProp = _.has(object, propName) && !_.isFunction(object[propName]);
        if (!hasProp) {
            isinterface = false;
            break;
        }
    }

    return isinterface;
};

/**
 * Maps properties/functions from one object to the other.
 * @param {object} from The object from which to read the properties/functions.
 * @param {object} to The object to which to write the properties/functions.
 * @param {object} conversion A conversion map with property/function names of
 * the 'from' object as keys, and property/function names of the 'to' object as
 * values.
 * @param {function} filter A filter function that takes the value of a property/function
 * and return whether it should be mapped (TRUE) or not (FALSE).
 * @returns {undefined}
 */
Prologram.Utils.map = function(from, to, conversion, filter) {
    for(var i in conversion) {
        if(_.has(from, i) &&
                (!_.isFunction(filter) || filter(from[i]))) {
            to[conversion[i]] = from[i];
        }
    }
};

/**
 * Checks if the parameter is a string or a number.
 * @param {any} variable
 * @returns {boolean}
 */
Prologram.Utils.isStringOrNumber = function(variable) {
    return !isNaN(parseFloat(variable)) || _.isString(variable);
}

/**
 * Merges an object into another object (by reference), setting values by path
 * rather than replacing entire branches.
 * @param {object} obj
 * @param {object} into
 * @returns {bool} Success (true) or failure (false).
 */
Prologram.Utils.mergeObjectInto = function(obj, into) {
    if(!_.isObject(obj) || !_.isObject(into)) {
        Prologram.Log.warn("First parameter must be object.", obj, into);
        return false;
    }

    for(var i in obj) {
        if(_.isObject(obj[i]) && _.isObject(into[i])) {
            Prologram.Utils.mergeObjectInto(obj[i], into[i]);
        } else {
            into[i] = obj[i];
        }
    }

    return true;
}

// Retuns object property value by a string key path ex Object.byString(obj, 'part3[0].name');
Object.byString = function(o, s) {
    s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
    s = s.replace(/^\./, '');           // strip a leading dot
    var a = s.split('.');
    while (a.length) {
        var n = a.shift();
        if (n in o) {
            o = o[n];
        } else {
            return;
        }
    }
    return o;
}

function isCyclic (obj) {
    var path = [];
    var stack = [];

    function detect (obj, objKey) {

        var itIsCyclic = false;

        stack.push(objKey);
        path.push(obj);


        _.forOwn(obj, function(value, key) {
            if (!_.isObject(value)) {
                return;
            }
            if (_.find(path, function(object) {
                if (value === object) {
                    return true;
                }
                return false;
            })) {
                itIsCyclic = true;
                return false;
            }
        });

        if (itIsCyclic) {
            return itIsCyclic;
        }

        _.forOwn(obj, function(value, key) {
            if (!_.isObject(value)) {
                return;
            }
            if (detect(value, key)) {
                itIsCyclic = true;
                return false;
            }
        });

        stack.pop();
        path.pop();

        return itIsCyclic;
    }

    return detect(obj, 'ROOT');
}



function isSimpleValue(value) {
    if (_.isFinite(value) || _.isString(value)) {
        return true;
    }

    return false;
}

function ensureIsArray(array) {
    if (!_.isArray(array)) {
        array = [array];
    }
    return array;
}

// if value is undefined returns defaultValue else returns undefined
function resolve(value, defaultValue) {
    if (value === undefined) {
        return defaultValue;
    }

    return value;
}

// for _.includes() '1' !== 1, this function treats them the same
function includesLoose(array, item, fromIndex) {
    if (_.isObject(item) || _.isArray(item)) {
        return _.includes(array, item, fromIndex);
    }

    var intItem = parseInt(item, 10);
    var strItem = item.toString();

    var result = _.includes(array, strItem, fromIndex);

    if (!result) {
        result = _.includes(array, intItem, fromIndex);
    }

    return result;
}

function ensureTrailingSlash(string) {
    if(string.slice(-1) !== '/') {
            string += "/";
    }

    return string;
}

function isPromise(promise) {
    if (!_.isObject(promise)) {
        return false;
    }

    if (!_.has(promise, 'then') || !_.isFunction(promise.then) || !_.has(promise, 'done') || !_.isFunction(promise.done)) {
        return false;
    }

    return true;
}

function hasStructure(object, structure) {
    if (!_.isObject(structure)) {
        return (typeof(structure) === typeof(object));
    }
    if (!_.isObject(object)) {
        return false;
    }

    var result = true;

    _.forOwn(structure, function(value, key) {
        if (!_.has(object, key)){
            result = false;
            console.error(key + ' key was not found');
            return false;
        }

        if (value !== undefined && typeof(object[key]) !== typeof(value)) {
            result = false;
            console.error(key + 'key is not of type ' + typeof(value));
            return false;
        }
    })

    return result;
}

// Log error message and return error value; you can use this like
// return withError('someMessage']) -> will log message and return false
// return withError(['someMessage', someVar], ) will log the array and return someVar
function withError(errorParameters, errorValueToReturn) {
    console.error(errorParameters);
    return resolve(errorValueToReturn, false);
}

function log() {
    console.log(arguments);
}

function stripTags(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

function isValidDate(date) {
    if (date instanceof Date) {
        return true;
    }

    return false;
}

function diffDates(date1, date2) {
    if (!isValidDate(date1)) {
        return 0;
    }

    if (!isValidDate(date2)) {
        return 0;
    }

    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

    return diffDays;
}

function diffDatesInYears(date1, date2) {
    return Math.trunc(diffDates(date1, date2) / 365);
}
