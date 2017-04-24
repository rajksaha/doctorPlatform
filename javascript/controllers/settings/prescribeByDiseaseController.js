app.controller('PrescribeByDiseaseController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, JsonService, $window) {
	
	
	$scope.masterDiseaseData = {};
	$scope.drugSettingList = [];
	$scope.invSettingList = [];
	$scope.advieSettingList = [];
	
	$scope.doctorData = {};

	$scope.diagnosisData = {};
	
	
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
    
	
	$scope.bringSettings = function (){
		
		
			
		var dataString = "query=" + 3 + '&diagnosisName=' + $scope.diagnosisData.diseaseName;
		

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	
        	$scope.masterDiseaseData.diseaseID = parseInt(result);
        	
        	$scope.bringData();
        });
	};
	
	$scope.bringDrugSettingData = function (diseaseID){
		
		var dataString = "query=0" + "&diseaseID=" + diseaseID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugSettingList = result;
        	 
        });
	};
	
	$scope.bringInvSettingData = function (diseaseID){
		
		var dataString = "query=1" + "&diseaseID=" + diseaseID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.invSettingList = result;
        	 
        });
	};
	
	
	$scope.bringAdviceSettingData = function (diseaseID){
		
		var dataString = "query=2" + "&diseaseID=" + diseaseID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.advieSettingList = result;
        	 
        });
	};
	
	$scope.delAdviceFromSetting = function (advciceSettingID){
		
		var dataString = "query=10" + "&advciceSettingID=" + advciceSettingID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringAdviceSettingData($scope.masterDiseaseData.diseaseID);
        	 
        });
	};
	
	$scope.deleteInvFromSetting = function (invSettingID){
		
		var dataString = "query=11" + "&invSettingID=" + invSettingID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringInvSettingData($scope.masterDiseaseData.diseaseID);
        	 
        });
	};
	
	$scope.deleteDrugsFromSetting = function (drugSettingID){
		
		var dataString = "query=12" + "&drugSettingID=" + drugSettingID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringDrugSettingData($scope.masterDiseaseData.diseaseID);
        	 
        });
	};
	
	
	
	$scope.modalForDrugs = function(){
		
	var prescription = {};
		prescription.diseaseID = $scope.masterDiseaseData.diseaseID;
		prescription.doctorID = $scope.doctorData.doctorID;
	var modalInstance = $modal.open({
        templateUrl: 'javascript/templates/settings/addDrugModal.html',
        windowClass: 'fade in',
        
        controller: 'PrescribeSettingsController.AddDrugsToSettings',
        resolve: {
        	data: function () {
                return {
                	prescription
                };
            }
        },
        backdrop: 'static'
    });
    modalInstance.result.then(function(result) {
    	$scope.bringDrugSettingData($scope.masterDiseaseData.diseaseID);
     });
    
	};
	
	$scope.modalForInv = function(){
		
		var prescription = {};
			prescription.diseaseID = $scope.masterDiseaseData.diseaseID;
			prescription.doctorID = $scope.doctorData.doctorID;
		var modalInstance = $modal.open({
	        templateUrl: 'javascript/templates/settings/addInvModal.html',
	        windowClass: 'fade in',
	        
	        controller: 'PrescribeSettingsController.AddInvToSettings',
	        resolve: {
	        	data: function () {
	                return {
	                	prescription
	                };
	            }
	        },
	        backdrop: 'static'
	    });
	    modalInstance.result.then(function(result) {
	    	$scope.bringInvSettingData($scope.masterDiseaseData.diseaseID);
	     });
	    
		};
		
	$scope.modalForAdvice = function(){
		
		var prescription = {};
			prescription.diseaseID = $scope.masterDiseaseData.diseaseID;
			prescription.doctorID = $scope.doctorData.doctorID;
		var modalInstance = $modal.open({
	        templateUrl: 'javascript/templates/settings/addAdviceModal.html',
	        windowClass: 'fade in',
	        
	        controller: 'PrescribeSettingsController.AddAdvcieToSettings',
	        resolve: {
	        	data: function () {
	                return {
	                	prescription
	                };
	            }
	        },
	        backdrop: 'static'
	    });
	    modalInstance.result.then(function(result) {
	    	$scope.bringAdviceSettingData($scope.masterDiseaseData.diseaseID);
	     });
	    
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
	
	$scope.bringData = function (){
		
		$scope.bringDrugSettingData($scope.masterDiseaseData.diseaseID);
    	$scope.bringInvSettingData($scope.masterDiseaseData.diseaseID);
    	$scope.bringAdviceSettingData($scope.masterDiseaseData.diseaseID);
    	$scope.diseaseSelected = true;
	};
	
	(function(){
		$scope.bringDoctorInfo();
    })()
	
});



