<?php 

//print_r($grade_list);
$skin = "skin-blue";
?>
<?php include 'header.php'; ?>

<style>

.forScroll {
	height: 250px;
	overflow-y: auto;
}

.padding-5{
	padding: 4px 6px;
}

.no-border{
	border: 0;
}

.ul-padding-left{
	list-style-type: none;
	padding-left: 20px;	
}

.m-b{
	margin-bottom: 15px;
}


</style>


<div class = 'row'>
    <div class='col-xs-12'>
        <div class="btn-toolbar pull-right">
            <div class="btn-group">
                  <a href="<?php echo base_url()?>leave/show/<?php echo $emp_id?>" class="btn ">Leave</a>
                  <a href="<?php echo base_url()?>attendance/request" class="btn ">Late/Early Request</a>
            <?php if($emp_id == $myInfo->userId || $isAdmin || $isManagement || $isManager) { ?>
                  <a href="<?php echo base_url()?>evaluation/details/<?php echo $emp_id?>" class="btn"> Evaluation</a>
            <?php } ?>
            </div>
            <?php if($emp_id == $myInfo->userId) { ?>
            <div class="btn-group">
                <a href="<?php echo base_url()?>settings/password" class="btn "> Change Password</a>
                
            </div>
            <?php } ?>
        </div>  
        
    </div>
</div>
 

	
<?php if($ArchiveV == "Y") { ?>
<table class="table table-condensed">
    <tr style="color: red;">
		<td><b>Resignation Date</b></td>
		<td><b>:</b></td>
		<td><b><?php echo date('Y-m-d', strtotime($resign_date)); ?></b></td>
	</tr>
</table>	
<?php } ?>

