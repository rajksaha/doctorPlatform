app.service('JsonService', function(){
	
	this.numberList = [];
	
	for(var i = 1; i<32 ; i++){
		var data = {"value" : i , "name" : i};
		this.numberList.push(data);
	}
	
	this.taxCalType = [
						{"value" : "PERCENT", "name" :'%'},
						{"value" : "AMOUNT", "name" :'$'}
		              ];
	
	this.taxBasis = [
						{"value" : "STAY", "name" :'Reservation'},
						{"value" : "PERSON", "name" :'Per Person/Per Reservation'}
	                ];
	 
	this.timesADay = [
						{"code" : "1", "name" :'Once Daily'},
						{"code" : "2", "name" :'12 hourly'},
						{"code" : "3", "name" :'8 hourly'},
						{"code" : "4", "name" :'6 hourly'},
						{"code" : "6", "name" :'4 hourly'},
						{"code" : "8", "name" :'3 hourly'},
						{"code" : "12", "name" :'2hourly'},
						{"code" : "-1", "name" :'Preidic Dose'},
						{"code" : "-2", "name" :'Same As'},
						{"code" : "-3", "name" :'Empty Dose'}
		              ];
	
    this.weekDayCodesNum = {
    	"monCodeNum" : 64,
    	"tueCodeNum" : 32,
    	"wedCodeNum" : 16,
    	"thuCodeNum" : 8,
    	"friCodeNum" : 4,
    	"satCodeNum" : 2,
    	"sunCodeNum" : 1
    };
    
});