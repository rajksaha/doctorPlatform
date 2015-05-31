<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<span class="headerText">Appointment</span>
	</div>
</div>
	<div class="panel  col-md-12 filter-panel">

		<div class="col-md-12 panel panel-primary mainPanelColor panel1">
			<div class="col-md-3 appointmentPhoto">
				<span style="padding-left:20px"><a href="javascript:" data-ng-click="addNewAppointment()"><img class="" src="images/forms.png"><span><br>New Appointment</span></a></span>
			</div>
		
			<div class="col-md-3 appointmentPhoto">
				<span style="padding-left:25px"><a href="javascript:" data-ng-click="followUpSearch = !followUpSearch"><img class=""  src="images/forms_11.png"><span ><br>F.U Appointment</span></a></span>
			</div>
		
			<div class="col-md-3 appointmentPhoto">
				<span style="padding-left:25px"><a href="#/research"><img class="" src="images/sym.png"><span ><br>Research</span></a></span>
			</div>
		
			<div class="col-md-3 appointmentPhoto">
				<span style="padding-left:25px"><a href="#/settings" ><img class=""  src="images/settings.png"><span><br>Setting</span></a></span>
			</div>
		</div>
	</div>
			<section  data-ng-show="followUpSearch" class=" contacts row" id="generalInfoContact">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		             <div class="panel panel-default">	                 
		                 <div class="panel-body">
		                 
		                 	<div class="text-muted form-group">By ID</div>
		                    <div class="room-desc form-group m-b-0" id="the-basics" >	
		                         <label for="inputName">ID </label>
		                         <input type="text" data-ng-model="patientCode" typeahead="patients.patientCode for patients in getPatients($viewValue)"  class="form-control" placeholder="Search Patients" typeahead-on-select='onSelectIDPatient($item, $model, $label)'/>
		                         <button class="btn btn-primary" data-ng-click="addAppFollowUP()" data-ng-show="addByID"><span class='glyphicon glyphicon glyphicon-plus' aria-hidden='true'></span> Add</button>
		                    </div>
		                               
		                 </div>
		             </div>
		         </div>
		         
		         <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		             <div class="panel panel-default">	                 
		                 <div class="panel-body">
		                 	<div class="text-muted form-group">By Name</div>	                    
		                    <div class="room-desc">	
		                         <label for="inputName"> Name</label>
		                         <input type="text" data-ng-model="patientName" typeahead="patients.name for patients in getPatients($viewValue)"  class="form-control" placeholder="Search Patients" typeahead-on-select='onSelectNamePatient($item, $model, $label)'/>
		                    </div>
		                    <button class="btn btn-primary pull-right" data-ng-click="addAppFollowUP()" data-ng-show="addByName"><span class='glyphicon glyphicon glyphicon-plus' aria-hidden='true'></span> Add</button>
		                 </div>
		             </div>
		         </div>
		         
		         <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		             <div class="panel panel-default" >	                 
		                 <div class="panel-body">
		                 	<div class="text-muted form-group">By Phone</div> 
		                     <div class="room-desc">
		                     	<label for="inputName">Phone</label>
								<input type="text" data-ng-model="patientPhone" typeahead="patients.phone for patients in getPatients($viewValue)"  class="form-control" placeholder="Search Patients" typeahead-on-select='onSelectPhonePatient($item, $model, $label)'/>
								<button class="btn btn-primary" data-ng-click="addAppFollowUP()" data-ng-show="addByID"><span class='glyphicon glyphicon glyphicon-plus' aria-hidden='true'></span> Add</button>
		                     </div>
		                 </div>
		             </div>
		         </div>
			</section>
			
			
			<div class="panel col-md-12 appointment">
				<div class="col-md-12 panel panel-primary mainPanelColor panel1" style="padding-top: 12px;padding-bottom: 12px">
					<div  style="padding-top: 25px;">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
		             	<div class="panelHead">	                 
							<div class="panelHead-body">
								<span>Today's Appointment List</span>
								<span class="pull-right">Total:{{numberOfAppointment}}</span>
							</div>
						</div>
					</div>
					
				</div>
			
				<div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 400px; overflow-y: scroll;" >
		             	<div class="panelChild">	                 
							<div class="" data-ng-repeat="appointmentData in appointmentList" >
								<div class="panelChild-body form-group" data-ng-show="appointmentData.status == 0" data-ng-click="letsPrescribe(appointmentData)" >
									<span class="textSpc">{{$index + 1}} | {{appointmentData.name}} | {{appointmentData.age}} yrs  | {{appointmentData.address}}</span>
									<span class="textSpc pull-right">{{appointmentData.appointmentTypeName}}</span>
								</div>
								<div  class="panelVisited-body form-group " data-ng-show="appointmentData.status == 1"
		                                            ktr-confirmation="letsPrescribe(item)" 
		                                            confirmation-message="Want to re-visit this patient?"
		                                            confirmation-title="Confirmation"
		                                            item="appointmentData">
									<span class="textSpc">{{$index + 1}} | {{appointmentData.name}} | {{appointmentData.age}} yrs  | {{appointmentData.address}}</span>
									<span class="textSpc pull-right">{{appointmentData.appointmentTypeName}}</span>
								</div>
							</div>
						</div>
					</div>
						
				</div>
				</div>
				
			</div>
			