<div class="box box-success">

	<div class="box-header with-border">
	    <h3 class="box-title"><?php echo $name; ?></h3>
	</div>
	
    <div class="box-body">	
        
        <div class='row'>
        
            <div class="col-md-3 col-lg-3 ">
                    
                <div class='row'>
                    <div class='col-sm-6 col-md-12' align="left">
                        <img class="img-rounded img-responsive" src="<?php echo $image_path."?v=".date("d"); ?>" alt="Employee Profile Picture" style="max-width: 150; max-height: 180px">
                    </div>
                    <div class='col-sm-6 col-md-12'>

                        <table class='table no-border'>
                    		<tr>
                    			<td style="padding-left: 0;"><span class="glyphicon glyphicon-alert" style="color: DarkRed;"></span>
                    				<a href="<?php echo base_url()?>remark/notice">Unread Notice(<?php echo $unread_notice; ?>)</a></td>
                    		</tr>
                    
                    		<tr>
                    			<td style="padding-left: 0;"><span class="glyphicon glyphicon-paperclip"></span> <a href="<?php echo base_url()?>remark/attachment">Unread Attachments(<?php echo $unread_attach; ?>)</a>
                    			</td>
                    		</tr>
                    
                    		<tr>
                    			<td style="padding-left: 0;"><span class="glyphicon glyphicon-tree-conifer"
                    				style="color: green;"></span>
                    					Genuity Life (<?php echo $gslLife; ?>)</td>
                    		</tr>
                    		<tr>
                    			<td style="padding-left: 0;">
                    				<ul class="list-unstyled">
                    					<li><span class="glyphicon glyphicon-log-in"
                    						style="color: darkblue;"></span> Last Login -
                    						<ul class='ul-padding-left'>
                    							<li><span class="glyphicon glyphicon-triangle-right"></span><a
                    								href="<?php echo base_url()?>"> <?php echo $loginDay; ?></a></li>
                    							<li><span class="glyphicon glyphicon-triangle-right"></span><a
                    								href="<?php echo base_url()?>"> <?php echo $loginTime; ?></a></li>
                    						</ul></li>
                    				</ul>
                    			</td>
                    		</tr>
                    
                    		<tr>
                    			<td style="padding-left: 0;">
                    			<?php if($online == "Y"){
                    			    echo "<span class='glyphicon glyphicon-user' style='color:DarkGreen;'></span><span> Online</span>";
                    			    
                    			}else if($online == "N"){
                    			    echo "<span class='glyphicon glyphicon-user' style='color:DarkRed;'></span></span><span> Offline</span>";
                    			}?> 
                    			</td>
                    		</tr>
                    		<tr>
                        		<td style="padding-left: 0;">
                        			<?php if ($activeLock == 'U'){
                        			    echo "<span class='glyphicon glyphicon-lock' style='color: DarkGreen;'></span><span> Unlock</span>";
                        			} else if($activeLock == 'L') {
                        			    echo "<span class='glyphicon glyphicon-lock' style='color: DarkRed;'></span><span> Lock</span>";
                        			} ?>	
                        			
                        		</td>
                    		</tr>
                    
                    	</table>
                    	
                    </div>
                </div>
                    
                    
                	
            
            </div>
            <!-- end of col-3 -->
            
            <div class="col-md-9 col-lg-9 ">

            	<!-- Table -->
            	<table class="table table-condensed no-border">
            	    <tr>
            	       <td class="info" colspan="2"><span class='glyphicon glyphicon-user'></span> Employee Information</td>
            	    </tr>
            		<tr>
            			<td width="180">Employee ID</td>
            			<td><?php echo $emp_id; ?></td>
            		</tr>
            		<tr>
            			<td>Grade</td>
            			<td ><span id='gradeTdId'><?php echo $grade; ?></span> <a id='gradeShow' class='btn btn-xs'><?php if($uType == 'A') echo 'Change'; else echo 'See all';?></a></td>
            		</tr>
            		<tr>
            			<td>Operational Designation</td>
            			<td><?php echo $desig; ?></td>
            		</tr>
            		<tr>
            			<td>Department</td>
            			<td><?php echo $dept ?></td>
            		</tr>
            		<tr>
            			<td>Joining Date</td>
            			<td><?php echo !empty($jdate) && $jdate != '0000-00-00' ? $jdate : ""; ?></td>
            		</tr>
            		<tr>
            			<td>Current Status</td>
            			<td><?php 
                			    if(!empty($status))
                			        echo $status_array[$status];    			    
                			    
                			    if(!empty($status_history[$status])){
                			        $currentStatus = $status_history[$status];
                			        echo " (on ".$currentStatus->date.")";
                			    }
                			        
            			    ?>
            			    <a	id='oldStatus'>Old Status</a></td>
            		</tr>
            		<tr>
            	       <td class="info" colspan="2"><span class='glyphicon glyphicon-phone-alt'></span> Contact Information</td>
            	    </tr>
            		<tr>
            			<td>Mobile</td>
            			<td><?php echo $mobile; ?></td>
            		</tr>
            		<tr>
            			<td>Home Phone</td>
            			<td><?php echo $phone; ?></td>
            		</tr>
            		<tr>
            			<td>Email</td>
            			<td><?php echo $email; ?></td>
            		</tr>
            		<tr>
            			<td>Present Address</td>
            			<td><?php echo $pre_address; ?></td>
            		</tr>
            		<tr>
            			<td>Permanent Address</td>
            			<td><?php echo $per_address; ?></td>
            		</tr>
            		<tr>
            	       <td class="info" colspan="2"><span class='glyphicon glyphicon-education'></span> Educational Information</td>
            	    </tr>
            		<tr>
            			<td>Last Achievement</td>
            			<td><?php echo $last_edu_achieve; ?></td>
            		</tr>
            		<tr>
            	       <td class="info" colspan="2"><span class='glyphicon glyphicon-info-sign'></span> Personal Information</td>
            	    </tr>
            		<tr>
            			<td>Date of Birth</td>
            			<td><?php echo !empty($dob) && $dob != "0000-00-00" ? date('d F, Y', strtotime($dob)) : ""; ?></td>
            		</tr>
            		<tr>
            			<td>Blood Group</td>
            			<td><?php echo $blood_group; ?></td>
            		</tr>
            		<tr>
            			<td>Gender</td>
            			<td><?php if(!empty($gen)) echo $gender_array[$gen]; ?></td>
            		</tr>
            	</table>
            </div>
            <!-- end of detail col-8  -->
        
        </div>
	       <!-- end of row  -->
    </div>
    <!-- end of box-body  -->    
    

	<div class="box-footer">
	
	    <?php if($controller->myEmpId == $emp_id) {?>
	    <div class="row m-b">
	        <div class="col-xs-12">
	            <?php if(empty($gmail)){ ?>
	            <label>Integrate Gmail</label>
	            <div class="g-signin2" data-onsuccess="onSignIn" data-onfailure="onSignInFailure" data-theme="dark"></div>
	            <?php } else{ ?>	            
	            <button id="removeGmailId" class="btn btn-danger">Remove Integrated Gmail A/c</button>	            
	            <?php } ?>
	        </div>
	    </div>
	    <?php }?>
	    
	    <?php if($uType=="A") { ?>
	    
            <?php if($ArchiveV == 'N') { ?>
            
            <button class="btn btn-danger btn-md" type="button" id="btn_delete_Emp"
    			data-toggle="modal" data-target="#confirmDeleteEmp"
    			title="Delete this employee">
    			<span class="glyphicon glyphicon-trash"></span> Delete
    		</button>
    
    		<button class="btn btn-default btn-md" type="button" id="btn_toggle_lock"
    			data-toggle="modal" data-target="#toggleLock"
    			title="Lock/Unlock this employee">
        		<?php if ($activeLock == 'U') {
        		   echo '<span class="glyphicon glyphicon-lock"></span> LOCK';
        		}else {
        		    echo '<span class="glyphicon glyphicon-lock"></span> UNLOCK';
        		}
        		?>
        	</button>
    
    		<button class="btn btn-danger btn-md" type="button" id="btn_archive_modal"
    			data-toggle="modal" data-target="#archiveModal"
    			title="Archive this employee">
    			<span class="glyphicon glyphicon-hdd"></span> Archive
    		</button>

            <span class='pull-right'>        	       
            	<a class='btn btn-warning '
        			href="<?php echo base_url()?>user/edit/<?php echo $emp_id; ?>"
        			title="Edit this employee's info"> <span
        			class="glyphicon glyphicon-edit"></span> Edit
        		</a>
        		
        		<a data-toggle="modal" class="btn btn-success btn-md"
        			data-target="#facilityEmpModal"
        			url="<?php echo base_url()?>user/facilities/<?php echo $emp_id;?>"><span
        			class="glyphicon glyphicon-paperclip"></span> Facilities</a>        
                <?php } ?>
            </span>    
    
            <?php if($ArchiveV == 'Y') { ?>
            <span class='pull-right'>       
                <button class="btn btn-danger btn-md" type="button" id="btn_delete_Emp"
        			data-toggle="modal" data-target="#confirmDeleteEmp"
        			title="Delete this employee">
        			<span class="glyphicon glyphicon-trash"></span> Delete
        		</button>
        	</span>	
            <?php } ?>
        <?php } ?>    
	</div>
	
    <!-- end of box-footer  --> 
    
