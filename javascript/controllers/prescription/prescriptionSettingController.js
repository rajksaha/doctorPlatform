app.controller('PrescriptionController.PrescriptionSettingController', function($scope, $modalInstance, data, $http, $window, $location) {

    $scope.prescriptionSettingData = data;

    $scope.savePrint = function (){


        var dataString = "query=14" + '&diseaseID=' + $scope.prescriptionSettingData.prescriptionSettingData.diseaseID + '&doctorID=' + $scope.prescriptionSettingData.prescriptionSettingData.doctorID;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $modalInstance.close();
        });

    };



    $scope.printOnly = function (){
        $modalInstance.close();
    };


});