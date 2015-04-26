/**
 *  Implement 'raty' directive in Angular. 
 *  On click - Call back function is setRating(). Implement setRating() in the angular controller to set value into model
 */
app.directive("raty", function() {
    return {
        restrict: 'AE',
        link: function(scope, elem, attrs) {
            $(elem).raty({
                score : scope[attrs.ngModel] === null || scope[attrs.ngModel] === undefined ? 0 : scope[attrs.ngModel],
                half : true,
                click : function(score, event) {
                    scope.$apply(function() {
                        scope.setRating(attrs.ngModel, score);
                    });
                },
                path : 'images/raty',
                starOn : 'star-on.png',
                starOff : 'star-off.png',
                cancel : true
            }); 
        }
    }
});