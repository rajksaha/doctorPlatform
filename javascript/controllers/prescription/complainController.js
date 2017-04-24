app.controller('PrescriptionController.PrescribeComplainController', function($scope, $http, $modalInstance, JsonService, record, limitToFilter) {

    $scope.symptom = {};
    $scope.complainList = [];
    $scope.drugNumOfDayList = JsonService.fractionNumberList;
    $scope.drugDayTypeList = JsonService.timesADay;


    $scope.init = function(){
        if(record.copmplainData.id){
            $scope.bringdrugsDayType(false, null);
        }else{
            $scope.bringdrugsDayType(true, null);
        }

    };

    $scope.bringdrugsDayType = function (addMood, selectedDayTypeID){

        var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
            $scope.drugDayTypeList = result;

            //$scope.drugDayTypeList.splice(5, 1);
            $scope.drugDayTypeList.splice(4, 1);

            if(addMood){
                var data = {"title": "Symptom 1","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" : "","id" : 0};
                $scope.complainList.push(data);
                data = {"title": "Symptom 2","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" :"","id" : 0};
                $scope.complainList.push(data);
                data = {"title": "Symptom 3","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" :"","id" : 0};
                $scope.complainList.push(data);
                data = {"title": "Symptom 4","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[4],"note" :"","id" : 0};
                $scope.complainList.push(data);

            }else{

                $scope.complainData = {"title": "Symptom"};

                angular.forEach($scope.drugNumOfDayList, function(data, key) {
                    if(data.value == record.copmplainData.durationNum){
                        $scope.complainData.numOfDay = data;
                    }
                });

                angular.forEach($scope.drugDayTypeList, function(value, key) {
                    if(value.id == record.copmplainData.durationID){
                        $scope.complainData.dayType = value;
                    }
                });
                $scope.complainData.id = record.copmplainData.id;

                $scope.complainData.name = record.copmplainData.symptomName;

                $scope.complainList.push($scope.complainData);
            }

        });

    };

    $scope.saveGroupOfComplain = function(){

        var entryFound = false;
        var int = 0;
        var complainList = [];
        for (int; int < $scope.complainList.length; int++) {
            var name = $scope.complainList[int].name;
            var noOfDay = $scope.complainList[int].numOfDay.value;
            var dayType = $scope.complainList[int].dayType.id;
            var note = $scope.complainList[int].note;
            var id = $scope.complainList[int].id;
            if(name){
                entryFound = true;
                if(dayType > 4){
                    noOfDay = 0;
                }

                var dataString = {'complainName': name , 'numOfDay' : noOfDay ,'dayType' :  dayType, 'note' : note, 'complainPrescribeID' : id};
                complainList.push(dataString);

            }

        }

        /*angular.forEach($scope.complainList, function(value, key) {
         if(value.name){
         entryFound = true;
         if(value.dayType.id > 4){
         value.numOfDay.value = null;
         }

         var dataString = {'complainName': value.name , 'numOfDay' : value.numOfDay.value ,'dayType' :  value.dayType.id, 'note' : value.note, 'complainPrescribeID' : value.id, 'query' : 2};

         $http({
         method: 'POST',
         url: "phpServices/complain/complainService.php",
         data: JSON.stringify(dataString),
         headers: {'Content-Type': 'application/x-www-form-urlencoded'}
         }).success(function (result) {
         });
         }
         });*/


        if(!entryFound){
            if($scope.complainList.length == 1){
                $scope.errorMessage = "Please Select Symptom Name";
                $scope.succcess = false;
                $scope.error = true;
            }else{
                $scope.errorMessage = "Please Select At-least One Symptom";
                $scope.succcess = false;
                $scope.error = true;
            }

        }else{

            jQuery.post("phpServices/complain/complainAdderService.php",  {json: JSON.stringify(complainList)}, function(data){
                $modalInstance.close();
            });

        }



    };


    $scope.cancelGroupOfComplain = function(){
        $modalInstance.dismiss('cancel');
    };

    $scope.getSymptoms = function(term) {

        var data = {'data': term, 'query': 1};

        return $http({
            method: 'POST',
            url: "phpServices/complain/complainService.php",
            data: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        }).then(function(result) {
            $scope.symptoms = result.data;
            return limitToFilter($scope.symptoms, 10);
        });
    };

    $scope.onSelectSymptoms = function(item, model, label){
        alert(item.name);
    };

    $scope.init();
});