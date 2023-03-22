<style>

	table.table {
		font-size: 12px;
	}
</style>

<?php if(count($rosterSlot)==0) { ?>
	No Slot Found.
	<button href="#rosterSlotModal" id="openBtn" data-toggle="modal"
			class="btn btn-primary pull-right">Add Roster Slot</button>
<?php } else { ?>
<div class="table-responsive" style="max-height:400px;margin-bottom: 15px;">
<table class='table table-striped table-bordered'>
	<tr>
		<?php

		foreach ($rosterSlot as $key=>$array) {
			$from = date("h:i a",strtotime($array["from"]));
			$to =  date("h:i a",strtotime($array["to"]));

			echo "<td>";
			echo "<b>".$from."</b> to <b>".$to."</b>";
			// echo "<b>".$from."</b> to <b>"; // Zia's code

			if(count($rosterSlot)==$key) echo "<button href='#rosterSlotModal' id='openBtn' data-toggle='modal' class='btn btn-primary pull-right'>Add Roster Slot</button>";
			echo "</td>";
		}
		?>
		<td><b>Holiday</b></td>
		<td><b>Weekend</b></td>
	</tr>

	<?php

	//echo $myInfo->userDeptCode."++++++++++++";
	for($idate=$sdate; $idate<=$edate; ) {

		$nexDate = date("Y-m-d", strtotime($idate." +1 day"));
		// echo "<tr><td colspan='".count($rosterSlot)."'><b>".$idate."</b>(<i>".date("l",strtotime($idate))."</i>) &nbsp;&nbsp;&nbsp;&nbsp; to &nbsp;&nbsp;&nbsp;&nbsp; <b>".$nexDate."</b>(<i>".date("l",strtotime($nexDate))."</i>)</td>";
		echo "<tr><td colspan='".count($rosterSlot)."'><b>".date('j M, Y', strtotime($idate))."</b> (<i>".date("l",strtotime($idate))."</i>)"; // Zia's code

		echo "<tr class='rowTr'>";



		foreach ($rosterSlot as $slotNo=>$array) {

			$from = date("h:i a",strtotime($array["from"]));
			$to =  date("h:i a",strtotime($array["to"]));
			$key = $from." ".$to;

			// echo "<td class='topTips' title='<b>$from</b> to <b>$to</b>'>"; //
			echo "<td class='topTips' title='<b>$from</b>'>"; // Zia's code


			if(isset($rosterData[$key][$idate]) && count($rosterData[$key][$idate])> 0){

				$staffs = $rosterData[$key][$idate];

				foreach ($staffs as $obj) {
					//print_r($obj);

					$chk = $obj->is_incharge == "Y" ? "checked" : "";


					echo "<div data-emp_id='".$obj->emp_id."' data-slot='$slotNo' data-date='$idate'>
					<input type='radio' class='radioButton' name='".$idate.":".$slotNo."' $chk '> 
					<label>".$obj->name."</label> &nbsp; 
					<button type='button' class='staffDel btn btn-default btn-xs' '>
			          	<span class='glyphicon glyphicon-minus'></span>
			        </button></div>";

				}
			}

			$dtfrom = $idate." ".$array["from"];
			$dtto = strtotime($idate." ".$array["from"]) > strtotime($idate." ".$array["to"]) ? $nexDate." ".$array["to"] : $idate." ".$array["to"];

			echo "<select class='staffPicker selectpicker' data-live-search='true' data-date='$idate' data-slot='$array[slot_no]' data-from='$dtfrom' data-to='$dtto'>";
			echo "<option value=''>---Select---</option>";
			foreach ($staffArray as $emp_id=>$name) {
				if(in_array($emp_id,$rosterStatus)){
					echo "<option value='$emp_id'>$name</option>";
				}
			}
			echo "</select>";

			echo "</td>";
		}

		echo "<td>";

		if(isset($holidayData[$idate]) && count($holidayData[$idate])> 0){

			$staffs = $holidayData[$idate];

			foreach ($staffs as $key => $obj) {

			echo "<div data-emp_id='".$key."' data-slot='holiday' data-date='$idate'>
			<label>".$obj."</label> &nbsp; 
			<button type='button' class='staffDel btn btn-default btn-xs' '>
				<span class='glyphicon glyphicon-minus'></span>
			</button></div>";

			}
		}
			echo "<select class='staffPicker selectpicker' data-live-search='true' data-date='$idate' data-slot='holiday' data-from='$idate' data-to='$idate'>";
			echo "<option value=''>---Select---</option>";
			foreach ($staffArray as $emp_id=>$name) {
				if(in_array($emp_id,$rosterStatus)){
					echo "<option value='$emp_id'>$name</option>";
				}
			}
			echo "</select>";

		echo "</td>";
		/* Zia's Cde */
		echo "<td class='tokens'>";
		if(isset($weekendData[$idate]) && count($weekendData[$idate]) >0){
			foreach ($weekendData[$idate] as $emp_name){
				echo "<div class='token'>$emp_name</div><br>";
			}
		}
		/* ************ */
		echo "</td>"; // Zia's Code
		echo "</td>";
		echo "</tr>";

		$idate = date("Y-m-d",strtotime($idate." +1 day"));
	}
	}
	?>

