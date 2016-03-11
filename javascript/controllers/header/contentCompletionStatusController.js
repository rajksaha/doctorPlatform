/**
 * KTR-1038:
 * Add a “completion” link in the header when a user is in the approval center.
 * When clicked a pop up shows a summary of completion that replicates the approval center main menu.
 * If possible, the completion link shows progress such as “Completion: 4 of 9”.
 * This status is populated by the number of items completed/approved.
 * Only when the section has a full circle does the completion data change. 
 */
app.controller('ContentCompletionStatusController', function($scope, $modalInstance){

	$scope.url = {};
	$scope.url.fullCircle = "images/icon-c-stat-2.png";
	$scope.url.halfCircle = "images/icon-c-stat-1.png";
	$scope.url.blankCircle = "images/icon-c-stat-0.png";
	
	$scope.approvalStatus = {};

	$scope.cancelModal = function () {
		$modalInstance.dismiss('cancel');
	};

});