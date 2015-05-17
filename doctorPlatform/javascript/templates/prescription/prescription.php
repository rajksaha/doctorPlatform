<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<span class="headerText">Prescription</span>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-top: 5px;padding-bottom: 5px">
			<button style="padding-left:12px;" class="btn btn-info  pull-right" data-ng-click="menuState = !menuState"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Menu</button>
			<a style="padding-left:12px;" class="btn btn-info  pull-right" href="#/appointment"><span class="glyphicon glyphicon-th-list" aria-hidden="true"> Appointment</span></a>
			<button style="padding-left:12px;" class="btn btn-info pull-right" data-ng-click="patientSetting = !patientSetting"><span class="glyphicon glyphicon glyphicon-wrench" aria-hidden="true"></span> Setteing</button>
			<button style="padding-left:12px;" class="btn btn-info pull-right" data-ng-click="print()"><span class="glyphicon glyphicon-print" aria-hidden="true"> Print</span></button>
		</div>
</div>
<div class="panel  col-md-12 filter-panel" style="padding-top: 20px;padding-left: 20px;padding-right: 20px;">
	<div class="row">
		
		<div class="col-md-11 panel panel-primary keyPanelColor panel1" style="padding-top: 12px;padding-bottom: 12px">
			<div class="col-md-2 patinetInfo form-group">
				
				<span data-ng-show="!patientInfoEdit" >Name: <span >{{patientData.name}}</span></span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.name" class="form-control" />	
			</div>
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Age: {{patientData.age}} yrs</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.age" class="form-control" />
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Gender: {{patientData.sex}}</span>
				
				<select data-ng-show="patientInfoEdit" data-ng-model="patientData.sex" class="form-control">
					<option value="MALE">Male</option>
					<option value="FEMALE">Fe-male</option>
					<option value="OTHER">Other</option>
				</select>
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit">Phone: {{patientData.phone}}</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.patientCode" class="form-control" />
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Address: {{patientData.address}}</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.patientCode" class="form-control" />
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Code: {{patientData.patientCode}}</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.patientCode" size="5" class="form-control"  disabled="disabled"/>
			</div>
			
		</div>
		
		<div class="col-md-1 panel keyPanelColor panel1" style="padding-top: 12px;padding-bottom: 12px">
		
			<div class="col-md-12 patinetInfo form-group">
				<span>
					<i data-ng-show="!patientInfoEdit" class="pull-center glyphicon glyphicon-pencil" data-ng-click="patientInfoEdit = true" ></i>
					<i data-ng-show="patientInfoEdit" class="pull-center glyphicon glyphicon-folder-open" data-ng-click="patientInfoEdit = false" ></i>
				</span>
			</div>
			
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

<div class="col-md-12 panel panel-primary keyPanelColor panel1" style="padding-top: 20px;padding-left: 20px; padding-right: 20px" data-ng-show="menuState">

	<div class="row">
		
		<div class="list-item col-sm-4 col-md-3 col-lg-3" data-ng-repeat="menuData in menuDataList" style="padding-bottom: 10px">
			<span style="padding-left:20px">
				<img ng-src="images/icon-c-stat-2.png" />
				<span style="padding-left:12px;">
					<a href="{{menuData.menuURL}}">{{menuData.menuHeader}}</a>
					<a href="{{menuData.menuURL}}" class="pull-right" style="margin-right:10px"><img ng-src="images/edit1.png" width="18" height="18"/></a>
				</span>
			</span>
		</div>
		
	</div>
</div>

