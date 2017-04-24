app.controller('PrescribeInvController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $modalInstance) {
	

	$scope.invNameData = {};
	$scope.selectedInvID = 0;
	$scope.invSettingData = [];
	$scope.prescribedInvData = [];
	$scope.invsttingNameData = {};
	$scope.invAdderData = {};
	$scope.addByName = false;

    $scope.prescription = function (num) {
        $modalInstance.close(true);
    };

    $scope.getInvName = function(term) {
        
    	var dataString = 'query=0'+ '&invName=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.invNameData = result.data;
        	return limitToFilter($scope.invNameData, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectInvName = function(item, model, label){
		  $scope.selectedInvID = item.id;
		  $scope.addByName = true;
	  };
	  
	
	$scope.addToDoctorPreference = function (){
		
		$scope.selectedInvID = 0;
		
		var displayOrder = 1;
		if($scope.invSettingData != undefined && $scope.invSettingData.length > 0){
			displayOrder = parseInt($scope.invSettingData[$scope.invSettingData.length -1].displayOrder) + 1;
		}
		
		var dataString = 'query=2'+ '&invName=' + $scope.invName + '&displayOrder=' + displayOrder;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.docTorINVSetter = false;
        	$scope.invName = "";
        	$scope.bringINVDetail();
        });
		
	};
	
	$scope.delINVFromSetting = function (invSettingID){
		
		var dataString = 'query=6'+ '&invSettingID=' + invSettingID;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringINVDetail();
        });
	};
	
	$scope.addORDelINV = function (addedToPrescription,inv){
		inv.addedToPrescription = addedToPrescription;
		if(addedToPrescription){
			
			$scope.addInvToPresciption(inv.name, "");
			
	        
		}else{
			$scope.deleteInvByInvID(inv.id);
		}
	};
	
	$scope.addInvToPresciption = function (invName,note){
		
		var dataString = 'query=4'+ '&invName=' + invName + '&note=' + note;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedInv();
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
        	$scope.bringPrescribedInv();
        	$scope.bringINVDetail();
        });
	};
	
	$scope.deleteInvByInvID = function (invID){
		
		var dataString = 'query=10'+ '&invID=' + invID;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedInv();
        	
        });
	};
	
	$scope.bringINVDetail = function (){


        var dataString = "query=15";

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.invCategoryList = result;
            $scope.invCategoryList.push({name : "No Category", invCategoryID : 0});
            var dataString = "query=1";

            $http({
                method: 'POST',
                url: "phpServices/inv/invService.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
                $scope.invSettingData = result;
                angular.forEach($scope.invSettingData, function(value, key) {
                    if(parseInt(value.prescribedInvID) > 0){
                        value.addedToPrescription = true;
                    }else{
                        value.addedToPrescription = false;
                    }
                });
            });

        });


	};
	
	$scope.bringPrescribedInv = function (){
		
		$scope.invAdderData = {};
		
		var dataString = "query=7";

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedInvData = result;
        	$scope.numberOfInvAdded = $scope.prescribedInvData.length;
        });
	};
	
	
	$scope.prepareInvAdderData = function(invAdderData){
		
		if(!invAdderData.note){
			invAdderData.note = "";
		}
		
		if(invAdderData.addByTypeHead){
			$scope.addInvToPresciption(invAdderData.id, invAdderData.note);
			$scope.bringPrescribedInv();
		}else{
			
			var dataString = 'query=3'+ '&invName=' + invAdderData.name;

	        $http({
	            method: 'POST',
	            url: "phpServices/inv/invService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.addInvToPresciption(result, invAdderData.note);
	        	$scope.bringPrescribedInv();
	        });
	        
		}
	};
	
	
	
	
	
	
	$scope.addInvToPrescription = function(){
		
		var invAdderData = {};
		
		var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/inv/addInvModal.html',
            windowClass: 'fade in',
            controller: 'PrescribeInvController.InvMasterContoller',
            resolve: {
            	record: function () {
                    return {
                    	invAdderData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPrescribedInv();
	     });
	};
	
	$scope.editFromPresciption = function  (invAdderData){
		
		
		var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/inv/addInvModal.html',
            windowClass: 'fade in',
			size : 'sm',
            controller: 'PrescribeInvController.InvMasterContoller',
            resolve: {
            	record: function () {
                    return {
                    	invAdderData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPrescribedInv();
	     });
		
	};
	  
	
	(function(){
		$scope.bringINVDetail();
		$scope.bringPrescribedInv();
    })()

	
});

app.controller('PrescribeInvController.InvMasterContoller', function($scope, $http, $modalInstance, limitToFilter, $filter, record) {

	$scope.invAdderData = {};

	if(record.invAdderData.id){
		$scope.invAdderData = record.invAdderData;
	}else{
		$scope.invAdderData = {};
		$scope.invAdderData.note = "";
	}

	$scope.getInvNameForMaster = function(term){


		var dataString = 'query=8'+ '&invName=' + term;

        return $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.invsttingNameData = result.data;
        	return limitToFilter($scope.invsttingNameData, 10);
        });
	};

	$scope.onSelectInvNameMaster = function (item, model, label){
	};

	$scope.save = function(another){

		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {

			var dataString = "";
			if($scope.invAdderData.id){

				dataString = 'query=9'+ '&invName=' + $scope.invAdderData.invName + '&note=' + $scope.invAdderData.note + '&ID=' + $scope.invAdderData.id;
			}else{
				dataString ='query=4'+ '&invName=' + $scope.invAdderData.invName + '&note=' + $scope.invAdderData.note;

			}

			 $http({
		            method: 'POST',
		            url: "phpServices/inv/invService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	if(another){
                        $scope.invAdderData = {};
                        $scope.error = false;
                        $scope.success = true;
                        $scope.errorMessage = "Inv added to your prescription, Please add another";
					}else{
                        $modalInstance.close();
					}
		        });
		}else{
            $scope.success = false;
			$scope.error = true;
		}




	};

	$scope.cancel = function(){
		$modalInstance.close();
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