<?php
class Roster_model extends G_Model{
	public function __construct(){
	  $this->load->database(); 
	}
	public function getStaffArrayByIds($eid=array()) {

        $this->db->select('emp_id, name, dept_code');
        $this->db->from('employee');
        $this->db->where('archive', 'N');
        $this->db->where('roster', 'Y');
        if(count($eid)>0){
            $this->db->where_in('emp_id', $eid);
        }
        $this->db->order_by('emp_id', 'asc');
        $query = $this->db->get();

        $results = $query->result();

        $staff_array['all'] =  $results;

        return $staff_array;
    }
	public function isThisStaffRoster($emp_id){
	    $this->db->select('dept_code');
	    $this->db->from('employee');
	    $this->db->where('emp_id',$emp_id);
	    $this->db->where('roster','Y');
	    $query = $this->db->get();
	    
	    $result = $query->row_array();
	    
	    $dept_code = isset($result['dept_code']) ? $result['dept_code'] : '';
	    
	    return $dept_code;
	}
	
	public function getRosterStatusOfEmployee($dept_code,$rstatus){
		$this->db->select('emp_id');
		$this->db->from('employee');
		$this->db->where('archive','N');
		$this->db->where('dept_code',$dept_code);
		$this->db->where('roster',$rstatus);
		$this->db->order_by('emp_id','asc');

		$query = $this->db->get();
		return $query->result_array();
	}
	public function getRosterSlot($dept_code) 
	{
		$this->db->select('*');
		$this->db->from('roster_slot');
		$this->db->where('dept_code',$dept_code);
		$this->db->order_by('slot_no','asc');
		
		$query = $this->db->get();
		$res = $query->result_array();
		
		$return = array();
		foreach ($res as $ary){
		    $return[$ary['slot_no']] = $ary;
		    
		}
		return $return;
	}

	public function getRosterRecord($dept_code, $sdate)
	{
		$this->db->select('e.emp_id, e.name, r.id, r.stime, r.etime, r.is_incharge');
		$this->db->from('employee e, rostering r');
		$this->db->where('e.archive','N');
		$this->db->where('`e`.`emp_id`=`r`.`emp_id`');
		$this->db->where('`e`.`dept_code`',$dept_code);
		$this->db->where('LEFT(`r`.`stime`,10) >=',$sdate);
		
		$recordSet = $this->db->get();
		
		return $recordSet->result();
				
	}
		
	public function getRosterRegulerDataOfEmployee($emp_id, $sdate, $edate)
	{
	    $this->db->select('stime, etime, is_incharge');
	    $this->db->from('rostering');
	    $this->db->where('LEFT(`stime`,10) >=',$sdate);
	    $this->db->where('LEFT(`etime`,10) <=',$edate);
	    $this->db->where('emp_id', $emp_id);
	    $this->db->order_by('stime','asc');
	
	    $query = $this->db->get();
	    
	    $rosterData = $query->result_array();	    
	    $result = array();	    
	    foreach ($rosterData as $ary){
	        $date = date('Y-m-d', strtotime($ary['stime']));
	        $result[$date] = $ary;	        
	    }	
	    return $result;

	    
	    //return $rosterData;
	}
	
	public function getRosterRegulerWeekendOfEmployee($emp_id){
	    $this->db->select('date,');
	    $this->db->from('weekend');
	    $this->db->where('emp_id', $emp_id);
	    $this->db->order_by('date','asc');
	    
	    $query = $this->db->get();
	    
	    $data = $query->result_array();
	    
	    $result = array();
	    
	    foreach ($data as $array){
	        
	        $result[$array['date']] = true;
	    }
	    
	    return $result;
	    
	}
	
	public function getRosterSlotDataOfEmployee($dept_code, $sdate, $edate)
	{
	    $this->db->select('r.emp_id, e.name, r.id, r.stime, r.etime, r.is_incharge');
	    $this->db->from('employee e');
	    $this->db->where('e.archive','N');
	    $this->db->join('rostering r','r.emp_id = e.emp_id', 'inner');
	    $this->db->where('LEFT(r.stime,10) >=',$sdate);
	    $this->db->where('LEFT(r.etime,10) <=',$edate);
	    $this->db->where('e.dept_code', $dept_code);
	    $this->db->order_by('stime','asc');
	
		$query = $this->db->get();		
		$rosterRecord =  $query->result();
		
		//echo $this->db->last_query();
	    
	    $rosterData = array();
	    foreach($rosterRecord as $obj) {
	        $dt = date("Y-m-d",strtotime($obj->stime));
	        $stm = date("h:i a",strtotime($obj->stime));
	        $etm = date("h:i a",strtotime($obj->etime));
	        $key =  $stm." ".$etm;
	        $rosterData[$key][$dt][] = $obj;
	    }	
	     
        return $rosterData;
	}

