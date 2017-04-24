app.controller('SettingSelectionController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, JsonService, $window) {
	
	

	$scope.changePage = function (page) {

		$scope.selectedPage = page;
		if(page == 1){
			$scope.pageName = "Create Template";
		}else if(page == 2){
            $scope.pageName = "Inv Category";
		}else if(page == 3){
            $scope.pageName = "Bangla Drug Advice";
        }else if(page == 4){
            $scope.pageName = "Default Follow-up";
        }else if(page == 5){
			alert("Work on progress, Coming soon");
        }else if(page == 6){
            alert("Work on progress, Coming soon");
        }

        $scope.detailView = true;
    };
	
});