</div>    



<?php include 'footer.php'; ?>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="confirmDeleteEmp" role="dialog"
	aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this employee ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<a
					href="<?php echo base_url()?>user/deleteEmp/<?php echo $id."/".$dept_code; ?>"
					type="button" class="btn btn-danger" id="btn_modal_del_emp">Delete</a>
			</div>
		</div>
	</div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteGmailModal" role="dialog"
	aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to remove integrated gmail Account?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<a
					href="<?php echo base_url()."user/delete_gmail/".$controller->myEmpId; ?>"
					type="button" class="btn btn-danger" >Delete</a>
			</div>
		</div>
	</div>
</div>

<!-- Toggle Lock Modal -->
<div class="modal fade" id="toggleLock" tabindex="-1" role="dialog"
	aria-labelledby="toggleLockLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
            	<?php
                if ($activeLock == 'U') {
                    echo '<p>Are you sure you want to <mark>LOCK</mark> this employee ?</p>';
                } else {
                    echo '<p>Are you sure you want to <mark>UNLOCK</mark> this employee ?</p>';
                }
                ?>
			</div>
			<div class="modal-footer">
			    
			    <?php $avtive = ($activeLock == 'U') ? "L" : "U"; ?>
			    
			    <a id="btn_lock" type="button" class="btn btn-danger"
					active="<?php echo $avtive; ?>">Yes</a>
				<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>

			</div>
		</div>
	</div>