	public function new_getRosterSlotDataOfEmployee($dept_code, $sdate, $edate,$staffIDs = array()) {
        $this->db->select('r.emp_id, e.name, r.id, r.ddate, r.start_time,r.end_time,r.is_holiday');
        $this->db->from('employee e');
        $this->db->where('e.archive','N');
        $this->db->join('employee_roster_schedule r','r.emp_id = e.emp_id', 'inner');
        $this->db->where('r.ddate >=',$sdate);
        $this->db->where('r.ddate <=',$edate);
        $this->db->where('r.dept_code', $dept_code);
        $this->db->where_in('r.emp_id', $staffIDs);
        $this->db->order_by('ddate','asc');

        $query = $this->db->get();
		$rosterRecord =  $query->result();
		// echo "<pre>";
		// var_dump($rosterRecord);
		// die();
        $rosterData = array();
        foreach($rosterRecord as $obj) {
            $dt = date("Y-m-d",strtotime($obj->ddate));
            $stm = date("h:i a",strtotime($obj->start_time));
            $etm = date("h:i a",strtotime($obj->end_time));
            $time_slot =  $stm." ".$etm;
            if($obj->is_holiday == ''){
                $rosterData[$dt][$time_slot][] = $obj;
            } else {
                $rosterData[$dt][$obj->is_holiday][] = $obj;
            }
        }
        return $rosterData;
	}
	
	public function getRosterSlotWeekendOfEmployee($dept_code){
	    
	    $this->db->select('w.date, e.emp_id, e.name');
	    $this->db->from('employee e');
	    $this->db->where('e.archive','N');
	    $this->db->join('weekend w','w.emp_id = e.emp_id', 'inner');
	    $this->db->where('e.dept_code', $dept_code);
	    $this->db->order_by('date','asc');
	     
	    $query = $this->db->get();
	     
	    $data = $query->result_array();
	    //print_r($data);
	     
	    $result = array();
	     
	    foreach ($data as $array){
	         
	        $result[$array['date']][$array['emp_id']] = $array['name'];
	    }

	    //print_r($result);
	    
	    return $result;
	}

	public function getRosterSlotHolidayOfEmployee($dept_code){
	    
	    $this->db->select('h.date, e.emp_id, e.name');
	    $this->db->from('employee e');
	    $this->db->where('e.archive','N');
	    $this->db->join('roster_holiday h','h.emp_id = e.emp_id', 'inner');
	    $this->db->where('e.dept_code', $dept_code);
	    $this->db->order_by('date','asc');
	     
	    $query = $this->db->get();
	     
	    $data = $query->result_array();
	    //print_r($data);
	     
	    $result = array();
	     
	    foreach ($data as $array){
	         
	        $result[$array['date']][$array['emp_id']] = $array['name'];
	    }

	    //print_r($result);
	    
	    return $result;
	}
	
	public function getAllRosterDeptCode(){
	    $this->db->select('d.dept_code');
	    $this->db->distinct();
	    $this->db->from('departments d');
	    $this->db->join('employee e', 'd.dept_code = e.dept_code', 'left');
	    $this->db->where('e.archive','N');
	    $this->db->where('e.roster', 'Y');
	    //$this->db->where('e.scheduled_attendance', 'N');
	    $this->db->order_by('d.dept_code','asc');
	    
	    $query = $this->db->get();
	    $res = $query->result_array();
	    $return = array();

	    foreach ($res as $ary){
	        $return[] = $ary['dept_code'];
	    }

	    return $return;
	}
	
	public function getAllDeptCode(){
	    $this->db->select('d.dept_code');
	    $this->db->distinct();
	    $this->db->from('departments d');
	    //$this->db->join('employee e', 'd.dept_code = e.dept_code', 'left');
	    //$this->db->where('e.archive','N');
	    //$this->db->where('e.roster', 'Y');
	    //$this->db->where('e.scheduled_attendance', 'N');
	    $this->db->order_by('d.dept_code','asc');
	     
	    $query = $this->db->get();
	    $res = $query->result_array();
	    $return = array();
	
	    foreach ($res as $ary){
	        $return[] = $ary['dept_code'];
	    }
	
	    return $return;
	}
	
