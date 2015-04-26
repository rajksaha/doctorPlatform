/*
 * This directive is used for OTA type(policy, activity, amenity, attraction) delete confirmation
 * 
 * confirmationCondition: callback function which checks condition whether modal needs to be open or not. If not pass, then it's checked against undefined value
 * confirmationSuccess: callback function after user confirms(press ok button)
 * confirmationCancel: callback function after user cancels(press cancel button)
 * item: callback function's parameter
 */

app.directive('ktrOtaConfirmation', function($modal) {
    return {
    restrict: 'AE',
    scope: {
        confirmationCondition: '&ktrOtaConfirmation',
        confirmationSuccess: '&',
        confirmationCancel: '&',
        item: '=item',
    },
    link: function(scope, elem, attrs) {
        elem.on('click', function() {
            if(scope.confirmationCondition({item: scope.item}) == true) {
                var ModalInstanceCtrl = function($scope, $modalInstance) {
                    $scope.confirmationTitle = attrs.confirmationTitle == undefined ? "Confirmation" : attrs.confirmationTitle;
                    $scope.confirmationMessage = attrs.confirmationMessage == undefined ? "Are you sure?" : attrs.confirmationMessage;
                    
                    $scope.ok = function() {
                        $modalInstance.close();
                    };

                    $scope.cancel = function() {
                        $modalInstance.dismiss('cancel');
                    };
                };
                
                var modalInstance = $modal.open({
                    templateUrl: 'javascript/templates/confirmationModal.html',
                    controller: ModalInstanceCtrl,
                    windowClass: 'fade in modalConfirmation'
                });
                
                modalInstance.result.then(function(result) {
                    scope.confirmationSuccess({item: scope.item});
                },
                function() {
                    scope.confirmationCancel({item: scope.item});
                });
            } else {
                scope.confirmationSuccess({item: scope.item});
            }
        });
    }
    }
});