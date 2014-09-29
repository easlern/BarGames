/**
 * A controller for searching the database
 */
angular.module('bargamesApp').controller("searchCtrl", function ($scope, $rootScope, searchSvc) {
    $scope.businesses = [
        {
            name: "Vitale's Restauraunt",
            distance: "1.5",
            street: "167 Fulton St. SW",
            city: "Grand Rapids",
            state: "MI",
            zip: "49548",
            phone: "(616) 334-3452"
        }
    ];
    /* instantiation methods */
});