	public function GetStaffListByDeptCode($dept_code) {
		
		$this->db->select('emp_id,name');
		$this->db->from('employee');
		$this->db->where('archive','N');
		//$this->db->where('roster', 'Y');
		$this->db->where('dept_code',$dept_code);
		$this->db->order_by('emp_id','asc');
	
		$query = $this->db->get();
		return $query->result();
	}
	
	public function addRosterSlot($data){
	    
	    $this->db->insert('roster_slot', $data);
	    return  $this->db->insert_id();
	}
	
	public function updateRosterSlot($id, $data){
	
	    $this->db->where('id', $id);
	    $this->db->update('roster_slot', $data);
	     
	    return ($this->db->affected_rows() > 0);
	}
	
	public function del_roster_slot($id){
	    $this->db->where('id', $id);
	    $this->db->delete('roster_slot');
	
	    return ($this->db->affected_rows() > 0);
	}
	
	public function addRoster($obj) {
	    
		$this->db->select('emp_id, is_incharge');
		$this->db->from('rostering');
		$this->db->where(array("emp_id"=>$obj->emp_id,"stime"=>$obj->from));
		$query = $this->db->get();
		$res = $query->row_array();
		
		$data = array("emp_id"=>$obj->emp_id,
		    "stime"=>$obj->from,
		    "etime"=>$obj->to);
		
		if(isset($obj->incharge) && $obj->incharge){
		    $data['is_incharge'] = 'Y';
		}
		
		if(count($res) == 0) {
		    //add
			$this->db->insert('rostering', $data);
			
			//if this is in weekend table then delete
			$from = substr($obj->from, 0,10);
			$this->db->where(array("emp_id"=>$obj->emp_id,"date"=>$from));
			$this->db->delete('weekend');

 		} else if(isset($obj->incharge) && $obj->incharge){

 		    if($res['is_incharge'] != 'Y'){
 		        //update to employee
 		        $this->db->where(array("emp_id"=>$obj->emp_id,"stime"=>$obj->from));
 		        $this->db->update('rostering',array('is_incharge'=>'Y'));
 		         
 		        //update to other employee
 		        $this->db->where(array("stime"=>$obj->from));
 		        $this->db->where('emp_id !=',$obj->emp_id );
 		        $this->db->update('rostering',array('is_incharge'=>'N')); 		        
 		    }
        }
        
        return ($this->db->affected_rows() > 0);                	
	}
	
	public function addRosterTmp($obj) {
	     
	    $this->db->select('emp_id');
	    $this->db->from('rostering_tmp');
	    $this->db->where(array("emp_id"=>$obj->emp_id,"stime"=>$obj->from));
	    $query = $this->db->get();
	    $res = $query->row_array();
	
	    if(count($res) == 0) {	        
	        //add
	        $data = array(
	            "emp_id"=>$obj->emp_id,
	            "stime"=>$obj->from,
	            "etime"=>$obj->to,
	            "is_incharge"=>$obj->incharge,
	            "tstamp"=>$obj->tstamp,
	        );
	        $this->db->insert('rostering_tmp', $data);
	        	
	        //if this is in weekend_tmp table then delete
	        $from = substr($obj->from, 0,10);
	        $this->db->where(array("emp_id"=>$obj->emp_id,"date"=>$from));
	        $this->db->delete('weekend_tmp');
	        
	        return ($this->db->affected_rows() > 0);
	    }
	
	    return false;
	}
	
	public function addWeekend($data, $table){	  	   
	    	    
	    $this->db->select('date');
	    $this->db->where($data);
	    $query = $this->db->get($table);
	    $res = $query->result();
	    
	    if(count($res) <1){
	        $this->db->insert($table, $data);
	        return ($this->db->affected_rows() > 0 );
	    }else{
	        return true;
	    }

	}
	
	public function addHoliday($obj){	  
		
		$this->db->select('emp_id');
		$this->db->from('roster_holiday');
		$this->db->where(array("emp_id"=>$obj->emp_id,"date"=>$obj->date));
		$query = $this->db->get();
		$res = $query->row_array();

		$data = array(
			'holiday_id' => $obj->holiday_id,
			'emp_id' => $obj->emp_id,
			'date' => $obj->date
		);

		if(count($res) <1) {

			$this->db->insert('roster_holiday', $data);

			$this->db->where(array("emp_id"=>$obj->emp_id,"date"=>$obj->date));
			$this->db->delete('weekend');
			
		}

		return ($this->db->affected_rows() > 0);  

	}

