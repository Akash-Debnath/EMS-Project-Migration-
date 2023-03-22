<link href="<?php echo base_url();?>assets/css/main.css"
		type="text/css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/css/leave.css"
		type="text/css" rel="stylesheet" />


<div class="col-sm-12 leaveHeader" >Approval Request: <?php echo count($approvalRequest); ?></div>

<div class ='row'>
   	<?php
    if(count($approvalRequest)==0) {
    	echo "<div class='noFound'>No approval request found.</div>";
    } else {
    	foreach ($approvalRequest as $ary) {
    	?>
    	<div class="col-xs-12 col-sm-4 col-md-3 leaveBox">
    	   <div class ='squareBox'>
        		<?php echo $ary["emp_id"];?><br>
        		<?php echo "<a href='".base_url()."user/detail/".$ary["emp_id"]."'>".$ary["name"]."</a>";?><br>
        		<?php echo $ary["designation"];?><br>
        		<?php echo $ary["dept_name"];?><br>
        		<div class='squareBoxHeader'>Leave Brief Info</div>
        		<div class='row'> 
            		<p><label class ='col-xs-5'>Leave Type:</label> <?php echo $leaves_array[$ary["leave_type"]];?></p>
            		<p><label class ='col-xs-5'>From: </label> <?php echo $ary["leave_start"];?></p>
            		<p><label class ='col-xs-5'>To: </label> <?php echo $ary["leave_end"];?></p>
            		<p><label class ='col-xs-5'>Day: </label> <?php echo $ary["period"];?></p>
        		</div>
        		<div align="center">
        		    <a href='<?php echo base_url()."leave/show/".$ary["emp_id"];?>' class='btn btn-default btn-xs'>Leave List</a> | 
        		    <a href='<?php echo base_url()."leave/request/$ary[rid]"; ?>' class='btn btn-primary btn-xs'>Request Detail</a>
        		</div>
        	</div>
    	</div>
    	<?php
    	}
    }
    ?>
</div>

<div class="col-sm-12 leaveHeader">Verification Request: <?php echo count($verificationRequest); ?></div>

<div class = 'row'>
   <?php
    if(count($verificationRequest)==0) {
        echo "<div class='noFound'>No verification request found.</div>";
    } else {
    	foreach ($verificationRequest as $ary) {
    	?>
    	<div class="col-xs-12 col-sm-4 col-md-3 leaveBox">
    	   <div class ='squareBox'>
        		<?php echo $ary["emp_id"];?><br>
        		<?php echo "<a href='".base_url()."user/detail/".$ary["emp_id"]."'>".$ary["name"]."</a>";?><br>
        		<?php echo $ary["designation"];?><br>
        		<?php echo $ary["dept_name"];?><br>
        		<div class='squareBoxHeader'>Leave Brief Info</div>
        		<div class='row'> 
        		<p><label class ='col-xs-5'>Leave Type:</label> <?php echo $leaves_array[$ary["leave_type"]];?></p>
        		<p><label class ='col-xs-5'>From: </label> <?php echo $ary["leave_start"];?></p>
        		<p><label class ='col-xs-5'>To: </label> <?php echo $ary["leave_end"];?></p>
        		<p><label class ='col-xs-5'>Day: </label> <?php echo $ary["period"];?></p>
        		</div>
        		<div align="center">
        		  <a class = 'btn btn-primary btn-xs' href='<?php echo base_url()."leave/show/".$ary["emp_id"];?>'>Leave List</a> | 
        		  <a class = 'btn btn-info btn-xs' href='<?php echo base_url()."leave/request/$ary[rid]"; ?>'>Request Detail</span></a>
        		</div>
        		
    		</div>
    	</div>
    	<?php
    	}
    }
    ?>
</div>


<div class="col-sm-12 leaveHeader" >Cancelation Request: <?php echo count($cancelationRequest)-1; ?></div>

<div class ='row'>
   	<?php
    if(count($cancelationRequest)==0) {
    	echo "<div class='noFound'>No cancelation request found.</div>";
    } else {
		
    	foreach ($cancelationRequest as $ary) {

			if(isset($ary["emp_id"])) {
	
    	?>
    	<div class="col-xs-12 col-sm-4 col-md-3 leaveBox">
    	   <div class ='squareBox'>
        		<?php echo $ary["emp_id"];?><br>
        		<?php echo "<a href='".base_url()."user/detail/".$ary["emp_id"]."'>".$ary["name"]."</a>";?><br>
        		<?php echo $ary["designation"];?><br>
        		<?php echo $ary["dept_name"];?><br>
        		<div class='squareBoxHeader'>Leave Brief Info</div>
        		<div class='row'> 
            		<p><label class ='col-xs-5'>Leave Type:</label> <?php echo $leaves_array[$ary["leave_type"]];?></p>
            		<p><label class ='col-xs-5'>From: </label> <?php echo $ary["leave_start"];?></p>
            		<p><label class ='col-xs-5'>To: </label> <?php echo $ary["leave_end"];?></p>
            		<p><label class ='col-xs-5'>Day: </label> <?php echo $ary["period"];?></p>
        		</div>
        		<div align="center">
        		    <a href='<?php echo base_url();?>leave/show' class='btn btn-default btn-xs'>Leave List</a> | 
        		    <a href='<?php echo base_url()."leave/request/$ary[rid]"; ?>' class='btn btn-primary btn-xs'>Request Detail</a>
        		</div>
        	</div>
    	</div>
    	<?php
			}
    	}
    }
    ?>
</div>

