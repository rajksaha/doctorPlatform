app.controller('LoginController', function($scope, $http, $timeout, $location) {
	
	$scope.username = "";
	$scope.password = "";
	$scope.userNotexist = false;
	$scope.passwordErr = false;
	$scope.error = false;
	$scope.errorMessage = "";
	$scope.slideImages = [];	
	var data1 = {"contentURL":'images/slides/slide1.jpg'};
	var data2 = {"contentURL":'images/slides/slide2.jpg'};
	var data3 = {"contentURL":'images/slides/slide3.jpg'};
	var data4 = {"contentURL":'images/slides/slide4.jpg'};
	var data5 = {"contentURL":'images/slides/slide5.jpg'};
	var data6 = {"contentURL":'images/slides/slide6.jpg'};
	var data7 = {"contentURL":'images/slides/slide7.jpg'};
	//$scope.slideImages = {"contentURL":'images/slides/slide1.jpg',"contentURL":'images/slides/slide1.jpg',"contentURL":'images/slides/slide1.jpg',"contentURL":'images/slides/slide1.jpg',"contentURL":'images/slides/slide1.jpg'};
	
	$scope.slideImages.push(data1);
	$scope.slideImages.push(data2);
	$scope.slideImages.push(data3);
	$scope.slideImages.push(data4);
	$scope.slideImages.push(data5);
	$scope.slideImages.push(data6);
	$scope.slideImages.push(data7);
	
	$timeout(function() {
		angular.element('#slides').superslides({
			play: 5000,
			animation: 'fade',
			pagination: true
		});
    }, 100);
	
    $scope.login = function(){
    	
    	if($scope.username == "" && $scope.password == ""){
    		$scope.passwordErr = true;
    		$scope.userNotexist = true;
    		$scope.error = true;
    		$scope.errorMessage = "Please select a doctor code and password";
    		return false;
    	}
    	
    	if($scope.username == ""){
    		$scope.userNotexist = true;
    		$scope.error = true;
    		$scope.errorMessage = "Please select a doctor code";
    		return false;
    	}
    	
    	if($scope.password == ""){
    		$scope.passwordErr = true;
    		$scope.error = true;
    		$scope.errorMessage = "Please Provide a password";
    		return false;
    	}
    	
        var dataString = 'username='+ $scope.username +'&query='+1+'&password='+ $scope.password;

        $http({
            method: 'POST',
            url: "phpServices/login/loginHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(result == -1){
        		$scope.userNotexist = true;
        		$scope.error = true;
        		$scope.errorMessage = "User dose not exist";
        		return false;
        	}else if(result == 0){
        		$scope.passwordErr = true;
        		$scope.error = true;
        		$scope.errorMessage = "Password dose not match";
        		return false;
        	}else if(result == 1){
        		$location.path("/appointment");
        	}
        });
    }
}); 