	public function deleteHoliday($obj) {

		$this->db->where(array("emp_id"=>$obj->emp_id, "date"=>$obj->date));
	    $this->db->delete('roster_holiday');
	
	    return ($this->db->affected_rows() > 0);
	}

	public function deleteRoster($obj) {
	    
	    $this->db->where(array("emp_id"=>$obj->emp_id, "stime"=>$obj->from));
	    $this->db->delete('rostering');
	
	    return ($this->db->affected_rows() > 0);
	}
	
	public function deleteRosterById($pkid) {
		
		$this->db->where('id', $pkid);
		$this->db->delete('rostering');

		return ($this->db->affected_rows() > 0);
	}
	
	public function getRosterInfoByPkId($pkid) {
		
		
		$this->db->select('*');
		$this->db->from('rostering');
		$this->db->where('id',$pkid);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		//echo $pkid;die;
		
		return $query->row_array();

	}
	
	public function updateRoster($row) {

		$this->db->where('id', $row["id"]);
		$this->db->update('rostering', array("is_incharge"=>"Y"));
		
		//echo $this->db->last_query();
		
		if($this->db->affected_rows()) {
			$this->db->where('stime', $row["stime"]);
			$this->db->where('id !=', $row["id"]);
			$this->db->update('rostering', array("is_incharge"=>"N"));
			
			//echo $this->db->last_query();
		}

		
		return ($this->db->affected_rows() > 0);
		
	}
	
	public function getRosterRow($emp_id, $date, $table) {
		$this->db->select('emp_id');
		$this->db->from($table);
		$this->db->where('emp_id',$emp_id);
		$this->db->where("stime LIKE '".$date."%'");
		
		$query = $this->db->get();
		
		return $query->num_rows();
	}
	
	public function updateRosterRow($emp_id,$date,$stime,$etime) {
	    	    
		$sql = "UPDATE rostering SET stime='$date $stime', etime='$date $etime' 
				WHERE emp_id='$emp_id' AND LEFT(stime,10)='$date' AND LEFT(etime,10)='$date'";
		
		$this->db->query($sql);
		
		return ($this->db->affected_rows() > 0);
	}
	
	public function updateRosterRow_tmp($emp_id,$date,$stime,$etime, $tstamp) {
	
	    $sql = "UPDATE rostering_tmp SET stime='$date $stime', etime='$date $etime', tstamp='$tstamp'
	    WHERE emp_id='$emp_id' AND LEFT(stime,10)='$date' AND LEFT(etime,10)='$date'";
	    $this->db->query($sql);
	
	    return ($this->db->affected_rows() > 0);
	}
	
	public function addRosterRow($data) {

	    $this->db->insert('rostering', $data);	    	    
		return ($this->db->affected_rows() > 0);
	}

	public function addRosterRow_tmp($data) {
    	$this->db->insert('rostering_tmp', $data);
    	
        return ($this->db->affected_rows() > 0 );
	}
	
	public function add_rostering_control($data){
	    $this->db->insert('rostering_control', $data);
	    
	    return  $this->db->insert_id();
	}

	public function new_add_rostering_control($data){

        $this->db->where('emp_ids',$data['emp_ids']);
        $this->db->where('sdate >=',$data['sdate']);
        $this->db->where('edate <=',$data['edate']);
        $this->db->delete('rostering_control');

        $this->db->insert('rostering_control', $data);
        return $this->db->insert_id();
	}

	public function deleteWeekendTempData($emp_ids = array(),$sdate,$edate){
        $this->db->where_in('emp_id',$emp_ids);
        $this->db->where('date >=', $sdate);
        $this->db->where('date <=', $edate);
        $this->db->delete('weekend_tmp');
        //echo $this->db->last_query();
	}

	public function insertRosterSchedule($data = array()){
		echo json_encode($data);
        $this->db->insert_batch('employee_roster_schedule',$data);
        return $this->db->affected_rows();
	}
	
	public function deleteWeekend($ddate,$dept_code){
        $this->db->delete('employee_roster_schedule', array('ddate'=>$ddate,'is_holiday'=>'W','dept_code'=>$dept_code));
	}

