app.controller('PrescriptionController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter, $window, JsonService, $upload) {
	
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

	$scope.hideMenu= false;

    $scope.toggoleButton = function () {
        $scope.hideMenu = !$scope.hideMenu;
    };

    $scope.getComplainString = function (complain) {

    	var data = complain.symptomName;

        if(complain.durationID < 5){
			data = data + " " + complain.durationNum + " " + complain.durationType
		}
        if(complain.durationID == 7){
            data = data + " " + complain.durationType
        }

        return data;
    };
    $scope.onFileSelect = function($files){
        $scope.file = $files[0];
        $scope.uploading = true;
        $scope.hasCsvError = false;

        $upload.upload({
            url : 'phpServices/prescription/savePhoto.php',
            method: 'POST',
            data : {},
            file: $scope.file
        }).then(function(result) {
        	$scope.bringPatientInfo();
        }, function(result) {
            $scope.uploading = false;
        }, function(evt) {

        });
    };




	$scope.fixNextVisit = function (){
		
		$scope.nextVisitData.needSaveButton = false;
		
		var filteredDate = "";
		var numOfDay = 0;
		var dayType = 0;
		
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
	
	$scope.menuPopUp = function (popUp) {
		if(popUp = 'history'){
            $scope.historyModal();
		}

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
        $scope.bringClinicalRecord($scope.appoinmentData.appointmentID);
    };


    $scope.clinicalRecordList = [];
    $scope.bringClinicalRecord = function (appointmentID) {


        var dataString = "query=11" + '&appointmentID=' + appointmentID + '&contentType=' + 'CLINICAL_RECORD';

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.clinicalRecordList = result;
        });
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
    $scope.invModal = function () {

        var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/inv/invModal.html',
            windowClass: 'fade in',
            controller: 'PrescribeInvController',
            size: 'lg',
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
            $scope.bringPrescribedInv($scope.appoinmentData.appointmentID);
        });
    };

    $scope.adviceModal = function () {

        var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/advice/adviceModal.html',
            windowClass: 'fade in',
            controller: 'PrescribeAdviceController',
            size: 'lg',
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
            $scope.bringPrescribedAdvice($scope.appoinmentData.appointmentID);
        });
    };

    $scope.addVital = function () {

        var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/prescription/vitalModal.html',
            windowClass: 'fade in',
            controller: 'PrescribeVitalController',
            size: 'lg',
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
            $scope.bringPrescribedVital($scope.appoinmentData.appointmentID);
        });
    };

    $scope.historyModal = function () {

        var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/history/pastHistory.html',
            windowClass: 'fade in',
            controller: 'PastHistoryController',
            size: 'lg',
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
            $scope.bringPrescribedHistory($scope.appoinmentData.appointmentID);
        });
    };


	$scope.addCCToPrescription = function(){
		
		var copmplainData = {};
		
		var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/complain/complain.html',
            windowClass: 'center-modal',
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
            		$rootScope.defaultPdf = "default";
            		$scope.openPdf("default");
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
        	 $window.open("mpdf/" + pdf + ".php", '_blank');
        	 $location.path("/appointment");
        	 
        });
    };
    
    $scope.performDiganosis = function (diagnosisData) {
    	
		var modalInstance = $modal.open({
			templateUrl: 'javascript/templates/diagnosis/diagnosis.html',
            windowClass: 'fade in',
            size: 'sm',
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

    $scope.cancelPatientInfo = function(){
        $scope.patientInfoEdit = false;
    };

	$scope.inIt = function (){
		$scope.bringDoctorInfo();
		$scope.bringPatientInfo();
		$scope.bringMenu();
		
		
	};

    $scope.inIt();

	
});









