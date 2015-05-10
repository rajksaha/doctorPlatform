app.controller('PrescribeDiagnosisController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter) {
	
	
	$scope.diagnosisData = {};
	$scope.diagnosisName = "";
	
	$scope.diagnosisNote = "";
	
	$scope.saveDiagnosis = function(){
		
		var dataString = "";
		if($scope.diagnosisData.id){
			
			dataString = "query=" + 3 + '&diagnosisName=' + $('.diagnosisAdderName').val() + '&note=' + $scope.diagnosisNote + '&id=' + $scope.diagnosisData.id;

		}else{
			dataString = "query=" + 2 + '&diagnosisName=' + $('.diagnosisAdderName').val() + '&note=' + $scope.diagnosisNote;
		}
		

        $http({
            method: 'POST',
            url: "phpServices/diagnosis/diagnosis.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	
        	$location.path("/prescription");
        });
	};
	
	$scope.bringDiagnosisData = function(){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/diagnosis/diagnosis.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.diagnosisData = result;
        	if($scope.diagnosisData.id){
        		$scope.diagnosisName = $scope.diagnosisData.diseaseName;
            	$scope.diagnosisNote = $scope.diagnosisData.note;
        	}
        	
        	
        });
	};
	
	
	(function(){
		
		
		$scope.bringDiagnosisData();
    })()

	
});

function lookup(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBox').fadeOut(); // Hide the suggestions box
	} else {
            $.post("phpServices/diagnosis/diagnosis.php", {queryString: ""+inputString+"", query : 0}, function(data) { // Do an AJAX call
			$('.suggetionBox').fadeIn(); // Show the suggestions box
			$('.suggetionBox').html(data); // Fill the suggestions box
		});
	}
}

function autocomplete(dataString) {
	$('.diagnosisAdderName').val(dataString);
	$('.suggetionBox').fadeOut();
	$('.suggetionBox').hide();
}