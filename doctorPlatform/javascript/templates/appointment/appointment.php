<div class="panel  col-md-12 filter-panel" style="padding-top: 20px">
	<span style="padding-left:20px"><a href="javascript:" data-ng-click="addNewAppointment()"><img class="photo-room" width="117" height="89" src="images/forms.png"></a><span style="padding-left:12px;">New Appointment</span></span>
	<span style="padding-left:25px"><a href="javascript:" data-ng-click="followUpSearch = !followUpSearch"><img class="photo-room" width="117" height="89" src="images/forms_11.png"></a><span style="padding-left:12px;">F.U Appointment</span></span>
	<span style="padding-left:25px"><a href="research/gen_pat.php"><img class="photo-room" width="117" height="89"src="images/sym.png"></a><span style="padding-left:12px;">Research</span></span>
	<span style="padding-left:25px"><a href="#/settings" ><img class="photo-room"width="117" height="89" src="images/settings.png"></a><span style="padding-left:12px;">Setting</span></span>
		
		
</div>

			<section  data-ng-show="followUpSearch" class=" contacts row" id="generalInfoContact">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		             <div class="panel">	                 
		                 <div class="panel-body">
		                 
		                 	<div class="text-muted form-group">By ID</div>
		                    <div class="room-desc form-group m-b-0" id="the-basics" >	
		                         <label for="inputName">ID </label>
		                         <input type="text" data-ng-model="patientCode" typeahead="patients.patientCode for patients in getPatients($viewValue)"  class="form-control" placeholder="Search Patients" typeahead-on-select='onSelectIDPatient($item, $model, $label)'/>
		                         <button data-ng-show="addByID" data-ng-click="addAppFollowUP()">Add</button>
		                    </div>
		                               
		                 </div>
		             </div>
		         </div>
		         
		         <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		             <div class="panel">	                 
		                 <div class="panel-body">
		                 	<div class="text-muted form-group">By Name</div>	                    
		                    <div class="room-desc">	
		                         <label for="inputName"> Name</label>
		                         <input type="text" data-ng-model="patientName" typeahead="patients.name for patients in getPatients($viewValue)"  class="form-control" placeholder="Search Patients" typeahead-on-select='onSelectNamePatient($item, $model, $label)'/>
		                         <button data-ng-show="addByName" data-ng-click="addAppFollowUP()">Add</button>
		                    </div>
		                 </div>
		             </div>
		         </div>
		         
		         <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		             <div class="panel" >	                 
		                 <div class="panel-body">
		                 	<div class="text-muted form-group">By Phone</div> 
		                     <div class="room-desc form-group">
		                     	<label for="inputName">Phone</label>
								<input type="text" data-ng-model="patientPhone" typeahead="patients.phone for patients in getPatients($viewValue)"  class="form-control" placeholder="Search Patients" typeahead-on-select='onSelectPhonePatient($item, $model, $label)'/>
								<button data-ng-show="addByPhone" data-ng-click="addAppFollowUP()">Add</button>
		                     </div>
		                 </div>
		             </div>
		         </div>
			</section>
			
			
			<div class="panel panel-primary col-md-12 appointment">
				<div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		             	<div class="panelHead">	                 
							<div class="panelHead-body">
								<span>Appointment</span>
							</div>
						</div>
					</div>
				</div>
			
				<div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		             	<div class="panelChild">	                 
							<div class="panelChild-body form-group" data-ng-repeat="appointmentData in appointmentList">
								<div class="" data-ng-click="letsPrescribe(appointmentData)" >
									<span class="textSpc">{{$index + 1}} | {{appointmentData.name}} | {{appointmentData.age}} yrs  | {{appointmentData.address}}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			