app.controller('invReportController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.patientData = {};
	$scope.oldAppoinmentList =[];
	$scope.invReportList = [];
	$scope.appoinmentData ={};
	$scope.patientStateList = [];
	
	$scope.bringPatientInfo = function(){
		
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.patientData = result;
        	
        	$scope.bringPatientOldPrescription($scope.patientData.patientID)
        });
	};
	
    
	$scope.bringPatientOldPrescription = function (patientID){
    	
		var dataString = "query=0" + '&patientID=' + patientID;

        $http({
            method: 'POST',
            url: "phpServices/oldPrescription/oldPrescription.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.oldAppoinmentList = result;
        	$scope.appoinmentData.selector = $scope.oldAppoinmentList[0];
        	$scope.getInveports($scope.appoinmentData.selector.appointmentID);
        });
    };
    
    $scope.getInveports = function(appointmentID){
    	
    	
    	var dataString = "query=0" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/invReports/invReportHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.invReportList = result;
        });
    };
    
    $scope.editInvReport = function(invData){
    	
    	angular.forEach($scope.invReportList, function(value, key) {
			value.otherEditMode = true;
		});
		
    	invData.oterEditMode = false;
    	invData.editMode = true;
    };
    
    $scope.saveInvReport = function(invData){
    	
    	if(validator.validateForm("#validateReq","#lblMsg",null)) {
    		
    		var dataString = "";
        	if(invData.savedreportID){
        		dataString = "query=2" + '&savedreportID=' + invData.savedreportID + "&invResult=" + invData.result + "&invStatus=" + true;
        	}else{
        		dataString = "query=1" + '&invPrescribeID=' + invData.id + "&invResult=" + invData.result + "&invStatus=" + true;
        	}

            $http({
                method: 'POST',
                url: "phpServices/invReports/invReportHelper.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
            	$scope.succcess = true;
				$scope.error = false;
				$scope.message = "Information Updated Successfully";
            	$scope.getInveports($scope.appoinmentData.selector.appointmentID);
            });
            
    	}else{
    		$scope.error = true;
    		$scope.message = "";
    		$scope.succcess = false;
    	}
    	
    };
    
	
	$scope.prescribedInvData = [];
	
	$scope.bringPrescribedInv = function (appointmentID){
		
		$scope.invAdderData = {};
		
		var dataString = "query=1" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedInvData = result;
        });
	};
	
	
	

	$scope.inIt = function (){
		$scope.bringPatientInfo();
		
	};
	
	(function(){
		$scope.inIt();
    })()

	
});