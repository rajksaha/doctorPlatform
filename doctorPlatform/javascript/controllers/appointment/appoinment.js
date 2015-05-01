app.controller('AppointmentController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.numberOfAppointment = 0;
 	$scope.limit = 10;
 	$scope.addMoreToLimit = 10;
 	$scope.appointmentList = [];
 	$scope.doctorData = {};
 	$scope.followUpSearch = false;
 	$scope.patientName = "";
 	$scope.addAppointMentData = {};
 	
 	
 	
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
    	
    	$scope.followUpSearch = false;
    	$scope.patientName = "";
    	$scope.addByName = false;
    	
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
    
    $scope.addNewAppointment = function () {
    	
    	var addAppointAdderData = {};
    		addAppointAdderData.doctorCode = $scope.doctorData.doctorCode;
    		addAppointAdderData.personCodeInitial = $scope.doctorData.personCodeInitial;
    	var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/appointment/addNewPatient.html',
            windowClass: 'fade in',
            
            controller: 'AppointmentController.AddNewPatientController',
            resolve: {
            	data: function () {
                    return {
                    	addAppointAdderData
                    };
                }
            },
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
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
    	

        var  dataString = 'patientCode='+ appointMentData.patientCode  +'&patientID='+ appointMentData.patientID +'&appointmentID='+ appointMentData.appointmentID +'&query='+4;
        

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
      
      $scope.onSelectNamePatient = function(item, model, label){
    	  $scope.addAppointMentData.patientCode = item.patientCode;
    	  $scope.addByName = true;
      };
      
      $scope.onSelectPhonePatient = function(item, model, label){
    	  $scope.addAppointMentData.patientCode = item.patientCode;
    	  $scope.addByPhone = true;
      };
      
      $scope.onSelectIDPatient = function(item, model, label){
    	  $scope.addAppointMentData.patientCode = item.patientCode;
    	  $scope.addByID = true;
      };
      
     $scope.addAppFollowUP  = function (){
    	 
    	 var  dataString='doctorCode='+ $scope.doctorData.doctorCode +'&patientCode='+  $scope.addAppointMentData.patientCode +'&doctorID='+ $scope.doctorData.doctorID +'&query='+3;
         
          $http({
             method: 'POST',
             url: "phpServices/appointment/appointmentHelper.php",
             data: dataString,
             headers: {'Content-Type': 'application/x-www-form-urlencoded'}
         }).then(function(result) {
        	 $scope.bringAppointment();
         });
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

app.controller('AppointmentController.AddNewPatientController', function($scope, $modalInstance, data, $http) {
	
	$scope.patientData = {};
	$scope.error = false;
	$scope.errorMessage = "";
	$scope.patientData.sex = "MALE";
	
	$scope.createNewPatient = function (){
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
			var dataString = 'doctorCode='+ data.addAppointAdderData.doctorCode + '&dotorPatInitial='+ data.addAppointAdderData.personCodeInitial +'&doctorID='+ $scope.doctorData.doctorID +
			'&name='+ $scope.patientData.name +'&age='+ $scope.patientData.age +'&address='+ $scope.patientData.address + '&sex=' + $scope.patientData.sex +'&phone='+ $scope.patientData.phone +'&query=2';

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