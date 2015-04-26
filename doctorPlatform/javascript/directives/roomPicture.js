angular.module('roomPictureDirective', []) 

  .directive('roomPic', function(){
    return {
        scope: {
            roomPic: '='
           },    
        link: function(scope, elem, attrs){ 
           scope.$watch("roomPic", function(newValue, oldValue) {
                if (newValue != undefined)
                    elem.attr('src',newValue);
           });               
        }
    }
  })