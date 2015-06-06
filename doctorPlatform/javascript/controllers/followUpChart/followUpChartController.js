app.controller('followUpChartController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.invNameData = [];
	$scope.invData = {};
	$scope.invFollowUpChart = [];
	$scope.followUpChartData = [];
	$scope.recentStart = 0;
	$scope.recentEnd = 0;
	$scope.patientAppoinmentList = [];
	
	$scope.typeHeadSelected = false;
	
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
		  $scope.invData.id = item.id;
		  $scope.typeHeadSelected = true;
	  };
	
	
	  $scope.bringfollowUpChart = function (){
	    var found = false;
		  angular.forEach($scope.invFollowUpChart, function(value, key) {
				if(value.invID == $scope.invData.id){
					found = true;
				}
			});
		  if(found){
			  alert("You allready have this inv report in your follow uo chat");
			  return false;
		  }
		  
		  var dataString = "query=0" + "&invID=" + $scope.invData.id;

          $http({
              method: 'POST',
              url: "phpServices/followUpChart/followUpChart.php",
              data: dataString,
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function (result) {
        	  if(result.length == 0){
        		  alert("Dont have followup chart report for this patient");
    			  return false;
        	  }
        	  var data = {};
        	  data.invName = $scope.invData.name;
        	  data.invID = $scope.invData.id;
        	  data.invReportList = result;
        	  $scope.invFollowUpChart.push(data);
        	  $scope.invData = {};
        	  $scope.typeHeadSelected = false;
          });
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

	
	
	

	$scope.inIt = function (){
		
	};
	
	(function(){
		$scope.inIt();
    })()

	
});