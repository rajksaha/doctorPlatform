app.controller('PrescribeComplainController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.symptom = {};
	
	$scope.init = function(){
		
    };

    $scope.getSymptoms = function(term) {
    	var data = {'data': term, 'query': 1};
        
        return $http({
            method: 'POST',
            url: "phpServices/complain/complainService.php",
            data: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        }).then(function(result) {
        	$scope.symptoms = result.data;
        	return limitToFilter($scope.symptoms, 10);
        });
    };
    
      $scope.onSelectSymptoms = function(item, model, label){
    	  alert(item.name);
      };

    $scope.init();
});