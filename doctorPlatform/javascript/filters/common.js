app.filter('partition', function() {
  var cache = {};
  var filter = function(arr, size) {
    if (!arr) { return; }
    var newArr = [];
    for (var i=0; i<arr.length; i+=size) {
      newArr.push(arr.slice(i, i+size));
    }
    var arrString = JSON.stringify(arr);
    var fromCache = cache[arrString+size];
    if (JSON.stringify(fromCache) === JSON.stringify(newArr)) {
      return fromCache;
    }
    cache[arrString+size] = newArr;
	console.log(newArr);
    return newArr;
  };
  return filter;
});

app.filter('unsafe', ['$sce', function ($sce) {
    return function (val) {
        return $sce.trustAsHtml(val);
    };
}]);

app.filter('convertStringToDate', function() { 
    return function(val) {
    	if(val) {
    		//check valid date mm/dd/yyyy
    		var dateaprts = val.split('/');
    		var dt = new Date(dateaprts[2], dateaprts[0] - 1, dateaprts[1]);
    		if(dt.getDate() == dateaprts[1] && dt.getMonth() == dateaprts[0] - 1 && dt.getFullYear() == dateaprts[2]) {
    			return dt;
    		} else {
    			return "";
    		}
    	} else {
    		return val;
    	}
    };
	}
);

app.filter('searchRoomByRoomTypeCode', function(){
	return function(arr, searchRoomTypeString){

		if(!searchRoomTypeString){
			return arr;
		}

		var result = [];

		searchRoomTypeString = searchRoomTypeString.toLowerCase();

		angular.forEach(arr, function(item){

			if(item.roomTypeCode.toLowerCase().indexOf(searchRoomTypeString) !== -1){
				result.push(item);
			}

		});

		return result;
	};
});