app.controller('DrugAdvisorController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.drugAdviceList = [];
	$scope.drugWhenList = [];
	$scope.masterUpdate = true;


    $scope.convertToUniCode = function (data) {
        var str = ConvertToASCII(ConvertFrom, data.bangla);
        data.pdf = str;
    };

	$scope.init = function(){
		$scope.bringDrugAdviceList();
		
		$scope.bringDrugWhenList();
    };
    
	$scope.bringDrugAdviceList = function (){
		
		
		var dataString = {'data': 1, 'query': 1};

        $http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	
        	$scope.drugAdviceList = result;
        	
        	
        });
		
	};
	
	$scope.bringDrugWhenList = function (){
		
		
		var dataString = {'data': 1, 'query': 4};

        $http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	
        	$scope.drugWhenList = result;
        	
        	
        });
		
	};
	
	$scope.addDrugAdvice = function(){
		
		angular.forEach($scope.drugAdviceList, function(value, key) {
			value.otherEditMode = true;
		});
		
		$scope.showDrugAdvice = true;
		var drugAdviceData = {};
		
		drugAdviceData.bangla = "";
		drugAdviceData.pdf = "";
		drugAdviceData.editMode = true;
		$scope.masterUpdate = false;
		drugAdviceData.bangla = "";
		
		$scope.drugAdviceList.splice(0,0, drugAdviceData);
		
	};

    $scope.saveDrugAdvice = function(data) {
    	
    	var data = {'bangla': data.bangla, 'pdf': data.pdf, 'query': 2};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.bringDrugAdviceList();
        });
    };
    
    $scope.delDrugAdvice = function(data) {
    	
    	var data = {'delId': data.drugAdviceID, 'query': 3};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.bringDrugAdviceList();
        });
    };
    
    
    $scope.addDrugWhen = function(){
		
		angular.forEach($scope.drugWhenList, function(value, key) {
			value.otherEditMode = true;
		});
		
		$scope.showDrugWhen = true;
		var data = {};
		
		data.bangla = "";
		data.pdf = "";
		data.editMode = true;
		$scope.masterUpdate = false;
		data.bangla = "";
		
		$scope.drugWhenList.splice(0,0, data);
		
	};

    $scope.saveDrugWhen = function(data) {
    	
    	var data = {'bangla': data.bangla, 'pdf': data.pdf, 'query': 5};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.masterUpdate = true;
        	$scope.bringDrugWhenList();
        });
    };
    
    $scope.delDrugWhen = function(data) {
    	
    	var data = {'delId': data.id, 'query': 6};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.bringDrugWhenList();
        });
    };

    $scope.init();
});

function ConvertToASCII(ConvertTo, line)
{
    var conversion_map = uni2bijoy_string_conversion_map;
    if(ConvertTo=="bijoy")
        conversion_map = uni2bijoy_string_conversion_map;
    else if(ConvertTo=="somewherein")
        conversion_map = uni2somewherein_string_conversion_map;
    else if(ConvertTo=="boisakhi")
        conversion_map = uni2boisakhi_string_conversion_map;

    var myRegExp;
    myRegExp = new RegExp("ো", "g");
    line = line.replace(myRegExp, "ো");
    myRegExp = new RegExp("ৌ", "g");
    line = line.replace(myRegExp, "ৌ");

    line = ReArrangeUnicodeText(line);

    for (var unic in conversion_map)
    {
        myRegExp = new RegExp(unic, "g");
        line = line.replace(myRegExp, conversion_map[unic]);
    }

	/*
	 if(ConvertTo=="bijoy" || ConvertTo=="somewherein")
	 {
	 var temp_map = {
	 "‘":"Ô",
	 "’":"Õ",
	 "“":"Ò",
	 "”":"Ó"
	 };
	 for (var unic in temp_map)
	 {
	 myRegExp = new RegExp(unic, "g");
	 line = line.replace(myRegExp, temp_map[unic]);
	 }
	 }

	 */
    return line;
}