app.service('JsonService', function(){
	
	this.numberList = [];
	this.fractionNumberList = [];
	
	
	var data = {"value" : '' , "name" : '--Select--'};
	this.numberList.push(data);
	for(var i = 1; i<32 ; i++){
		var data = {"value" : i , "name" : i};
		this.numberList.push(data);
	}
	
	var i = .5;
	while( i<31 ){
		var data = {"value" : i , "name" : i};
		this.fractionNumberList.push(data);
		i = i + 0.5;
	}
	
	 
	this.timesADay = [
						{"code" : "1", "name" :'Once Daily'},
						{"code" : "2", "name" :'12 hourly'},
						{"code" : "3", "name" :'8 hourly'},
						{"code" : "4", "name" :'6 hourly'},
						{"code" : "6", "name" :'4 hourly'},
						{"code" : "8", "name" :'3 hourly'},
						{"code" : "12", "name" :'2hourly'},
						{"code" : "-1", "name" :'Preiodic Dose'},
						{"code" : "-2", "name" :'Same As'},
						{"code" : "-3", "name" :'Empty Dose'}
		              ];
	
    
});