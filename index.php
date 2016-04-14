<!doctype html>
<html lang="en" ng-app="doctorPlatform">
<head>





    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link href="lib/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="lib/css/custom-icon.css" rel="stylesheet"/>
    <link href="lib/css/bootstrap-theme-ktr.css" rel="stylesheet"/>
    <link href="css/common/bottomUp.css" rel="stylesheet"/>
    <link href="lib/css/jquery-ui-1.11.0/jquery-ui.min.css" rel="stylesheet"/>

    
	<!-- ng-table -->
    <link href="lib/css/ng-table.min.css" rel="stylesheet"/>
    <link href="lib/css/ng-sortable.min.css" rel="stylesheet"/>

	<!-- Animate.css -->
    <link href="lib/css/animate.css" rel="stylesheet"/>

	
	<link href="css/theme/kaitair/common.css" rel="stylesheet"/>
	<link href="css/theme/kaitair/login.css" rel="stylesheet"/>
    <link href="css/theme/kaitair/room.css" rel="stylesheet"/>
    <link href="css/theme/kaitair/approval-center.css" rel="stylesheet"/>
    <link href="css/theme/kaitair/colors.css" rel="stylesheet"/>
    <link href="css/theme/kaitair/menu.css" rel="stylesheet"/>
    
    <link href="css/common/autocomplete.css" rel="stylesheet"/>

  

	<!-- Superslides v0.6.2 -->
	<link href='lib/css/superslides.css' rel='stylesheet' />

	<!-- Sticky Footer -->
	<link href='css/common/sticky-footer.css' rel='stylesheet' />
	

    <title>Doctor</title>
</head>

<body>
    <div class="main">
        <div ui-view="header"></div>
        <div ui-view="status"></div>
        <div ui-view="menu" class="container-menu"></div>
        <div ui-view="container" class="page container-main container-fluid"></div>
        <div ui-view="footer" class="footer"></div>
    </div>

    <!-- Based on http://developer.yahoo.com/blogs/ydn/high-performance-sites-rule-6-move-scripts-bottom-7200.html -->
    <!-- Load JS at the bottom -->


    <!-- JQuery-UI -->
    <!--<script src="lib/javascript/jquery-1.11.0.min.js"></script>-->
    <script src="lib/javascript/jquery-2.1.1.min.js"></script>

	<!-- Superslides v0.6.2 -->
	<script src="lib/javascript/jquery.animate-enhanced.min.js"></script>
	<script src="lib/javascript/jquery.easing.1.3.js"></script>
	<script src="lib/javascript/jquery.superslides.js"></script>


	<!-- <script type="text/javascript" src="lib/javascript/angular-file-upload/angular-file-upload-shim.min.js"> -->


    <!-- Angular -->
    <script src="lib/javascript/angular-1.2.23/angular.js"></script>

    <!-- Angular modules -->
    <script src="lib/javascript/angular-ui-modules-0.1.1/event/event.js"></script>

        <!-- Angular extension -->
	<!-- Using angular-ui-router instead of angular-route-->
	<script type="text/javascript" src="lib/javascript/ui-router-0.2.10/angular-ui-router.min.js"></script>
	<script src="lib/javascript/ui-bootstrap-0.10.0.js"></script>
	<script type="text/javascript" src="lib/javascript/ui-bootstrap-tpls-0.10.0.js"></script>
	<script type="text/javascript" src="lib/javascript/ng-table.min.js"></script>
	<script type="text/javascript" src="lib/javascript/ui-slider.js"></script>
    <script type="text/javascript" src="lib/javascript/ui-tinymce.js"></script>
    <script type="text/javascript" src="lib/javascript/angular-file-upload/angular-file-upload.min.js">



    <!-- angular-block-ui -->
    <script src="lib/javascript/angular-block-ui/angular-block-ui.js"></script>

    <!-- Arshaw Full Calendar -->
    <script src='lib/javascript/moment.min.js'></script>
    <script src='lib/javascript/fullcalendar.custom.js'></script>
    <script src='lib/javascript/angular-calendar.js'></script>

    <!-- date formatter -->
    <script src='lib/javascript/date.js'></script>

    <!-- ozLazyLoad - lazy loading -->
    <script src="lib/javascript/ocLazyLoad.min.js"></script>
    
    
    <!-- Angular Google Analytics -->
    <script src="lib/javascript/angulartics-0.17.2/angulartics.min.js"></script>
    <script src="lib/javascript/angulartics-0.17.2/angulartics-ga.min.js"></script>

    <!-- Kaitair lib -->
    <!-- core -->
    <script src="javascript/app.js?v=1.0"></script>
    <script src="javascript/config.js?v=1.0"></script>
    <!-- listener, for login -->
    
    <script src="javascript/listener.js"></script>

    <!-- These are require by listener.js -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->

	
    <!-- directives -->
    <script src="javascript/directives/roomPicture.js"></script>
    <script src="javascript/directives/scroller.js"></script>
    <script src="javascript/directives/ktrConfirmation.js"></script>
    <script src="javascript/directives/ktrOtaConfirmation.js"></script>
    <!-- <script src="javascript/directives/raty.js"></script> -->
    <script src="javascript/directives/imageRepaint.js"></script>
    
	<!-- <script src="lib/javascript/ui-bootstrap-0.11.2/ui-bootstrap-tpls-0.11.2.min.js"></script> -->
    <!-- filters -->
	<script src="javascript/filters/common.js"></script>

	<!-- Input Validator -->
	<!-- <script type="text/javascript" src="javascript/inputValidator/inputValidator.js"></script> -->
	<script type="text/javascript" src="javascript/inputValidator/inputValidator.0.1.js"></script>


</body>
</html>
