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
                    templateUrl: "scripts/bargames/partials/home/homeNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/home/homeContent.html"
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
                    templateUrl: "scripts/bargames/partials/search/searchContent.html",
                    controller: "searchCtrl"
                }
            }
        })
        .state('userProfile', {
            url: "/user",
            abstract: true,
            template: '<div ui-view></div>',
            views: {
                'navigation': {
                    templateUrl: "scripts/bargames/partials/mainNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/userProfile/userProfile.html",
                    controller: "userProfileCtrl as user"
                }
            }
        })
        .state('userProfile.profile', {
            url: "",
            templateUrl: "scripts/bargames/partials/userProfile/userProfile-profile.html",
            controller: "userProfileProfileCtrl as userProfile"
        })
        .state('userProfile.friends', {
            url: "/userFriends",
            templateUrl: "scripts/bargames/partials/userProfile/userProfile-friends.html",
            controller: "userProfileFriendsCtrl"
        })
        .state('userProfile.following', {
            url: "/userFollowing",
            templateUrl: "scripts/bargames/partials/userProfile/userProfile-following.html",
            controller: "userProfileFollowingCtrl"
        })
        .state('userProfile.followers', {
            url: "/userFollowers",
            templateUrl: "scripts/bargames/partials/userProfile/userProfile-followers.html",
            controller: "userProfileFollowersCtrl"
        })
        .state('userProfile.groups', {
            url: "/userGroups",
            templateUrl: "scripts/bargames/partials/userProfile/userProfile-groups.html",
            controller: "userProfileGroupsCtrl"
        })
        .state('businessProfile', {
            url: "/business",
            views: {
                'navigation': {
                    templateUrl: "scripts/bargames/partials/mainNav.html"
                },
                'mainContent': {
                    templateUrl: "scripts/bargames/partials/businessProfile/businessProfile.html",
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
                    templateUrl: "scripts/bargames/partials/groupPage/groupPage.html",
                    controller: "groupPageCtrl"
                }
            }
        })
});