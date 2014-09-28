'use strict';

angular.module('bargamesApp', [
    'ui.bootstrap'
]);

/* declare rootscope variables */
angular.module('bargamesApp').run(function ($rootScope, $http) {

});

/*************************************************************/
/// Static Methods
/*************************************************************/

/* takes two arrays and returns any matching elements as an array */
angular.module('bargamesApp').compare = function (array1, array2) {
    var foundValues = [];
    for(var i = 0; i < array1.length; i++)
    {
        if(array2.indexOf(array1[i]) > -1)
        {
            foundValues.push(array1[i]);
        }
    }

    return foundValues;
};

/* remove an element from an array */
angular.module('bargamesApp').removeA = function (arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length)
    {
        what = a[--L];
        while ((ax = arr.indexOf(what)) !== -1)
        {
            arr.splice(ax, 1);
        }
    }
    return arr;
};


/*
 * Check to see if a module is loaded
 */
angular.module('bargamesApp').moduleExists = function (moduleName) {
    try { var mod = angular.module(moduleName); }
    catch (ex) { return false; }
};

/* create a unique id */
angular.module('bargamesApp').uniqueID = function () {
    /** return the number of seconds since epoch */
    var r = Math.random().toString(36).substr(2);
    return Date.now().toString(36) + r;
};