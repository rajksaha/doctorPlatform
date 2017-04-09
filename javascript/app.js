'use strict';

var app = angular.module('doctorPlatform', [
    // Third party libs declare here
	  // We going to use ocLazyLoad with ui.router
    'ui.router',
    'oc.lazyLoad',
    'ui.bootstrap',
    'ui.event',
    'ui.calendar',
    
    // Google Analytics
    'angulartics',
    'angulartics.google.analytics'
    
]);
