var ImageCropper = {
	baseURL	: "",	
	id		: undefined,
	JCroper	: undefined,
	width 	: 256,
	height 	: 192,
	
	init: function(){		
		ImageCropper.id 		= $('#crop_image').val();
		ImageCropper.baseURL 	= $('#base_url').val();	
	},
	
	onClickImageSelector: function(selectorWidth, selectorHeight){
		
		ImageCropper.init();
		
		$('#btnCropper').removeClass('btn-crop-red');
		$('#btnCropper').addClass('btn-crop');
		
		var width = $.trim($('#curr_width').val()) != "" ? eval($('#curr_width').val()) : ImageCropper.width;
		var height = $.trim($('#curr_height').val()) != "" ? eval($('#curr_height').val()) : ImageCropper.height;
		selectorWidth = selectorWidth != undefined ? selectorWidth : width;
		selectorHeight = selectorHeight != undefined ? selectorHeight : height;
		
		ImageCropper.initJCroper(ImageCropper.id, width, height, selectorWidth, selectorHeight);
		
	},
	
	hideJCroperButton: function(){
		$('#btnCropper').removeClass('btn-crop-red');
		$('#btnCropper').addClass('btn-crop');
		
		$('#btnSelector').hide();
		$('#btnUndo').hide();
		$('#btnFullViewer').hide();
		$('#btnCropper').hide();
		
	},
	
	showJCroperButton: function(){
		$('#btnCropper').removeClass('btn-crop-red');
		$('#btnCropper').addClass('btn-crop');
		
		$('#btnSelector').show();
		$('#btnUndo').show();
		$('#btnFullViewer').show();
		$('#btnCropper').show();	
		
	},

	initJCroper: function(id, width, height, selectorWidth, selectorHeight){
		
		if($('.jcrop-holder').get(0) == undefined){
			
			ImageCropper.JCroper = $.Jcrop("#"+id,{
				onSelect : ImageCropper.setCoordinates,
	            bgColor: 'black',
	            bgOpacity: .4,
	            setSelect: [ 0, 0, selectorWidth, selectorHeight ],
	            aspectRatio: 4 / 3,
	            trueSize: [width, height],
	            minSize : [ImageCropper.width, ImageCropper.height],
	            keySupport : false
			});
					
		}

	},
	
	destroyJCroper: function(){
		//if(ImageCropper.JCroper != undefined){
		if($('.jcrop-holder').get(0) != undefined){
			ImageCropper.JCroper.destroy();
			//ImageCropper.JCroper = undefined;
		}
	},
	
	getImageCropData: function(){
				
		$('#btnCropper').removeClass('btn-crop-red');
		$('#btnCropper').addClass('btn-crop');
		
		if($('.jcrop-holder').get(0) == undefined){
			return false;
		}
		
		var crop_width 	= $('#crop_width').val();
		var crop_height = $('#crop_height').val();
		var crop_x		= $('#crop_x').val();
		var crop_y 		= $('#crop_y').val();
		var curr_url	= $('#curr_url').val();
		var contentURL 	= curr_url.indexOf('?') != undefined && curr_url.indexOf('?') > 0 ? curr_url.substring(0, curr_url.indexOf('?')) : curr_url;
		
		return {
			contentURL	: contentURL,
			cropWidth 	: Math.round(eval(crop_width)), 
    		cropHeight	: Math.round(eval(crop_height)), 
    		cropX		: Math.round(eval(crop_x)), 
    		cropY		: Math.round(eval(crop_y))			
		};
	},
	
	setImageCropParameters : function (result) {
		
		ImageCropper.init();
		
    	if(result && result.success == true){
    		
    		ImageCropper.destroyJCroper();
    		
    		var height	= eval(result.height);
    		var width 	= eval(result.width);
    		var imgUrl 	= result.contentURL;
	
    		$('#'+ImageCropper.id).attr("style", "");
    		$('#'+ImageCropper.id).attr("height", "");
			$('#'+ImageCropper.id).attr("width", "");
			$('#'+ImageCropper.id).css("width", "");
			$('#'+ImageCropper.id).css("height", "");
			$('#'+ImageCropper.id).attr('src', imgUrl);
    		
    		$('#curr_url').val(imgUrl);
    		$('#curr_width').val(width);
			$('#curr_height').val(height);

			if( (width/height) < 1.33 ){
				$('#'+ImageCropper.id).attr("width", (width/height)*240);
				$('#'+ImageCropper.id).attr("height", 240);
				
				$('#'+ImageCropper.id).css("width", ((width/height)*240) + 'px');
				$('#'+ImageCropper.id).css("height", '240px');
			}else{
				$('#'+ImageCropper.id).attr("width", 320);
				$('#'+ImageCropper.id).attr("height", (height/width)*320);
				
				$('#'+ImageCropper.id).css("width", '320px');
				$('#'+ImageCropper.id).css("height", ((height/width)*320) + 'px');
			}					
    	}else{
    		ImageCropper.destroyJCroper();
    	}
    },
	
	updoImageCrop: function(){
		
		ImageCropper.init();
		ImageCropper.destroyJCroper();
		
		$('#btnCropper').removeClass('btn-crop-red');
		$('#btnCropper').addClass('btn-crop');
		
		var imgUrl = $('#orig_url').val();
		var width = eval($('#orig_width').val());
		var height = eval($('#orig_height').val());

		$('#'+ImageCropper.id).attr("style", "");
		$('#'+ImageCropper.id).attr("height", "");
		$('#'+ImageCropper.id).attr("width", "");
		$('#'+ImageCropper.id).attr('src', imgUrl + '&ts=' + Math.random());
		$('#imageURL').val(imgUrl);
		$("#hidImageURL").val(imgUrl);
		$('#contentImgUrlID').val(imgUrl);
		$('#uploadedImageUrlID').val($.trim(imgUrl));

		$('#curr_url').val(imgUrl);
		$('#curr_width').val(width);
		$('#curr_height').val(height);

		if( (width/height) < 1.33 ){
			$('#'+ImageCropper.id).attr("width", (width/height)*240);
			$('#'+ImageCropper.id).attr("height", 240);
			
		}else{
			$('#'+ImageCropper.id).attr("width", 320);
			$('#'+ImageCropper.id).attr("height", (height/width)*320);
		}
		
		$('#error_photo_wrap').html('');
		$('#error_photo_wrap').hide();
		
	},
	
	setCoordinates: function(c){		
		$('#crop_x').val(c.x);
		$('#crop_y').val(c.y);
		$('#crop_width').val(c.w);
		$('#crop_height').val(c.h);		
	},
	
	isSelectAreaEnabled: function(){
		if($('.jcrop-handle:visible').get(0) != undefined){
			return true;
		}
		return false;
	}
	
};

$(document).ready(function(){	
	ImageCropper.init();	
});