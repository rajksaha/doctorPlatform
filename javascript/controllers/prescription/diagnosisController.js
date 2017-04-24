app.controller('PrescriptionController.PrescribeDiagnosisController', function($scope, $http, $modalInstance, limitToFilter, $filter, record) {

    $scope.diagnosisData = {};

    if(record.diagnosisData.id){
        $scope.diagnosisData = record.diagnosisData;
    }else{
        $scope.diagnosisData = {};
        $scope.diagnosisData.note = "";
    }
    $scope.diagnosisNameData = {};

    $scope.diagnosisNote = "";

    $scope.save = function(){

        if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {

            var dataString = "";
            if($scope.diagnosisData.id){

                dataString = "query=" + 3 + '&diagnosisName=' + $scope.diagnosisData.diseaseName + '&note=' + $scope.diagnosisData.note + '&id=' + $scope.diagnosisData.id;

            }else{
                dataString = "query=" + 2 + '&diagnosisName=' + $scope.diagnosisData.diseaseName + '&note=' + $scope.diagnosisData.note;
            }

            $http({
                method: 'POST',
                url: "phpServices/diagnosis/diagnosis.php",
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
        $modalInstance.dismiss('cancel');
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