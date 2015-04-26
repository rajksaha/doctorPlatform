app.directive('scroller', function () {
    return {
        restrict: 'A',
        link: function (scope, elem, attrs) {
            rawElement = elem[0];
            elem.on('scroll', function () {
                if((rawElement.scrollTop + rawElement.offsetHeight + 5) >= rawElement.scrollHeight) {
                    scope.$apply(elem.attr('scroller'));
                }
            });
        }
    };
});