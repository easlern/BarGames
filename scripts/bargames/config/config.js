/**
 * Created by jortgonfreit on 9/28/14.
 */
angular.module('bargamesApp').config(function ($stateProvider, $urlRouterProvider) {
    //
    // For any unmatched url, redirect to home state
    $urlRouterProvider.otherwise('/home');

    //
    // Now set up the states
    $stateProvider
        .state('home', {
            url: "/home",
            views: {
                'navigation': {
                    templateUrl: "scripts/bargames/partials/homeNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/homeContent.html"
                }
            }
        })
        .state('search', {
            url: "/search",
            views: {
                'navigation': {
                    templateUrl: "scripts/bargames/partials/mainNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/searchContent.html",
                    controller: "searchCtrl"
                }
            }
        })
        .state('userProfile', {
            url: "/user",
            views: {
                'navigation': {
                    templateUrl: "scripts/bargames/partials/mainNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/userProfile.html",
                    controller: "userProfileCtrl"
                }
            }
        })
        .state('businessProfile', {
            url: "/business",
            views: {
                'navigation': {
                    templateUrl: "scripts/bargames/partials/mainNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/businessProfile.html",
                    controller: "businessProfileCtrl"
                }
            }
        })
        .state('groupPage', {
            url: "/group",
            views: {
                'navigation': {
                    templateUrl: "scripts/bargames/partials/mainNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/groupPage.html",
                    controller: "groupPageCtrl"
                }
            }
        })
});