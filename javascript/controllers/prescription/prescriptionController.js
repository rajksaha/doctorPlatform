app.controller('PrescriptionController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter, $window, JsonService) {
	
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
	$scope.menuState = true;
	
	$scope.prescribedComplainData = [];
	
	$scope.prescribedVitalData = [];
	
	$scope.prescribedAdviceData = [];
	
	
	$scope.fixNextVisit = function (){
		
		$scope.nextVisitData.needSaveButton = false;
		
		var filteredDate = "";
		var numOfDay = null;
		var dayType = null;
		
		if($scope.nextVisitData.nextVisitType == 2){
			numOfDay = $scope.nextVisitData.numOfDay.value;
			dayType = $scope.nextVisitData.dayType.id;
		}else{
			filteredDate = $filter('date')($scope.nextVisitData.date, "yyyy-MM-dd");
			$scope.nextVisitData.nextVisitType = 1;
		}
		
		var dataString = "query=8" + "&nextVisitDate=" + filteredDate + "&numOfDay=" + numOfDay + "&dayType=" + dayType + "&nextVisitType=" + $scope.nextVisitData.nextVisitType;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	
        });
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
        	$scope.bringAppointmentInfo();
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
        	$scope.bringAppoinmentInfo();
        });
    };
    
    $scope.bringAppoinmentInfo = function (){
    	$scope.bringPresCribedDiagnosis($scope.appoinmentData.appointmentID);
    	$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedInv($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedAdvice($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedVital($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedFamilyHistory($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedPastHistory($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedHistory($scope.appoinmentData.appointmentID, $scope.appoinmentData.patientID);
		$scope.bringPrescribedDrugHistory($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedRefferedDoctor($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedComment($scope.appoinmentData.appointmentID);
		$scope.bringPrescribedNextVisit($scope.appoinmentData.appointmentID);
    };
    
    $scope.historyList = [];
    
    $scope.bringPrescribedHistory = function(appointmentID, patientID){
    	$scope.historyList = [];
    	angular.forEach($scope.menuDataList, function(value, key) {
    		if(value.inPrescription == 2){
    			var dataString = "query=5" + '&typeCode='+ value.defaultName  + '&appointmentID=' + appointmentID + '&patientID=' + patientID;

    	        $http({
    	            method: 'POST',
    	            url: "phpServices/commonServices/prescriptionDetailService.php",
    	            data: dataString,
    	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    	        }).success(function (result) {
    	        	if(result){
    	        		var historyData = {};
        	        	historyData.headerName = value.menuHeader;
        	        	historyData.prescribedHistoryList = result;
        	        	$scope.historyList.push(historyData);
    	        	}
    	        });
    		}
    	});
    	
    };
    
    $scope.drugHistory = [];
    
    $scope.bringPrescribedDrugHistory = function(appointmentID){
    	
    	$scope.drugHistory = [];
    	
    	var dataString = "query=11" + '&appointmentID=' + appointmentID + '&contentType=' + 'OLDDRUGS';

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(result && result.length > 0){
        		var historyData = {};
	        	historyData.headerName = "Old Drugs";
	        	historyData.prescribedDrugList = result;
	        	$scope.drugHistory.push(historyData);
        	}
        });
        
        var dataString = "query=11" + '&appointmentID=' + appointmentID + '&contentType=' + 'CURRDRUGS';

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(result && result.length > 0){
        		var historyData = {};
	        	historyData.headerName = "Current Drugs";
	        	historyData.prescribedDrugList = result;
	        	$scope.drugHistory.push(historyData);
        	}
        });
    	
    };
    
    $scope.removeDrugHistory = function (data){
    	
    	var dataString = "query=19" + '&contentDetailID=' + data.contentDetailID;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedDrugHistory($scope.appoinmentData.appointmentID);
        });
        
    };
    
    $scope.deleteInvFromPresciption = function (id){
		
		var dataString = 'query=5'+ '&id=' + id;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedInv($scope.appoinmentData.appointmentID);
        });
	};
	
	$scope.updateCommentText = function (){
		
		var dataString = "query=17" + '&comment=' + $scope.commentText;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        });
	};
    
	
	$scope.bringPrescribedComment = function (appointmentID){
		
		var dataString = "query=11" + '&appointmentID=' + appointmentID + '&contentType=' + 'COMMENT';

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(result && result.length > 0){
        		$scope.commentText = result[0].detail;
        	}
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
	

	
	
	$scope.nextVisitData = {};
	
	$scope.bringPrescribedNextVisit = function (appointmentID){
		
		
		$scope.dayList = JsonService.numberList;
		
		$scope.bringDayType = function (addMood, selectedDay, selectedDayTypeID){
			
			var dataString = "query=1";

	        $http({
	            method: 'POST',
	            url: "phpServices/drugs/drugsService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.dayTypeList = result;
	        	$scope.dayTypeList.splice(5, 1);
	        	$scope.dayTypeList.splice(4, 1);
	        	if(addMood){
	        		$scope.nextVisitData.numOfDay = $scope.dayList[7];
	        		$scope.nextVisitData.dayType = $scope.dayTypeList[0];
	        	}else{
	        		angular.forEach($scope.dayTypeList, function(value, key) {
	        			if(value.id == selectedDayTypeID){
	        				$scope.nextVisitData.dayType = value;
	        			}
	        		});
	        		angular.forEach($scope.dayList, function(data, key) {
	        			if(data.value == selectedDay){
	        				$scope.nextVisitData.numOfDay = data;
	        			}
	        		});
	        	}
	        	
	        });
			
		};
		
		
		
		
		var dataString = "query=7" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(result.date){
        		$scope.nextVisitData = result;
        		if($scope.nextVisitData.nextVisitType == 2){
        			$scope.nextVisitData.date = "";
        			$scope.bringDayType(false, $scope.nextVisitData.numOfDay, $scope.nextVisitData.dayType);
        		}else{
        			$scope.bringDayType(true, null);
        		}
        		
        	}else{
        		$scope.nextVisitData = {};
        		$scope.nextVisitData.date = "";
        		$scope.bringDayType(true, null);
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
	
	$scope.pastDiseaseList = [];
	
	$scope.bringPrescribedPastHistory = function (appointmentID){
		
		var dataString = "query=9" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.pastDiseaseList = result;
        });
	};
	
	$scope.deletePastHistory = function(id){
		
		var dataString = "query=" + 5 + "&pastHistoryID=" + id;
        
		$http({
            method: 'POST',
            url: "phpServices/history/pastHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedPastHistory($scope.appoinmentData.appointmentID);
        });
	};
	
	$scope.familyDiseaseList = [];
	
	$scope.bringPrescribedFamilyHistory = function (appointmentID){
		
		var dataString = "query=10" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.familyDiseaseList = result;
        });
	};
	
	$scope.deleteFamilyHistory = function(id){
		
		var dataString = "query=" + 5 + "&familyHistoryID=" + id;
        
		$http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedFamilyHistory($scope.appoinmentData.appointmentID);
        });
	};
	
	$scope.deleteVitalFromPrescibtion = function(id){
		
		var dataString = 'query=9'+ '&prescribedVitalID=' + id;
        $http({
            method: 'POST',
            url: "phpServices/vital/vitalService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedVital($scope.appoinmentData.appointmentID);
        });
	};
	
	$scope.deleteCCFromPresciption = function(id){
		
		var data = {'id': id, 'query': 4};
        
		$http({
            method: 'POST',
            url: "phpServices/complain/complainService.php",
            data: JSON.stringify(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
        });
	};
	
	$scope.deleteHistory = function(data){
		
        
		var dataString = 'query=4'+ '&savedHistorysID=' + data.id;
        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedHistory($scope.appoinmentData.appointmentID, $scope.appoinmentData.patientID);
        });
	};
	
	$scope.deleteAdviceFromPresciption = function (adviceId){
		
		var dataString = 'query=5'+ '&adviceID=' + parseInt(adviceId);

        $http({
            method: 'POST',
            url: "phpServices/advice/adviceService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedAdvice($scope.appoinmentData.appointmentID);
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
	
	$scope.addCCToPrescription = function(){
		
		var copmplainData = {};
		
		var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/complain/complain.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeComplainController',
            resolve: {
            	record: function () {
                    return {
                    	copmplainData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
	     });
	};
	
	$scope.editFromPresciption = function (copmplainData){

		
		var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/complain/complain.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeComplainController',
            resolve: {
            	record: function () {
                    return {
                    	copmplainData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
	     });
	};
	
	$scope.addDrugsToPrescription = function(){
		
		var drugData = {};
		
		var modalInstance = $modal.open({
			templateUrl: 'javascript/templates/drugs/drugModalNew.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeDrugsController',
            resolve: {
            	record: function () {
                    return {
                    	drugData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
	     });
		
	};
	
	$scope.editDrugsFromPresciption = function(drugDataDB){
		
		var drugData = {};
		
		drugData = drugDataDB;
		
		
		var modalInstance = $modal.open({
			templateUrl: 'javascript/templates/drugs/drugModalNew.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeDrugsController',
            resolve: {
            	record: function () {
                    return {
                    	drugData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
	     });
		
	};
	
	$scope.deletePrescribedDrug = function(drugPrescribeID){
		
		var dataString = "query=7" + '&drugPrescribeID=' + drugPrescribeID;

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
        	
        });
		
	};
	
	
    $scope.printPreview = function (){
    	
    	if(!$rootScope.defaultPdf){
    		var dataString = "query=20" + '&doctorID=' + $scope.doctorData.doctorID;

            $http({
                method: 'POST',
                url: "phpServices/prescription/prescriptionHelperService.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
            	if(result && result.length > 1){
            		
            		var modalInstance = $modal.open({
            			templateUrl: 'javascript/templates/prescription/pdfSelection.html',
                        windowClass: 'fade in',
                        
                        controller: 'PrescriptionController.PdfSelectionController',
                        resolve: {
                        	record: function () {
                                return {
                                	result
                                };
                            }
                        },
                        backdrop: 'static'
                    });
            		modalInstance.result.then(function(modalResult) {
            			$rootScope.defaultPdf = modalResult.code;
            			$scope.openPdf(modalResult.code);
            	     });
            		
            	}else if(result && result.length == 1) {
            		$rootScope.defaultPdf = result[0].code;
            		$scope.openPdf(result[0].code);
            	}else{
            		$rootScope.defaultPdf = "default.php";
            		$scope.openPdf("default.php");
            	}
            });
    	}else {
    		$scope.openPdf($rootScope.defaultPdf);
		}
    	
    	
    	
    };
    
    $scope.openPdf = function(pdf){
    	
    	var dataString = "query=15";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	 $window.open("tpdf/" + pdf + ".php", '_blank');
        	 $location.path("/appointment");
        	 
        });
    };
    
    $scope.performDiganosis = function (diagnosisData) {
    	
		var modalInstance = $modal.open({
			templateUrl: 'javascript/templates/diagnosis/diagnosis.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeDiagnosisController',
            resolve: {
            	record: function () {
                    return {
                    	diagnosisData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringAppoinmentInfo();
	     });
    };
    
    $scope.savePatientInfo = function(patientData){
    	
    	if(validator.validateForm("#validateReq","#lblMsg",null)) {
    		var dataString = 'name='+ patientData.name +'&age='+ patientData.age +'&address='+ patientData.address + '&sex=' + patientData.sex +'&phone='+ patientData.phone+ '&id='+ patientData.patientID +'&query=16';
    		
    		 $http({
		            method: 'POST',
		            url: "phpServices/prescription/prescriptionHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	$scope.patientInfoEdit = false;
		        	
		        });
    		 
    	}else{
    		alert("Please Select all required fields properly");
    	}
    	
    };

	$scope.inIt = function (){
		$scope.bringDoctorInfo();
		$scope.bringPatientInfo();
		$scope.bringMenu();
		
		
	};
	
	
	
	(function(){
		$scope.inIt();
    })()

	
});


app.controller('PrescriptionController.PdfSelectionController', function($scope, $http, $modalInstance, limitToFilter, $filter, record) {
	
	$scope.pdfList = record.result;
	
	$scope.selectPdf = function(pdf){
		$modalInstance.close(pdf);
	};
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel');
	};
	

	
});


app.controller('PrescriptionController.PrescribeDiagnosisController', function($scope, $http, $modalInstance, limitToFilter, $filter, record) {
	
	$scope.diagnosisData = {};
	
	if(record.diagnosisData.id){
		$scope.diagnosisData = record.diagnosisData;
	}else{
		$scope.diagnosisData = {};
		$scope.diagnosisData.note = "";
	}
	$scope.diagnosisNameData = {};
	
	$scope.diagnosisNote = "";
	
	$scope.save = function(){
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
			
			var dataString = "";
			if($scope.diagnosisData.id){
				
				dataString = "query=" + 3 + '&diagnosisName=' + $scope.diagnosisData.diseaseName + '&note=' + $scope.diagnosisData.note + '&id=' + $scope.diagnosisData.id;

			}else{
				dataString = "query=" + 2 + '&diagnosisName=' + $scope.diagnosisData.diseaseName + '&note=' + $scope.diagnosisData.note;
			}
			
			 $http({
		            method: 'POST',
		            url: "phpServices/diagnosis/diagnosis.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	
		        	$modalInstance.close();
		        	
		        });
		}else{
			$scope.error = true; 
		}
		
		

       
	};
	
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel');
	};
	
	$scope.getDisease = function(term) {
    	
    	var dataString = "query=" + 0 + "&data=" + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/diagnosis/diagnosis.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.diagnosisNameData = result.data;
        	return limitToFilter($scope.diagnosisNameData, 10);
        });
    };
    
      $scope.onSelectDisease = function(item, model, label){
    	  $scope.diagnosisData.diseaseName = item.name;
      };

	
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

app.controller('PrescriptionController.PrescribeComplainController', function($scope, $http, $modalInstance, JsonService, record, limitToFilter) {
	
	$scope.symptom = {};
	$scope.complainList = [];
	$scope.drugNumOfDayList = JsonService.fractionNumberList;
	$scope.drugDayTypeList = JsonService.timesADay;
	
	
	$scope.init = function(){
		if(record.copmplainData.id){
			$scope.bringdrugsDayType(false, null);
		}else{
			$scope.bringdrugsDayType(true, null);
		}
		
    };
    
	$scope.bringdrugsDayType = function (addMood, selectedDayTypeID){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugDayTypeList = result;
        	
        	//$scope.drugDayTypeList.splice(5, 1);
        	$scope.drugDayTypeList.splice(4, 1);
        	
        	if(addMood){
        		var data = {"title": "Symptom 1","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" : "","id" : 0};
        		$scope.complainList.push(data);
        		data = {"title": "Symptom 2","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" :"","id" : 0};
        		$scope.complainList.push(data);
        		data = {"title": "Symptom 3","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" :"","id" : 0};
        		$scope.complainList.push(data);
        		data = {"title": "Symptom 4","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" :"","id" : 0};
        		$scope.complainList.push(data);
        		
        	}else{
        		
        		$scope.complainData = {"title": "Symptom"};
        		
        		angular.forEach($scope.drugNumOfDayList, function(data, key) {
        			if(data.value == record.copmplainData.durationNum){
        				$scope.complainData.numOfDay = data;
        			}
        		});
        		
        		angular.forEach($scope.drugDayTypeList, function(value, key) {
        			if(value.id == record.copmplainData.durationID){
        				$scope.complainData.dayType = value;
        			}
        		});
        		$scope.complainData.id = record.copmplainData.id;
        		
        		$scope.complainData.name = record.copmplainData.symptomName;
        		
        		$scope.complainList.push($scope.complainData);
        	}
        	
        });
		
	};
	
	$scope.saveGroupOfComplain = function(){
		
		var entryFound = false;
		var int = 0;
		for (int; int < $scope.complainList.length; int++) {
			var name = $scope.complainList[int].name;
			var noOfDay = $scope.complainList[int].numOfDay.value;
			var dayType = $scope.complainList[int].dayType.id;
			var note = $scope.complainList[int].note;
			var id = $scope.complainList[int].id;
			if(name){
				entryFound = true;
				if(dayType > 4){
					noOfDay = null;
				}
				
				var dataString = {'complainName': name , 'numOfDay' : noOfDay ,'dayType' :  dayType, 'note' : note, 'complainPrescribeID' : id, 'query' : 2};
				
		        $http({
		            method: 'POST',
		            url: "phpServices/complain/complainService.php",
		            data: JSON.stringify(dataString),
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}
			
		}
		
		/*angular.forEach($scope.complainList, function(value, key) {
			if(value.name){
				entryFound = true;
				if(value.dayType.id > 4){
					value.numOfDay.value = null;
				}
				
				var dataString = {'complainName': value.name , 'numOfDay' : value.numOfDay.value ,'dayType' :  value.dayType.id, 'note' : value.note, 'complainPrescribeID' : value.id, 'query' : 2};
				
		        $http({
		            method: 'POST',
		            url: "phpServices/complain/complainService.php",
		            data: JSON.stringify(dataString),
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}
		});*/
		
		
		if(!entryFound){
			if($scope.complainList.length == 1){
				$scope.errorMessage = "Please Select Symptom Name";
				$scope.succcess = false;
				$scope.error = true;
			}else{
				$scope.errorMessage = "Please Select At-least One Symptom";
				$scope.succcess = false;
				$scope.error = true;
			}
			
		}else if(int == $scope.complainList.length){
			$modalInstance.close();
		}
		
		
		
	};
	
	
	$scope.cancelGroupOfComplain = function(){
		$modalInstance.dismiss('cancel');
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


app.controller('PrescriptionController.PrescribeDrugsController', function($scope, $http, $modalInstance, limitToFilter, JsonService, record) {
	
	$scope.drugTypeList =[];
	$scope.drugNumOfDayList = JsonService.numberList;
	$scope.drugtimesADay = JsonService.timesADay;
	$scope.drugDayTypeList =[];
	$scope.drugsWhenList =[];
	$scope.drugAdviceTypeList =[];
	$scope.drugDoseList =[];
	$scope.drugData = {};
	$scope.drugPeriodicDoseList = [];
	$scope.enteredDrugDoseList = [];
	$scope.addByName = false;
	$scope.doseList = [];
	
	$scope.drugNameList = {};
	
	$scope.cancelDrug = function (){
		$modalInstance.close();
	};
	
	
	$scope.bringPresCribedDrugs = function (){
		
		if(record.drugData.id){
			
			$scope.drugData = {};
			$scope.drugData.drugName = record.drugData.drugName;
			$scope.drugData.drugPrescribeID = record.drugData.id;
			$scope.drugData.preiodicList = record.drugData.preiodicList;
			$scope.bringdrugsDayTypeList(false, record.drugData.drugTypeID , record.drugData.drugTimeID);
			$scope.bringdrugsWhatType(false, record.drugData.drugWhenID);
			$scope.bringdrugsAdviceType(false, record.drugData.drugAdviceID);
			
		}else{
			
			$scope.drugData.drugName = "";
	    	$scope.drugData.addMood = true; 
	    	$scope.drugData.delDrug = false;
	    	$scope.drugData.editName = false;
	    	$scope.drugData.preiodicList = [];
	    	$scope.bringdrugsDayTypeList(true, 1 , 3);
			$scope.bringdrugsWhatType(true, null);
			$scope.bringdrugsAdviceType(true, null);
		}
		
	};
	
	$scope.bringdrugsDayTypeList = function (addMode, typeID, timeID){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugDayTypeList = result;
        	
        	if(addMode){
        		var periodicData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, doseDataList: [] , numOfDay : 7, durationType : 1, dose: '' };
    	    	$scope.drugData.preiodicList.push(periodicData);
    	    	
    	    	$scope.inItDrugsType(addMode, typeID, timeID, $scope.drugData.preiodicList);
        	}else{
        		
        		angular.forEach($scope.drugData.preiodicList, function(value, key) {
        			
        			value.drugDayTypeList = $scope.drugDayTypeList;
        			value.drugNumOfDayList = $scope.drugNumOfDayList;
        			value.doseDataList = [];
        		});
        		$scope.inItDrugsType(addMode, typeID, timeID, $scope.drugData.preiodicList);
        	}
        	
        	
        });
		
	};
	
	$scope.inItDrugsType = function (addMode, selectedDrugTypeID, selectedTimesADay, preiodicList){
		
		angular.forEach($scope.drugtimesADay, function(value, key) {
			if(value.code == selectedTimesADay){
				$scope.drugData.timesADay = value;
			}
		});
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugTypeList = result;
        	
        	angular.forEach($scope.drugTypeList, function(value, key) {
    			if(value.id == selectedDrugTypeID){
    				$scope.drugData.drugType = value;
    				$scope.preiodicDoseHanleler(addMode, $scope.drugData.drugType.unit, preiodicList, selectedTimesADay);
    			}
    		});
        	
        });
		
	};
	
	
	$scope.preiodicDoseHanleler = function (addMode, unit, preiodicList, selectedTimesADay){
		
		if(selectedTimesADay == -1){
			$scope.drugData.preodicValue = preiodicList.length;
			selectedTimesADay = 3;
		}else if(selectedTimesADay == -2){
			selectedTimesADay = 1;
		}
			
		angular.forEach(preiodicList, function(preiodicData, key) {
			$scope.doseHandeler(unit, preiodicData.doseDataList, selectedTimesADay, preiodicData.dose);
			
			angular.forEach(preiodicData.drugDayTypeList, function(value, key) {
				if(value.id == preiodicData.durationType){
					preiodicData.durationDayType = value;
				}
			});
			
			angular.forEach(preiodicData.drugNumOfDayList, function(data, key) {
				if(data.value == preiodicData.numOfDay){
					preiodicData.dataNumOFDay = data;
				}
			});
			
		});
	};
	
	$scope.doseHandeler = function (unit, doseDataList, selectedTimesADay, dose){
		
		var val = parseFloat(unit);
		if(dose != ''){
			$scope.enteredDrugDoseList = dose.split(' - ');
		}
		
		
		
		for(var i = 0; i< selectedTimesADay; i++){
			if($scope.enteredDrugDoseList.length > 0){
				var data = {"value" : $scope.enteredDrugDoseList[i]};
				unit = $scope.enteredDrugDoseList[i];
			}else{
				var data = {"value" : val};
				unit = val;
			}
			
			doseDataList.push(data);
		}
		$scope.enteredDrugDoseList = [];
	};
	
	$scope.doseChanger = function (change, doseDataList){
		
		angular.forEach(doseDataList, function(data, key) {
			var val = parseFloat(data.value);
			data.value = val + change;
			
		});
		
	};
	
	$scope.timeChanger = function (addMode, drugType, selectedTimesADay, preiod){
		
		$scope.drugData.preiodicList = [];
		
		if(selectedTimesADay == -1){
			
			for(var i = 0; i < preiod; i++){
				
				var periodicData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, doseDataList: [] , numOfDay : 7, durationType : 1, dose: '' };
		    	$scope.drugData.preiodicList.push(periodicData);
			}
			
			$scope.drugData.preodicValue = preiod;
	    	
		}else{
			var periodicData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, doseDataList: [] , numOfDay : 7, durationType : 1,  dose: ''};
	    	$scope.drugData.preiodicList.push(periodicData);
		}
		
    	
    	$scope.inItDrugsType(addMode,drugType.id, selectedTimesADay, $scope.drugData.preiodicList);
		
	};
	
	
	$scope.doseMaker = function (unit, numOfTime, change){
		
		var drugDoseList = [];
		
		var val = parseFloat(unit) + change;
		var data = {"value" : val};
		for(var i = 0; i< numOfTime; i++){
			if($scope.enteredDrugDoseList.length > 0){
				var data = {"value" : $scope.enteredDrugDoseList[i]};
				unit = $scope.enteredDrugDoseList[i];
			}else{
				var data = {"value" : val};
				unit = val;
			}
			
			drugDoseList.push(data);
		}
		$scope.enteredDrugDoseList = [];
		
		
		$scope.bringdrugsDayType(true , null, drugDoseList);
	};
	
	
	
		
	$scope.bringdrugsDayType = function (addMood, selectedDayTypeID, doseDataList){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugDayTypeList = result;
        	
        	
        	if(addMood){
        		
    	    	var drugDoseData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, drugDoseList: doseDataList};
    	    	$scope.drugData.doseList.push(drugDoseData);
    	    	
        	}else{
        		angular.forEach($scope.drugDayTypeList, function(value, key) {
        			if(value.id == selectedDayTypeID){
        				$scope.drugData.dayType = value;
        			}
        		});
        	}
        	
        });
		
	};
	
	$scope.bringdrugsWhatType = function (addMood, selectedWhatTypeID){
		
		var dataString = "query=2";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugWhatTypeList= result;
        	if(addMood){
        		$scope.drugData.whatType = $scope.drugWhatTypeList[0];
        	}else{
        		angular.forEach($scope.drugWhatTypeList, function(value, key) {
        			if(value.id == selectedWhatTypeID){
        				$scope.drugData.whatType = value;
        			}
        		});
        	}
        });
		
	};
	
	$scope.bringdrugsAdviceType = function (addMood, selectedAdviceTypeID){
		
		var dataString = "query=3";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugAdviceTypeList = result;
        	if(addMood){
        		$scope.drugData.adviceType = $scope.drugAdviceTypeList[0];
        	}else{
        		angular.forEach($scope.drugAdviceTypeList, function(value, key) {
        			if(value.drugAdviceID == selectedAdviceTypeID){
        				$scope.drugData.adviceType = value;
        			}
        		});
        	}
        });
		
	};
	
	$scope.saveDrug = function(isAnother) {
		
		if($scope.drugData.drugName) {
				$scope.prepareDrugSaveData(isAnother);
		}else{
			$scope.errorMessage = "Please Select Drug Name"; 
			$scope.error = true;
			$("#drugName").addClass('has-error');
		}
		
		
	};
	
	$scope.prepareDrugSaveData = function(isAnother){
		
		
		var drugType = $scope.drugData.drugType.id;
		var drugName =  $scope.drugData.drugName;
		
		var drugTime = $scope.drugData.timesADay.code;
		
		var doseUnit = "";
		if($scope.drugData.optionalInitial != undefined && $scope.drugData.optionalInitial){
			doseUnit = $scope.drugData.drugType.optionalUnitInitial;
		}else{
			doseUnit = $scope.drugData.drugType.unitInitial;
		}
		
		var drugWhen = $scope.drugData.whatType.id;
		
		var drugAdvice = $scope.drugData.adviceType.drugAdviceID;
		
		
		var query = 6;
		var drugPrescribeID = 0;
		if(!$scope.drugData.addMood){
			query = 5;
			drugPrescribeID = $scope.drugData.drugPrescribeID;
		}
		
		var dataString = 'drugType='+ drugType +'&drugName='+ drugName +'&drugStr='+ $scope.drugData.drugStr + '&drugTime='+ drugTime +'&doseUnit='+ doseUnit + '&drugWhen='+ drugWhen +'&drugAdvice='+ drugAdvice+ '&drugPrescribeID='+ drugPrescribeID +'&query=' + query;
		
        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(drugPrescribeID == 0){
        		drugPrescribeID = result;
        	}
        	angular.forEach($scope.drugData.preiodicList, function(preiodicData, key) {
    			
    			var drugDose = "";
    			
    			for(var i = 0;i < preiodicData.doseDataList.length; i++){
    				if(i == 0){
    					drugDose = preiodicData.doseDataList[i].value;
    				}else{
    					drugDose = drugDose + " - "+ preiodicData.doseDataList[i].value;
    				}
    			}
    			
    			var durationType = preiodicData.durationDayType.id;
    			
    			var numOfDay = null;
    			
    			if(durationType < 4){
    				numOfDay = preiodicData.dataNumOFDay.value;
    			}
    			
    			var dataString = "query=12" + '&drugPrescribeID=' + drugPrescribeID + '&dose=' + drugDose + '&numOfDay=' + numOfDay + '&durationType=' + durationType;

    	        $http({
    	            method: 'POST',
    	            url: "phpServices/drugs/drugsService.php",
    	            data: dataString,
    	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    	        }).success(function (result) {

    	        	
    	        });
    			
    		});
        	
        	if(isAnother){
        		$scope.bringPresCribedDrugs();
        	}else{
        		$modalInstance.close();
        	}
        	
        	
        });
		
		
		
	};
	
	
	
	$scope.deleteDrugFromDB = function(){
		
		var dataString = "query=10" + '&drugID=' + $scope.drugData.drugID;

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPresCribedDrugs();
        	
        });
		
	};
	
	$scope.editDrugName = function(){
		
        
        var dataString = "query=11" + '&drugID=' + $scope.drugData.drugID + '&drugName=' + $scope.drugData.drugName;

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(record.drugData.id){
        		$modalInstance.close();
        	}else{
        		$scope.bringPresCribedDrugs();
        	}
        	
        	
        });
	};
	
	
	
	
	
	
	
    $scope.getDrugName = function(term) {
        
    	var dataString = 'query=8'+ '&drugName=' + term + '&drugType=' + $scope.drugData.drugType.id;
        
        return $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.addByName = false;
        	$scope.drugNameList = result.data;
        	return limitToFilter($scope.drugNameList, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectDrugName = function(item, model, label){
		  $scope.drugData.drugID = item.drugID;
		  //bring settings 
		  
		  var dataString = "query=13" + '&drugID=' + $scope.drugData.drugID;

	        $http({
	            method: 'POST',
	            url: "phpServices/drugs/drugsService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	if(result){
	        		$scope.doctorDrugData = result;
					$scope.drugData.preiodicList = $scope.doctorDrugData.preiodicList;
					$scope.bringdrugsDayTypeList(false, item.typeID , $scope.doctorDrugData.drugTimeID);
					$scope.bringdrugsWhatType(false, $scope.doctorDrugData.drugWhenID);
					$scope.bringdrugsAdviceType(false, $scope.doctorDrugData.drugAdviceID);
	        	}
	        });
		  
		  $scope.drugData.drugName = item.drugName;
		  $scope.drugData.drugStr = item.strength;
		  $scope.drugData.delDrug = true;
		  $scope.drugData.editName = true;
	  };
	
	
	  $scope.bringPresCribedDrugs();
	  
	  $scope.saveToDoctorDrugSetting = function(){
		  
		  
		  var drugType = $scope.drugData.drugType.id;
			var drugName =  $scope.drugData.drugName;
			
			var drugTime = $scope.drugData.timesADay.code;
			
			var doseUnit = "";
			if($scope.drugData.optionalInitial != undefined && $scope.drugData.optionalInitial){
				doseUnit = $scope.drugData.drugType.optionalUnitInitial;
			}else{
				doseUnit = $scope.drugData.drugType.unitInitial;
			}
			
			var drugWhen = $scope.drugData.whatType.id;
			
			var drugAdvice = $scope.drugData.adviceType.drugAdviceID;
			
			
			var query = 14;
			
			var dataString = 'drugType='+ drugType +'&drugName='+ drugName +'&drugStr='+ $scope.drugData.drugStr + '&drugTime='+ drugTime +'&doseUnit='+ doseUnit + '&drugWhen='+ drugWhen +'&drugAdvice='+ drugAdvice +'&query=' + query;
			
	        $http({
	            method: 'POST',
	            url: "phpServices/drugs/drugsService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	doctorDrugID = result;
	        	angular.forEach($scope.drugData.preiodicList, function(preiodicData, key) {
	    			
	    			var drugDose = "";
	    			
	    			for(var i = 0;i < preiodicData.doseDataList.length; i++){
	    				if(i == 0){
	    					drugDose = preiodicData.doseDataList[i].value;
	    				}else{
	    					drugDose = drugDose + " - "+ preiodicData.doseDataList[i].value;
	    				}
	    			}
	    			
	    			var durationType = preiodicData.durationDayType.id;
	    			
	    			var numOfDay = null;
	    			
	    			if(durationType < 4){
	    				numOfDay = preiodicData.dataNumOFDay.value;
	    			}
	    			
	    			var dataString = "query=15" + '&doctorDrugID=' + doctorDrugID + '&dose=' + drugDose + '&numOfDay=' + numOfDay + '&durationType=' + durationType;

	    	        $http({
	    	            method: 'POST',
	    	            url: "phpServices/drugs/drugsService.php",
	    	            data: dataString,
	    	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    	        }).success(function (result) {

	    	        	
	    	        });
	    			
	    		});
	        });
	  }

	
});