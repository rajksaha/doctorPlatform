<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		
		<label class="headerText" >Visit Type</label>
		<span data-ng-repeat="patientState in patientStateList" style="padding-left: 10px">
			<input type="checkbox"  data-ng-model="patientState.patientStateData" data-ng-checked="appoinmentData.appointmentType == patientState.id" data-ng-change="changePatientState(patientState)"> <span > {{patientState.name}}</span>
		</span>
	</div>
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding-top: 5px;padding-bottom: 5px">
		<button style="padding-left:12px;" class="btn btn-info  pull-right" data-ng-click="menuState = !menuState"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Menu</button>
		<a style="padding-left:12px;" class="btn btn-info  pull-right" href="#/appointment"><span class="glyphicon glyphicon-th-list" aria-hidden="true"> Appointment</span></a>
		<button data-ng-show="doctorData.category == 8" style="padding-left:12px;" class="btn btn-info pull-right" data-ng-click="patientSetting = !patientSetting"><span class="glyphicon glyphicon glyphicon-wrench" aria-hidden="true"></span> Patient Type</button>
		<button style="padding-left:12px;" class="btn btn-info pull-right" data-ng-click="print()"><span class="glyphicon glyphicon-print" aria-hidden="true"> Print</span></button>
	</div>
</div>

<div class="panel  col-md-12 filter-panel" style="padding-top: 5px;padding-left: 20px; padding-right: 20px;padding-bottom: 5px;" data-ng-show="patientSetting">

	<div class="row">
		<fieldset class="col-md-5  panel panel-primary  panel1">
			<legend>Patient Type</legend>
			<span data-ng-repeat="patientType in patientTypeList" style="padding-left: 10px">
				<input type="checkbox" data-ng-model="patientType.patientTypeData" data-ng-checked="patientData.type == patientType.id" data-ng-change="changePatientType(patientType)"> {{patientType.typeName}}
			</span>
		</fieldset>
	</div>
</div>

<div class="panel  col-md-12 filter-panel" id="validateReq" style="padding-top: 10px;padding-left: 20px;padding-right: 20px;">
	<div class="row">
		<span class="pull-right">
			<i data-ng-show="!patientInfoEdit" class="pull-center glyphicon glyphicon-pencil" data-ng-click="patientInfoEdit = true" ></i>
			<i data-ng-show="patientInfoEdit" class="pull-center glyphicon glyphicon-folder-open" data-ng-click="savePatientInfo(patientData)" ></i>
		</span>
		<div class="col-md-12 panel panel-primary mainPanelColor panel1" style="padding-top: 12px;padding-bottom: 10px">
			<div class="col-md-2 patinetInfo form-group">
				
				<span data-ng-show="!patientInfoEdit" >Name: <span >{{patientData.name}}</span></span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.name" class="form-control required" />	
			</div>
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Age: {{patientData.age}} yrs</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.age" class="form-control required" />
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Gender: {{patientData.sex}}</span>
				
				<select data-ng-show="patientInfoEdit" data-ng-model="patientData.sex" class="form-control">
					<option value="MALE">Male</option>
					<option value="FEMALE">Female</option>
					<option value="OTHER">Other</option>
				</select>
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit">Phone: {{patientData.phone}}</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.phone" class="form-control phnnr" maxlength="16" placeholder="Phone"/>
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Address: {{patientData.address}}</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.address" class="form-control" maxlength="100" placeholder="Address" />
			</div>
			
			<div class="col-md-2 patinetInfo form-group">
				<span data-ng-show="!patientInfoEdit" >Code: {{patientData.patientCode}}</span>
				<input data-ng-show="patientInfoEdit" data-ng-model="patientData.patientCode" size="5" class="form-control"  disabled="disabled"/>
			</div>
			
		</div>
		
		
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