</div>

<!-- Archive Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog"
	aria-labelledby="archiveModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">

				<p>
					Are you sure you want to
					<mark>Archive</mark>
					this employee ?
				</p>
				<form id="archiveForm" class="form-horizontal" role="form"
					method="post"
					action="<?php echo base_url();?>user/changeArchive/<?php echo $emp_id; ?>">

					<label for="jdate" class="control-label">Resignation Date:</label>
					<input type="text" class="form-control " id="resDate"
						name="resDate" placeholder="YYYY-MM-DD"
						value="<?php echo date("Y-m-d"); ?>" required
						style="width: 100px; display: inline-block;">
				</form>

			</div>
			<div class="modal-footer">
				<a id="btn_archive" type="button" class="btn btn-danger">Yes</a>
				<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
			</div>
		</div>
	</div>
</div>

<!-- Facility Modal -->
<div class="modal fade" id="facilityEmpModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Facilities occupied</h4>
			</div>
			<div class="form-group" style="margin: 5px 15px 0px 0px;">
				<button class="btn btn-warning btn-xs pull-right"
					id="btn_add_facility" data-toggle="modal"
					data-target="#addFacilityModal" type="button">Add Facility</button>
				<div class="clearfix"></div>
			</div>
			<div class="modal-body forScroll"></div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary " data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Add Facility Modal -->
<div class="modal fade" id="addFacilityModal" tabindex="-1"
	role="dialog" aria-labelledby="addFacilityModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="facilityForm" class="form-horizontal" role="form"
				method="post" action="#">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title">Update Facility</h4>
				</div>
				<div class="modal-body">

					<div class="form-group">
						<label for="lastname" class="col-sm-2 control-label">Facility</label>
						<div class="col-sm-10">
							<select name="facilitySelect" class="selectpicker "
								id='facilitySelect' data-live-search="false"
								style='width: 200px;' required>
                    	<?php echo "<option value=''>---Select---</option>"; ?>
                    	<?php foreach ($facility_array as $obj) {
                    		echo "<option value='".$obj->facility_id."'>".$obj->facility."</option>";
                    	} ?>
                    	</select>
						</div>
					</div>
					<div class="form-group">
						<label for="fromDate" class="control-label col-sm-2">From</label>
						<div class="col-sm-3">
							<input type="text" class="form-control " id="fromDate"
								name="fromDate" placeholder="YYYY-MM-DD"
								value="<?php echo date("Y-m-d"); ?>" required
								style="width: 100px;">
						</div>
					</div>
					<div class="form-group">
						<label for="toDate" class="control-label col-sm-2">To</label>
						<div class="col-sm-3">
							<input type="text" class="form-control " id="toDate"
								name="toDate" placeholder="YYYY-MM-DD"
								value="<?php //echo date("Y-m-d"); ?>" style="width: 100px;"
								disabled>
						</div>
						<div class="col-sm-3">
							<label> <input type="checkbox" id="continue_id" name="continue_id"
								checked> Continuing </label>
						</div>
					</div>
					<div class="form-group">
						<label for="txtRemark" class="control-label col-sm-2">Remark</label>
						<div class="col-sm-10">
							<textarea class="form-control " rows="3" id="txtRemark"
								name="txtRemark" placeholder="write down some note"
								style="max-width: 70%;"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a id="btn_save_facility" type="submit" class="btn btn-primary">Save</a>
					<button id="btn_add_close" type="button" class="btn btn-default"
						data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Delete Facility Modal Dialog -->
<div class="modal fade" id="deleteFacilityModal" role="dialog"
	aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this facility ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger"
					id="delete_confirm_facility">Delete</button>
			</div>
		</div>
	</div>
