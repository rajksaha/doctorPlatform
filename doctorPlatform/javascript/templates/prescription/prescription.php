<div class="panel  col-md-12 filter-panel" style="padding-top: 20px;padding-left: 20px;">
	<div class="row">
		<div class="col-md-8 panel panel-primary keyPanelColor panel1">
			<span style="padding-left:20px">Patient Name:<span style="padding-left:12px;">{{patientData.name}}</span></span>
			<span style="padding-left:40px">Age:<span style="padding-left:12px;">{{patientData.age}} yrs</span></span>
			<span style="padding-left:40px">Gender:<span style="padding-left:12px;">{{patientData.sex}}</span></span>
		</div>
		<div class="col-md-4">
			<a class="pull-right" href="#/appointment"><span class="glyphicon glyphicon-th-list" aria-hidden="true">Appointment</span></a>
			<button class="pull-right btn btn-info" data-ng-click="patientSetting = !patientSetting"><span class="glyphicon glyphicon glyphicon-wrench" aria-hidden="true"></span> Setteing</button>
		</div>
	</div>
</div>
<div class="panel  col-md-12 filter-panel" style="padding-top: 20px;padding-left: 20px; padding-right: 20px" data-ng-show="patientSetting && (doctorData.patientType == 1 || doctorData.patientState == 1)">

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
    <div class="panel panel-primary col-md-10 keyPanelColor panel2">
    	<div class="row">
    		<div class="col-md-4">
    			<table id="" class="table">
    				<tr data-ng-show="prescribedComplainData.length > 0"><td><a href="#/complain" >C.C</a></td><td>&nbsp;</td><td>&nbsp;</td></tr>
    				<tr data-ng-repeat="copmplainData in prescribedComplainData" >
    					<td>
    						<span>{{copmplainData.symptomName}}</span>
						</td>
						<td >
							<span>{{copmplainData.durationNum}} {{copmplainData.durationType}}</span>
						</td>
						<td >
							<span>
								<a class="btn btn-danger btn-sm btnLanier"
					                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
					                  confirmation-message="Are you sure to remove?"
					                  confirmation-title="Confirmation"
					                  item="copmplainData.id">
					                  <span class="glyphicons glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</span>
						</td>
					</tr>
					<tr data-ng-show="prescribedMHData.length > 0"><td><a href="#/history" >M.H</a></td><td></td><td></td></tr>
    				<tr data-ng-repeat="data in prescribedMHData" >
    					<td>
    						<span>{{data.historyName}}</span>
						</td>
						<td >
							<span>{{data.historyResult}}</span>
						</td>
						<td >
							<span>
								<a class="btn btn-danger btn-sm btnLanier"
					                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
					                  confirmation-message="Are you sure to remove?"
					                  confirmation-title="Confirmation"
					                  item="data.id">
					                  <span class="glyphicons glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</span>
						</td>
					</tr>
					<tr data-ng-show="prescribedOBSData.length > 0"><td><a href="#/obsHistory">OBS History</a></td><td></td><td></td></tr>
    				<tr data-ng-repeat="data in prescribedOBSData" >
    					<td>
    						<span>{{data.historyName}}</span>
						</td>
						<td >
							<span>{{data.historyResult}}</span>
						</td>
						<td >
							<span>
								<a class="btn btn-danger btn-sm btnLanier"
					                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
					                  confirmation-message="Are you sure to remove?"
					                  confirmation-title="Confirmation"
					                  item="data.id">
					                  <span class="glyphicons glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</span>
						</td>
					</tr>
					<tr data-ng-show="prescribedVitalData.length > 0"><td><a href="#/vital" data-ng-show="prescribedVitalData.length > 0">O.E</a></td><td></td><td></td></tr>
    				<tr data-ng-repeat="vitalData in prescribedVitalData" >
    					<td>
    						<span>{{vitalData.vitalDisplayName}}</span>
						</td>
						<td >
							<span>{{vitalData.vitalResult}}</span>
						</td>
						<td >
							<span>
								<a class="btn btn-danger btn-sm btnLanier"
					                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
					                  confirmation-message="Are you sure to remove?"
					                  confirmation-title="Confirmation"
					                  item="vitalData.id">
					                  <span class="glyphicons glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</span>
						</td>
					</tr>
					<tr data-ng-show="prescribedInvData.length > 0"><td><a href="#/inv" data-ng-show="prescribedInvData.length > 0">INV</a></td><td></td><td></td></tr>
    				<tr data-ng-repeat="invData in prescribedInvData" >
    					<td>
    						<span>{{invData.invName}}</span>
						</td>
						<td >
							<span>&nbsp;</span>
						</td>
						<td >
							<span>
								<a class="btn btn-danger btn-sm btnLanier"
					                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
					                  confirmation-message="Are you sure to remove?"
					                  confirmation-title="Confirmation"
					                  item="invData.invID">
					                  <span class="glyphicons glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</span>
						</td>
					</tr>
    			</table>

    		</div>
    		
    		<div class="col-md-6">
    			<a href="#/drugs">RX</a>
				<table id="content-data-list" class="table">
					<tbody>
						<tr class="appointment" data-ng-repeat="drugPres in prescribedDrugList" style="height: 50px;cursor: pointer;">
							<td class="nowrap" >
								{{$index + 1}}.
							</td>
											
							<td >
								<span>{{drugPres.typeInitial}}. {{drugPres.drugName}} </span>
							</td>
							<td  >
								<span>{{drugPres.drugDose}}</span>
							</td>
							<td ng-if="drugPres.drugNoOfDay > 0">
								<span>{{drugPres.drugNoOfDay}} -  {{drugPres.dayTypeName}}</span>
							</td>
							<td ng-if="drugPres.drugNoOfDay < 1">
								<span>{{drugPres.dayTypeName}}</span>
							</td>
							<td  >
								<span>{{drugPres.whenTypeName}}</span>
							</td>
							<td  >
								<span>{{drugPres.adviceTypeName}}</span>
							</td>
							<td  >
								<a class="btn btn-danger btn-sm btnLanier"
									ktr-confirmation="deletePrescribedDrug(item)" 
									confirmation-message="Are you sure to remove?"
									confirmation-title="Confirmation"
									item="drugPres.id">
									<span class="glyphicons glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>			
							</td>
						</tr>
					</tbody>
				</table>
				
				<a href="#/advice" data-ng-show="prescribedAdviceData.length > 0">Advice</a>
    			
					<table id="" class="table">
						<tbody>
							<tr data-ng-repeat="adiviceData in prescribedAdviceData" style="height:50px;">
							
								
								<!-- <td class="nowrap" >
									<span>{{$index + 1}}. </span>
									
								</td> -->
								<td >
									<span>{{adiviceData.advice}}</span>
								</td>
								<td >
									<span>
										<a class="btn btn-danger btn-sm btnLanier"
							                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
							                  confirmation-message="Are you sure to remove?"
							                  confirmation-title="Confirmation"
							                  item="adiviceData.id">
							                  <span class="glyphicons glyphicon glyphicon-trash" aria-hidden="true"></span>
										</a>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
    			
    		</div>
    		<div class="col-md-2">
    		</div>
    	</div>
    </div>

</div>