	public function deleteData($data = array()){

        if($data['is_holiday'] != 'G') {
            $stime = strtotime($data['start_time']);
            $etime = strtotime($data['end_time']);

            if($stime > $etime){
                $oneDayAhead = date("Y-m-d",strtotime($data['ddate']." +1 days"));

                $start_time = $data['ddate']." ".date("H:i:s", $stime);
                $end_time = $oneDayAhead." ".date("H:i:s", $etime);
            } else {
                $start_time = $data['ddate']." ".date("H:i:s", $stime);
                $end_time = $data['ddate']." ".date("H:i:s", $etime);
            }


            $this->db->select('emp_id');
            $this->db->from('employee_roster_schedule');
            $this->db->where('emp_id', $data['emp_id']);
            $this->db->where('ddate', $data['ddate']);
            $this->db->where('is_holiday', '');
            $query = $this->db->get();
            $numRows = $query->num_rows();

            if ($numRows == 1) {
                $updData = array('is_holiday'=>'W','start_time'=>'0000-00-00 00:00:00','end_time'=>'0000-00-00 00:00:00','entry_time'=>'0000-00-00 00:00:00','out_time'=>'0000-00-00 00:00:00');
                $this->db->where('emp_id',$data['emp_id']);
                $this->db->where('ddate',$data['ddate']);
                $this->db->where('is_holiday != ','G');
                //$this->db->where('start_time',$start_time);
                //$this->db->where('end_time',$end_time);
                $this->db->update('employee_roster_schedule',$updData);

            } else {
                $this->db->delete('employee_roster_schedule', array('ddate' => $data['ddate'], 'emp_id' => $data['emp_id'], 'start_time' => $start_time, 'end_time' => $end_time));
                //echo $this->db->last_query();
            }
        } else {
            $dataAry = array('is_holiday'=>'W','start_time'=>'0000-00-00 00:00:00','end_time'=>'0000-00-00 00:00:00','entry_time'=>'0000-00-00 00:00:00','out_time'=>'0000-00-00 00:00:00');
            $this->db->where('emp_id',$data['emp_id']);
            $this->db->where('is_holiday',$data['is_holiday']);
            $this->db->where('ddate',$data['ddate']);
            $this->db->update('employee_roster_schedule',$dataAry);
        }
	}
	
	public function deleteRosterTempData($emp_ids = array(),$sdate,$edate){
        $this->db->where_in('emp_id',$emp_ids);
        $this->db->where('LEFT(`stime`,10) >=', $sdate);
        $this->db->where('LEFT(`stime`,10) <=', $edate);
        $this->db->delete('rostering_tmp');
        //echo $this->db->last_query();
    }
	
	public function get_weekend_tmp($id){
	    $this->db->select('*');
	    $this->db->from('weekend_tmp');
	    $this->db->where('id',$id);
	    $query = $this->db->get();
	     
	    return $query->row_array();
	}
		
	public function del_rostering_control($id){
	    
	    $this->db->select('tstamp');
	    $this->db->from('rostering_control');
	    $this->db->where('id', $id);
	    $query = $this->db->get();	    	    
	    $res = $query->row_array();
	    $tstamp = $res['tstamp']; 

		$this->db->where('id', $id);
		$this->db->delete('rostering_control');
		
		$this->db->where('tstamp', $tstamp);	
		$this->db->delete('rostering_tmp');
		
		$this->db->where('tstamp', $tstamp);
		$this->db->delete('weekend_tmp');

		return ($this->db->affected_rows() > 0);
	}
	
	public function get_holiday_request() {
	    $this->db->select('r.*, e.name, d.dept_name');
	    $this->db->from('rostering_control r');
	    $this->db->join('employee e','r.sender_id = e.emp_id', 'inner');
	    $this->db->join('departments d','r.dept_code = d.dept_code', 'inner');
	    $this->db->where('r.admin_id','');
	    $this->db->order_by('r.id','desc');	
	    $query = $this->db->get();
	    
	    return $query->result_array();
	}
	
