'use strict';

var httpHeaders;

var jsVersion = "?v=008";

// This will store the original URL before login sequence
var originalLocation = "/menu";

app.config(function( $stateProvider, $urlRouterProvider, $compileProvider, $controllerProvider, $filterProvider, $provide, $ocLazyLoadProvider, datepickerConfig, datepickerPopupConfig) {

    // For any unmatched url, redirect to /login
    $urlRouterProvider.otherwise("/login");

    // datepicker hide week
    datepickerConfig.showWeeks = false;

    var login = {
        name : 'login',
        url : '/login',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/login.html',
                controller : 'LoginController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/login.js' + jsVersion, 'javascript/controllers/footer.js'  + jsVersion]
                    });
            }]
        }
    };

    var root = {
        name : 'root',
        url : '',
        abstract : true,
        views : {
            'header' : {
                templateUrl : 'javascript/templates/header.html',
                controller : 'HeaderController'
            },
            'status' : {
                templateUrl : 'javascript/templates/status.html'
            },
            'footer' : {
                templateUrl : 'javascript/templates/footer.html',
                controller : 'FooterController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/header.js' + jsVersion, 'javascript/controllers/footer.js' + jsVersion]
                    });
            }]
        }
    };


    var appointment = {
        name : 'root.appointment',
        url : '/appointment',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/appointment/appointment.php',
                controller : 'AppointmentController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/appointment/appoinment.js' ]
                    });
            }]
        }
    };

    var prescription = {
        name : 'root.prescription',
        url : '/prescription',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/prescription/prescription.html',
                controller : 'PrescriptionController'
            }
        },
        resolve : {
            loadMyService: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/services/jsonService.js' + jsVersion]
                    });
            }],
                loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/prescription/prescriptionController.js',
                            'javascript/controllers/inv/inv.js',
                            'javascript/controllers/prescription/complainController.js',
                            'javascript/controllers/prescription/diagnosisController.js',
                            'javascript/controllers/prescription/drugController.js',
                            'javascript/controllers/prescription/pdfSelectionController.js',
                            'javascript/controllers/prescription/prescriptionSettingController.js',
                            'javascript/controllers/prescription/vitalController.js',
                            'javascript/controllers/history/pastHistoryController.js',
                            'javascript/controllers/advice/advice.js'
                        ]
                    });
            }]
        }
    };

    var drugs = {
        name : 'root.drugs',
        url : '/drugs',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/drugs/drugs.html',
                controller : 'PrescribeDrugsController'
            }
        },
        resolve : {
            loadMyService: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/services/jsonService.js' + jsVersion]
                    });
            }],
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/drugs/drugs.js' ]
                    });
            }]
        }
    };

    var inv = {
        name : 'root.inv',
        url : '/inv',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/inv/inv.html',
                controller : 'PrescribeInvController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/inv/inv.js' ]
                    });
            }]
        }
    };

    var familyHisory = {
        name : 'root.familyHisory',
        url : '/familyHisory',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/history/familyHistory.html',
                controller : 'FamilyHisoryController'
            }
        },
        resolve : {
            loadMyService: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/services/jsonService.js' + jsVersion]
                    });
            }],
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/history/familyHisoryController.js' ]
                    });
            }]
        }
    };

    var pastHistory = {
        name : 'root.pastHistory',
        url : '/pastHistory',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/history/pastHistory.html',
                controller : 'PastHistoryController'
            }
        },
        resolve : {
            loadMyService: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/services/jsonService.js' + jsVersion]
                    });
            }],
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/history/pastHistoryController.js' ]
                    });
            }]
        }
    };

    var genInfo = {
        name : 'root.genInfo',
        url : '/genInfo',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/genInfo/genInfo.html',
                controller : 'PrescribeGenInfoController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/genInfo/genInfo.js' ]
                    });
            }]
        }
    };

    var vital = {
        name : 'root.vital',
        url : '/vital',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/vital/vitalModal.html',
                controller : 'PrescribeVitalController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/vital/vital.js' ]
                    });
            }]
        }
    };

    var history = {
        name : 'root.history',
        url : '/history?historyType',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/history/history.html',
                controller : 'PrescribeHistoryController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/history/history.js' ]
                    });
            }]
        }
    };

    var advice = {
        name : 'root.advice',
        url : '/advice',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/advice/advice.html',
                controller : 'PrescribeAdviceController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/advice/advice.js' ]
                    });
            }]
        }
    };

    var diagnosis = {
        name : 'root.diagnosis',
        url : '/diagnosis',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/diagnosis/diagnosis.html',
                controller : 'PrescribeDiagnosisController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/diagnosis/diagnosisController.js' ]
                    });
            }]
        }
    };

    var oldPrescription = {
        name : 'root.oldPrescription',
        url : '/oldPrescription',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/oldPrescription/oldPrescription.html',
                controller : 'OldPrescriptionController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/oldPrescription/oldPrescription.js' ]
                    });
            }]
        }
    };

    var prescribeByDisease = {
        name : 'root.prescribeByDisease',
        url : '/prescribeByDisease',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/settings/prescribeByDisease.html',
                controller : 'PrescribeByDiseaseController'
            }
        },
        resolve : {
            loadMyService: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/services/jsonService.js' + jsVersion]
                    });
            }],
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/settings/prescribeByDiseaseController.js' ]
                    });
            }]
        }
    };


    var settingSelection = {
        name : 'root.settingSelection',
        url : '/settingSelection',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/settings/settingSelection.html',
                controller : 'SettingSelectionController'
            }
        },
        resolve : {
            loadMyService: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/services/jsonService.js' + jsVersion]
                    });
            }],
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/settings/settingSelectionController.js',
                            'javascript/controllers/settings/prescribeByDiseaseController.js',
                            'javascript/controllers/settings/drugTemplateController.js',
                            'javascript/controllers/invCategory/invCategoryController.js',
                            'javascript/controllers/drugAdvisor/drugAdvisorController.js',
                            'javascript/controllers/followUpSetup/followUpSetupController.js'
                        ]
                    });
            }]
        }
    };

    var invReport = {
        name : 'root.invReport',
        url : '/invReport',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/invReport/invReport.html',
                controller : 'invReportController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/invReport/invReportController.js' ]
                    });
            }]
        }
    };


    var followUpChart = {
        name : 'root.followUpChart',
        url : '/followUpChart',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/followUpChart/followUpChart.html',
                controller : 'FollowUpChartController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/followUpChart/followUpChartController.js' ]
                    });
            }]
        }
    };

    var drugHistory = {
        name : 'root.drugHistory',
        url : '/drugHistory',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/drugHistory/drugHistory.html',
                controller : 'DrugHistoryController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/drugHistory/drugHistoryController.js' ]
                    });
            }]
        }
    };

    var drugAdvisor = {
        name : 'root.drugAdvisor',
        url : '/drugAdvisor',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/drugAdvisor/drugAdvisor.html',
                controller : 'DrugAdvisorController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/drugAdvisor/drugAdvisorController.js' ]
                    });
            }]
        }
    };

    var researchHome = {
        name : 'root.researchHome',
        url : '/researchHome',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/researchHome/researchHome.html',
                controller : 'ResearchHomeController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/researchHome/researchHomeController.js' ]
                    });
            }]
        }
    };

    var followUpSetup = {
        name : 'root.followUpSetup',
        url : '/followUpSetup',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/followUpSetup/followUpSetup.html',
                controller : 'FollowUpSetupController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/followUpSetup/followUpSetupController.js' ]
                    });
            }]
        }
    };

    var invCategory = {
        name : 'root.invCategory',
        url : '/invCategory',
        views : {
            'container@' : {
                templateUrl : 'javascript/templates/invCategory/invCategory.html',
                controller : 'InvCategoryController'
            }
        },
        resolve : {
            loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
                // you can lazy load files for an existing module
                return $ocLazyLoad.load(
                    {
                        name: 'doctorPlatform',
                        files: ['javascript/controllers/invCategory/invCategoryController.js' ]
                    });
            }]
        }
    };




    $stateProvider
        .state(root)
        .state(login)
        .state(prescription)
        .state(drugs)
        .state(inv)
        .state(familyHisory)
        .state(pastHistory)
        .state(genInfo)
        .state(vital)
        .state(history)
        .state(advice)
        .state(diagnosis)
        .state(oldPrescription)
        .state(prescribeByDisease)
        .state(settingSelection)
        .state(invReport)
        .state(followUpChart)
        .state(drugAdvisor)
        .state(drugHistory)
        .state(researchHome)
        .state(followUpSetup)
        .state(invCategory)
        .state(appointment);


    $ocLazyLoadProvider.config({debug:true, events:true});

});

app.config(function ($httpProvider) {
    //configure $http to view a login whenever a 401 unauthorized response arrives
    $httpProvider.responseInterceptors.push(function ($rootScope, $q) {
        return function (promise) {
            return promise.then(
                //success -> don't intercept
                function (response) {
                    return response;
                },
                //error -> if 401 save the request and broadcast an event
                function (response) {
                    if (response.status === 401) {
                        // Set the message why is unauthorized
                        $rootScope.warn = true;
                        //$rootScope.warnMessage = response.data.message;
                        $rootScope.warnMessage = response.data.message;

                        var deferred = $q.defer(),
                            req = {
                                config: response.config,
                                deferred: deferred
                            };
                        $rootScope.requests401.push(req);
                        //Hide and remove all open dialog.
                        $('.modal-backdrop').hide();
                        $(".modal").remove();
                        $rootScope.$broadcast('event:loginRequired');
                        return deferred.promise;
                    }
                    return $q.reject(response);
                }
            );
        };
    });
    httpHeaders = $httpProvider.defaults.headers;
});