app.controller('PrescribeSettingsController.AddInvToSettings', function($scope, $modalInstance, data, $http, $window, $location,limitToFilter) {
	
	$scope.postData = data;
	$scope.postData.note = "";
	
	$scope.createInvSetting = function (){
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
			var dataString = "query=6" + '&diseaseID=' + $scope.postData.prescription.diseaseID + '&doctorID=' + $scope.postData.prescription.doctorID + "&invName=" + $scope.postData.invName + "&note=" + $scope.postData.note;

	        $http({
	            method: 'POST',
	            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$modalInstance.close();
	        });
		}else{
			$scope.error = true;
		}
		
        
    };
    
    $scope.getInvNameForMaster = function(term){
		
		
		var dataString = 'query=8'+ '&queryString=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.invsttingNameData = result.data;
        	return limitToFilter($scope.invsttingNameData, 10);
        });
	};
	
	$scope.onSelectInvNameMaster = function (item, model, label){
	};
	
	$scope.cancel = function (){
		$modalInstance.dismiss('cancel');
	};
	
	
});

app.controller('PrescribeSettingsController.AddAdvcieToSettings', function($scope, $modalInstance, data, $http, $window, $location,limitToFilter) {
	
	$scope.postData = data;
	
	$scope.type = 0;
	
	$scope.saveNewAdviceSetting = function (){
		
		
			
			if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
				
			
				var dataString = "query=7" + '&diseaseID=' + $scope.postData.prescription.diseaseID + '&doctorID=' + $scope.postData.prescription.doctorID + "&adviceName=" + $scope.name;

		        $http({
		            method: 'POST',
		            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	$modalInstance.close();
		        });
		        
			}else{
				$scope.error = true;
			}
		
    };
    
    $scope.getAdvcieName = function(term) {
        
    	var dataString = 'query=9'+ '&queryString=' + term + '&lang=' + $scope.type;
        
        return $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.adviceNameData = result.data;
        	return limitToFilter($scope.adviceNameData, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectAdviceName = function(item, model, label){
		  $scope.name = item.advice;
	  };
    

	
	$scope.cancel = function (){
		$modalInstance.dismiss('cancel');
	};
	
	
	
	
});




/*function lookupDrug(inputString) {
	if(inputString.length == 0) {
		$('.drugSuggetionBox').fadeOut(); // Hide the suggestions box
	} else {
			var type = parseInt($("#drugTypeAdder").val()) + 1 ;
            $.post("phpServices/prescriptionSetting/prescriptionSetting.php", {drugName: ""+inputString+"", query : 4 , drugType : type}, function(data) { // Do an AJAX call
			$('.drugSuggetionBox').fadeIn(); // Show the suggestions box
			$('.drugSuggetionBox').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteDrugs(dataString) {
	$('.drugAdderName').val(dataString);
	$('.drugSuggetionBox').fadeOut();
	$('.drugSuggetionBox').hide();
}

function lookupInv(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBox').fadeOut(); // Hide the suggestions box
	} else {
            $.post("phpServices/diagnosis/diagnosis.php", {queryString: ""+inputString+"", query : 0}, function(data) { // Do an AJAX call
			$('.suggetionBoxInv').fadeIn(); // Show the suggestions box
			$('.suggetionBoxInv').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteInv(dataString) {
	$('.adderNameInv').val(dataString);
	$('.suggetionBoxInv').fadeOut();
	$('.suggetionBoxInv').hide();
}

function lookupInv(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBoxInv').fadeOut(); // Hide the suggestions box
	} else {
            $.post("phpServices/prescriptionSetting/prescriptionSetting.php", {queryString: ""+inputString+"", query : 8}, function(data) { // Do an AJAX call
			$('.suggetionBoxInv').fadeIn(); // Show the suggestions box
			$('.suggetionBoxInv').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteInv(dataString) {
	$('.adderNameInv').val(dataString);
	$('.suggetionBoxInv').fadeOut();
	$('.suggetionBoxInv').hide();
}

function lookupAdvice(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBoxAdvice').fadeOut(); // Hide the suggestions box
	} else {
			var lang = parseInt($("#langSelector").val()) ;
            $.post("phpServices/prescriptionSetting/prescriptionSetting.php", {queryString: ""+inputString+"", query : 9, lang : lang}, function(data) { // Do an AJAX call
			$('.suggetionBoxAdvice').fadeIn(); // Show the suggestions box
			$('.suggetionBoxAdvice').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteAdvice(dataString) {
	$('.adderNameAdvice').val(dataString);
	$('.suggetionBoxAdvice').fadeOut();
	$('.suggetionBoxAdvice').hide();
}*/