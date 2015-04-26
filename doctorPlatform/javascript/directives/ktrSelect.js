/*
 * header-value			- this is the text will shown as first item into list
 * header-key			- this is the key value against header text 
 * list					- json list from which select will be populated
 * list-key				- name of the list item key field
 * list-value			- name of the list item key field (text shown into dropdown)
 * selected-item		- if want to select an item at the loading time
 * on-change			- this function will call on click event into given list
 * btn-style-class		- name of button style class (optional)
 * list-style-class		- name of list style class (optional)
 * 
 * example
 * 
 * $scope.selectedItem={ code: 'AK', name: 'Alaska' };
 * $scope.myItems = [
            { code: 'AL', name: 'Alabama' },
            { code: 'AK', name: 'Alaska' },
            { code: 'AZ', name: 'Arizona' }
        ];
 * $scope.callbackFunc = function (item) {
		$scope.selectedItem = item;
	};

 *<style>
 *.btnStyle{width: 170 !important;}
 *.liStyle{width: 195 !important;}
 *</style>	

 * <div 
		ktr-select
		header-value="--Select--"
		header-key="0"
		list-value="name"
		list-key="code"
		list="myItems"
		selected-item="selectedItem"
		on-change="callbackFunc(item)"
		btn-style-class="btnStyle"
		list-style-class="liStyle">
	</div>
 */
app.directive('ktrSelect', function () {

        return {
            restrict: 'A',
            scope: {
                list: '=',
                listValue: '@',
                listKey: '@',
                ngModel:'=',
				selectedItem: '=',
				callback: '&onChange'
            },
            template: '<div class="propSelect btn-group btn-block">' +
                '<button class="btn col-sm-11 btn-default dropdown-toggle {{btnStyleClass}}" data-toggle="dropdown">{{currentItemLabel}}</button>' +
                '<button class="btn btn-default col-sm-1 dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>' +
                '<ul class="dropdown-menu btn-block {{listStyleClass}}" role="menu">' +
			        '<li ng-repeat="item in list"  ng-click="selectVal(item)">' +
                        '<a href=""  tabindex="-1" > {{item[listValue]}}</a>' +
                    '</li>' +
                '</ul>',
            link: function (scope, element, attrs, ngModelCtrl) {
            	
            	if(typeof(attrs.listStyleClass) == "undefined"){
					scope.listStyleClass = '';
				}else{
					scope.listStyleClass = attrs.listStyleClass;
				}
				if(typeof(attrs.btnStyleClass) == "undefined"){
					scope.btnStyleClass = '';
				}else{
					scope.btnStyleClass = attrs.btnStyleClass;
				}
				var listKey = scope.listKey.toString().trim();
                var listValue = scope.listValue.toString().trim();
                
                scope.setLabel = function() {
                   scope.currentItemLabel = scope.selectedItem[listValue].toString();
                };

                scope.selectVal = function (_item) {
                	scope.selectedItem = _item;
                    scope.setLabel();
    				if (scope.callback && typeof(scope.callback) === "function") {
    					scope.callback({item : _item});
    				}
                };

				if(typeof(scope.selectedItem) != "undefined" && scope.selectedItem && scope.selectedItem[listValue] != undefined){
					var found = false;
					angular.forEach(scope.list, function (item) {
						if(!found){
							if (scope.selectedItem[listValue].toString() === item[listValue].toString()) {
								scope.selectVal(scope.selectedItem);
								found = true;
							}
						}
                    });
				}else{
					scope.selectedItem = scope.list[0];
				}
				scope.setLabel();
				
				scope.$watch('selectedItem',function(v){
					scope.selectVal(v);
	            },true);
				
            }
        };
    });
    



    


