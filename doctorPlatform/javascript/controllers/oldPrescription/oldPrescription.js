app.controller('OldPrescriptionController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.patientData = {};
	$scope.oldAppoinmentList =[];
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
        	$scope.numberOfPrescription = $scope.oldAppoinmentList.length;
        });
    };
    
    $scope.prescribedDrugList = [];
	
	$scope.bringPresCribedDrugs = function (appointmentID){
		
		var dataString = "query=0" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedDrugList = result;
        });
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
	
	$scope.prescribedAdviceData = [];
	
	$scope.bringPrescribedAdvice = function(appointmentID){
		
		var dataString = "query=2" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedAdviceData = result;
        });
		
	};
	
	$scope.prescribedVitalData = [];
	
	$scope.bringPrescribedVital = function(appointmentID){
		
		var dataString = "query=3" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedVitalData = result;
        });
		
	};
	
	$scope.prescribedComplainData = [];
	
	$scope.bringPrescribedComplain = function(appointmentID){
		
		var dataString = "query=4" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedComplainData = result;
        });
		
	};
	
	$scope.prescribedMHData = [];
	
	$scope.bringPrescribedMH = function(appointmentID, patientID){
		
		var dataString = "query=5" + '&typeCode=MH' + '&appointmentID=' + appointmentID + '&patientID=' + patientID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedMHData = result;
        });
		
	};
	
	$scope.prescribedOBSData = [];
	
	$scope.bringPrescribedOBS = function(appointmentID, patientID){
		
		var dataString = "query=5" + '&typeCode=OBS' + '&appointmentID=' + appointmentID + '&patientID=' + patientID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedOBSData = result;
        });
		
	};
	
	$scope.prescribedDiagnosisData = [];
	
	$scope.bringPrescribedDiagnosis = function(appointmentID){
		
		var dataString = "query=6" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedDiagnosisData = result;
        });
		
	};
	

    
	
	
    $scope.viewPrescription = function (data) {
    	
    	
    	$scope.bringPresCribedDrugs(data.appointmentID);
    	$scope.bringPrescribedInv(data.appointmentID);
    	$scope.bringPrescribedAdvice(data.appointmentID);
    	$scope.bringPrescribedVital(data.appointmentID);
    	$scope.bringPrescribedComplain(data.appointmentID);
    	$scope.bringPrescribedOBS(data.appointmentID, data.patientID);
    	$scope.bringPrescribedMH(data.appointmentID, data.patientID);
    	
    	$scope.showPrescriptionView = true;
    };

	$scope.inIt = function (){
		$scope.bringPatientInfo();
		
	};
	
	(function(){
		$scope.inIt();
    })()

	
});


app.controller('OldPrescriptionController.ViewPrescriptionController', function($scope, $modalInstance, data, $http) {
	
	
	$(".modal-dialog").addClass('finalStepWidth');
	angular.element(".modal-dialog").addClass('finalStepWidth');
	$scope.$apply();
	
	
	
	$scope.cancelNewPatient = function (){
		$modalInstance.dismiss('cancel');
	};
	
	
});