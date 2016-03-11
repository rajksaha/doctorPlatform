angular.module('ktrTablet.directive', [])
	.directive('multiMenu', function ($timeout) {
		return {
			restrict: 'E',
			replace: true,
			scope: {
				menuItems: '='
			},
			template: '<ul><li ng-repeat="menuItem in menuItems"><a href="{{menuItem.link}}">{{menuItem.text}}</a> \
						<ul ng-if="menuItem.submenu.length > 0"> \
							<li ng-repeat="subMenuItem in menuItem.submenu"><a href="{{subMenuItem.link}}">{{subMenuItem.text}}</a></li> \
						</ul>\
						</li></ul>',
			link : function (scope, element, attrs) {
				// wait for data to be populated
				scope.$watch('menuItems', function(newValue, oldValue) {										
					if (newValue !== oldValue){
                    	// finally wait for timeout so anuglar can do binding
						// before letting jquery ui do it's magic ...
						$timeout(function() {
							$(element).menu();
						});						
					}
           	 	});
			}
		}		
	});