</table>
</div>
<button id='saveBtn' class='btn btn-info'>Set Roster Time</button>

<!-- Roster Slot Setter Modal -->
<div class="modal fade" id="rosterSlotModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">x</button>
				<h3 class="modal-title">Roster Slot List</h3>
			</div>
			<div class="modal-body">
				<h5 class="text-center">Department: <?php echo $departments[$selectedDeptCode]; ?></h5>
				<form id='rosterSlotForm' name='rosterSlotForm' class=''
					  method="post" action="">
					<table class="table table-bordered table-condensed"
						   id="rosterTable">

						<thead id="tblHead">
						<tr>
							<th>Slot No.</th>
							<th>From</th>
							<th>To</th>
							<th class="text-center">Action</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($rosterSlot as $ary){
							echo "<tr><td>";
							echo "<div class ='tableData'>$ary[slot_no]</div>";
							//hidden
							echo "<div class='tableEdit hidden'><select class='slotNo selectpicker form-control btn-sm' name='slotNo' data-width='100%'>";
							for($i=1; $i<=10; $i++) {
								if($i == $ary['slot_no']) echo "<option value='".$i."' selected >".$i."</option>";
								else echo "<option value='".$i."' >".$i."</option>";

							}
							echo "</select></div>";

							echo "</td><td>";
							echo "<div class='tableData'>$ary[from]</div>";
							echo "<div class='tableEdit hidden bootstrap-timepicker'>                   			     	
                                	<input type='text' class='rosterSlotFrom form-control' name='rosterSlotFrom' value='$ary[from]' placeholder='hh:mm:ss'>
                                </div>";

							echo "</td><td>";
							echo "<div class='tableData'>$ary[to]</div>";
							echo "<div class='tableEdit hidden bootstrap-timepicker'>                   			     	
                                	<input type='text' class='rosterSlotTo form-control' name='rosterSlotTo' value ='$ary[to]' placeholder='hh:mm:ss'>
                                </div>";

							echo "</td><td class='text-center'>";
							echo "<div class='tableData'><a class='rosterSlotEdit btn btn-warning btn-xs' data-id='".$ary['id']."' >Edit</a> | <a class='rosterSlotDelete btn btn-danger btn-xs' data-id='".$ary['id']."' >Delete</a></div>";
							echo "<div class='tableEdit hidden'><input class='updateRosterSlot btn btn-primary btn-xs' data-id='$ary[id]' value='Update'></div>";

							echo "</td></tr>";
						}?>

						<tr id='rosterRow'>

							<td><select class='selectpicker form-control btn-sm'
										name='slotNo' id='slotNo' data-width='100%'>
									<?php for($i=1; $i<=10; $i++) {
										echo "<option value='".$i."' >".$i."</option>";
									} ?>
								</select></td>
							<td><div class='bootstrap-timepicker'>
									<input type='text' class='form-control' id='rosterSlotFrom'
										   name='rosterSlotFrom' placeholder='hh:mm:ss'>
								</div></td>
							<td><div class='bootstrap-timepicker'>
									<input type='text' class='form-control' id='rosterSlotTo'
										   name='rosterSlotTo' placeholder='hh:mm:ss'>
								</div></td>
							<td class="text-center"><input id='addRosterSlot'
														   class='btn btn-primary btn-sm' value='Add' type='Submit'></td>
						</tr>
						</tbody>
					</table>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-primary">Cancel</button>
			</div>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Reaquest Modal Dialog -->
