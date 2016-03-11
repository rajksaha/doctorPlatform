app.controller('ResearchHomeController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter, $window) {
	
	$scope.numberOfAppointment = 0;
 	$scope.limit = 10;
 	$scope.addMoreToLimit = 10;
 	$scope.appointmentList = [];
 	$scope.doctorData = {};
 	$scope.followUpSearch = false;
 	$scope.patientName = "";
 	$scope.addAppointMentData = {};
 	
 	
 	
    $scope.bringByDate = function (appointmentDate){
    	
    	var filteredDate = $filter('date')(appointmentDate, "yyyy-MM-dd");
    	
    	var  dataString='filteredDate='+  filteredDate +'&query='+1;

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.appointmentList = result;
        	$scope.numberOfAppointment = $scope.appointmentList.length;
        });
    };
    
    $scope.printPreview = function (appointmentData){
    	
    	
    	var  dataString = 'patientCode='+ appointmentData.patientCode  +'&patientID='+ appointmentData.patientID +'&appointmentID='+ appointmentData.appointmentID +'&query='+18;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	 $window.open("tpdf/" + $scope.doctorData.pdfPage + ".php", '_blank');
        	 
        });
    	
    };
    
    $scope.addINAppointment  = function (patientCode){
   	 
    	 var currentDate = new Date();
      	var filteredDate = $filter('date')(currentDate, "yyyy-MM-dd");
      	
     	 
     	 var  dataString='doctorCode='+ $scope.doctorData.doctorCode +'&patientCode='+ patientCode +'&doctorID='+ $scope.doctorData.doctorID +'&query='+3 + '&filteredDate='+  filteredDate;
        
         $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
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
         	$rootScope.doctorData = $scope.doctorData;
         }, function(error){
         	$location.path("/login");
         });
     };
    

	
	(function(){
		$scope.bringDoctorInfo();
    })()

	
});