<div class="row" style="">


    
    
    <div class="panel panel-primary col-md-12 keyPanelColor panel2" style="padding-top: 5px;height: 600px; overflow-y: scroll;">
    	<div class="row">
    		<div class="col-md-4">
    		
    		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 5px">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><label>Next Vist</label></div>
							<div class="input-group input-group-sm">
								<input type="text"  class="form-control date" id="txtStartDate" placeholder="Next Visit Date" ng-change="fixNextVisit()" datepicker-popup="dd/MM/yyyy" close-text="Close" ng-model="nextVisitData.date" is-open="false"/>
								<span  class="input-group-addon" > 
									<i class="glyphicon glyphicon-calendar" ></i> 
								</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><label>Reffred Doctor :</label></div>
							<div class="input-group input-group-sm" data-ng-if="!refferedDoctorData.id">
								<input type="text" data-ng-model="refferedAdderData.doctorName" typeahead="refDoc.doctorName for refDoc in getRefDoctor($viewValue)"  class="form-control" placeholder="Search Doctor" typeahead-on-select='onSelectRefDocotor($item, $model, $label)'/>
								<input type="text" data-ng-model="refferedAdderData.doctorAdress" class="form-control" placeholder="Doctor Address"/>
								<button data-ng-show="refferedAdderData.doctorAdress && refferedAdderData.doctorName" data-ng-click="saveReffredDoctor(refferedAdderData)">Save</button>
							</div>
							
							<div class="input-group input-group-sm" data-ng-if="refferedDoctorData.id">
								
								<span>{{refferedDoctorData.doctorName}} - {{refferedDoctorData.doctorAdress}}</span>
								<button  data-ng-click="deleteReffredDoctor(refferedDoctorData.id)">X</button>
						</div>
						</div>
					</div>
				</div>
				
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><a href="#/complain" >C.C</a></div>
								<table class="table">
									<tr data-ng-repeat="copmplainData in prescribedComplainData" >
										<td style="width: 5%">
											<span>
												<a class="btn btn-warning btn-sm btnLanier"
									                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="data.id">
									                  <span class=" glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 45%">
				    						<span>{{copmplainData.symptomName}}</span>
										</td>
										<td style="width: 50%">
											<span>{{copmplainData.durationNum}} {{copmplainData.durationType}}</span>
										</td>
								</table>
						</div>
					</div>
				</div>
    			
    			
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-ng-show="prescribedMHData.length > 0">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><a href="#/history">MH History</a></div>
								<table class="table">
									<tr data-ng-repeat="data in prescribedMHData" >
										<td style="width: 5%">
											<span>
												<a class="btn btn-warning btn-sm btnLanier"
									                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="data.id">
									                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 45%">
				    						<span>{{data.historyName}}</span>
										</td>
										<td style="width: 50%">
											<span>{{data.historyResult}}</span>
										</td>
								</table>
						</div>
					</div>
				</div>
    			
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-ng-show="prescribedOBSData.length > 0">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><a href="#/obsHistory">OBS History</a></div>
								<table class="table">
									<tr data-ng-repeat="data in prescribedOBSData" >
										<td style="width: 5%">
											<span>
												<a class="btn btn-warning btn-sm btnLanier"
									                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="data.id">
									                  <span class=" glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 45%">
				    						<span>{{data.historyName}}</span>
										</td>
										<td style="width: 50%">
											<span>{{data.historyResult}}</span>
										</td>
								</table>
						</div>
					</div>
				</div>
    			
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><a href="#/vital">O.E</a></div>
								<table class="table">
									<tr data-ng-repeat="vitalData in prescribedVitalData" >
										<td style="width: 5%">
											<span>
												<a class="btn btn-warning btn-sm"
									                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="vitalData.id">
									                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 45%">
				    						<span>{{vitalData.vitalDisplayName}}</span>
										</td>
										<td style="width: 50%">
											<span>{{vitalData.vitalResult}}</span>
										</td>
								</table>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><a href="#/inv">INV</a></div>
								<table class="table">
									<tr data-ng-repeat="invData in prescribedInvData">
										<td style="width: 5%">
											<span>
												<a class="btn btn-warning btn-sm"
									                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="invData.invID">
									                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 95%">
				    						<span>{{invData.invName}}</span>
										</td>
									</tr>
								</table>
						</div>
					</div>
				</div>
				
					
    		</div>
    		
    		<div class="col-md-8">
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 5px">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><a href="#/diagnosis">Diagnosis:</a><label > {{diagnosisData.diseaseName}}</label></div>
							
						</div>
					</div>
				</div>
	                 
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 5px">
	             <div class="panel">	                 
	                 <div class="panel-body">
	                 
	                 	<div class="text-muted form-group"><a href="#/drugs">RX</a></div>
	                    <div class="room-desc form-group" data-ng-repeat="drugPres in prescribedDrugList">	
							<table id="" class="table">
								<tbody>
									<tr class="" >
										<td  style="width: 5%">
											<a class="btn btn-warning btn-sm btnLanier"
												ktr-confirmation="deletePrescribedDrug(item)" 
												confirmation-message="Are you sure to remove?"
												confirmation-title="Confirmation"
												item="drugPres.id">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</a>			
										</td>
										<td style="width: 20%">
											<span>{{drugPres.typeInitial}}. {{drugPres.drugName}} </span>
										</td>
										<td  style="width: 20%">
											<span>{{drugPres.drugDose}}</span>
										</td>
										<td style="width: 15%" ng-if="drugPres.drugNoOfDay > 0">
											<span>{{drugPres.drugNoOfDay}} -  {{drugPres.dayTypeName}}</span>
										</td>
										<td  style="width: 15%" ng-if="drugPres.drugNoOfDay < 1">
											<span>{{drugPres.dayTypeName}}</span>
										</td>
										<td   style="width: 20%">
											<span>{{drugPres.whenTypeName}}</span>
										</td>
										<td   style="width: 20%">
											<span>{{drugPres.adviceTypeName}}</span>
										</td>
										
									</tr>
								</tbody>
							</table>
	                    </div>
	                 </div>
	             </div>
	         </div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	             	<div class="panel">	                 
						<div class="panel-body">
							<div class="form-group"><a href="#/advice">Advice</a></div>
								<table id="" class="table">
									<tbody>
										<tr data-ng-repeat="adiviceData in prescribedAdviceData" style="height:50px;">
											<td style="width: 10%">
												<span>
													<a class="btn btn-warning btn-sm"
										                  ktr-confirmation="deleteInvFromPrescibtion(item)" 
										                  confirmation-message="Are you sure to remove?"
										                  confirmation-title="Confirmation"
										                  item="adiviceData.id">
										                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
													</a>
												</span>
											</td>
											<td style="width: 90%">
												<span>{{adiviceData.advice}}</span>
											</td>
											
										</tr>
									</tbody>
								</table>
						</div>
					</div>
				</div>
				
				
    			
					
    			
    		</div>
    	</div>
    </div>

</div>