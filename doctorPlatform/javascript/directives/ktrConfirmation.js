/*
*  This directive is used to open confirmation modal 
* 
*  ktrConfirmation: callback function after user confirms(press ok button)
*  confirmationCondition: callback function which checks condition whether modal needs to be open or not. If not pass, then it's checked against undefined value
*  item: callback function's parameter
*  moreItem: callback function's parameter. If undefined then we call callback function with with item parameter.
*/

app.directive('ktrConfirmation', function($modal) {
    return {
    restrict: 'AE',
    scope: {
        ktrConfirmation: '&',
        confirmationCondition: '&',
        item: '=item',
        moreItem: '=moreItem'
    },
    link: function(scope, elem, attrs) {
        
            elem.bind('click', function() {
                var ModalInstanceCtrl = function($scope, $modalInstance) {
                    $scope.confirmationTitle = attrs.confirmationTitle == undefined ? "Confirmation" : attrs.confirmationTitle;
                    $scope.confirmationMessage = attrs.confirmationMessage == undefined ? "Are you sure?" : attrs.confirmationMessage;
                    
                    $scope.ok = function() {
                        $modalInstance.close(1);
                    };
                    
                    $scope.cancel = function() {
                        $modalInstance.dismiss('cancel');
                    };
                };
                
                var modalOpen = true;
                
                if(scope.confirmationCondition() != undefined) {
                    if(scope.moreItem != undefined) {
                        var modalOpen = scope.confirmationCondition({item: scope.item, moreItem: scope.moreItem});
                    } else {
                        var modalOpen = scope.confirmationCondition({item: scope.item});
                    }
                }
                
                if(modalOpen) {
                    var modalInstance = $modal.open({
                        templateUrl: 'javascript/templates/confirmationModal.html',
                        controller: ModalInstanceCtrl,
                        windowClass: 'fade in modalConfirmation'
                    });
                    
                    modalInstance.result.then(function(result) {
                        if(scope.moreItem != undefined) {
                            scope.ktrConfirmation({item: scope.item, moreItem: scope.moreItem});
                        } else {
                            scope.ktrConfirmation({item: scope.item});
                        }
                    },
                    function() {
                    });
                }
            });
        }
    }
});