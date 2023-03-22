<?php
class Test extends G_Controller {

	public function __construct() {
		parent::__construct();
		//echo 'Hello';
		/*
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->model('attendance_model');
		$mySessionId = $this->session->GetLoginId();
		if (empty($mySessionId) || ($mySessionId != '0181' && $mySessionId != '0146')){
		    echo "Access Denied";
		    exit(); return false;
		}
		*/
	}
	public function index() {
	echo 'Hello';
	}
	
	public function showPendingList() {	    
	    $this->isLoggedIn();
		$tableData = $this->attendance_model->getTableData('activity_permission', 'staff_id', 'ASC');
	    	$this->gPrint($tableData);
	    //$tableData = $this->attendance_model->getTableData('late_early_req', 'date', 'DESC');
	    //$this->gPrint($tableData);
	}
	
	public function getLastLeaveRecords(){
	    //$tableData = $this->attendance_model->getTableData('activity_log', 'log_time', 'DESC', 100);
	    //$tableData = $this->attendance_model->getTableData('settings', 'emp_id', 'ASC', 100);
	    //$tableData = $this->attendance_model->getTableData('iorecords', 'date', 'DESC', 100, "emp_id = '0132'");
	    //$tableData = $this->attendance_model->getTableData('rostering', 'stime', 'DESC', 50, "emp_id = '0264'");
	    //$tableData = $this->attendance_model->getTableData('weekend', 'date', 'DESC', 50, "emp_id = '0128'");
	    //$tableData = $this->attendance_model->getTableData('roster_holiday', 'date', 'DESC', 50, "emp_id = '0128'");

	    //$tableData = $this->attendance_model->getTableData('rostering', 'stime', 'DESC', 50, "emp_id = '0132'");
	    //$tableData = $this->attendance_model->getTableData('weekend', 'date', 'DESC', 50, "date = '2016-06-30'");
	    $tableData = $this->attendance_model->getTableData('iorecords', 'date', 'DESC', 100, "date = '2016-06-07'");
	
	    $this->gPrint($tableData);
	}

	public function getLast10LeaveRequest(){
	    $tableData = $this->attendance_model->getTableData('leaves', 'id', 'DESC', 10);
	    $this->gPrint($tableData);
	}
	
	public function getImagePath(){
	    $this->isLoggedIn();	    
	    $dealFilePath = getcwd() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'pictures';
	    
	    if (is_dir($dealFilePath)) {
	        $handler = opendir($dealFilePath);
	        $count = 0;
	        while ($file = readdir($handler)) {
	            echo "<br>".$file;
	        }
	        closedir($handler);
	    }
	}
}