<div class="panel  col-md-12 filter-panel" style="padding-top: 20px;padding-left: 20px;">
	<div class="row">
		<div class="col-md-8 panel panel-primary keyPanelColor panel1">
			<span style="padding-left:20px">Patient Name:<span style="padding-left:12px;">{{patientData.name}}</span></span>
			<span style="padding-left:40px">Age:<span style="padding-left:12px;">{{patientData.age}} yrs</span></span>
			<span style="padding-left:40px">Gender:<span style="padding-left:12px;">{{patientData.sex}}</span></span>
		</div>
		<div class="col-md-4">
			<span style="padding-left:20px"><a href="javascript:" data-ng-click="addNewAppointment()"><img class="photo-room" width="18" height="18" src="images/forms.png"></a><span style="padding-left:12px;">Appointment</span></span>
			<span style="padding-left:20px"><a href="javascript:" data-ng-click="addNewAppointment()"><img class="photo-room" width="18" height="18" src="images/forms.png"></a><span style="padding-left:12px;">Prescription</span></span>
			<span style="padding-left:20px"><a href="javascript:" data-ng-click="addNewAppointment()"><img class="photo-room" width="18" height="18" src="images/forms.png"></a><span style="padding-left:12px;">Print</span></span>
		</div>
	</div>
</div>
<div class="panel  col-md-12 filter-panel" style="padding-top: 20px;padding-left: 20px; padding-right: 20px" data-ng-show="doctorData.patientType == 1 || doctorData.patientState == 1">

	<div class="row">
		
		<fieldset class="col-md-5  panel panel-primary  panel1" >
			<legend>Patient Type</legend>
			<span data-ng-repeat="patientType in patientTypeList" style="padding-left: 10px">
				<input type="checkbox" data-ng-model="patientType.patientTypeData" data-ng-checked="patientData.type == patientType.id" data-ng-change="changePatientType(patientType)">{{patientType.typeName}}
			</span>
		</fieldset>
		<span class="col-md-1"></span>
		<fieldset class="col-md-6 panel panel-primary">
			<legend>Visit Type</legend>
			<span data-ng-repeat="patientState in patientStateList" style="padding-left: 10px">
				<input type="checkbox" data-ng-model="patientState.patientStateData" data-ng-checked="appoinmentData.appointmentType == patientState.id" data-ng-change="changePatientState(patientState)">{{patientState.shortName}}
			</span>
		</fieldset>
	</div>
</div>
<div class="row">

<div class=" panel panel-primary col-md-2 keyPanelColor panel2">
        <table class="table table-condensed table-responsive">
            <tbody>
                <tr data-ng-repeat="menuData in menuDataList">
                    <td class="tableDark leftCellBorder tblWidthHeight" style="padding-left:12px;line-height:35px">
                        <img ng-src="images/icon-c-stat-2.png"  />
                    </td>
                    <td class="tableDark" style="padding-left:20px;line-height:35px">
                    	<a href="{{menuData.menuURL}}">{{menuData.menuHeader}}</a>
                    	<a href="{{menuData.menuURL}}" class="pull-right" style="margin-right:10px"><img ng-src="images/edit1.png" width="18" height="18"/></a>
                    </td>
                </tr>
            </tbody>
        </table>    
    </div>
    <div class=" panel panel-primary col-md-10 keyPanelColor panel2">
    </div>

</div>