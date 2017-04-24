app.controller('FollowUpChartController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter) {
	
	$scope.invNameData = [];
	$scope.invData = {};
	$scope.invFollowUpChart = [];
	$scope.followUpChartData = [];
	$scope.recentStart = 0;
	$scope.recentEnd = 0;
	$scope.patientAppoinmentList = [];
	
	$scope.typeHeadSelected = false;


	
	  $scope.bringFollowUpChart = function (){


          var dataString = "query=3";

          $http({
              method: 'POST',
              url: "phpServices/followUpChart/followUpChart.php",
              data: dataString,
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function (result) {
              $scope.uniqueDateList = result;

              angular.forEach($scope.patientFollowUpList, function(value, key) {

                  var dataString = "query=2" + "&patientFollowUpID=" + value.patientFollowUpID;

                  $http({
                      method: 'POST',
                      url: "phpServices/followUpChart/followUpChart.php",
                      data: dataString,
                      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                  }).success(function (result) {
                      value.invReportList = result;
                  });
              });
          });
	    };

	  $scope.getFollowupResult = function (date, reportList) {
	      var result = "";
          angular.forEach(reportList, function(value, key) {
              if(value.entryDate == date){
                  result =  value.data;
              }
          });

          return result;
      };

    $scope.addToPrescription = function (uDate, patientFollowUpList) {
        var result = "";
        var filteredDate = $filter('date')(uDate.entryDate, "yyyy-MM-dd");
        var jsonArray = [];
        angular.forEach(patientFollowUpList, function(value, key) {
            var invName = value.invName;
            angular.forEach(value.invReportList, function(inv, key) {
                if(inv.entryDate == uDate.entryDate){
                    console.log(inv.data);
                    var jsonItem = {data : invName + "-" + inv.data, entryDate : filteredDate};
                    jsonArray.push(jsonItem);
                }
            });
        });

        var dataString = 'query=5'+ '&jsonArray=' + JSON.stringify(jsonArray);

        $http({
            method: 'POST',
            url: "phpServices/followUpChart/followUpChart.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            uDate.addedToPrescription = true;
        });

    };

    $scope.editFinding = function (uDate, patientFollowUpList) {

        var followUp = {};
        followUp.entryDate = uDate.entryDate;
        followUp.followUpList = [];
        angular.forEach(patientFollowUpList, function(value, key) {
            var data = null;
            angular.forEach(value.invReportList, function(inv, key) {
                if(inv.entryDate == uDate.entryDate){
                    data = inv.data
                }
            });
            var jsonItem = {invName : value.invName, data: data, patientFollowUpID : value.patientFollowUpID};
            followUp.followUpList.push(jsonItem);
        });
        var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/followUpChart/followUpSetupModal.html',
            windowClass: 'fade in',
            controller: 'PatientFollowUPController',
            resolve: {
                record: function () {
                    return {
                        followUp
                    };
                }},
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
            $scope.init();
        });
    };

	  $scope.addFollowUp = function () {

          var followUp = {};
          followUp.followUpList = [];
          angular.forEach($scope.patientFollowUpList, function(value, key) {
              var jsonItem = {invName : value.invName, patientFollowUpID : value.patientFollowUpID};
              followUp.followUpList.push(jsonItem);
          });

          var modalInstance = $modal.open({
              templateUrl: 'javascript/templates/followUpChart/followUpSetupModal.html',
              windowClass: 'fade in',
              controller: 'PatientFollowUPController',
              resolve: {
                  record: function () {
                      return {
                          followUp
                      };
                  }},
              backdrop: 'static'
          });
          modalInstance.result.then(function(result) {
              $scope.init();
          });
      };

	  $scope.save  = function () {

          if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {


              var followUpAdder = [];
              /*var filteredDate = $filter('date')($scope.uniqueDateList[0].entryDate, "yyyy-MM-dd");
              angular.forEach($scope.patientFollowUpList, function(value, key) {
                  if(value.editMode){
                      var temp = {followUpID : value.invReportList[0].followUpID, data : value.invReportList[0].data, entryDate : filteredDate};
                      followUpAdder.push(temp);
                  }
              });*/

              var dataString = 'query=4'+ '&jsonString=' + JSON.stringify($scope.uniqueDateList[0]);

              $http({
                  method: 'POST',
                  url: "phpServices/followUpChart/followUpChart.php",
                  data: dataString,
                  headers: {'Content-Type': 'application/x-www-form-urlencoded'}
              }).success(function (result) {
                  //$scope.init();
                  console.log(result);
              });
          }

      };
	    
	    $scope.displayStatus = function (invData, index){

	    	var maxIndex = 3;
	    	if(invData.maxLength){
	    		maxIndex = invData.maxLength;
	    	}else{
	    		invData.minLength = 0;
	    	}
	    	if(invData.minLength <= index && index <= maxIndex){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    };
	    
	    $scope.progressFlow = function (invData,increment){
	    	
	    	if(increment){
	    		if(invData.maxLength){
	    			invData.maxLength = invData.maxLength + 1;
	    			invData.minLength = invData.minLength + 1;
	    		}else{
	    			invData.maxLength = 4;
	    			invData.minLength = invData.minLength + 1;
	    		}
	    		invData.needPrevious  = true;
	    		
	    		
	    	}else{
	    		invData.maxLength = invData.maxLength - 1;
	    		invData.minLength = invData.minLength - 1;
	    		if(invData.minLength == 0){
	    			invData.needPrevious = false;
	    		}
	    	}
	    	
	    	if((invData.invReportList.length -1) == invData.maxLength){
	    		invData.noNeedNext = true;
	    	}else{
	    		invData.noNeedNext = false;
	    	}
	    	
	    	
	    };

	
	
	$scope.addFollowUpINV = function () {

        var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/followUpChart/invSelectorModal.html',
            windowClass: 'fade in',
            controller: 'InvSelectorController',
            resolve: {
                record: function () {
                    return {
                    };
                }
            },
            backdrop: 'static'
        });
        modalInstance.result.then(function(result) {
            $scope.init();
        });
    };

	$scope.init = function (){
		
		
		var dataString = "query=0";
		
		$http({
            method: 'POST',
            url: "phpServices/followUpChart/followUpChart.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
      	  $scope.patientFollowUpList = result;
      	  $scope.bringFollowUpChart();
        });
	};
	
	$scope.init();
	
	

	
});