<div class="panel  col-md-12 filter-panel">


    
    <div class="panel panel-primary col-md-12 mainPanelColor panel2" style="padding-top: 5px;height: 600px; overflow-y: scroll;">
    	<div class="row">
    		<div class="col-md-4">
    		
    		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 5px">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group">
								<label>Next Visit</label> 
								<span class="pull-right">
									<button class="btn btn-info btn-sm" data-ng-show="nextVisitData.nextVisitType != 2" data-ng-click="nextVisitData.nextVisitType = 2;nextVisitData.needSaveButton = true">By Day</button>
									<button class="btn btn-info btn-sm" data-ng-show="nextVisitData.nextVisitType == 2" data-ng-click="nextVisitData.nextVisitType = 1">By Date</button>
								</span>
							</div>
							<div class="input-group input-group-sm" data-ng-show="nextVisitData.nextVisitType != 2">
								<input type="text"  class="form-control date" id="txtStartDate" placeholder="Next Visit Date" ng-change="fixNextVisit()" datepicker-popup="dd/MM/yyyy" close-text="Close" ng-model="nextVisitData.date" is-open="false"/>
								<span  class="input-group-addon" > 
									<i class="glyphicon glyphicon-calendar" ></i> 
								</span>
							</div>
							
							<div class="form-group" data-ng-show="nextVisitData.nextVisitType == 2" >
								<label>After:</label> 
								<button class="btn btnLanier btn-success pull-right" data-ng-show="nextVisitData.needSaveButton" title="Save" data-ng-show="true" data-ng-click="fixNextVisit()">
									<span class="glyphicons glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								</button>
								<select class="form-control"  data-ng-model="nextVisitData.numOfDay"  data-ng-options="drugDay.name for drugDay in dayList" data-ng-change="nextVisitData.needSaveButton = true">
								</select>
								<select class="form-control"   data-ng-model="nextVisitData.dayType" data-ng-options="drugTime.english for drugTime in dayTypeList" data-ng-change="nextVisitData.needSaveButton = true">
								</select>
								
							</div>
						</div>
					</div>
				</div>
				
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><a href="javascript:" data-ng-click="addCCToPrescription()"><label class="mainLabel">C.C</label></a></div>
								<table class="table">
									<tr data-ng-repeat="copmplainData in prescribedComplainData" >
										<td style="width: 10%">
												<a class="btn btn-danger btn-sm btnLanier"
									                  ktr-confirmation="deleteCCFromPresciption(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="copmplainData.id">
									                  <span class=" glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
										</td>
										<td style="width: 10%">
												<button class="btn btn-info btn-sm" data-ng-click="editFromPresciption(copmplainData)">
													<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
												</button>
										</td>
				    					<td style="width: 50%">
				    						<span>{{copmplainData.symptomName}}</span>
										</td>
										<td style="width: 30%" ng-if="copmplainData.durationID < 5">
											<span>{{copmplainData.durationNum}} {{copmplainData.durationType}}</span>
										</td>
										<td style="width: 30%" ng-if="copmplainData.durationID == 7">
											<span>{{copmplainData.durationType}}</span>
										</td>
								</table>
						</div>
					</div>
				</div>
    			
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-ng-repeat="history in historyList">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><label class="mainLabel">{{history.headerName}}</label></div>
								<table class="table">
									<tr data-ng-repeat="data in history.prescribedHistoryList" >
										<td style="width: 5%">
											<span>
												<a class="btn btn-danger btn-sm btnLanier"
									                  ktr-confirmation="deleteHistory(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="data">
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
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-ng-repeat="drug in drugHistory">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><label class="mainLabel">{{drug.headerName}}</label></div>
								<table class="table">
									<tr data-ng-repeat="data in drug.prescribedDrugList" >
										<td style="width: 5%">
											<span>
												<a class="btn btn-danger btn-sm btnLanier"
									                  ktr-confirmation="removeDrugHistory(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="data"  >
									                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 45%">
				    						<span>{{data.detail}}</span>
										</td>
								</table>
						</div>
					</div>
				</div>
    			
    			
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><a href="#/vital"><label class="mainLabel">O.E</label></a></div>
								<table class="table">
									<tr data-ng-repeat="vitalData in prescribedVitalData" >
										<td style="width: 5%">
											<span>
												<a class="btn btn-danger btn-sm"
									                  ktr-confirmation="deleteVitalFromPrescibtion(item)" 
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
						<div class="prescriptionPanel-body">
							<div class="form-group"><a href="#/inv"><label class="mainLabel">INV</label></a></div>
								<table class="table">
									<tr data-ng-repeat="invData in prescribedInvData">
										<td style="width: 5%">
											<span>
												<a class="btn btn-danger btn-sm"
									                  ktr-confirmation="deleteInvFromPresciption(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="invData.id">
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
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-ng-show="pastDiseaseList.length > 0">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><a href="#/pastHistory"><label class="mainLabel">Past Disease</label></a></div>
								<table class="table">
									<tr data-ng-repeat="data in pastDiseaseList">
										<td style="width: 5%">
											<span>
												<a class="btn btn-danger btn-sm"
									                  ktr-confirmation="deletePastHistory(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="data.id">
									                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 95%">
				    						<span>{{data.diseaseName}}</span>
										</td>
									</tr>
								</table>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-ng-show="familyDiseaseList.length > 0">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><a href="#/familyHisory"><label class="mainLabel" >Family Disease</label></a></div>
								<table class="table">
									<tr data-ng-repeat="data in familyDiseaseList">
										<td style="width: 5%">
											<span>
												<a class="btn btn-danger btn-sm"
									                  ktr-confirmation="deleteFamilyHistory(item)" 
									                  confirmation-message="Are you sure to remove?"
									                  confirmation-title="Confirmation"
									                  item="data.id">
									                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
												</a>
											</span>
										</td>
				    					<td style="width: 50%">
				    						<span>{{data.diseaseName}}</span>
										</td>
										
										<td style="width: 45%">
				    						<span>{{data.relationName}}</span>
										</td>
									</tr>
								</table>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><label>Reffred Doctor :</label></div>
							<div class="input-group input-group-sm" data-ng-if="!refferedDoctorData.id">
								<input type="text" data-ng-model="refferedAdderData.doctorName" typeahead="refDoc.doctorName for refDoc in getRefDoctor($viewValue)"  class="form-control" placeholder="Search Doctor" typeahead-on-select='onSelectRefDocotor($item, $model, $label)'/>
								<input type="text" data-ng-model="refferedAdderData.doctorAdress" class="form-control" placeholder="Doctor Address"/>
								<button class="btn btnLanier btn-success" title="Save" data-ng-show="refferedAdderData.doctorAdress && refferedAdderData.doctorName" data-ng-click="saveReffredDoctor(refferedAdderData)">
									<span class="glyphicons glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
								</button>
							</div>
							
							<div class="input-group input-group-sm" data-ng-if="refferedDoctorData.id">
								
								<span>{{refferedDoctorData.doctorName}} - {{refferedDoctorData.doctorAdress}}</span>
								<button  class="btn btnLanier btn-danger" title="Delete" data-ng-click="deleteReffredDoctor(refferedDoctorData.id)">
									<span class="glyphicons glyphicon glyphicon-remove" aria-hidden="true"></span>
								</button>
						</div>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group">
								<span class="pull-let"><label>Comment :</label></span>
								<span class="pull-right">
									<button  class="btn btnLanier btn-success" title="Save" data-ng-click="updateCommentText()">
										<span class="glyphicons glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
									</button>
								</span>
							</div>
							<div class="input-group input-group-sm">
								
								<span><textarea rows="5" cols="40" data-ng-model="commentText">{{commentText}}</textarea></span>
						</div>
						</div>
					</div>
				</div>
				
					
    		</div>
    		
    		<div class="col-md-8">
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 5px">
	             	<div class="panel">	                 
						<div class="prescriptionPanel-body">
							<div class="form-group"><a href="javascript:" data-ng-click="performDiganosis(diagnosisData)" ><label class="mainLabel">Diagnosis </label></a><span > {{diagnosisData.diseaseName}}</span></div>
							
						</div>
					</div>
				</div>
	                 
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 5px">
	             <div class="panel">	                 
	                 <div class="prescriptionPanel-body">
	                 
	                 	<div class="text-muted form-group"><a href="javascript:" data-ng-click="addDrugsToPrescription()"><label class="mainLabel">RX</label></a></div>
	                    <div class="room-desc form-group" data-ng-repeat="drugPres in prescribedDrugList">	
							<table id="" class="table">
								<tbody>
									<tr class="" >
										<td  style="width: 5%">
											<a class="btn btn-danger btn-sm btnLanier"
												ktr-confirmation="deletePrescribedDrug(item)" 
												confirmation-message="Are you sure to remove?"
												confirmation-title="Confirmation"
												item="drugPres.id">
												<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</a>			
										</td>
										<td style="width: 5%">
												<button class="btn btn-info btn-sm" data-ng-click="editDrugsFromPresciption(drugPres)">
													<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
												</button>
										</td>
										<td style="width: 20%">
											<span>{{drugPres.typeInitial}}. {{drugPres.drugName}} - {{drugPres.drugStrength}}</span>
										</td>
										<td  style="width: 30%" >
											<div data-ng-repeat="drugDose in drugPres.preiodicList">
												<span>{{drugDose.dose}} <span class="pull-right"> {{drugDose.numOfDay}}  {{drugDose.bangla}} </span></span>
											</div>
											
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
						<div class="prescriptionPanel-body">
							<div class="form-group"><a href="#/advice"><label class="mainLabel">Advice</label></a></div>
								<table id="" class="table">
									<tbody>
										<tr data-ng-repeat="adiviceData in prescribedAdviceData" style="height:50px;">
											<td style="width: 10%">
												<span>
													<a class="btn btn-danger btn-sm"
										                  ktr-confirmation="deleteAdviceFromPresciption(item)" 
										                  confirmation-message="Are you sure to remove?"
										                  confirmation-title="Confirmation"
										                  item="adiviceData.adviceID">
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