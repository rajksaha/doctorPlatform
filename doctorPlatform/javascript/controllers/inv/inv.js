app.controller('PrescribeInvController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	

	$scope.invNameData = {};
	$scope.selectedInvID = 0;
	$scope.invSettingData = [];
	$scope.prescribedInvData = [];
	$scope.invsttingNameData = {};
	$scope.invAdderData = {};
	$scope.addByName = false;
	
	$scope.addButton = "<span class='glyphicon glyphicon glyphicon-plus' aria-hidden='true'></span>";
	
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
	  
	$scope.prepareDoctorSettingData = function (){
		
		if($scope.addByName == false){
			
			var dataString = 'query=3'+ '&invName=' + $scope.invName;

	        $http({
	            method: 'POST',
	            url: "phpServices/inv/invService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.addToDoctorPreference(result);
	        });
			
		}else{
			
			$scope.addToDoctorPreference($scope.selectedInvID);
		}
	};
	
	$scope.addToDoctorPreference = function (invID){
		
		$scope.selectedInvID = 0;
		$scope.invName = "";
		
		var displayOrder = 1;
		if($scope.invSettingData != undefined && $scope.invSettingData.length > 0){
			displayOrder = parseInt($scope.invSettingData[$scope.invSettingData.length -1].displayOrder) + 1;
		}
		
		var dataString = 'query=2'+ '&invID=' + parseInt(invID) + '&displayOrder=' + displayOrder;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
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
			
			$scope.addInvToPresciption(inv.id, "");
			
	        
		}else{
			$scope.deleteInvFromPrescibtion(inv.id);
		}
	};
	
	$scope.addInvToPresciption = function (invId,note){
		
		var dataString = 'query=4'+ '&invID=' + parseInt(invId) + '&note=' + note;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedInv();
        });
	};
	
	$scope.deleteInvFromPrescibtion = function (invId){
		
		var dataString = 'query=5'+ '&invID=' + parseInt(invId);

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
	
	$scope.onSelectInvNameMaster = function (item, model, label){
		$scope.invAdderData.id = item.id;
		$scope.invAdderData.addByTypeHead = true;
	};
	
	$scope.editFromPresciption = function  (invAdderData){
		
		$scope.invAdderData.name = invAdderData.invName;
		$scope.invAdderData.note = invAdderData.note;
		$scope.invAdderData.oldData = false;
		$scope.invAdderData.id = invAdderData.invID;
		$scope.invAdderData.addedID = invAdderData.id;
		$scope.invAdderData.addByTypeHead = false;
		$scope.invAdderData.editMode = true;
		$scope.docTorINVAdder = true;
		
	};
	
	$scope.prepareInvEditData = function (invAdderData){
		
		if(!invAdderData.note){
			invAdderData.note = "";
		}
		
		$scope.docTorINVAdder = false;
		
		if(invAdderData.addByTypeHead){
			$scope.updateInvPrecription(invAdderData);
			$scope.bringPrescribedInv();
		}else if(invAdderData.name == $scope.invAdderData.name){
			$scope.updateInvPrecription(invAdderData);
			$scope.bringPrescribedInv();
		}else{
			
			var dataString = 'query=3'+ '&invName=' + invAdderData.name;

	        $http({
	            method: 'POST',
	            url: "phpServices/inv/invService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	invAdderData.id = result;
	        	$scope.updateInvPrecription(invAdderData);
	        	$scope.bringPrescribedInv();
	        });
	        
		}
	};
	
	$scope.updateInvPrecription = function (invAdderData){
		
		var dataString = 'query=9'+ '&invID=' + parseInt(invAdderData.id) + '&note=' + invAdderData.note + '&ID=' + invAdderData.addedID;

        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	
        });
	};
	  
	
	(function(){
		$scope.bringINVDetail();
		$scope.bringPrescribedInv();
    })()

	
});