	public function update_rostering_control($data, $id){
	    $return =array();
	    
	    $this->db->where('id', $id );
	    $this->db->update('rostering_control',$data );
	    
	    $flag1 = ($this->db->affected_rows() > 0);
	    
	    $this->db->select('e.emp_id, e.name, e.email');
	    $this->db->from('rostering_control r');
	    $this->db->join('employee e','e.emp_id=r.sender_id', 'inner');
	    $this->db->where('r.id', $id );
	    
	    $query = $this->db->get();
	    $return['requesterInfo'] = $query->row();
	    	    
	    //get $tstamp
	    $this->db->select('tstamp');
	    $this->db->from('rostering_control');
	    $this->db->where('id', $id);
	    $query = $this->db->get();
	    $res = $query->row_array();
	    $tstamp = $res['tstamp'];
	    
	    //move data from rostering_tmp to rostering 	    
	    //$sql = "INSERT INTO rostering (emp_id, stime, etime) SELECT emp_id, stime, etime FROM rostering_tmp WHERE tstamp='$tstamp'";
        $sql = "INSERT INTO rostering (emp_id, stime, etime)
                (
                  SELECT tmp.emp_id, tmp.stime, tmp.etime
                  FROM rostering_tmp tmp
                  LEFT JOIN rostering ros ON tmp.emp_id = ros.emp_id AND tmp.stime = ros.stime
                  WHERE ros.emp_id IS NULL AND ros.stime IS NULL AND tmp.tstamp='$tstamp'
                )";
	    
        $this->db->query($sql);
        $flag2 = ($this->db->affected_rows() > 0);
        //echo $this->db->last_query();
        //del from rostering_tmp
        $this->db->where('tstamp', $tstamp);
        $this->db->delete('rostering_tmp');
       //echo $this->db->last_query();
        
        //move data from weekend_tmp to weekend
        //$sql = "INSERT INTO weekend (emp_id, date) SELECT emp_id, date FROM weekend_tmp WHERE tstamp='$tstamp'";
        $sql = "INSERT INTO weekend (emp_id, date) 
                (
                    SELECT tmp.emp_id, tmp.date 
                    FROM weekend_tmp tmp 
                    LEFT JOIN weekend wk ON tmp.emp_id = wk.emp_id AND tmp.date = wk.date
                    WHERE wk.emp_id IS NULL AND wk.date IS NULL AND tmp.tstamp='$tstamp'
                )";
        
        $this->db->query($sql);
        $flag3 = ($this->db->affected_rows() > 0);
        //del from weekend_tmp
        $this->db->where('tstamp', $tstamp);
        $this->db->delete('weekend_tmp');
	    
        $return['flag'] = $flag1 && $flag2 && $flag3;
        
	    return $return;	    
	}
	
