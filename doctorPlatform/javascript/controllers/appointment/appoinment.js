app.controller('AppointmentController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.numberOfAppointment = 0;
 	$scope.limit = 10;
 	$scope.addMoreToLimit = 10;
 	$scope.appointmentList = [];
 	$scope.doctorData = {};
 	$scope.followUpSearch = false;
 	$scope.patientName = "";
 	
 	
    $scope.bringDoctorInfo = function (){
    	
        var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.doctorData = result;
        	$rootScope.doctorData = $scope.doctorData;
        });
    };
    
    $scope.bringAppointment = function (){
    	
        var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.appointmentList = result;
        	$scope.numberOfAppointment = $scope.appointmentList.length;
        });
    };
    
    $scope.saveNewPatient =  function(patientCode){
    	
        var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/appointment/addNewPatient.html',
            windowClass: 'fade in',
            
            controller: 'AppointmentController.AddNewPatientController',
            resolve: {
            	patientCode: function () {
                    return patientCode;
                }
            },
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
        	$scope.bringAppointment();
         });
    	
    };
    
    $scope.addNewAppointment = function () {
    	
        var  dataString='doctorCode='+ $scope.doctorData.doctorCode +'&patientCode='+ $scope.doctorData.personCodeInitial +'&doctorID='+ $scope.doctorData.doctorID +'&query='+3;
        

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringAppointment();
        });
    };
    
    $scope.showHelp = function(){    	
    	$scope.modalInstance = $modal.open({
			templateUrl: 'javascript/templates/header/helpMenuPopup.html',
            controller: 'appointmentController.InformationModalController',
            size: 'sm',
            resolve: {
            	modalConfig: function () {
            		var data = {};
            		data.title = "Help Desk";
                    return data;
                }
            }
		});
    };
    
    $scope.addFollowUpAppointment = function (doctorCode){
    	window.location = "followUpAppoinment.php";
    };
    
    $scope.letsPrescribe = function (appointMentData){
    	

        var  dataString = 'patientCode='+ appointMentData.patientCode  +'&appointmentID='+ appointMentData.appointmentID +'&query='+4;
        

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$location.path("/prescription");
        });
    };
    
    $scope.getPatients = function(term) {
        
        var  dataString='data='+  term +'&query='+5;
        
        return $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.patients = result.data;
        	return limitToFilter($scope.patients, 10);
        });

        
       // return $scope.products;
      };
      
      $scope.onSelectPatient = function(item, model, label){
    	  alert(item.phone);
    	  $scope.addByName = true;
      };
    

	
	(function(){
		$scope.bringDoctorInfo();
    	$scope.bringAppointment();
    })()

	
});

app.controller('AppointmentController.InformationModalController', function($scope, $modalInstance) {
	
	$scope.title = "";
	$scope.message = "";
	
	$scope.onOkClicked = function() {
		$modalInstance.dismiss('cancel');
	};
	
	(function() {
		
		$scope.title = "Information"
		
		//$scope.message = modalConfig.message;
		
	})();
	
});

app.controller('AppointmentController.AddNewPatientController', function($scope, $modalInstance, patientCode, $http) {
	
	$scope.patientData = {};
	$scope.error = false;
	$scope.errorMessage = "";
	$scope.patientData.sex = "MALE";
	
	$scope.createNewPatient = function (){
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
			var dataString = 'patientCode='+ patientCode +'&name='+ $scope.patientData.name +'&age='+ $scope.patientData.age +'&address='+ $scope.patientData.address + '&sex=' + $scope.patientData.sex +'&phone='+ $scope.patientData.phone +'&query=2';

	        $http({
	            method: 'POST',
	            url: "phpServices/appointment/appointmentHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$modalInstance.close(result);
	        });
		}else{
			$scope.error = true;
		}
		

    }
	
	$scope.cancelNewPatient = function (){
		$modalInstance.dismiss('cancel');
	};
	
	
});