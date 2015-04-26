app.controller('InformationModalController', function($scope, $modalInstance, $timeout, modalConfig) {
	
	$scope.title = "";
	$scope.message = "";
	
	$scope.onOkClicked = function() {
		if(modalConfig.okCallback && typeof modalConfig.okCallback === "function") {
			modalConfig.okCallback();
		}
		$modalInstance.dismiss('cancel');
	};
	
	(function() {
		
		if(modalConfig.title) {
			$scope.title = modalConfig.title;
		} else {
			$scope.title = "Information"
		}
		
		if(modalConfig.message) {
			$scope.message = modalConfig.message;
		}
		
		if(modalConfig.initCallback && typeof modalConfig.initCallback === "function") {
			modalConfig.initCallback();
		}
		
	})();
	
});