<div class="modal fade" id="confirmModal" role="dialog"
	 aria-labelledby="confirmModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
				<h4 class="modal-title">Send Request</h4>
			</div>
			<div class="modal-body">
				<p>To select weekends more than as usual in a week slot, a request will be sent
					to admin. Otherwise <mark><b>Cancel</b></mark> it.</p>
				<div class="form-group">
					<label>Reason</label>
					<textarea id ="confirmReason" placeholder="Enter valid reason for this weekend ..." rows="2" class="form-control"></textarea>
				</div>
				<div class='clearfix'></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="confirmCancel" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-warning" id="confirmOk">Ok</button>
			</div>
		</div>
	</div>
</div>

<script
	src="<?php echo base_url()?>assets/lib/bootstrap/js/bootstrap-select.js"
	type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/lib/tipsy/jquery.tipsy.js"
		type="text/javascript"></script>

<script
	src="<?php echo base_url();?>assets/lib/bootstrap/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script>
<script
	src="<?php echo base_url();?>assets/js/additional-methods.min.js"></script>

<?php

$ros = new stdClass();
$wek = new stdClass();

for($idate=$sdate; $idate<=$edate; ) {
	$ros->$idate = new stdClass();
	$wek->$idate = new stdClass();


	$idate = date("Y-m-d",strtotime($idate." +1 day"));
}
//print_r($ros);

foreach ($rosterData as $fromTo =>$ary){
	//$slotT;
	foreach ($ary as $date=>$AryOfObj){
		$data = new stdClass();
		foreach ($AryOfObj as $obj){
			$data->from = $obj->stime;
			$data->to = $obj->etime;
			$data->staff[]=  $obj->emp_id;
		}
		$slotTime = date("H:i:s", strtotime(substr($fromTo, 0,8)));
		$slotNo;
		foreach ($rosterSlot as $sn=>$ary){
			if($slotTime == $ary['from']){
				$slotNo = $sn;
				break;
			}
		}

		$ros->$date->$slotNo = $data;
		$ros->$date->weekend = isset($weekendData[$date])? $weekendData[$date] : array();
		if(isset($holidayData[$date])){
		$ros->$date->holiday = [
			'from' => $date,
			'to' => $date,
			'staff' => array_keys($holidayData[$date])
		];
	}
		//$ros[$date]['weekend'] = isset($weekendData[$date])? $weekendData[$date] : array();
	}
}

foreach ($weekendData as $date=>$ary){
	$data = new stdClass();
	foreach ($ary as $eid=>$name){
		$data->$eid = $name;
	}
	$wek->$date = $data;
}

//print_r($wek);
//echo "<pre>";
//print_r($ros);
//echo "</pre>";
?>