app.controller('PatientFollowUPController', function($scope, $http, $modalInstance, record, $filter) {



    $scope.followUp = {};
	$scope.followUp.entryDate = record.followUp.entryDate;
	$scope.followUp.followUpList= record.followUp.followUpList;



	$scope.save = function () {

        if($scope.followUp.entryDate) {
            var filteredDate = $filter('date')($scope.followUp.entryDate, "yyyy-MM-dd");
            var jsonArray = [];
            angular.forEach($scope.followUp.followUpList, function(value, key) {
                var jsonItem = {followUpID : value.patientFollowUpID, data: value.data, entryDate : filteredDate};
                jsonArray.push(jsonItem);
            });
            var dataString = 'query=4'+ '&jsonArray=' + JSON.stringify(jsonArray);

            $http({
                method: 'POST',
                url: "phpServices/followUpChart/followUpChart.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
                $modalInstance.close();
            });
        }else{
            $scope.error = true;
            $scope.errorMessage = "Date field required"
        }

    };
    $scope.cancel = function(){
        $modalInstance.close();
    };


});

app.controller('InvSelectorController', function($scope, $http, $modalInstance, limitToFilter) {

    $scope.invName = "";

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

    $scope.invData = {};
    $scope.onSelectInvName = function(item, model, label){
        $scope.invData = item;
    };

    $scope.save = function(){

        var dataString = 'query=1'+ '&invName=' + $scope.invData.name;

        $http({
            method: 'POST',
            url: "phpServices/followUpChart/followUpChart.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $modalInstance.close();
        });
	};

    $scope.cancel = function(){
        $modalInstance.close();
    };


});