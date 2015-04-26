/*angular.module('ktrTablet.directive', [])
	.directive('jqmSlider', function ($timeout) {
		return {
			restrict: 'E',
			replace: true,
			scope: {
				data: '='
			},
			template: '<input ng-repeat="item in data" type="range" min="{{item.min}}" max="{{item.max}}" value="{{item.value}}"/>',
			//template: '{{data.min}}',
			link : function (scope, element, attrs, ngModelCtrl) {
				// wait for data to be populated
				scope.$watch('data', function(newValue, oldValue) {										
					if (newValue !== oldValue){
                    	// finally wait for timeout so anuglar can do binding
						// before letting jquery ui do it's magic ...
						$timeout(function() {							
							$(element).parent().trigger("create");
							
							$(element).parent().bind('slidestop', function(event, ui) {
								console.log("update happen");
								scope.$apply();
							});															
						});						
					}
           	 	});								
			}
		}		
	});*/

angular.module('ktrTablet.directive', [])
	.directive('jqmSlider', function ($timeout) {
		return {
			require:"ngModel",
			compile: function () {
				return function (scope, elm, attrs, ngModel) {
					elm.bind('slide', function(event, ui) {
						ngModel.$setViewValue(ui.values || ui.value);
						scope.$apply();
					});					
				}
			}
		}
	});