<script type="text/javascript">
	$(document).ready(function(e) {

		var start = "<?php echo $sdate?>";
		var end = "<?php echo $edate?>";
		var selDept = "<?php echo $selectedDeptCode ?>";
		var staffs = <?php echo json_encode($staffArray); ?>;
		var mLimit = <?php echo $max_weekend?>;
		var reason;

		var dataObj = <?php echo json_encode($ros)?>;
		var weekendObj = <?php echo json_encode($wek)?>;
		dataObj.toAdmin = false;

		console.log(dataObj);
		//alert(dataObj);

		//generate data object with date keys
		var setOfDate = new Array();
		var tmpAry = new Array();
		startDate = new Date(start);
		endDate = new Date(end);

		while(startDate <= endDate){

			var dayN = startDate.getDay();
			var dt = startDate.toISOString().slice(0,10);
			//dataObj[dt] = {};
			//weekendObj[dt] = {};

			tmpAry[tmpAry.length] = dt;

			if(dayN == 6 || (startDate.getTime() === endDate.getTime())){
				setOfDate[setOfDate.length] = tmpAry;
				tmpAry = new Array();
			}

			var newDate = startDate.setDate(startDate.getDate() + 1);
			startDate = new Date(newDate);
		}



		$('#leaveDate, #leaveStart, #leaveEnd').datepicker({
			format: 'yyyy-mm-dd'
		});
		$('#leaveDate').on('changeDate', function(ev){
			$(this).datepicker('hide');
		});


		//$(".tips").tipsy({html: true, gravity: 'e',delayOut:10,clsStyle: 'blue'});
		//$(".topTips").tipsy({html: true, gravity:'s', delayOut:10, clsStyle: 'blue'});
		//$(".leftTips").tipsy({html: true, gravity:'e', delayOut:10,clsStyle: 'blue',css: {"max-width": 300+"px"}});

		$(".staffPicker").change(function(){
			var emp_id = $(this).val();
			if(emp_id=="") return;

			var from = $(this).attr('data-from');
			var to = $(this).attr('data-to');
			var date = $(this).attr('data-date');
			var slot = $(this).attr('data-slot');

			if(dataObj[date] == undefined){
				var staff = [emp_id];
				var aObj = new Object();
				aObj.toAdmin = false;
				aObj[slot] = {'from':from, 'to':to, 'staff':staff};
				dataObj[date] = aObj;
			}else {

				var aObj =  dataObj[date];

				if(aObj[slot] == undefined){
					var staff = [emp_id];
					var slotObj = {'from':from, 'to':to, 'staff':staff};
					aObj[slot] = slotObj;

				}else{
					slotObj = aObj[slot];
					var staff =  slotObj.staff;

					var index = $.inArray(emp_id, staff);
					if(index == -1){
						staff.push(emp_id);
					}else{
						return;
					}
					slotObj.staff = staff;
				}
			}

			// remove from RmoveList
			slotObj = aObj[slot];


			if(slotObj.removeStaff !== undefined  && slotObj.removeStaff.length>0){
				var removeStaff = slotObj.removeStaff;

				var index = removeStaff.indexOf(emp_id);
				if (index >= 0) {
					removeStaff.splice( index, 1 );
				}
			}

			// Add weekend list
			var newObj = jQuery.extend(true, {}, staffs);
			//var aObj =  dataObj[date];

			for(slotKey in aObj){
				slotObj = aObj[slotKey];
				var staff =  slotObj.staff;

				for(key in staff){
					delete newObj[staff[key]];
				}
			}
			//dataObj
			weekendObj[date] = newObj;
			aObj.weekend = newObj;

			var parentTr = $(this).parents('.rowTr');
			appendWeekend(parentTr, date);
			
			if(slot === 'holiday') {

				var html = "<div data-date='"+date+"' data-slot='holiday' data-emp_id='"+emp_id+"'><label>"+staffs[emp_id]+"</label> &nbsp; \
		<button type='button' class='staffDel btn btn-default btn-xs' >\
      		<span class='glyphicon glyphicon-minus'></span>\
        </button></div>";

			} else {

				var html = "<div data-date='"+date+"' data-slot='"+slot+"' data-emp_id='"+emp_id+"'><input type='radio' class='radioButton' name='"+date+":"+slot+"'><label>"+staffs[emp_id]+"</label> &nbsp; \
		<button type='button' class='staffDel btn btn-default btn-xs' >\
      		<span class='glyphicon glyphicon-minus'></span>\
        </button></div>";

			}

			

			$(this).before(html);
			$(this).val("");
			console.log(dataObj);
		});

		$(document).on("click",".staffDel",function(){

			if(confirm("Do you want to delete?")) {

				var parentDiv = $(this).parent();
				var emp_id = parentDiv.attr("data-emp_id");
				var date = parentDiv.attr("data-date");
				var slot = parentDiv.attr("data-slot");

				// delete staff Id from object
				var aObj =  dataObj[date];
				var slotObj = aObj[slot];
				var staff =  slotObj.staff;
				var index = staff.indexOf(emp_id);
				if (index >= 0) {
					staff.splice( index, 1 );
				}


				//delete from database
				if(slotObj.removeStaff == undefined){
					slotObj.removeStaff = new Array();
				}
				var removeStaff = slotObj.removeStaff;
				var index = $.inArray(emp_id, removeStaff);
				if(index == -1){
					removeStaff.push(emp_id);
				}

				// Add weekend list
				var newObj = jQuery.extend(true, {}, staffs);

				for(slotKey in aObj){
					slotObj = aObj[slotKey];
					var staff =  slotObj.staff;

					for(key in staff){
						delete newObj[staff[key]];
					}
				}
				weekendObj[date] = newObj;
				aObj.weekend = newObj;

				var parentTr = $(this).parents('tr.rowTr');
				parentDiv.remove();
				appendWeekend(parentTr, date);

			}
		});

		$(document).on("click",".radioButton",function(){
			var parentDiv = $(this).parent();
			var emp_id = parentDiv.attr("data-emp_id");
			var date = parentDiv.attr("data-date");
			var slot = parentDiv.attr("data-slot");

			var aObj =  dataObj[date];
			var slotObj = aObj[slot];
			slotObj.inCharge = emp_id;

			//console.log(dataObj);

		});

		$('button#saveBtn').click(function(){

			/*var toAdmin = checkValidity();

			 if(toAdmin){
			 $('#confirmModal').modal('show');
			 }else{
			 sendData();
			 }*/

			sendData();

		});

		function checkValidity(){

			for(empId in staffs){

				for(k in setOfDate){
					var count = 0;
					var kx = parseInt(k);
					var innerSet = setOfDate[kx];
					var prvSet = setOfDate[kx-1];
					var nxtSet = setOfDate[kx+1];

					for(key in innerSet){

						var dateVal = innerSet[key];
						var weekend = weekendObj[dateVal];
						var isWeekend = (weekend[empId] != undefined);

						if(!$.isEmptyObject(weekend) && isWeekend){
							//weekend

							if(key==0 && prvSet != undefined){
								//var prvFlag = true;
								var countprv = 0;
								var countIn = 0;

								for(var t=0; t<mLimit; t++){
									//prev Set
									var x = (prvSet.length - (1+t));
									if(x < 0)
										break;

									var dateVal2 = prvSet[x];
									var weekend2 = weekendObj[dateVal2];

									var isPrvWeekend = (weekend2[empId] != undefined);

									if(isPrvWeekend){
										countprv++;
									}else {
										break;
									}
								}

								for(var i=0; i<mLimit; i++){
									//inner Set
									var dateVal3 = innerSet[i];
									if(dateVal3 == undefined){
										break;
									}

									var weekend3 = weekendObj[dateVal3];
									var isInnerWeekend = (weekend3[empId] != undefined);

									if(isInnerWeekend){
										countIn++;
									}else {
										break;
									}
								}

								var sum = countprv+countIn;
								if(sum <= mLimit){
									countprv=0;
								}

								count += countprv;

							} else if( key == (innerSet.length-1) && nxtSet != undefined){
								//next set
								var countnxt = 0;
								var countIn = 0;

								for(var t=0; t<mLimit; t++){
									//Next Set
									if(t >= nxtSet.length)
										break;
									var dateVal4 = nxtSet[t];
									var weekend4 = weekendObj[dateVal4];

									var isNxtWeekend = (weekend4[empId] != undefined);

									if(isNxtWeekend){
										countnxt++;
									}else {
										break;
									}
								}

								for(var i= (innerSet.length-1); i>(innerSet.length-1-mLimit); i--){
									//inner Set
									if(i<0)
										break;
									var dateVal5 = innerSet[i];
									var weekend5 = weekendObj[dateVal5];
									var isNxtWeekend = (weekend5[empId] != undefined);

									if(isNxtWeekend){
										countIn++;
									}else {
										break;
									}
								}

								var sum = countnxt+countIn;
								if(sum <= mLimit){
									countnxt=0;
								}
								count += countnxt;
							}

							count++;

							if(count > mLimit){
								dataObj.toAdmin = true;
								return true;
							}
						}

					}

				}//setOfDate
			}//empID

			return false;
		}

		$('button#confirmOk').on('click', function(){

			var txt = $(this).parents('.modal-content').find('#confirmReason').val();

			if(txt.length<10){
				alert("Please enter a valid reason(in atleast 10 words).");
				return;
			}else{
				$('#confirmModal').modal('hide');
				reason = txt;

				sendData();
			}
		});

		$('#confirmModal').on('hidden.bs.modal', function(){

			$(this).find('#confirmReason').val('');
		});

		//Roster Slot Related
		$('#rosterSlotFrom, #rosterSlotTo, .rosterSlotFrom, .rosterSlotTo').timepicker({
			defaultTime: false,
			showMeridian: false,
			minuteStep: 5,
			showSeconds: true,
			disableFocus: true,
			modalBackdrop: true,
			template: 'dropdown'
		});

		$.validator.addMethod("time", function(value, element) {
			return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);
		}, "Please enter a valid time.");

		$("#rosterSlotForm").validate({
			rules: {
				slotNo: {
					required: true,
				},
				rosterSlotFrom: "required time",
				rosterSlotTo: "required time",
			},
			submitHandler : function(event) {
				//var it = $(this);
				var rosterSlotModal = $('#rosterSlotModal');
				var slotNo = rosterSlotModal.find('#slotNo').val();
				var from = rosterSlotModal.find('#rosterSlotFrom').val();
				var to = rosterSlotModal.find('#rosterSlotTo').val();
				$.ajax({
					type:"POST",
					url:"<?php echo base_url()?>roster/add_roster_slot/<?php echo $selectedDeptCode; ?>",
					data: {slotNo:slotNo,rosterSlotFrom:from,rosterSlotTo:to},
					dataType:"json",
					success:function(response) {
						if(response.status) {

							var part1 = "<tr><td>\
                          		                    <div class ='tableData'>"+slotNo+"</div><div class='tableEdit hidden'>\
                          	                            <select class='slotNo selectpicker form-control btn-sm' name='slotNo' data-width='100%'>";

							var option ="";
							for(var i=1; i<=10; i++) {
								if(i == slotNo)	option += "<option value='"+i+"' selected >"+i+"</option>";
								else option += "<option value='"+i+"'>"+i+"</option>";
							}

							var part2 = "</select></div></td>\
                                    		<td>\
                      			                <div class='tableData'>"+from+"</div>\
                                    		    <div class='tableEdit hidden bootstrap-timepicker'>\
                                    		        <input type='text' class='rosterSlotFrom form-control' name='rosterSlotFrom' value='"+from+"' placeholder='hh:mm:ss'>\
                                    		    </div>\
                                    		</td>\
                                    		<td>\
                                        		<div class='tableData'>"+to+"</div>\
                                        		<div class='tableEdit hidden bootstrap-timepicker'>\
                                        			<input type='text' class='rosterSlotTo form-control' name='rosterSlotTo' value ='"+to+"' placeholder='hh:mm:ss'>\
                                        		</div>\
                                    		</td>\
                                    		<td class='text-center'>\
                                    		<div class='tableData'>\
                                        		<a class='rosterSlotEdit btn btn-warning btn-xs' data-id='"+response.insert_id+"' >Edit</a> | <a class='rosterSlotDelete btn btn-danger btn-xs' data-id='"+response.insert_id+"' >Delete</a></div>\
                                        		<div class='tableEdit hidden'><input type class='updateRosterSlot btn btn-primary btn-xs' data-id='"+response.insert_id+"' value='Update'></div>\
                                    		</td>\
                                    		</tr>";
							var myRow = part1 + option + part2;

							$("#rosterRow").before(myRow);
							$('.slotNo').selectpicker('refresh');
							rosterSlotModal.find('#rosterSlotFrom').val("");
							rosterSlotModal.find('#rosterSlotTo').val("");
							//rosterSlotModal.find('#SlotNo option:selected').prop("selected", false);


							$('.rosterSlotDelete').unbind("click").bind("click",function(){
								bindDeleteEvent(this);
							});

							$('.updateRosterSlot').unbind("click").bind("click",function(){
								bindUpdateEvent(this);
							});

							$('.rosterSlotFrom, .rosterSlotTo').timepicker({
								defaultTime: false,
								showMeridian: false,
								minuteStep: 5,
								showSeconds: true,
								disableFocus: true,
								modalBackdrop: true,
								template: 'dropdown'
							});

						} else {
							alert(response.msg);
							return;
						}
					}
				});
			}
		});

		$('.rosterSlotDelete').unbind("click").bind("click",function(){
			bindDeleteEvent(this);
		});

		var parentRow;
		$("#rosterSlotModal").on("click",'.rosterSlotEdit',function(){
			parentRow = $(this).parent().parent().parent();

			parentRow.find('.tableData').addClass('hidden');
			parentRow.find('.tableEdit').removeClass('hidden');

			return;
		});

		$('#rosterSlotModal').on('hidden.bs.modal', function () {
			$(this).find('.tableData').removeClass('hidden');
			$(this).find('.tableEdit').addClass('hidden');

			$(this).find('#rosterSlotFrom').val("");
			$(this).find('#rosterSlotTo').val("");
		});

		$('.updateRosterSlot').unbind("click").bind("click",function(){
			bindUpdateEvent(this);
		});

		function sendData(){
			//console.log(dataObj);
			$.ajax({
				type:"POST",
				url:"<?php echo base_url()?>roster/add_roster_all/",
				data:{"dataObj" : JSON.stringify(dataObj), 'weekendObj':JSON.stringify(weekendObj), 'selDept':selDept, 'reason':reason},
				dataType:"json",
				success:function(response) {
					if(response.status) {
						window.location.href = "";
					} else {
						alert(response.msg);
						return;
					}
				}
			});
		}

		function bindUpdateEvent(it){

			parentRow = $(it).parent().parent().parent();
			var rosterId = $(it).attr('data-id');
			var slotNo = parentRow.find('.slotNo').val();
			var from = parentRow.find('.rosterSlotFrom').val();
			var to = parentRow.find('.rosterSlotTo').val();

			$.ajax({
				type:"POST",
				url:"<?php echo base_url()?>roster/update_roster_slot/<?php echo $selectedDeptCode; ?>",
				data:{rosterId:rosterId,
					slotNo:slotNo,
					rosterSlotFrom:from,
					rosterSlotTo:to},
				dataType:"json",
				success:function(response) {
					if(response.status) {
						parentRow.find("td:nth-child(1) .tableData").text(slotNo);
						parentRow.find("td:nth-child(2) .tableData").text(from);
						parentRow.find("td:nth-child(3) .tableData").text(to);

						$('#rosterSlotModal').find('.tableData').removeClass('hidden');
						$('#rosterSlotModal').find('.tableEdit').addClass('hidden');
					} else {
						alert(response.msg);
						return;
					}
				}
			});
		}

		function bindDeleteEvent(it){
			var rosterId = $(it).attr('data-id');

			$.ajax({
				type:"POST",
				url:"<?php echo base_url()?>roster/del_roster_slot/"+rosterId,
				data:{},
				dataType:"json",
				success:function(response) {
					if(response.status) {
						$(it).parents('tr').remove();
					} else {
						alert(response.msg);
						return;
					}
				}
			});
		}
		function appendWeekend(parentTr, date) {
			//console.log(parentTr);
			newObj = weekendObj[date];
			var str = "";
			for(key in newObj){
				str += "<div class='token'>"+newObj[key]+"</div><br>";
			}
			parentTr.find('.tokens').html(str);
		}

	});

</script>