</div>

<!--Status Dialog Modal -->
<div id='dialogModal' class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header custom-modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;&nbsp;</span>
				</button>
				<h4 class="modal-title">Status Information</h4>
			</div>
			<div class="modal-body">
				<table id = 'statusTable' class="table table-bordered table-condensed ">

					<thead>
						<tr>
							<th>Status</th>
							<th>Date</th>
							<?php if($uType=="A") echo "<th>Action</th>"; ?>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($status_history as $key=>$obj){
					    echo "<tr><td>";
					    echo $status_array[$key];
					    echo "</td><td>";
					    echo $obj->date;
					    echo "</td>";
					    if($uType=="A") echo "<td><a class='statusDelete btn btn-danger btn-xs' data-id='".$obj->id."' >Delete</a></td>";
                        echo "</tr>";
					}?>
				    </tbody>
				</table>
			</div>
			<?php if( $uType=="A"){?>
			<div class='modal-footer'>
				<div class="row">
					<div class="col-xs-5">
						<select class="selectpicker form-control btn-sm" name="statusType" id='statusType'
							data-live-search="false" data-height='80' data-width="100%">
                			<?php foreach ($status_array as $key=>$val) {                			    
                			    echo "<option value='".$key."' >".$val."</option>";
                			} ?>
        	            </select>
					</div>
					<div class="row col-xs-5">
						<input type="text" id="statusDate" class="form-control input-sm" name="statusDate" value="<?php echo date("Y-m-d");?>" placeholder="yy-mm-dd" required>
					</div>
					<div class="col-xs-2">
						<a id='statusAdd' class='btn btn-primary btn-sm' >Add</a>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--Grade Dialog Modal -->
<div id='gradeDialogModal' class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header custom-modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;&nbsp;</span>
				</button>
				<h4 class="modal-title">Grade Information</h4>
			</div>
			<div class="modal-body">
				<table id = 'gradeTable' class="table table-bordered table-condensed text-center">

					<thead>
						<tr>
							<th>Grade</th>
							<th>Person</th>
							<?php if($uType=="A") echo "<th>Action</th>"; ?>
						</tr>
					</thead>
					<tbody id='gradeTbody'>
					<?php foreach ($grade_list as $key=>$aryOfObj){
					    echo "<tr><td>";
					    echo $key;
					    echo "</td><td class='personTd'>";
					    echo "<a href='javascript:;' class='gradePerson' data-grade='".$key."'>".(count($aryOfObj) -1)."</a>";
					    echo "</td>";
					    if($uType=="A") {
					        if($key == $grade)
					            echo "<td class='gradeOk'><span class=' glyphicon glyphicon-ok' data-id='".$aryOfObj['grade_id']."' ></span></td>";
					        else
    					        echo "<td><a class='gradeSet btn btn-warning btn-xs' data-id='".$aryOfObj['grade_id']."' >Set</a></td>";
					    }
                        echo "</tr>";
					}?>
				    </tbody>
				</table>
			</div>
			<?php if( $uType=="A"){?>
			<div class='modal-footer'>
				<div class="row">
					<div class="col-xs-8">
						<select class="selectpicker form-control btn-sm" name="newGradePoint" id="newGradePoint"
							data-live-search="false" data-height='80' data-width="100%">
                			<?php foreach ($grades as $key=>$val) {                			    
                			    echo "<option value='".$key."' >".$val."</option>";
                			} ?>
        	            </select>
					</div>
					<div class="col-xs-4">
						<a id='addGradeBtn' class='btn btn-primary btn-sm' >Add</a>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Grade Person Info Modal Dialog -->
<div id='GradePersonModal' class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<link href="<?php echo base_url();?>assets/css/user.css"
		type="text/css" rel="stylesheet" />
<script
	src="<?php echo base_url();?>assets/lib/bootstrap/js/bootstrap-select.js"
	type="text/javascript"></script>
<link
	href="<?php echo base_url();?>assets/lib/bootstrap/css/bootstrap-select.css"
	type="text/css" rel="stylesheet" />
<script
	src="<?php echo base_url();?>assets/lib/bootstrap/js/bootstrap-datepicker.js"
	type="text/javascript"></script>
<link
	href="<?php echo base_url();?>assets/lib/bootstrap/css/datepicker3.css"
	type="text/css" rel="stylesheet" />

<script src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script>
<script
	src="<?php echo base_url();?>assets/js/additional-methods.min.js"></script>

<script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>	
	

<script type="text/javascript">

var gradeList = <?php echo json_encode($grade_list)?>;
var staffId = "<?php echo $emp_id?>";
var grades =  <?php echo json_encode($grades)?>;

$.signIn = false;

console.log($.signIn );
                
$(document).ready(function() {

   	/* gmail login  */
	$(".g-signin2").click(function(){
		
		$.signIn = true;	
	});
	
   	$("#removeGmailId").click(function(){

   	   	$("#deleteGmailModal").modal('show');   	   	
   	});
   	//end

	
    $("#btn_lock").on("click",function(){  
    	var val = "<?php echo $activeLock == 'U' ? "L" : "U"; ?>";
        $.ajax({
      	    type:"POST",
      	    url:"<?php echo base_url()?>user/lock/<?php echo $emp_id; ?>",
      	    data:{active:val},
      	    dataType:"json",
      	    success:function(response) {
      	    	//$('#toggleLock').modal('hide');
        	    // .modal({ show: true });
        	    if(response.status) {
        	    	location.reload(true);
        	    } else {
        	        alert(response.message);
        	    }
      	    }
        });
    }); 

	$("a[data-target=#facilityEmpModal]").unbind("click").bind("click",function(ev) {
	    ev.preventDefault();
	    var target = $(this).attr("url");

	    // load the url and show modal on success
	    $("#facilityEmpModal .modal-body").load(target, function() { 
	         $("#facilityEmpModal").modal("show"); 
	         bindMethod();
	    });
	});
    //local variable
	var f_id ="";
	
	$('#btn_add_facility').unbind("click").bind("click",function(){
		$('#facilityEmpModal').modal('toggle');

		$('#btn_add_close').unbind("click").bind("click",function(){
			$("a[data-target=#facilityEmpModal]").click();
		});		
	});
	
	$('#addFacilityModal').on('hidden.bs.modal', function () {
		$("#facilitySelect").val('');
		$("#facilitySelect").selectpicker('refresh');
		$('#fromDate').val(new Date().toJSON().slice(0,10));
		$('#toDate').val('');    			
		$('#txtRemark').val('');
		$("#toDate").prop("disabled", true);
		$('#continue_id').prop('checked', treu);
		
	});	
	
	function bindMethod() {
		
    	$('.btn_edit_facility').unbind("click").bind("click",function(){
        	
    		$('#addFacilityModal').on('shown.bs.modal', function (e) {
    			var btn_edit = $(e.relatedTarget);
    			f_id = btn_edit.attr('data-id');
    			var facility = btn_edit.attr('data-facility');
    			var fdate = btn_edit.attr('data-fdate');
    			var tdate = btn_edit.attr('data-tdate');
    			var remark = btn_edit.attr('data-remark');

    			if(tdate != null){
    				$("#toDate").prop("disabled", false);
    				$('#continue_id').prop('checked', false);
            	}
    			
    			$('#facilitySelect').val(facility);
    			$("#facilitySelect").selectpicker('refresh');
    			$('#fromDate').val(fdate);
    			$('#toDate').val(tdate);    			
    			$('#txtRemark').val(remark);
    			
    		});
    	});
 
		$('#deleteFacilityModal').off('shown.bs.modal').on('shown.bs.modal', function (e) {
    		var btn_edit = $(e.relatedTarget);
			var fac_id = btn_edit.attr('data-id');
			var emp_id = btn_edit.attr('data-empid');			
			$('#delete_confirm_facility').unbind("click").bind("click",function(){
    			$.ajax({
    	      	    type:"POST",
    	      	    url:"<?php echo base_url()?>user/delete_facility",
    	      	    data:{fac_id:fac_id, emp_id:emp_id},
    	      	    dataType:"json",
    	      	    success:function(response) {
       	        	    if(response.status) {
    		      	    	$('#deleteFacilityModal').modal('hide');
    		      	    	$("a[data-target=#facilityEmpModal]").click();
    	        	    } else {
    	        	        alert(response.message);
    	        	    }
    	      	    }
    	        });
			}); 
		});  	
	}

	$("#resDate, #fromDate, #toDate, #statusDate").datepicker({
	    format: 'yyyy-mm-dd'
	});
	$('#resDate, #fromDate, #toDate, #statusDate').on('changeDate', function(ev){
	    $(this).datepicker('hide');
	});	
	
	$("#btn_archive").on("click",function(){
		$('#archiveForm').submit();
	});

    
	
	$("#archiveForm").validate({
		rules: {
			resDate: {
			    required: true,
			    date: true
			},
		}		
	});
	

	$("#continue_id").change(function() {
		
	    if(this.checked) {
	    	$("#toDate").prop("disabled", true);
	    }else{
	    	$("#toDate").prop("disabled", false);
	    }
	});
	
	$('#btn_save_facility').unbind("click").bind("click",function(){
		var toDate;
		var facility = $('#facilitySelect').val();
		var fromDate = $('#fromDate').val();		
		var remark = $('#txtRemark').val();
		//alert("test");
		var empID = "<?php echo $emp_id; ?>";

		if($('#continue_id').is(':checked')){
			toDate = "0000-00-00 00:00:00";
		}else{
			toDate = $('#toDate').val()+" 00:00:00";
	    }
	    
	    
		if(facility.length==0) {
			alert("Please select Item.");
		    return;
		}

		if(fromDate==0) {
			alert("Invalid Date.");
		    return;
		}
		
        $.ajax({
      	    type:"POST",
      	    url:"<?php echo base_url()?>user/update_facility",
      	    data:{facility:facility, fromDate:fromDate, toDate:toDate, remark:remark, empID:empID, f_id:f_id},
      	    dataType:"json",
      	    success:function(response) {
      	    
        	    if(response.status) {
            	    //alert("test");
	      	    	$('#facilitySelect').val("");
	      	    	$('#toDate').val("");
	      	    	$('#txtRemark').val("");
	      	    	
	      	    	$('#addFacilityModal').modal('hide');
	      	    	$("a[data-target=#facilityEmpModal]").click();
        	    } else {
        	        alert(response.msg);
    				return;
    			}
        	}      	    
        });
	});
	
	// Grade Modal
	$('#gradeShow').on('click', function(){
	    $('#gradeDialogModal').modal('show');
	});

	$(".gradePerson").on('click', function(){

		var grade_key = $(this).attr('data-grade');
		var aryObj = gradeList[grade_key];

		$('#GradePersonModal').find('.modal-title').text('Grade: '+grade_key);
		var text="";
		
		for(var key in aryObj){
			if(key == 'grade_id'){
				continue;
			}
			if (aryObj.hasOwnProperty(key)) {
				
				var obj = aryObj[key];
				text += "<div class='squareBox'><div>"+obj.emp_id+"</div>";
				text += "<div class='text-green text-bold'>"+obj.name+"</div>";
				text += "<div>"+obj.designation+",</div>";
				text += "<div>"+obj.dept_name+"</div></div>";
			}				
		}

	    
		$('#GradePersonModal').find('.modal-body').html(text);
		
		$('#gradeDialogModal').modal('hide');
		$('#GradePersonModal').modal('show');
	});
	$('#GradePersonModal').on('hidden.bs.modal', function(){
		$('#gradeDialogModal').modal('show');
	});
	

	//Status related
	var status_array = <?php echo json_encode($status_array); ?>;
	$('#oldStatus').on('click', function(){
	    $('#dialogModal').modal('show');
	});
	$('#statusAdd').unbind("click").bind("click",function(){
		var statusType = $('#statusType').val();
		var statusDate =  $('#statusDate').val();

		if(statusType == '' || statusDate == ''){
			alert("Status Type or Date Field can't be empty");
		    return;					
		}
        $.ajax({
      	    type:"POST",
      	    url:"<?php echo base_url()?>user/add_status_log/<?php echo $emp_id; ?>",
      	    data:{status:statusType, date:statusDate},
      	    dataType:"json",
      	    success:function(response) {
        	    if(response.status) {
        	       var myRow = "<tr><td>"+status_array[statusType]+"</td><td>"+statusDate+"</td><td><a data-id='"+response.insert_id+"' class='statusDelete btn btn-danger btn-xs'>Delete</a></td></tr>";
        	       //console.log(myRow);
        	       $("#statusTable tbody tr:last").after(myRow);
        	       $('.statusDelete').unbind("click").bind("click",function(){
               	   		bindDeleteEvent(this);
               	   	});
        	    } else {
        	        alert(response.msg);
    				return;
    			}
        	}      	    
        });
	});

	$('.statusDelete').unbind("click").bind("click",function(){
   		bindDeleteEvent(this);
   	});

   	$('tbody#gradeTbody').on('click', 'a.gradeSet', function(){

   	   	var grade_id = $(this).attr('data-id');
   	   	
      	var selectTd = $(this).parent();
      	var prvSelect = $(this).parents('#gradeTbody').find('.gradeOk');
      	var d_id = prvSelect.find('span').attr('data-id');
      	console.log($(this).parents('#gradeTbody'));
      	
    	$.ajax({
      	    type:"POST",
      	    url:"<?php echo base_url()?>user/change_grade/"+grade_id,
      	    data:{staffId:staffId},
      	    dataType:"json",
      	    success:function(response) {
        	    if(response.status) {

            	    
        	    	$('#gradeTdId').html(grades[grade_id]);
            	    selectTd.addClass('gradeOk');
            	    selectTd.html("<span class='glyphicon glyphicon-ok' data-id='"+grade_id+"'></span>");
        	    	
        	        prvSelect.removeClass('gradeOk');
        	        
        	        prvSelect.html("<a class='gradeSet btn btn-warning btn-xs' data-id='"+d_id+"' >Set</a>");
        	    	
        	    } else {
        	        alert(response.msg);
    				return;
    			}
        	}      	    
        });
      	
   	});

   	$('#addGradeBtn').unbind("click").bind("click",function(){
   	   	var grade_value = $('#newGradePoint').val();
      	
    	$.ajax({
      	    type:"POST",
      	    url:"<?php echo base_url()?>user/add_grade/"+grade_value,
      	    dataType:"json",
      	    success:function(response) {
        	    if(response.status) {
            	    alert(response.msg);
            	    location.reload();
        	    } else {
        	        alert(response.msg);
    				return;
    			}
        	}      	    
        });      	
   	});

});

function bindDeleteEvent(it){
	var statusId = $(it).attr('data-id');	
	
	$.ajax({
  	    type:"POST",
  	    url:"<?php echo base_url()?>user/del_status_log/"+statusId,
  	    data:{},
  	    dataType:"json",
  	    success:function(response) {
    	    if(response.status) {
    	    	$(it).parent().parent().remove();
    	    } else {
    	        alert(response.msg);
				return;
			}
    	}      	    
    });
}

function onSignIn(googleUser) {
	// Handle successful sign-in
    //alert ("success");
    var profile = googleUser.getBasicProfile();
	// The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;

    if($.signIn){

    	$.ajax({
      	    type:"POST",
      	    url:"<?php echo base_url()?>user/integrate_gmail/",
      	    data:{email:profile.getEmail()},
      	    dataType:"json",
      	    success:function(response) {
      	  	    
        	    if(response.status) {
            	    
        	    	alert(response.msg);
        	    	location.reload(true);
    				return;
        	    } else {
        	        alert(response.msg);
    				return;
    			}
        	}      	    
        });        
    }
}

function onSignInFailure() {
  // Handle sign-in errors
	console.log("failed"); 
}


</script>