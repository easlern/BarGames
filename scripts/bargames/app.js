'use strict';

var app = angular.module('bargamesApp', [
    'ui.bootstrap',
    'bargames-notifications',
    'bargames-services',
    'bargames-controllers'
]);

/* declare rootscope variables */
app.run(function ($rootScope, $http) {

});

/*************************************************************/
/// Static Methods
/*************************************************************/

/* takes two arrays and returns any matching elements as an array */
app.compare = function (array1, array2) {
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
app.removeA = function (arr) {
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
 * Dynamically load a file
 */
app.load = function (fileName) {
    /** check for array */
    if (!angular.isArray(fileName)) fileName = [fileName];

    /** check file type */
    angular.forEach(fileName, function (fn, i) {
        if (fn.indexOf('.js') > -1 && $('script[data-filename="' + fn + '"]').length < 1) app.loadJS(fn);
        if (fn.indexOf('.css') > -1 && $('link[data-filename="' + fn + '"]').length < 1) app.loadCSS(fn);
    });
};

/*
 * Dynamically load a javascript file
 */
app.loadJS = function (fileName) {
    /** load the javascript file */
    $.ajax({
        url: fileName,
        type: 'GET',
        cache: true,
        async: false,
        dataType: 'text',
        success: function (js) {
            /** write the source file to the body and add sourceURL for debugging purposes */
            $('body').append('<script type="text/javascript" data-filename="' + fileName + '">' + js + '\n\n//@ sourceURL=' + location.protocol + '//' + location.hostname + fileName + '</script>');
        }
    });
};

/*
 * Dynamically load a css file
 */
app.loadCSS = function (fileName) {
    /** load the css file */
    $('head').append('<link type="text/css" rel="stylesheet" href="' + fileName + '" data-filename="' + fileName + '" />');
};

/*
 * Check to see if a module is loaded
 */
app.moduleExists = function (moduleName) {
    try { var mod = angular.module(moduleName); }
    catch (ex) { return false; }
};

/* create a unique id */
app.uniqueID = function () {
    /** return the number of seconds since epoch */
    var r = Math.random().toString(36).substr(2);
    return Date.now().toString(36) + r;
};

app.load('/scripts/bargames/notifications/main.js');
app.load('/scripts/bargames/services/main.js');
app.load('/scripts/bargames/controllers/main.js');