	public function getNonRosterOfficeSchedule($emp_id, $sdate, $edate, $default_weekend){
	    
	    $this->db->select('stime, etime, is_incharge');
	    $this->db->from('rostering');
	    $this->db->where('LEFT(`stime`,10) >=',$sdate);
	    $this->db->where('LEFT(`etime`,10) <=',$edate);
	    $this->db->where('emp_id', $emp_id);
	    $this->db->order_by('stime','asc');	    
	    $query = $this->db->get();	     
	    $result = $query->result_array();
	    
	    $rosterData = array();
	    $dateValAry = array();
	    foreach ($result as $ary){

	        $date = substr($ary['stime'],0,10); //date('Y-m-d', strtotime($ary['stime']));
	        $rosterData[$date] = $ary;
	        $dateValAry[] = $date;
	    }

		//var_dump($dateValAry);
	    
	    
	    $this->db->select('office_stime, office_etime');
	    $this->db->from('employee');
	    $this->db->where('emp_id',$emp_id);
	    $this->db->where('roster','N');
	    $query = $this->db->get();	     
	    $officeSchedule = $query->row_array();

	    /* Weekend */
	    $this->db->select('date');
	    $this->db->from('weekend');
	    $this->db->where('emp_id',$emp_id);
	    $this->db->where('date >=',$sdate );
	    $this->db->where('date <=', $edate);
	    $query = $this->db->get();
	    $weekendObjs = $query->result();
	    
	    $weekendAry = array();
	    foreach ($weekendObjs as $obj){

	        $weekendAry[]  = $obj->date;
	        $dateValAry[] = $obj->date;
	    }
	    
	    sort($dateValAry);
	    $startRosterDate = reset($dateValAry);
	    $endRosterDate = end($dateValAry);
	    
	    

	    $query = $this->db->get_where('weekly_leave', array('emp_id'=>$emp_id));
	    $weekend = $query->row_array();
	    
	    $countWeekend = 0;
	    foreach ($weekend as $key=>$val){
	        
	        if($key === 'emp_id') continue;
	        if($val === 'Y'){
	            $countWeekend++;
	        }	            
	    }	    
	    if($countWeekend == 0 || count($weekend) == 0){

	        $weekend = $default_weekend;
	    }
	    
	    $this->db->select('date, description');
	    $this->db->from('holy_day');
	    $this->db->where('date >=',$sdate );
	    $this->db->where('date <=', $edate);
	    $query = $this->db->get();
	    $holidayObjs = $query->result();
	    
	    $holidays = array();
	    foreach($holidayObjs as $obj){
	        $holidays[$obj->date] = $obj->description;
	    }	    
	    
	    $retData = array();	    
	    for($idate = $sdate; $idate <= $edate;){
	        
	        if($startRosterDate && $endRosterDate && $idate >= $startRosterDate && $idate <= $endRosterDate){
	            /* roster table has data */
	            
	            if(in_array($idate, $weekendAry)){
	                
					$retData[$idate]['weekend'] = "Weekend";
					
	            }else{
	                
	                $data = $rosterData[$idate];
	                $retData[$idate]['from'] = substr($data['stime'], -8) ;
	                $retData[$idate]['to'] = substr($data['etime'], -8);	                
	            }
	            
	        }else{
	            
	            $dayName = strtolower(date('D', strtotime($idate)));
	            
	            if( $weekend[$dayName] == 'Y' ){
	                
	                $retData[$idate]['weekend'] = "Weekend";
	            }else{
	                $retData[$idate]['from'] = $officeSchedule['office_stime'] ;
	                $retData[$idate]['to'] = $officeSchedule['office_etime'] ;
	            }
	        }        
            
	        if(isset($holidays[$idate]) ){
	            
	            if(isset($retData[$idate]['weekend'])){
	                
	                $retData[$idate]['weekend'] .= " & ".$holidays[$idate];
	            }else{
	                $retData[$idate]['weekend'] = $holidays[$idate];
	            }
	        }        
	        
	        $idate = date("Y-m-d",strtotime($idate." +1 day"));
	    }
	    
	    return $retData;
	}
		
	public function addRosterForCustomTimeNonSlot($empIds, $date, $options, $stime, $etime){
	   
	    sort($date);
	    $firstDate = reset($date);
	    $lastDate = end($date);
	    
	    /* delete from weekend Table  */
	    $this->db->where_in("emp_id", $empIds);
	    $this->db->where("date >=", $firstDate);
	    $this->db->where("date <=", $lastDate);
	    $this->db->delete('weekend');
	    
	    /* delete from rostering Table */
	    $this->db->where_in("emp_id", $empIds);
	    $this->db->where("LEFT(stime,10) >=", $firstDate);
	    $this->db->where("LEFT(stime,10) <=", $lastDate);
	    $this->db->delete('rostering');
	    
        foreach ($empIds as $emp_id) {
            
            foreach($date as $key=>$rdate){
                
                if(in_array($rdate, $options)){
                    
                    //holiday, add to weekend
                    $Wdata =  array('emp_id'=>$emp_id, 'date'=> $rdate) ;
                    $flag1 = $this->addWeekend($Wdata, 'weekend');
    
                } else{
                    // add to rostering
                    $stm = $stime[$key];
                    $etm = $etime[$key];
                    
                    $Rdata = array('emp_id' =>$emp_id, 'stime'=>$rdate." ".$stm, 'etime'=>$rdate." ".$etm);
                    $flag2 =$this->addRosterRow($Rdata);
                }
            }
        }
        
        return ($flag1 || $flag2);
	}

	public function new_addRosterForCustomTimeNonSlot($empIds, $date, $options, $stime, $etime,$dept_code=""){

		
        sort($date);
        $firstDate = reset($date);
        $lastDate = end($date);

        $this->db->where_in("emp_id", $empIds);
        $this->db->where("ddate >=", $firstDate);
        $this->db->where("ddate <=", $lastDate);
        $this->db->delete('employee_roster_schedule');

        foreach ($empIds as $emp_id) {

            foreach($date as $key=>$rdate) {
                if (in_array($rdate, $options)) {
                    $weekend = 'W';
                    $start_time = '';
                    $end_time = '';
                } else {
                    $weekend = '';
                    $start_time = $rdate." ".$stime[$key];
                    $end_time = $rdate." ".$etime[$key];
                }
                $data = array('emp_id' =>$emp_id, 'ddate'=>$rdate, 'start_time'=>$start_time,'end_time'=>$end_time,'entry_time'=>'','out_time'=>'','dept_code'=>$dept_code,'comment'=>'','is_holiday'=>$weekend);
            }
            $status = $this->addEmployeeRosterData($data);
		}
		/* echo "<pre>";
		print_r($status);
		die(); */
        return $status;
    }
	
