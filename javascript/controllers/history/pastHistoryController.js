app.controller('PastHistoryController', function($scope, $http, $modal, $rootScope, limitToFilter, $modalInstance, $filter) {
	
	$scope.relationList = [];

	$scope.history = {};
	
	$scope.addMoreButton = true;

    $scope.typeCode = "";

    $scope.pageNameList = ["M.H", "Obs History", "Gynaecological History", "General History", "Immunization History"];

    $scope.prescription = function () {
        $modalInstance.close(true);
    };

	/*Family History*/

    $scope.bringRelationList = function(){


        var dataString = "query=6";

        $http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.relationList = result;
        });

    };

    $scope.bringFamilyHistoryData = function(){

        $scope.bringRelationList();
        $scope.addMoreButton = true;
        var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.historyList[1].itemList = result
            if($scope.historyList[1].itemList.length == 0){
                $scope.addFamilyHistory();
            }
        });
    };

    $scope.addFamilyHistory = function (){

        angular.forEach($scope.historyList[1].itemList, function(value, key) {
            value.otherEditMode = true;
        });

        $scope.addMoreButton = false;

        $scope.familyHistoryData = {};

        $scope.familyHistoryData.relation = $scope.relationList[0];

        $scope.familyHistoryData.type = "Consanguinity";

        $scope.familyHistoryData.present = "Yes";

        $scope.familyHistoryData.detail = "";

        $scope.familyHistoryData.editMode = true;

        $scope.historyList[1].itemList.splice(0,0, $scope.familyHistoryData);
    };

    $scope.saveFamilyHistory = function(familyHistoryData){


        if(validator.validateForm("#validateReq","#lblMsg",null)) {
            var dataString = "";
            if(familyHistoryData.id){

                dataString = "query=" + 4 + '&diseaseName=' + familyHistoryData.diseaseName + '&relation=' + familyHistoryData.relation.id
                    + '&present=' + familyHistoryData.present + '&type=' + familyHistoryData.type + '&detail=' + familyHistoryData.detail + '&familyHistoryID=' + familyHistoryData.id;

            }else{
                dataString = "query=" + 1 + '&diseaseName=' + familyHistoryData.diseaseName + '&relation=' + familyHistoryData.relation.id
                    + '&present=' + familyHistoryData.present + '&type=' + familyHistoryData.type + '&detail=' + familyHistoryData.detail;
            }


            $http({
                method: 'POST',
                url: "phpServices/history/familyHistoryHelper.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
                $scope.succcess = true;
                $scope.error = false;
                $scope.message = "Information Updated Successfully";
                $scope.bringFamilyHistoryData();
            });

        }else{
            $scope.message = "";
            $scope.succcess = false;
            $scope.error = true;
        }

    };

    $scope.editFamilyHistory = function (familyHistoryData){

        angular.forEach($scope.familyHistoryList, function(value, key) {
            value.otherEditMode = true;
        });

        familyHistoryData.oterEditMode = false;
        familyHistoryData.editMode = true;

        angular.forEach($scope.relationList, function(value, key) {
            if(value.id == familyHistoryData.relation){
                familyHistoryData.relation = value;
            }
        });
    };

    $scope.addToPresFamily = function(data){


        if(data.addedToPres == 1){


            var dataString = "query=" + 3 + "&familyHistoryID=" +  data.id;

            $http({
                method: 'POST',
                url: "phpServices/history/familyHistoryHelper.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
                $scope.succcess = true;
                $scope.error = false;
                $scope.message = "Information Deleted From Prescription";
                data.addedToPres = false;
            });

        }else{

            var dataString = "query=" + 2 + "&familyHistoryID=" + data.id;

            $http({
                method: 'POST',
                url: "phpServices/history/familyHistoryHelper.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
                $scope.succcess = true;
                $scope.error = false;
                $scope.message = "Information Added To Prescription";
                data.addedToPres = true;
            });
        }

    };

    $scope.cancelFamilyHistory  = function(){
        $scope.bringFamilyHistoryData();
    };

    $scope.deleteFamilyHistory = function(id){

        var dataString = "query=" + 5 + "&familyHistoryID=" + id;

        $http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.succcess = true;
            $scope.error = false;
            $scope.message = "Information Deleted Successfully";
            $scope.bringFamilyHistoryData();
        });
    };

    /*Family History Ends*/

    /*Past Disease*/
	
	
	$scope.savePastHistory = function(pastHistoryData){
		
		if(validator.validateForm("#validateReq","#lblMsg",null)) {
			
			 
			
			var dataString = "";
			if(pastHistoryData.id){
				
				dataString = "query=" + 4 + '&diseaseName=' + pastHistoryData.diseaseName + '&isPresent=' + pastHistoryData.isPresent + '&detail=' + pastHistoryData.detail + '&pastHistoryID=' + pastHistoryData.id;

			}else{
				dataString = "query=" + 1 + '&diseaseName=' + pastHistoryData.diseaseName + '&isPresent=' + pastHistoryData.isPresent + '&detail=' + pastHistoryData.detail;
			}
			

	        $http({
	            method: 'POST',
	            url: "phpServices/history/pastHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.succcess = true;
				$scope.error = false;
				$scope.message = "Information Updated Successfully";
					$scope.bringPastHistoryData();
					$scope.bringCurrHistoryData();
	        });
		}else{
			$scope.message = "";
			$scope.succcess = false;
			$scope.error = true;
		}
		
		
	};
	
	$scope.editPastHistory = function (pastHistoryData){
		angular.forEach($scope.pastHistoryList, function(value, key) {
			value.otherEditMode = true;
		});
		pastHistoryData.oterEditMode = false;
		pastHistoryData.editMode = true;
		
	};
	
	$scope.addToPresPast = function(data){
		
		
		if(data.addedToPres == 1){
			
			
			var dataString = "query=" + 3 + "&pastHistoryID=" +  data.id;
			
			$http({
	            method: 'POST',
	            url: "phpServices/history/pastHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.succcess = true;
				$scope.error = false;
				$scope.message = "Information Deleted From Prescription";
	        	data.addedToPres = false;
	        });
	        
	    }else{
			
			var dataString = "query=" + 2 + "&pastHistoryID=" + data.id;
	        
			$http({
	            method: 'POST',
	            url: "phpServices/history/pastHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.succcess = true;
				$scope.error = false;
				$scope.message = "Information Added To Prescription";
	        	data.addedToPres = true;
	        });
	    }
	    
		};
		
	$scope.cancelPastHistory  = function(){
		$scope.bringPastHistoryData();
	};

	$scope.deletePastHistory = function(id){
		
		var dataString = "query=" + 5 + "&pastHistoryID=" + id;
        
		$http({
            method: 'POST',
            url: "phpServices/history/pastHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.succcess = true;
			$scope.error = false;
			$scope.message = "Information Deleted Successfully";
			$scope.bringPastHistoryData();
        });
	};

    $scope.addPastHistory = function (isPresent) {

        angular.forEach($scope.historyList[0].itemList, function (value, key) {
            value.otherEditMode = true;
        });

        $scope.addMoreButton = false;

        $scope.pastHistoryData = {};

        $scope.pastHistoryData.isPresent = isPresent;

        $scope.pastHistoryData.detail = "";

        $scope.pastHistoryData.editMode = true;

        if(isPresent){
            $scope.historyList[1].itemList.splice(0, 0, $scope.pastHistoryData);
        }else {
            $scope.historyList[0].itemList.splice(0, 0, $scope.pastHistoryData);
        }

    };

    $scope.bringPastHistoryData = function(){

        $scope.addMoreButton = true;
        var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/history/pastHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.historyList[0].itemList = [];
            $scope.historyList[0].itemList = result;
        });
    };

    /*Past History End*/

    /*Drug History*/

    $scope.bringCurrentDrugList = function (){
        var dataString = {'status' : 1, 'query': 1};

        $http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
            $scope.currentDrugList = result;
        });

    };

    $scope.bringOldDrugList = function (){


        var dataString = {'status' : 0,  'query': 1};

        $http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
            $scope.oldDrugList = result;
        });

    };

    $scope.addDrug = function(status){

        $scope.showDrugAdvice = true;
        var data = {};

        data.drugName = "";
        data.editMode = true;
        $scope.masterUpdate = false;

        if(status == 1){
            angular.forEach($scope.currentDrugList, function(value, key) {
                value.otherEditMode = true;
            });

            $scope.currentDrugList.splice(0,0, data);
        }else{
            angular.forEach($scope.oldDrugList, function(value, key) {
                value.otherEditMode = true;
            });

            $scope.oldDrugList.splice(0,0, data);
        }
    };

    $scope.saveDrug = function(data, status) {

        var data = {'drugName': data.drugName, 'status': status, 'query': 2};

        $http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
            if(status == 1){
                $scope.bringCurrentDrugList();
            }else{
                $scope.bringOldDrugList();
            }
        });
    };

    $scope.delDrug = function(data, status) {

        var data = {'delId': data.drugHistoryID, 'query': 3};

        $http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
            if(status == 1){
                $scope.bringCurrentDrugList();
            }else{
                $scope.bringOldDrugList();
            }
        });
    };

    $scope.addDrugToPres = function(data){

        if(data.addedToPres == 0){

            var dataStr = {'drugName': data.drugName, 'status': data.currentStatus, 'query': 7};

            $http({
                method: 'POST',
                url: "phpServices/drugHistory/drugHistoryHelper.php",
                data: dataStr,
                headers: {'Content-Type': 'application/json'}
            }).success(function (result) {
                data.contentDetailID = result;
                data.addedToPres = true;
            });

        }else{

            var dataStr = {'contentDetailID': data.contentDetailID, 'query': 8};

            $http({
                method: 'POST',
                url: "phpServices/drugHistory/drugHistoryHelper.php",
                data: dataStr,
                headers: {'Content-Type': 'application/json'}
            }).success(function (result) {
                data.addedToPres = false;
            });
        }

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
  };





	$scope.initialiseTab = function () {

        $scope.historyList = [];

        var item = 1;
        var temp = {name : 'Past History', id :item++};
        $scope.historyList.push(temp);

        temp = {name : 'Family History' , id :item++};
        $scope.historyList.push(temp);

        temp = {name : 'Drug History' , id :item++};
        $scope.historyList.push(temp);

        var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.menuDataList = result;
            angular.forEach($scope.menuDataList, function(value, key) {
               if(value.isPopUp == 1 && value.inPrescription == 2){
                   temp = {name : value.menuHeader , id :item++, typeCode : value.defaultName};
                   $scope.historyList.push(temp);
               }
            });

        });





        /*temp = {name : 'Family History'};
        $scope.historyList.push(temp);*/

    };
	
	$scope.changeTab = function (history) {

		$scope.error = false;
		$scope.succcess = false;

		if(history.id == 1){
            $scope.bringPastHistoryData();
		}else if (history.id == 2){
            $scope.bringFamilyHistoryData();
		}else if(history.id == 3){
            $scope.bringCurrentDrugList();
            $scope.bringOldDrugList();
		}else {
            $scope.typeCode = history.typeCode;

            var num = 1;

            if($scope.typeCode == "OBS"){
                num = 1;
            }else if ($scope.typeCode == "GYNAE") {
                num = 2;
            }else if ($scope.typeCode == "GENERAL") {
                num = 3;
            }else if ($scope.typeCode == "IMMUNIZATION") {
                num = 4;
            }
            $scope.pageName = $scope.pageNameList[num];
            $scope.bringHistoryDetail();
        }
    };

    $scope.bringHistoryDetail = function (){

        $scope.historySetteingData = {};

        var dataString = "query=0" + '&typeCode=' + $scope.typeCode;;

        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.paientHistoryList = result;

            angular.forEach($scope.paientHistoryList, function(value, key) {
                if(parseInt(value.savedHistorysID) > 0){
                    value.addToPrescription = true;
                }
            });
        });
    };

    $scope.getHistory = function(term) {

        var dataString = 'query=5'+ '&name=' + term + '&typeCode=' + $scope.typeCode;

        return $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
            $scope.historyData = result.data;
            return limitToFilter($scope.historyData, 10);
        });
    };

    $scope.onSelectHistory = function(item, model, label){
        $scope.historySetteingData.historyID = item.id;
        $scope.historySetteingData.shortName = item.shortName;
        $scope.addByName = true;
    };

    $scope.bringHistoryOption = function(historydata, term){


        var dataString = 'query=1'+ '&historyID=' + historydata.historyID + '&term=' + term;;


        return $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
            $scope.historyOption = result.data;
            return limitToFilter($scope.historyOption, 10);
        });
    };

    $scope.deleteHistoryFromSetting = function (id){

        var dataString = 'query=12'+ '&historySettingID=' + id;
        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.bringHistoryDetail();
        });
    };

    $scope.addHistoryToDoctorPref = function (){

        if(validator.validateForm("#historySetting","#lblMsg",null)) {
            if($scope.addByName == false){
                var dataString = 'query=6'+ '&historyName=' + $scope.historySetteingData.historyName + '&shortName=' + $scope.historySetteingData.shortName +  '&typeCode=' + $scope.typeCode;
                $http({
                    method: 'POST',
                    url: "phpServices/history/historyHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                    $scope.addToDoctorPreference(result);
                });

            }else{
                $scope.addToDoctorPreference($scope.historySetteingData.historyID);
            }

        }else{
            alert("what");
        }
    };


    $scope.addToDoctorPreference = function (historyID){

        var hisID = parseInt(historyID);
        var displayOrder = 1;
        if($scope.paientHistoryList != undefined && $scope.paientHistoryList.length > 0){
            displayOrder = parseInt($scope.paientHistoryList[$scope.paientHistoryList.length -1].displayOrder) + 1;
        }

        var dataString = 'query=7'+ '&historyID=' + hisID + '&displayOrder=' + displayOrder;

        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.bringHistoryDetail();
        });

    };


    $scope.saveHistory = function(){

        var prescribedHistory = $scope.paientHistoryList;

        angular.forEach(prescribedHistory, function(value, key) {
            if(parseInt(value.patientHistoryID) > 0 && value.historyResult){//Update
                var dataString = 'query=11'+ '&historyID=' + value.historyID + '&historyResult=' + value.historyResult ;
                $http({
                    method: 'POST',
                    url: "phpServices/history/historyHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                });
            }else if(!parseInt(value.patientHistoryID) &&  value.historyResult){//ADD
                var dataString = 'query=10'+ '&historyID=' + value.historyID + '&historyResult=' + value.historyResult ;
                $http({
                    method: 'POST',
                    url: "phpServices/history/historyHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                });
            }else if(parseInt(value.patientHistoryID) > 0 && value.historyResult == ""){//delete

                var dataString = 'query=9'+ '&savedHistorysID=' + value.patientHistoryID;
                $http({
                    method: 'POST',
                    url: "phpServices/history/historyHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                });
            }
        });

        angular.forEach(prescribedHistory, function(value, key) {
            if(parseInt(value.savedHistorysID) > 0 && value.historyResult && value.addToPrescription){
                //do noting
            }else if(parseInt(value.savedHistorysID) > 0 && value.historyResult && !value.addToPrescription){

                var dataString = 'query=4'+ '&savedHistorysID=' + value.savedHistorysID;
                $http({
                    method: 'POST',
                    url: "phpServices/history/historyHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                });

            }else if(parseInt(value.savedHistorysID) > 0 && value.historyResult == ""){


                var dataString = 'query=4'+ '&savedHistorysID=' + value.savedHistorysID;
                $http({
                    method: 'POST',
                    url: "phpServices/history/historyHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                });

            }else if(!(parseInt(value.savedHistorysID) > 0) > 0 && value.historyResult == "" && value.addToPrescription){
                //do noting
            }else if(!(parseInt(value.savedHistorysID) > 0) && value.historyResult  && value.addToPrescription){

                var dataString = 'query=3'+ '&historyID=' + value.historyID;
                $http({
                    method: 'POST',
                    url: "phpServices/history/historyHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                });
            }
        });

        setTimeout(function() {
            $scope.$apply(function() {
                $scope.succcess = true;
                $scope.error = false;
                $scope.message = 'Information Saved Successfully';
            });
        }, 3000);

    };





  $scope.init = function () {
	  $scope.initialiseTab();
	  $scope.bringPastHistoryData();

  };

    $scope.init();

	
});