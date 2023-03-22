<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Session {

	public function __construct()
	{
		if(!session_id()) session_start();
	}
	
	public function SetSessionData($data)
	{
    	$_SESSION["G_SesData"] = urlencode(serialize($data));
	}
	
	public function GetSessionData()
	{
		if(isset($_SESSION["G_SesData"]) && !empty($_SESSION["G_SesData"])) {
			$arr = unserialize(urldecode($_SESSION["G_SesData"]));
			return $arr;
		}
		return null;
	}
	
	public function SetSessionSettings($settings)
	{
		$_SESSION["G_SesSettings"] = urlencode(serialize($settings));
	}
	
	public function GetSessionSettings()
	{
		if(isset($_SESSION["G_SesSettings"]) && !empty($_SESSION["G_SesSettings"])) {
			$arr = unserialize(urldecode($_SESSION["G_SesSettings"]));
			return $arr;
		}
		return null;
	}
	
	public function IsManager($emp_id)
	{
		$arr = $this->GetSessionSettings();
		
		//print_r($arr);exit;
		//$managers = $arr["admin_ary"];
		$managers = isset($arr["manager"]) ? $arr["manager"] : array();

		if(in_array($emp_id, $managers)) {
			return true;
		}
		return false;
	}
	
	public function IsAdmin($emp_id)
	{
		$arr = $this->GetSessionSettings();
		$admin = $arr["admin"] ?? '';
		if(is_array($admin) && in_array($emp_id, $admin)) {

			return true;
		}
		return false;
	}
	
	public function IsManagement($emp_id)
	{
		$arr = $this->GetSessionSettings();
		$management = isset($arr["management"]) ? $arr["management"] : array();
		
		if(in_array($emp_id, $management)) {
			return true;
		}
		return false;
	}
	
	public function getManagerDepartments($emp_id)
	{
	    $arr = $this->GetSessionSettings();
	    //print_r($arr);
	    $departmentsArray = $arr["manager_n_dept"];
	    return isset($departmentsArray[$emp_id]) ? $departmentsArray[$emp_id] : array();
	}
	
	public function GetAdminArray() {
	    $arr = $this->GetSessionSettings();
	    $admin = $arr["admin"];
	
	    return $admin;
	}
	
	public function GetManagerArray() {
	    $arr = $this->GetSessionSettings();
	    $manager = $arr["manager"];
	
	    return $manager;
	}
	
	public function getManagersByDeptCode($dept_code) {
	   $arr = $this->GetSessionSettings();
	   $managersArray = $arr["dept_n_manager"];
	   $managers =  isset($managersArray[$dept_code]) ? $managersArray[$dept_code] : array();
	   
	   return $managers;
	}
	
	public function getManagement() {
	    $arr = $this->GetSessionSettings();
	    	    
	    return $arr["management"];
	}
	
	public function GetMyBriefInfo()
	{
		$arr = $this->GetSessionData();
		$info = new stdClass();
		
		$info->userId = isset($arr["emp_id"]) && !empty($arr["emp_id"]) ? $arr["emp_id"] : "";
		$info->userName = isset($arr["name"]) && !empty($arr["name"]) ? $arr["name"] : "";
		$info->gender = isset($arr["gender"]) && !empty($arr["gender"]) ? $arr["gender"] : "";
		$info->email = isset($arr["email"]) && !empty($arr["email"]) ? $arr["email"] : "";
		$info->userDeptCode = isset($arr["dept_code"]) && !empty($arr["dept_code"]) ? $arr["dept_code"] : "";
		$info->userDesignation = isset($arr["designation"]) && !empty($arr["designation"]) ? $arr["designation"] : "";
		$info->userDepartment = isset($arr["dept_name"]) && !empty($arr["dept_name"]) ? $arr["dept_name"] : "";
		$info->userImage = isset($arr["image"]) && !empty($arr["image"]) ? $arr["image"] : "";		
		return $info;
	}
	
	public function GetUserType()
	{
	    $arr = $this->GetSessionData();
	    if(isset($arr["uType"]) && !empty($arr["uType"])) {
	        return $arr["uType"];
	    }
	    return "";
	}
	
	public function GetUserName()
	{
		$arr = $this->GetSessionData();
		if(isset($arr["name"]) && !empty($arr["name"])) {
			return $arr["name"];
		}
		return "";
	}
	
	public function GetUserImage()
	{
		$arr = $this->GetSessionData();
		if(isset($arr["image"]) && !empty($arr["image"])) {
			return $arr["image"];
		}
		return "";
	}
	
	public function GetUserDepartment()
	{
		$arr = $this->GetSessionData();
		if(isset($arr["dept_name"]) && !empty($arr["dept_name"])) {
			return $arr["dept_name"];
		}
		return "";
	}
	
	public function GetUserDepartmentCode()
	{
	    $arr = $this->GetSessionData();
	    if(isset($arr["dept_code"]) && !empty($arr["dept_code"])) {
	        return $arr["dept_code"];
	    }
	    return "";
	}
	
	
	
	public function GetUserDesignation()
	{
		$arr = $this->GetSessionData();
		if(isset($arr["designation"]) && !empty($arr["designation"])) {
			return $arr["designation"];
		}
		return "";
	}
	
	public function GetLoginId()
	{
		$arr = $this->GetSessionData();
		if(isset($arr["emp_id"])) {
			return $arr["emp_id"];
		}
		return "";
	}
	
	public function ClearSessionData()
	{
		unset($_SESSION["G_SesData"]);
		unset($_SESSION["G_SesLastPage"]);
		unset($_SESSION["evaluation"]);
		
		//session_destroy();
		return true;
	}
	
	public function IsLoggedIn()
	{
		$arr = $this->GetSessionData();
		if(isset($arr["emp_id"]) && !empty($arr["emp_id"])) {
			return true;
		}
		return false;
	}
	
	public function set_lastpage($url) {
	    $_SESSION["G_SesLastPage"] = $url;//urlencode($url);
	}
	
	public function get_lastpage() {
	    if(isset($_SESSION["G_SesLastPage"]) && !empty($_SESSION["G_SesLastPage"])) {
	        return $_SESSION["G_SesLastPage"];
	    }
	    return "";
	}

	public function SetRosterSession($emp_id)
	{
		if(!empty($emp_id) && $emp_id != NULL){
			$_SESSION["G_SesRosterEmpId"] = $emp_id;
		}else{
			unset($_SESSION["G_SesRosterEmpId"]);
		}
	}
	
	public function HasRosterPrev($emp_id)
	{
		if(isset($_SESSION["G_SesRosterEmpId"]) && !empty($_SESSION["G_SesRosterEmpId"])) {
			return true;
		}
		return false;
	}
}
?>