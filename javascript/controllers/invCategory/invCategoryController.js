app.controller('InvCategoryController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {


    $scope.invCategoryList = [];
    $scope.invList = [];
    $scope.perPage = 10;
    $scope.from = 0;
    $scope.to = $scope.from + $scope.perPage;



    $scope.bringData = function () {
        $scope.hasError = false;
        var dataString = "query=14"+ '&perPage=' + $scope.perPage + '&from=' + $scope.from;
        $scope.to = parseInt($scope.from)  + parseInt($scope.perPage);
        $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.invList = result;
        });
    };
    
    $scope.save = function () {
        if(validator.validateForm("#validateReq","#lblMsg",null)) {
            var dataString = "query=16"+ '&invList=' + JSON.stringify($scope.invList);

            $scope.to = parseInt($scope.from)  + parseInt($scope.perPage);
            $http({
                method: 'POST',
                url: "phpServices/inv/invService.php",
                data: dataString,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (result) {
                $scope.from += $scope.perPage;
                $scope.bringData();
            });
        }else{
            $scope.hasError = true;
            $scope.errorMessage = "Please Select Category of each item";
        }

    };

   $scope.init = function () {


       $scope.bringData();
       
       var dataString = "query=15";

       $http({
           method: 'POST',
           url: "phpServices/inv/invService.php",
           data: dataString,
           headers: {'Content-Type': 'application/x-www-form-urlencoded'}
       }).success(function (result) {
           $scope.invCategoryList = result;
       });
   };


    $scope.init();


});

app.controller('PrescribeInvController.InvMasterContoller', function($scope, $http, $modalInstance, limitToFilter, $filter, record) {

    $scope.invAdderData = {};

    if(record.invAdderData.id){
        $scope.invAdderData = record.invAdderData;
    }else{
        $scope.invAdderData = {};
        $scope.invAdderData.note = "";
    }
    $scope.diagnosisNameData = {};


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

    $scope.save = function(){

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

                $modalInstance.close();

            });
        }else{
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