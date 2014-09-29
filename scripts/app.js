"use strict";
angular.module("bargamesApp", ["ui.bootstrap", "ui.router"]), angular.module("bargamesApp").run(["$rootScope", "$http", function () {
}]), angular.module("bargamesApp").compare = function (array1, array2) {
    for (var foundValues = [], i = 0; i < array1.length; i++)array2.indexOf(array1[i]) > -1 && foundValues.push(array1[i]);
    return foundValues
}, angular.module("bargamesApp").removeA = function (arr) {
    for (var what, ax, a = arguments, L = a.length; L > 1 && arr.length;)for (what = a[--L]; -1 !== (ax = arr.indexOf(what));)arr.splice(ax, 1);
    return arr
}, angular.module("bargamesApp").moduleExists = function (moduleName) {
    try {
        {
            angular.module(moduleName)
        }
    } catch (ex) {
        return!1
    }
}, angular.module("bargamesApp").uniqueID = function () {
    var r = Math.random().toString(36).substr(2);
    return Date.now().toString(36) + r
}, angular.module("bargamesApp").config(["$stateProvider", "$urlRouterProvider", function ($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise("/home"), $stateProvider.state("home", {url: "/home", views: {navigation: {templateUrl: "scripts/bargames/partials/homeNav.html"}, mainContent: {templateUrl: "scripts/bargames/partials/homeContent.html"}}}).state("search", {url: "/search", views: {navigation: {templateUrl: "scripts/bargames/partials/mainNav.html"}, mainContent: {templateUrl: "scripts/bargames/partials/searchContent.html", controller: "searchCtrl"}}})
}]), angular.module("bargamesApp").controller("searchCtrl", ["$scope", "$rootScope", "searchSvc", function ($scope) {
    $scope.businesses = [
        {name: "Vitale's Restauraunt", distance: "1.5", street: "167 Fulton St. SW", city: "Grand Rapids", state: "MI", zip: "49548", phone: "(616) 334-3452"}
    ]
}]), angular.module("bargamesApp").service("searchSvc", ["$http", "$rootScope", function () {
}]);
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm1vZHVsZS5qcyIsImNvbmZpZy9jb25maWcuanMiLCJjb250cm9sbGVycy9zZWFyY2hDdHJsLmpzIiwic2VydmljZXMvc2VhcmNoU3ZjLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBLFlBRUEsU0FBQSxPQUFBLGVBQ0EsZUFDQSxjQUlBLFFBQUEsT0FBQSxlQUFBLEtBQUEsYUFBQSxRQUFBLGVBU0EsUUFBQSxPQUFBLGVBQUEsUUFBQSxTQUFBLE9BQUEsUUFFQSxJQUFBLEdBREEsZ0JBQ0EsRUFBQSxFQUFBLEVBQUEsT0FBQSxPQUFBLElBRUEsT0FBQSxRQUFBLE9BQUEsSUFBQSxJQUVBLFlBQUEsS0FBQSxPQUFBLEdBSUEsT0FBQSxjQUlBLFFBQUEsT0FBQSxlQUFBLFFBQUEsU0FBQSxLQUVBLElBREEsR0FBQSxNQUFBLEdBQUEsRUFBQSxVQUFBLEVBQUEsRUFBQSxPQUNBLEVBQUEsR0FBQSxJQUFBLFFBR0EsSUFEQSxLQUFBLElBQUEsR0FDQSxNQUFBLEdBQUEsSUFBQSxRQUFBLFFBRUEsSUFBQSxPQUFBLEdBQUEsRUFHQSxPQUFBLE1BT0EsUUFBQSxPQUFBLGVBQUEsYUFBQSxTQUFBLFlBQ0EsSUFBQSxDQUFBLFFBQUEsT0FBQSxhQUNBLE1BQUEsSUFBQSxPQUFBLElBSUEsUUFBQSxPQUFBLGVBQUEsU0FBQSxXQUVBLEdBQUEsR0FBQSxLQUFBLFNBQUEsU0FBQSxJQUFBLE9BQUEsRUFDQSxPQUFBLE1BQUEsTUFBQSxTQUFBLElBQUEsR0N0REEsUUFBQSxPQUFBLGVBQUEsUUFBQSxpQkFBQSxxQkFBQSxTQUFBLGVBQUEsb0JBR0EsbUJBQUEsVUFBQSxTQUlBLGVBQ0EsTUFBQSxRQUNBLElBQUEsUUFDQSxPQUNBLFlBQ0EsWUFBQSwwQ0FFQSxhQUNBLFlBQUEsaURBSUEsTUFBQSxVQUNBLElBQUEsVUFDQSxPQUNBLFlBQ0EsWUFBQSwwQ0FFQSxhQUNBLFlBQUEsK0NBQ0EsV0FBQSxvQkMzQkEsUUFBQSxPQUFBLGVBQUEsV0FBQSxjQUFBLFNBQUEsYUFBQSxZQUFBLFNBQUEsUUFDQSxPQUFBLGFBRUEsS0FBQSx1QkFDQSxTQUFBLE1BQ0EsT0FBQSxvQkFDQSxLQUFBLGVBQ0EsTUFBQSxLQUNBLElBQUEsUUFDQSxNQUFBLHNCQ1RBLFFBQUEsT0FBQSxlQUFBLFFBQUEsYUFBQSxRQUFBLGFBQUEiLCJmaWxlIjoiYXBwLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJ3VzZSBzdHJpY3QnO1xuXG5hbmd1bGFyLm1vZHVsZSgnYmFyZ2FtZXNBcHAnLCBbXG4gICAgJ3VpLmJvb3RzdHJhcCcsXG4gICAgJ3VpLnJvdXRlcidcbl0pO1xuXG4vKiBkZWNsYXJlIHJvb3RzY29wZSB2YXJpYWJsZXMgKi9cbmFuZ3VsYXIubW9kdWxlKCdiYXJnYW1lc0FwcCcpLnJ1bihmdW5jdGlvbiAoJHJvb3RTY29wZSwgJGh0dHApIHtcblxufSk7XG5cbi8qKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqL1xuLy8vIFN0YXRpYyBNZXRob2RzXG4vKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cblxuLyogdGFrZXMgdHdvIGFycmF5cyBhbmQgcmV0dXJucyBhbnkgbWF0Y2hpbmcgZWxlbWVudHMgYXMgYW4gYXJyYXkgKi9cbmFuZ3VsYXIubW9kdWxlKCdiYXJnYW1lc0FwcCcpLmNvbXBhcmUgPSBmdW5jdGlvbiAoYXJyYXkxLCBhcnJheTIpIHtcbiAgICB2YXIgZm91bmRWYWx1ZXMgPSBbXTtcbiAgICBmb3IodmFyIGkgPSAwOyBpIDwgYXJyYXkxLmxlbmd0aDsgaSsrKVxuICAgIHtcbiAgICAgICAgaWYoYXJyYXkyLmluZGV4T2YoYXJyYXkxW2ldKSA+IC0xKVxuICAgICAgICB7XG4gICAgICAgICAgICBmb3VuZFZhbHVlcy5wdXNoKGFycmF5MVtpXSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICByZXR1cm4gZm91bmRWYWx1ZXM7XG59O1xuXG4vKiByZW1vdmUgYW4gZWxlbWVudCBmcm9tIGFuIGFycmF5ICovXG5hbmd1bGFyLm1vZHVsZSgnYmFyZ2FtZXNBcHAnKS5yZW1vdmVBID0gZnVuY3Rpb24gKGFycikge1xuICAgIHZhciB3aGF0LCBhID0gYXJndW1lbnRzLCBMID0gYS5sZW5ndGgsIGF4O1xuICAgIHdoaWxlIChMID4gMSAmJiBhcnIubGVuZ3RoKVxuICAgIHtcbiAgICAgICAgd2hhdCA9IGFbLS1MXTtcbiAgICAgICAgd2hpbGUgKChheCA9IGFyci5pbmRleE9mKHdoYXQpKSAhPT0gLTEpXG4gICAgICAgIHtcbiAgICAgICAgICAgIGFyci5zcGxpY2UoYXgsIDEpO1xuICAgICAgICB9XG4gICAgfVxuICAgIHJldHVybiBhcnI7XG59O1xuXG5cbi8qXG4gKiBDaGVjayB0byBzZWUgaWYgYSBtb2R1bGUgaXMgbG9hZGVkXG4gKi9cbmFuZ3VsYXIubW9kdWxlKCdiYXJnYW1lc0FwcCcpLm1vZHVsZUV4aXN0cyA9IGZ1bmN0aW9uIChtb2R1bGVOYW1lKSB7XG4gICAgdHJ5IHsgdmFyIG1vZCA9IGFuZ3VsYXIubW9kdWxlKG1vZHVsZU5hbWUpOyB9XG4gICAgY2F0Y2ggKGV4KSB7IHJldHVybiBmYWxzZTsgfVxufTtcblxuLyogY3JlYXRlIGEgdW5pcXVlIGlkICovXG5hbmd1bGFyLm1vZHVsZSgnYmFyZ2FtZXNBcHAnKS51bmlxdWVJRCA9IGZ1bmN0aW9uICgpIHtcbiAgICAvKiogcmV0dXJuIHRoZSBudW1iZXIgb2Ygc2Vjb25kcyBzaW5jZSBlcG9jaCAqL1xuICAgIHZhciByID0gTWF0aC5yYW5kb20oKS50b1N0cmluZygzNikuc3Vic3RyKDIpO1xuICAgIHJldHVybiBEYXRlLm5vdygpLnRvU3RyaW5nKDM2KSArIHI7XG59OyIsIi8qKlxuICogQ3JlYXRlZCBieSBqb3J0Z29uZnJlaXQgb24gOS8yOC8xNC5cbiAqL1xuYW5ndWxhci5tb2R1bGUoJ2JhcmdhbWVzQXBwJykuY29uZmlnKGZ1bmN0aW9uKCRzdGF0ZVByb3ZpZGVyLCAkdXJsUm91dGVyUHJvdmlkZXIpIHtcbiAgICAvL1xuICAgIC8vIEZvciBhbnkgdW5tYXRjaGVkIHVybCwgcmVkaXJlY3QgdG8gaG9tZSBzdGF0ZVxuICAgICR1cmxSb3V0ZXJQcm92aWRlci5vdGhlcndpc2UoJy9ob21lJyk7XG5cbiAgICAvL1xuICAgIC8vIE5vdyBzZXQgdXAgdGhlIHN0YXRlc1xuICAgICRzdGF0ZVByb3ZpZGVyXG4gICAgICAgIC5zdGF0ZSgnaG9tZScsIHtcbiAgICAgICAgICAgIHVybDogXCIvaG9tZVwiLFxuICAgICAgICAgICAgdmlld3M6e1xuICAgICAgICAgICAgICAgICduYXZpZ2F0aW9uJzp7XG4gICAgICAgICAgICAgICAgICAgIHRlbXBsYXRlVXJsOiBcInNjcmlwdHMvYmFyZ2FtZXMvcGFydGlhbHMvaG9tZW5hdi5odG1sXCJcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICdtYWluQ29udGVudCc6IHtcbiAgICAgICAgICAgICAgICAgICAgdGVtcGxhdGVVcmw6IFwic2NyaXB0cy9iYXJnYW1lcy9wYXJ0aWFscy9ob21lY29udGVudC5odG1sXCJcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pXG4gICAgICAgIC5zdGF0ZSgnc2VhcmNoJyx7XG4gICAgICAgICAgICB1cmw6IFwiL3NlYXJjaFwiLFxuICAgICAgICAgICAgdmlld3M6e1xuICAgICAgICAgICAgICAgICduYXZpZ2F0aW9uJzp7XG4gICAgICAgICAgICAgICAgICAgIHRlbXBsYXRlVXJsOiBcInNjcmlwdHMvYmFyZ2FtZXMvcGFydGlhbHMvbWFpbm5hdi5odG1sXCJcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICdtYWluQ29udGVudCc6e1xuICAgICAgICAgICAgICAgICAgICB0ZW1wbGF0ZVVybDogXCJzY3JpcHRzL2JhcmdhbWVzL3BhcnRpYWxzL3NlYXJjaGNvbnRlbnQuaHRtbFwiLFxuICAgICAgICAgICAgICAgICAgICBjb250cm9sbGVyOiBcInNlYXJjaEN0cmxcIlxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcbn0pOyIsIi8qKlxuICogQSBjb250cm9sbGVyIGZvciBzZWFyY2hpbmcgdGhlIGRhdGFiYXNlXG4gKi9cbmFuZ3VsYXIubW9kdWxlKCdiYXJnYW1lc0FwcCcpLmNvbnRyb2xsZXIoXCJzZWFyY2hDdHJsXCIsIGZ1bmN0aW9uICgkc2NvcGUsICRyb290U2NvcGUsIHNlYXJjaFN2Yykge1xuICAgICRzY29wZS5idXNpbmVzc2VzID0gW1xuICAgICAgICB7XG4gICAgICAgICAgICBuYW1lOiBcIlZpdGFsZSdzIFJlc3RhdXJhdW50XCIsXG4gICAgICAgICAgICBkaXN0YW5jZTogXCIxLjVcIixcbiAgICAgICAgICAgIHN0cmVldDogXCIxNjcgRnVsdG9uIFN0LiBTV1wiLFxuICAgICAgICAgICAgY2l0eTogXCJHcmFuZCBSYXBpZHNcIixcbiAgICAgICAgICAgIHN0YXRlOiBcIk1JXCIsXG4gICAgICAgICAgICB6aXA6IFwiNDk1NDhcIixcbiAgICAgICAgICAgIHBob25lOiBcIig2MTYpIDMzNC0zNDUyXCJcbiAgICAgICAgfVxuICAgIF07XG4gICAgLyogaW5zdGFudGlhdGlvbiBtZXRob2RzICovXG59KTsiLCIvKipcbiAqIEEgc2VydmljZSBmb3Igc2VhcmNoaW5nIHRoZSBkYXRhYmFzZVxuICovXG5hbmd1bGFyLm1vZHVsZSgnYmFyZ2FtZXNBcHAnKS5zZXJ2aWNlKFwic2VhcmNoU3ZjXCIsIGZ1bmN0aW9uICgkaHR0cCwgJHJvb3RTY29wZSkge1xuXG59KTsiXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=