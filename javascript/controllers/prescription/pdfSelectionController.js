app.controller('PrescriptionController.PdfSelectionController', function($scope, $http, $modalInstance, limitToFilter, $filter, record) {
    $scope.pdfList = record.result;

    $scope.selectPdf = function(pdf){
        $modalInstance.close(pdf);
    };
    $scope.cancel = function(){
        $modalInstance.dismiss('cancel');
    };
});