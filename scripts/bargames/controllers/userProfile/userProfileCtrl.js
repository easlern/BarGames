/**
 * A controller for handling user profiles
 */
angular.module('bargamesApp').controller("userProfileCtrl", function ($scope, userProfileSvc) {

    this.mainInfo = {
        FirstName : "John",
        LastName : "Appleseed",
        Blurb : "I'm a beer craving, sports loving, super fly hot guy. I'm crazy in love with the Detroit Lions. I frequent many bars in the Grand Rapids area and would love to start a club revolving around them.",
        ProfileImagePath : "images/profile.jpg",
        City : "Grand Rapids",
        State : "Michigan",
        FriendQuantity : "231",
        FollowerQuantity : "352",
        FollowingQuantity : "25",
        GroupQuantity : "352"
    };
});