	public function addEmployeeRosterData($data) {

		$this->db->insert('employee_roster_schedule', $data);
		return ($this->db->affected_rows() > 0 );

	}

	public function addRosterForSameTimeNonSlot($empIds, $firstDate, $lastDate, $options, $stime, $etime){
	     
	    /* delete from weekend Table  */
	    $this->db->where_in("emp_id", $empIds);
	    $this->db->where("date >=", $firstDate);
	    $this->db->where("date <=", $lastDate);
	    $this->db->delete('weekend');
	     
	    /* delete from rostering Table */
	    $this->db->where_in("emp_id", $empIds);
	    $this->db->where("LEFT(stime,10) >=", $firstDate);
	    $this->db->where("LEFT(stime,10) <=", $lastDate);
	    $this->db->delete('rostering');
	    
	    $flag1 = false;
	    $flag2 = false;
	     
	    foreach ($empIds as $emp_id) {
	
	        for($iDate=$firstDate; $iDate <= $lastDate; ){
	
	        	$dayName = strtolower(date('D', strtotime($iDate)));
	        	
                if(in_array($dayName, $options)){
                    //holiday, add to weekend
                    $Wdata =  array('emp_id'=>$emp_id, 'date'=> $iDate) ;
                    $flag1 = $this->addWeekend($Wdata, 'weekend');
                    
                }else{                    
                    // add to rostering   
                    $Rdata = array('emp_id' =>$emp_id, 'stime'=>$iDate." ".$stime, 'etime'=>$iDate." ".$etime);
                    $flag2 = $this->addRosterRow($Rdata);
                }
	            
	            $iDate = date("Y-m-d",strtotime($iDate." +1 days"));
	        }
	    }
	
	    return ($flag1 || $flag2);
	}
	public function new_addRosterForSameTimeNonSlot($empIds, $firstDate, $lastDate, $options, $stime, $etime,$dept_code) {

        $this->db->where_in("emp_id", $empIds);
        $this->db->where("ddate >=", $firstDate);
        $this->db->where("ddate <=", $lastDate);
        $this->db->delete('employee_roster_schedule');



        foreach ($empIds as $emp_id) {
            for ($iDate = $firstDate; $iDate <= $lastDate;) {
                $dayName = strtolower(date('D', strtotime($iDate)));
                if (in_array($dayName, $options)) {
                    $start_time = '';
                    $end_time = '';
                    $weekend = 'W';
                } else {
                    $start_time = $iDate." ".$stime;
                    $end_time = $iDate." ".$etime;
                    $weekend = '';
                }
                $data[] = array('emp_id' =>$emp_id, 'ddate'=>$iDate, 'start_time'=>$start_time,'end_time'=>$end_time,'entry_time'=>'', 'out_time'=>'','dept_code'=>$dept_code,'comment'=>'','is_holiday'=>$weekend);
				$iDate = date("Y-m-d",strtotime($iDate." +1 days"));
				$officeTimedata = array(
					'office_stime' => $start_time,
					'office_etime' => $end_time
				);
				$this->db->update('employee', $officeTimedata, array('emp_id' =>$emp_id));
            }
        }
        $status = $this->saveSameTimeData($data);


		
        return $status;
	}
	private function saveSameTimeData($data) {

        $this->db->insert_batch('employee_roster_schedule',$data);
		return $this->db->affected_rows() > 0;
    }
	public function delete_roster_per_day($iDate,$nextDate,$dept_code)
	{
		$this->db->select('r.id');
	    $this->db->from('rostering r');
	    $this->db->join('employee e','r.emp_id=e.emp_id');
	    $this->db->where('LEFT(`stime`,10) =',$iDate);
		$this->db->where('e.dept_code =',$dept_code);
	    $query = $this->db->get();
	    $str = $this->db->last_query();
		$rosterData = $query->result_array();

		array_map(function($arr) {
			$this->db->where('id', $arr['id']);
			$this->db->delete('rostering'); 
		 }, $rosterData);
	}
	
	

}