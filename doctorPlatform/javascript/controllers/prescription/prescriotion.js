app.controller('PrescriptionController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter, $window) {
	
	$scope.menuDataList = [];
	$scope.patientData = {};
	$scope.doctorData = {};
	$scope.patientTypeList =[];
	$scope.appoinmentData ={};
	$scope.patientStateList = [];
	
	$scope.refferedAdderData = {};
	
	$scope.prescribedDrugList = [];
	$scope.numberOfPrescribedDrugs = 0;
	
	$scope.prescribedInvData = [];
	$scope.numberOfInvAdded = 0;
	
	$scope.prescribedComplainData = [];
	
	$scope.prescribedVitalData = [];
	
	$scope.prescribedAdviceData = [];
	
	
	$scope.fixNextVisit = function (){
		
		var filteredDate = $filter('date')($scope.nextVisitData.date, "yyyy-MM-dd"); 
		
		if($scope.nextVisitData.appointmentID){
			
			
			
			var dataString = "query=7" + "&nextVisitDate=" + filteredDate + "&appointmentID=" + $scope.nextVisitData.appointmentID;

	        $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        });
	        
		}else{
			
			var dataString = "query=8" + "&nextVisitDate=" + filteredDate;

	        $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        });
	        
		}
	};
	
	$scope.refDoc = {};
	
    $scope.getRefDoctor = function(term) {
        
    	var dataString = 'query=9'+ '&refDocName=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.refDoc = result.data;
        	return limitToFilter($scope.refDoc, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectRefDocotor = function(item, model, label){
		  $scope.refferedAdderData.doctorAdress = item.doctorAdress;
		  $scope.refferedAdderData.refDocID = item.id;
	  };
	  
	  $scope.saveReffredDoctor = function(refDocData){
		  
		  
		  if(refDocData.refDocID){
			  $scope.addReffredDoctor(refDocData.refDocID);
		  }else{
			  var dataString = 'query=10'+ '&refDocName=' + refDocData.doctorName + '&refDocAdress=' + refDocData.doctorAdress;
		        
			  $http({
		            method: 'POST',
		            url: "phpServices/prescription/prescriptionHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	$scope.addReffredDoctor(result);
		        });
		  }
	  };
	  
	  $scope.addReffredDoctor = function(doctorID){
		  
		  var dataString = 'query=11'+ '&refDocID=' + parseInt(doctorID);
	        
		  $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.bringPrescribedRefferedDoctor($scope.appoinmentData.appointmentID);
	        });
	  };
	  
	  $scope.deleteReffredDoctor = function(redDocID){
		  
		  var dataString = 'query=12';
	        
		  $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.bringPrescribedRefferedDoctor($scope.appoinmentData.appointmentID);
	        });
	  };
	
	$scope.bringPatientInfo = function(){
		
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.patientData = result;
        });
	};
	
	$scope.bringMenu = function(){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.menuDataList = result;
        });
		
	};
	
    $scope.bringDoctorInfo = function (){
    	
        var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.doctorData = result;
        	if($scope.doctorData.patientType == 1){
        		
        		var dataString = "query=2" + "&doctorType=" + $scope.doctorData.category;

                $http({
                    method: 'POST',
                    url: "phpServices/prescription/prescriptionHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                	$scope.patientTypeList = result;
                });
        	}
        	
        	if($scope.doctorData.patientState== 1){
        		
        		var dataString = "query=5";

                $http({
                    method: 'POST',
                    url: "phpServices/prescription/prescriptionHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                	$scope.patientStateList = result;
                });
        	}
        	
        });
    };
    
    $scope.changePatientType = function(patientType){
    	
    	
    	var dataString = "query=3" + "&patientType=" + patientType.id + "&patientDetailID=" + $scope.patientData.patientDetailID + "&patientID=" + $scope.patientData.patientID;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.inIt();
        });
    	
    	
    };
    

    
    $scope.changePatientState = function (patientState){
    	
    	var dataString = "query=6" + "&patientState=" + patientState.id;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.appoinmentData.appointmentType = patientState.id;
        });
    };
    
    
    $scope.bringAppointmentInfo = function (){
    	
    	var dataString = "query=4";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.appoinmentData = result;
        	
        	$scope.bringPresCribedDiagnosis($scope.appoinmentData.appointmentID);
        	$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedInv($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedAdvice($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedVital($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedOBS($scope.appoinmentData.appointmentID, $scope.appoinmentData.patientID);
    		$scope.bringPrescribedMH($scope.appoinmentData.appointmentID, $scope.appoinmentData.patientID);
    		$scope.bringPrescribedNextVisit($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedRefferedDoctor($scope.appoinmentData.appointmentID);
        });
    };
    
	$scope.bringPresCribedDrugs = function (appointmentID){
		
		var dataString = "query=0" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedDrugList = result;
        	$scope.numberOfPrescribedDrugs = $scope.prescribedDrugList.length;
        });
	};
	
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
        	$scope.numberOfInvAdded = $scope.prescribedInvData.length;
        });
	};
	
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
	
	
	$scope.nextVisitData = {};
	
	$scope.bringPrescribedNextVisit = function (appointmentID){
		
		var dataString = "query=7" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(result.date){
        		$scope.nextVisitData = result;
        		
        	}else{
        		$scope.nextVisitData = {};
        		$scope.nextVisitData.date = "";
        	}
        	
        	
        });
	};
	
	$scope.refferedDoctorData = {};
	
	$scope.bringPrescribedRefferedDoctor = function (appointmentID){
		
		var dataString = "query=8" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.refferedDoctorData = result;
        });
	};
	
	
	$scope.diagnosisData = {};
	
	$scope.bringPresCribedDiagnosis = function (appointmentID){
		
		var dataString = "query=6" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.diagnosisData = result;
        });
	};
	
	
	$scope.print = function (){
		
		//chcek if there is any diagonsis set and any settings connected to it
		
		if($scope.diagnosisData.diseaseID){
			
			
			var dataString = "query=13" + '&diseaseID=' + $scope.diagnosisData.diseaseID + '&doctorID=' + $scope.doctorData.doctorID;

	        $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	if(parseInt(result) == -1){
	        		
	        		var prescriptionSettingData = {};
	        		prescriptionSettingData.diseaseID = $scope.diagnosisData.diseaseID;
	        		prescriptionSettingData.diseaseName = $scope.diagnosisData.diseaseName;
	        		prescriptionSettingData.doctorID = $scope.doctorData.doctorID;
	        		
	        		var modalInstance = $modal.open({
	                    templateUrl: 'javascript/templates/prescription/prescriptionSetting.html',
	                    windowClass: 'fade in',
	                    
	                    controller: 'PrescriptionController.PrescriptionSettingController',
	                    resolve: {
	                    	data: function () {
	                            return {
	                            	prescriptionSettingData
	                            };
	                        }
	                    },
	                    backdrop: 'static'
	                });
	                modalInstance.result.then(function(result) {
	                	$scope.printPreview();
	                 });
	                
	                
	        	}else{
	        		$scope.printPreview();
	        	}
	        });
	        
		}else{
    		$scope.printPreview();
    	}
	};
	
    $scope.printPreview = function (){
		
    	
    	var dataString = "query=15";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	 $window.open("tpdf/" + $scope.doctorData.pdfPage + ".php", '_blank');
        	 $location.path("/appointment");
        	 
        });
    	
    };

	$scope.inIt = function (){
		$scope.bringDoctorInfo();
		$scope.bringPatientInfo();
		$scope.bringMenu();
		$scope.bringAppointmentInfo();
		
	};
	
	(function(){
		$scope.inIt();
    })()

	
});

app.controller('PrescriptionController.PrescriptionSettingController', function($scope, $modalInstance, data, $http, $window, $location) {
	
	$scope.prescriptionSettingData = data;
	
	$scope.savePrint = function (){
		
		
		var dataString = "query=14" + '&diseaseID=' + $scope.prescriptionSettingData.prescriptionSettingData.diseaseID + '&doctorID=' + $scope.prescriptionSettingData.prescriptionSettingData.doctorID;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$modalInstance.close();
        });
        
    };
    

	
	$scope.printOnly = function (){
		$modalInstance.close();
	};
	
	
});