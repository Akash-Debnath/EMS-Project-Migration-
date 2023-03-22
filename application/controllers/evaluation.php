<?php
class Evaluation extends G_Controller {

    public $adminFlag = false;
	public $data = array();
	public $myEmpId = '';
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		
		$this->isLoggedIn();
		
		$this->load->model('user_model');
		$this->load->model('evaluation_model');
		$this->load->library('pagination');
		$this->load->library('mailer');
		$this->data["myInfo"] = $this->session->GetMyBriefInfo();
		$this->data['departments'] = $this->user_model->department();
		//$this->data["menu"] = "settings";
		$this->data["menu"] = "employee";
		$this->data["uType"] = $this->session->GetUserType();
		
		$this->myEmpId = $this->session->GetLoginId();
		$this->data['isManagement'] = $this->session->IsManagement($this->myEmpId);
		$this->data['isAdmin'] = $this->session->IsAdmin($this->myEmpId);
		$this->data['isManager'] = $this->session->IsManager($this->myEmpId);

		$this->data["title"] = "Evaluation";
		
		$this->data["controller"] = $this;
	}
	
	public function form($eid = "nil"){
	    
	    if(!$this->uri->segment(4)){
	        unset($_SESSION['evaluation']);
	    }
	    $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
	    $insert_id = ($this->uri->segment(5)) ? $this->uri->segment(5) : "";
	    
	    //dissablity
	    if(isset($_SESSION["evaluation"]) && !empty($_SESSION["evaluation"])) {
	        
	        $ses = unserialize(urldecode($_SESSION["evaluation"]));
	        if(isset($ses['employee']['[dept_code']))
	            $dept_code = $ses['employee']['[dept_code'];
	        else{
	            if($eid == "nil"){
	                $dept_code = "";
	            } else{
	                $dept_code = $this->evaluation_model->getDeptCode($eid);	                
	            }
	        }
	        if (!empty($ses['emp_id']) && $eid != $ses['emp_id']){
	            unset($_SESSION["evaluation"]);
	        }
	    }
	    if($eid == "nil"){
            $dept_code = "";            
        }else{
            $dept_code = $this->evaluation_model->getDeptCode($eid);
        }

	    $manager = $this->session->getManagersByDeptCode($dept_code);
	    $bossFlag = false;
	    if($this->session->IsManager($eid) || $this->session->IsAdmin($eid)){	         
	        $bossFlag = $this->session->IsManagement($this->myEmpId);
	    }
	    $this->data['manager'] = $manager;
	    $this->data['isAdmin'] = $this->session->IsAdmin($this->myEmpId);	    
	    $this->data['bossFlag'] = $bossFlag;	    
	    
	    if(isset($_POST['eval_id']) ) $insert_id = $_POST['eval_id'];	    
	    $last_page = isset($_POST['last_page']) ? $_POST['last_page'] : "";	   
	    $uFlag=false;
	    $isSetAvgRate = false;
	        	    
	    if($_POST){
	        
	        if($last_page == 1 && $eid != $this->myEmpId){
	            $data['eve_from'] = isset($_POST['eve_from']) ? $_POST['eve_from'] : '';
	            $data['eve_to'] = isset($_POST['eve_to']) ? $_POST['eve_to'] : '';	            
	            
	            if (empty($insert_id)){
	                $dataArray['emp_id'] = $eid;
	                $dataArray['eve_from'] = $data['eve_from'];
	                $dataArray['eve_to'] = $data['eve_to'];
	                $evaluation = $this->evaluation_model->getDraftEval($dataArray);
	                if (!empty($evaluation) && !empty($evaluation['id'])){
	                    $insert_id = $evaluation['id'];
	                    $_SESSION["evaluation"] = urlencode(serialize($evaluation));
	                }else {
	                    $ses = isset($_SESSION["evaluation"]) ? unserialize(urldecode($_SESSION["evaluation"])) : array();
	                    $ses = array_merge($ses, $data);
	                    $_SESSION["evaluation"] = urlencode(serialize($ses));
	                }
	            }
	            
	            if(!empty($insert_id)){
	                $flag = $this->evaluation_model->updateEval($insert_id, $data);
	            }else{
	                $data['emp_id'] = $eid;
	                $insert_id = $this->evaluation_model->addEval($data); 
	            }$ses = isset($_SESSION["evaluation"]) ? unserialize(urldecode($_SESSION["evaluation"])) : array();
	        } else if ($last_page == 2 && !empty($insert_id) && $last_page < $page){	            
	            $ary = array('ksa','ksa_comments','qlw', 'qlw_comments', 'qtw', 'qtw_comments', 'wh', 'wh_comments', 'com', 'com_comments');
	            
	            foreach ($ary as $val){
	                $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
	                $spKey = $val."_f";
	                if (!empty($_POST[$spKey]) && $_POST[$spKey] > 0 && $_POST[$spKey] <= 5){
	                    $data[$val] = $_POST[$spKey];
	                }
	            }
	            //print_r($data);
	            $ses = isset($_SESSION["evaluation"]) ? unserialize(urldecode($_SESSION["evaluation"])) : array();
	            $ses = array_merge($ses, $data);
	            //print_r($ses);
	            $_SESSION["evaluation"] = urlencode(serialize($ses));
	            //update to database
	            $flag = $this->evaluation_model->updateEval($insert_id, $data);
	            
	        }else if ($last_page == 3 && !empty($insert_id) && $last_page < $page){
	            $ary = array('dep','dep_comments','coo', 'coo_comments', 'ini', 'ini_comments', 'ada', 'ada_comments', 'jud', 'jud_comments', 'att', 'att_comments', 'pun', 'pun_comments');
	            
	            foreach ($ary as $val){
	                $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
	                $spKey = $val."_f";
	                if (!empty($_POST[$spKey]) && $_POST[$spKey] > 0 && $_POST[$spKey] <= 5){
	                    $data[$val] = $_POST[$spKey];
	                }
	            }
	            //print_r($data);
	            $ses = unserialize(urldecode($_SESSION["evaluation"]));
	            $ses = array_merge($ses, $data);
	            //print_r($ses);
	            $_SESSION["evaluation"] = urlencode(serialize($ses));
	            
	            //update to database
	            $flag = $this->evaluation_model->updateEval($insert_id, $data);
	            
	        } else if ($last_page == 4 && !empty($insert_id) && $last_page < $page){
	            $ary = array('avg_rate', 'led','led_comments','del', 'del_comments', 'pla', 'pla_comments', 'adm', 'adm_comments', 'per', 'per_comments');
	             
	            foreach ($ary as $val){
	                $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
	                $spKey = $val."_f";
	                if (!empty($_POST[$spKey]) && $_POST[$spKey] > 0 && $_POST[$spKey] <= 5){
	                    $data[$val] = $_POST[$spKey];
	                }
	                if ($val != 'avg_rate' && !empty($data[$val])){
	                    $isSetAvgRate = true;
	                }
	            }
	            
	            //print_r($data);
	            $ses = unserialize(urldecode($_SESSION["evaluation"]));
	            $ses = array_merge($ses, $data);
	            //print_r($ses);
	            $_SESSION["evaluation"] = urlencode(serialize($ses));	            //update to database
	            $flag = $this->evaluation_model->updateEval($insert_id, $data);
	            
	        } else if ($last_page == 5 && !empty($insert_id)){
	            $ary = array('avg_rate', 'opr', 'opr_comments', 'hbf', 'hbf_comments', 'manager_id', 'man_sig_date');	             
	            
	            foreach ($ary as $val){
	                $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
	                $spKey = $val."_f";
	                if (!empty($_POST[$spKey]) && $_POST[$spKey] > 0 && $_POST[$spKey] <= 5){
	                    $data[$val] = $_POST[$spKey];
	                }
	            }
	            //print_r($data);
	            $ses = unserialize(urldecode($_SESSION["evaluation"]));
	            $ses = array_merge($data, $ses);
	            //print_r($ses);
	            $_SESSION["evaluation"] = urlencode(serialize($ses));
	            //print_r($data);
	            
	            $flag = $this->evaluation_model->updateEval($insert_id, $data);
	        }
	    }	
	    

	    if($page == 1 && !empty($eid)){
            if(isset($_SESSION["evaluation"]) && !empty($_SESSION["evaluation"])) {
                $evaluation = unserialize(urldecode($_SESSION["evaluation"]));        
            }else{
                $evaluation = $this->evaluation_model->getFullEval($insert_id, $eid);
                $_SESSION["evaluation"] = urlencode(serialize($evaluation));
            }             
            $this->data['evaluation'] = $evaluation;


            if (isset($_SESSION["evaluation"]) && ! empty($_SESSION["evaluation"])) {
                $evaluation = unserialize(urldecode($_SESSION["evaluation"]));
                
                if(isset($evaluation['employee']) && count($evaluation['employee']) > 0){
                    $this->data['employee'] = $evaluation['employee'];
                    
                }else{
                    $employee = $this->evaluation_model->getEmpEvalInfo($eid);
                    
                    $evaluation = unserialize(urldecode($_SESSION["evaluation"]));
                    $evaluation['employee'] = $employee;                    
                    $_SESSION["evaluation"] = urlencode(serialize($evaluation));

                    $this->data['employee'] = $employee;
                }    
            } else {
                    $employee = $this->evaluation_model->getEmpEvalInfo($eid);
                    
                    //$evaluation = unserialize(urldecode($_SESSION["evaluation"]));
                    $evaluation['employee'] = $employee;                    
                    $_SESSION["evaluation"] = urlencode(serialize($evaluation));

                    $this->data['employee'] = $employee;
            }
            
	    } else if($page == 2 && !empty($eid) && !empty($insert_id)){
	        if(isset($_SESSION["evaluation"]) && !empty($_SESSION["evaluation"])) {
	            $ses = unserialize(urldecode($_SESSION["evaluation"]));
	            $this->data['evaluation'] = $ses;
	        }
	    } else if($page == 3 && !empty($eid) && !empty($insert_id)){
	        
	        if(isset($_SESSION["evaluation"]) && !empty($_SESSION["evaluation"])) {
	            $ses = unserialize(urldecode($_SESSION["evaluation"]));
	            //print_r($ses);
	            $this->data['evaluation'] = $ses;
	        }
	        

	    } else if($page == 4 && !empty($eid) && !empty($insert_id)){
	        if(isset($_SESSION["evaluation"]) && !empty($_SESSION["evaluation"])) {
	            $ses = unserialize(urldecode($_SESSION["evaluation"]));	            	            
	            $number = 0;
	            $sum = 0;
	            $field = array('ksa','qlw','qtw', 'wh', 'com', 'dep', 'coo', 'ini', 'ada', 'jud', 'att', 'pun') ;
	            foreach ($field as $val){
	                if(isset($ses[$val])){
	                    $sum += $ses[$val];
	                    $number++;
	                }
	            }
	            $ses['avg_rate'] = round(($sum/$number), 2);
	            $this->data['evaluation'] = $ses;
	        }

	    } else if($page == 5 && !empty($eid) && !empty($insert_id)){
	        if(isset($_SESSION["evaluation"]) && !empty($_SESSION["evaluation"])) {
	            $ses = unserialize(urldecode($_SESSION["evaluation"]));
	            $this->data['evaluation'] = $ses;
	            
	            if ($isSetAvgRate){
    	            $number=0;
    	            $sum = 0;
    	            $field = array('ksa','qlw','qtw', 'wh', 'com', 'dep', 'coo', 'ini', 'ada', 'jud', 'att', 'pun', 'led','del', 'pla', 'adm', 'per') ;
    	            foreach ($field as $val){
    	                if(isset($ses[$val])){
    	                    $sum += $ses[$val];
    	                    $number++;
    	                }
    	            }
    	            $ses['avg_rate'] = round(($sum/$number), 2);
	            }
//                 if (empty($ses['manager_id']) && $this->session->IsManager($this->myEmpId)) {
//                     $ses['manager_name'] = $this->data["myInfo"]->userName;
                    
//                 }else if(!empty($ses['manager_id'])){
//                     $ses['manager_name'] = $this->data["myInfo"]->userName;
//                 }

	            $this->data['evaluation'] = $ses;
	        }
	    }
	    
	    $this->data["page"] = $page;
	    $this->data['insert_id'] = $insert_id;	    

        $this->data['eid'] = $eid;
        $this->data['status_array'] = $this->status_array;
        $this->data['period_evaluation'] = $this->period_evaluation;
        $this->data['ems_start'] = $this->ems_start_date;

	    $this->data["sub_title"] = "Evaluation Form";
	    $this->view('evaluation_form', $this->data);
	}
	
	public function save($eid = ""){	    
	    $insert_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : "";	    
	    $ary = array('avg_rate', 'opr', 'opr_comments', 'hbf', 'hbf_comments');
	     
	    foreach ($ary as $val){
	        $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
	        $spKey = $val."_f";
	        if (!empty($_POST[$spKey]) && $_POST[$spKey] > 0 && $_POST[$spKey] <= 5){
	            $data[$val] = $_POST[$spKey];
	        }
	    }

	    $ses = unserialize(urldecode($_SESSION["evaluation"]));
	    $ses = array_merge($ses, $data);
	    $_SESSION["evaluation"] = urlencode(serialize($ses));
	    $flag = $this->evaluation_model->updateEval($insert_id, $data);
	    
	    if($flag){
	        $this->data["message"] = "<span style='color: #68a358;'>Evaluation Saved As Draft</span>";
	    }else{
	        $this->data["message"] = "<span style='color: #C98383;'>Failed to Save Evaluation As Draft</span>";
	    }

	    $this->data["sub_title"] = "Evaluation Form";
	    $this->view('evaluation_message', $this->data);
	}
	
	public function send($eid = ""){
	    $insert_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : "";
	    $ary = array('avg_rate', 'opr', 'opr_comments', 'hbf', 'hbf_comments', 'manager_id', 'man_sig_date');
	    foreach ($ary as $val){
	        $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
	        $spKey = $val."_f";
	        if (!empty($_POST[$spKey]) && $_POST[$spKey] > 0 && $_POST[$spKey] <= 5){
	            $data[$val] = $_POST[$spKey];
	        }
	    }
	    $data['status'] = 'A';
	    //print_r($data);
	    $ses = unserialize(urldecode($_SESSION["evaluation"]));
	    $ses = array_merge($data, $ses);
	    $_SESSION["evaluation"] = urlencode(serialize($ses));
	     
	    $flag = $this->evaluation_model->updateEval($insert_id, $data);
	    
	    //sent mail to Employee
	    $receiver = array();
	    $receiver[] = $this->user_model->getBriefInfo($eid);
	    
	    $sender = array();
	    $sender['name'] = $this->data["myInfo"]->userName;
	    $sender['email'] = $this->data["myInfo"]->email;
	    $subject = "Performance evaluation for you on EMS";
	     
	    $designation = $this->data['myInfo']->userDesignation;
	    $dept = $this->data['myInfo']->userDepartment;
	    $time = date('h:i:s A');
	    $day = date('l');
	    $emailBody="<table>
	        <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
	        <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
	        </thead>
	        <tbody>
	        <tr>
	        <td width='50%' valign='top' align='left'>
	        <table cellpadding='3' cellspacing='0'>
	        <tr><td>".$this->myEmpId."</td></tr>
	        <tr><td><b><a href='".$this->web_url."user/detail/".$this->data["myInfo"]->userId."'>$sender[name]</a></b></td></tr>
	    	        <tr><td><i>$designation</i></td></tr>
	    	        <tr><td>$dept</td></tr>
	    	        </table>
	    	        </td>
	    	        <td width='50%' valign='center' align='right'>
	    	        <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='".$this->web_url."evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
	    	        </td>
	    	        </tr>
	    	        <tr height='40'><td colspan='2'>has evaluated you and submitted a <a href='".$this->web_url."evaluation/form/$eid/1/$insert_id'>Evaluation Form</a> to <a href='".$this->web_url."'>EMS</a> on $day at $time and waiting for your further query.<br><b>Average Rating: $data[avg_rate]</b></td></tr>
	    	        </tbody>
	    	        <tfoot>
	    	        <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='".$this->web_url."'>EMS</a> &nbsp;</b></td></tr>
        	</tfoot>
        	</table>";
	    	             
    	if($flag && $this->mailer->sendMail($subject, $emailBody, $receiver, $sender )) {
    	    $this->data["message"] = "<span style='color:#68a358;'>Evaluation Successfully Sent to Staff</span>";
    	}else{
    	    $this->data["message"] = "<span style='color:#C98383;'>Failed to Send Evaluation Email to Staff</span>";
    	}
	    
	    $this->data["sub_title"] = "Evaluation Form";
	    $this->view('evaluation_message', $this->data);
	}	
	
	public function proceed($eid = "")
    {
        $insert_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : "";
        
        $ary = array(
            'emp_comments',
            'emp_sig_date'
        );        
        foreach ($ary as $val) {
            $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
        }
        $data['status'] = 'C';
        
        if(isset($_FILES['emp_attachment']) && !empty($_FILES['emp_attachment']['name'])){

            $fileObject = $_FILES['emp_attachment'];            
            $config = array(
                'upload_path'     => './assets/files/',
                'allowed_types'   => 'jpg|jpeg|png|gif|txt|pdf|doc|docx',
                'file_name'       => $fileObject["name"],
                'overwrite'       => TRUE,
                'max_size'        => "1000",
            );            
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('emp_attachment')) {
                echo "Image upload failed.";
            }else{
                //success
                $filename = './assets/files/'.$fileObject["name"];
                if (file_exists($filename)) {
                    echo 'file exits';
                    $uploadFlag = true;
                    $data['emp_attachment'] = $fileObject["name"];
                }            
//                 $data_upload_files = $this->upload->data();
//                 $image = $data_upload_files['file_name'];
            }

        }
        
        $ses = unserialize(urldecode($_SESSION["evaluation"]));
        $ses = array_merge($ses, $data);
        $_SESSION["evaluation"] = urlencode(serialize($ses));
        
        // update to database
        $flag = $this->evaluation_model->updateEval($insert_id, $data);
        
        // sent mail to Employee
        $receiver = array();
        $receiver[] = $this->user_model->getBriefInfo($ses['manager_id']);
        
        $sender = array();
        $sender['name'] = $this->data["myInfo"]->userName;
        $sender['email'] = $this->data["myInfo"]->email;
        $subject = "Evaluation: proceeded by employee";
        
        $designation = $this->data['myInfo']->userDesignation;
        $dept = $this->data['myInfo']->userDepartment;
        $time = date('h:i:s A');
        $day = date('l');
        $emailBody = "<table>
	        <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
	        <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
	        </thead>
	        <tbody>
	        <tr>
	        <td width='50%' valign='top' align='left'>
	        <table cellpadding='3' cellspacing='0'>
	        <tr><td>" . $this->myEmpId . "</td></tr>
	        <tr><td><b><a href='" . $this->web_url . "user/detail/" . $this->data["myInfo"]->userId . "'>$sender[name]</a></b></td></tr>
		        <tr><td><i>$designation</i></td></tr>
		        <tr><td>$dept</td></tr>
		        </table>
		        </td>
		        <td width='50%' valign='center' align='right'>
		        <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='" . $this->web_url . "evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
		        </td>
		        </tr>
		        <tr height='40'><td colspan='2'>has proceeded the evalution form on $day at $time. and given <br><b>Comments:</b>$data[emp_comments]</td></tr>
		        </tbody>
		        <tfoot>
		        <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='" . $this->web_url . "'>EMS</a> &nbsp;</b></td></tr>
		        </tfoot>
        	</table>";
        
        //echo $emailBody;
        
        if ($flag && $this->mailer->sendMail($subject, $emailBody, $receiver, $sender)) {
            $this->data["message"] = "<span style='color: #68a358;'>Evaluation Successfully Proceed</span>";
        } else {
            $this->data["message"] = "<span style='color:#C98383;'>Failed to Proceed Evaluation</span>";
        }
        
        $this->data["sub_title"] = "Evaluation Form";
        $this->view('evaluation_message', $this->data);
    }
    
    public function dispute($eid = "")
    {
        $insert_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : "";        
        $ary = array('emp_comments', 'emp_sig_date');        
        foreach ($ary as $val) {
            $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
        }
        $data['status'] = 'B';
        
        if(isset($_FILES['emp_attachment']) && !empty($_FILES['emp_attachment']['name'])){
            $fileObject = $_FILES['emp_attachment'];            
            $config = array(
                'upload_path'     => './assets/files/',
                'allowed_types'   => 'jpg|jpeg|png|gif|txt|pdf|doc|docx',
                'file_name'       => $fileObject["name"],
                'overwrite'       => TRUE,
                'max_size'        => "1000",
            );            
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('emp_attachment')) {
                echo "Upload failed.";
            }else{
                //success
                $filename = './assets/files/'.$fileObject["name"];
                if (file_exists($filename)) {
                    echo 'file exits';
                    $uploadFlag = true;
                    $data['emp_attachment'] = $fileObject["name"];
                }
            }
        }
        
        $ses = unserialize(urldecode($_SESSION["evaluation"]));
        $ses = array_merge($ses, $data);
        $_SESSION["evaluation"] = urlencode(serialize($ses));
        
        // update to database
        $flag = $this->evaluation_model->updateEval($insert_id, $data);
        // sent mail to Employee
        $receiver = array();
        $receiver[] = $this->user_model->getBriefInfo($ses['manager_id']);
        
        $sender = array();
        $sender['name'] = $this->data["myInfo"]->userName;
        $sender['email'] = $this->data["myInfo"]->email;
        $subject = "Evaluation: dispute by employee";
        
        $designation = $this->data['myInfo']->userDesignation;
        $dept = $this->data['myInfo']->userDepartment;
        $time = date('h:i:s A');
        $day = date('l');
        $emailBody = "<table>
	        <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
	        <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
	        </thead>
	        <tbody>
	        <tr>
	        <td width='50%' valign='top' align='left'>
	        <table cellpadding='3' cellspacing='0'>
	        <tr><td>" . $this->myEmpId . "</td></tr>
	        <tr><td><b><a href='" . $this->web_url . "user/detail/" . $this->data["myInfo"]->userId . "'>$sender[name]</a></b></td></tr>
		        <tr><td><i>$designation</i></td></tr>
		        <tr><td>$dept</td></tr>
		        </table>
		        </td>
		        <td width='50%' valign='center' align='right'>
		        <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='" . $this->web_url . "evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
		        </td>
		        </tr>
		        <tr height='40'><td colspan='2'>has dispute in evalution form on $day at $time. and given <br><b>Comments:</b>$data[emp_comments]</td></tr>
		        </tbody>
		        <tfoot>
		        <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='" . $this->web_url . "'>EMS</a> &nbsp;</b></td></tr>
		        </tfoot>
        	</table>";
        
        if ($flag && $this->mailer->sendMail($subject, $emailBody, $receiver, $sender)) {
            $this->data["message"] = "<span style='color: #68a358;'>Evaluation Successfully Dispute</span>";
        } else {
            $this->data["message"] = "<span style='color:#C98383;'>Failed to Dispute Evaluation</span>";
        }
        
        $this->data["sub_title"] = "Evaluation Form";
        $this->view('evaluation_message', $this->data);
    }
    
    public function confirm($eid = ""){
    
        if(!$this->session->IsManager($this->myEmpId)) {
            $this->data["status_array"] = $this->status_array;
            $this->data["title"] = "ABC";
            $this->data["sub_title"] = "ABC";
            $this->data["message"] = "You have no privilege to access this page!";
            $this->load->view('not_found', $this->data);
            return;
        }
        
        $insert_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : "";
        $ary = array('avg_rate', 'opr', 'opr_comments', 'hbf', 'hbf_comments', 'manager_id', 'man_sig_date');
        foreach ($ary as $val){
            $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
            $spKey = $val."_f";
            if (!empty($_POST[$spKey]) && $_POST[$spKey] > 0 && $_POST[$spKey] <= 5){
                $data[$val] = $_POST[$spKey];
            }
        }
        // update to database
        $data['status'] = 'D';
        $flag = $this->evaluation_model->updateEval($insert_id, $data);
        
        // sent mail to Admin
        $receiver = array();
        $receiver[] = $this->user_model->getBriefInfo($eid);
        
        $sender = array();
        $sender['name'] = $this->data["myInfo"]->userName;
        $sender['email'] = $this->data["myInfo"]->email;
        
        $designation = $this->data['myInfo']->userDesignation;
        $dept = $this->data['myInfo']->userDepartment;
        $time = date('h:i:s A');
        $day = date('l');
        $subject = "Evaluation Approve Request";
        $admin_ary = $this->session->GetAdminArray();
        $adminEmails = $this->user_model->getMailInfoByIds($admin_ary);
        $emailBody = "
            <table>
                <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
            	    <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
            	</thead>
            	<tbody>
            	    <tr>
            	        <td width='50%' valign='top' align='left'>
                	        <table cellpadding='3' cellspacing='0'>
                                <tr><td>".$this->myEmpId."</td></tr>
                                <tr><td><b>$sender[name]</b></td></tr>
                                <tr><td><i>$designation</i></td></tr>
                                <tr><td>$dept</td></tr>
                            </table>
                        </td>
                        <td width='50%' valign='center' align='right'>&nbsp;</td>
                    </tr>
                    <tr><td colspan='2'>&nbsp;</td></tr>
                    <tr><td colspan='2'>has been sent evaluation approve request of -</td></tr>
                    <tr><td colspan='2'>&nbsp;</td></tr>
                    <tr>
                        <td width='50%' valign='top' align='left'>
                            <table cellpadding='3' cellspacing='0'>
                                <tr><td>".$receiver[0]->emp_id."</td></tr>
                                <tr><td><b><a href='".$this->web_url."user/detail/".$receiver[0]->emp_id."'>".$receiver[0]->name."</a></b></td></tr>
                                <tr><td><i>".$receiver[0]->designation."</i></td></tr>
                            	<tr><td>".$receiver[0]->dept_name."</td></tr>
                        	</table>
                        </td>
                        <td width='50%' valign='center' align='right'>
                            <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='".$this->web_url."evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='".$this->web_url."'>EMS</a> &nbsp;</b></td></tr>
                </tfoot>
             </table>";

        if($flag && $this->mailer->sendMail($subject, $emailBody, $adminEmails, $sender )) {
            $this->data["message"] = "<span style='color:#68a358;'>Evaluation Successfully Sent to Admin</span>";
            
            $subject = "Evaluation: confirmed by manager";
            $designation = $this->data['myInfo']->userDesignation;
            $dept = $this->data['myInfo']->userDepartment;
            $time = date('h:i:s A');
            $day = date('l');
            $emailBody = "
            <table>
                <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
                    <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
                </thead>
                <tbody>
                    <tr>
                    <td width='50%' valign='top' align='left'>
                    <table cellpadding='3' cellspacing='0'>
                    <tr><td>" . $this->myEmpId . "</td></tr>
                    <tr><td><b><a href='" . $this->web_url . "user/detail/" . $this->data["myInfo"]->userId . "'>$sender[name]</a></b></td></tr>
                    <tr><td><i>$designation</i></td></tr>
                    <tr><td>$dept</td></tr>
                    </table>
                    </td>
                    <td width='50%' valign='center' align='right'>
                    <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='" . $this->web_url . "evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
                    </td>
                    </tr>
                    <tr height='40'><td colspan='2'>has finally confirm the evalution form on $day at $time. and forwarded to admin for further verification.</td></tr>
                </tbody>
                <tfoot>
                    <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='" . $this->web_url . "'>EMS</a> &nbsp;</b></td></tr>
                </tfoot>
        	</table>";
            
            if ($this->mailer->sendMail($subject, $emailBody, $receiver, $sender)) {
                $this->data["message"] = "<span style='color: #68a358;'>Evaluation Successfully Confirmed</span>";
            } else {
                $this->data["message"] = "<span style='color:#C98383;'>Failed to Confirm Evaluation</span>";
            }
        }else{
            $this->data["message"] = "<span style='color:#C98383;'>Failed to Send Evaluation Email to Admin</span>";
        }

        $this->data["sub_title"] = "Evaluation Form";
        $this->view('evaluation_message', $this->data);
    }
    
    public function protoadmin($eid = ""){
        if(!$this->session->IsManager($this->myEmpId)) {
            $this->data["status_array"] = $this->status_array;
            $this->data["title"] = "ABC";
            $this->data["sub_title"] = "ABC";
            $this->data["message"] = "You have no privilege to access this page!";
            $this->load->view('not_found', $this->data);
            return;
        }
 
        $this->data["message"] = "";
        $insert_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : "";
        $data['status'] = 'D';
        $flag = $this->evaluation_model->updateEval($insert_id, $data);
        
        $receiver = array();
        $receiver[] = $this->user_model->getBriefInfo($eid);
        
        $sender = array();
        $sender['name'] = $this->data["myInfo"]->userName;
        $sender['email'] = $this->data["myInfo"]->email;
        
        if ($flag){
            // Send mail to Admin
            $designation = $this->data['myInfo']->userDesignation;
            $dept = $this->data['myInfo']->userDepartment;
            $time = date('h:i:s A');
            $day = date('l');
            $subject = "Evaluation Approve Request";
            $admin_ary = $this->session->GetAdminArray();
            $adminEmails = $this->user_model->getMailInfoByIds($admin_ary);
            $emailBody = "
            <table>
                <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
            	    <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
            	</thead>
            	<tbody>
            	    <tr>
            	        <td width='50%' valign='top' align='left'>
                	        <table cellpadding='3' cellspacing='0'>
                                <tr><td>".$this->myEmpId."</td></tr>
                                <tr><td><b>$sender[name]</b></td></tr>
                                <tr><td><i>$designation</i></td></tr>
                                <tr><td>$dept</td></tr>
                            </table>
            	        </td>
            	        <td width='50%' valign='center' align='right'>&nbsp;</td>
                    </tr>
                    <tr><td colspan='2'>&nbsp;</td></tr>
                    <tr>
                        <td colspan='2'>has been sent evaluation approve request of -</td>
                    </tr>
                    <tr><td colspan='2'>&nbsp;</td></tr>
                    <tr>
                        <td width='50%' valign='top' align='left'>
                            <table cellpadding='3' cellspacing='0'>
                    	        <tr><td>".$receiver[0]->emp_id."</td></tr>
                    	        <tr><td><b><a href='".$this->web_url."user/detail/".$receiver[0]->emp_id."'>".$receiver[0]->name."</a></b></td></tr>
                            	<tr><td><i>".$receiver[0]->designation."</i></td></tr>
                            	<tr><td>".$receiver[0]->dept_name."</td></tr>
                        	</table>
                        </td>
                        <td width='50%' valign='center' align='right'>
            	            <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='".$this->web_url."evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='".$this->web_url."'>EMS</a> &nbsp;</b></td></tr>
                </tfoot>
            </table>";            
            
            if($this->mailer->sendMail($subject, $emailBody, $adminEmails, $sender )) {
                $this->data["message"] = "<span style='color:#68a358;'>Evaluation Successfully Sent to Admin</span>";
            }else{
                $this->data["message"] = "<span style='color:#C98383;'>Failed to Send Evaluation Email to Admin</span>";
                $data['status'] = 'C';
                $flag = $this->evaluation_model->updateEval($insert_id, $data);
            }
            
            if ($data['status'] == 'D'){
                // Send mail to Employee
                $subject = "Evaluation form has been sent to admin";            
                $emailBody = "
                <table>
                    <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
                	    <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
                	</thead>
                	<tbody>
                	    <tr>
                	        <td width='50%' valign='top' align='left'>
                    	        <table cellpadding='3' cellspacing='0'>
                        	        <tr><td>".$receiver[0]->emp_id."</td></tr>
                        	        <tr><td><b><a href='".$this->web_url."user/detail/".$receiver[0]->emp_id."'>".$receiver[0]->name."</a></b></td></tr>
                                	<tr><td><i>".$receiver[0]->designation."</i></td></tr>
                                	<tr><td>".$receiver[0]->dept_name."</td></tr>
                            	</table>
                	        </td>
                	        <td width='50%' valign='center' align='right'>
                	            <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='".$this->web_url."evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
                	        </td>
                	    </tr>
                	    <tr>
                	        <td colspan='2'>Your evaluation form has been sent to admin to approve</td>
                	    </tr>
                	    <tr><td colspan='2'>&nbsp;</td></tr>
                	    <tr>
                	        <td colspan='2' style='color:#3c8dbc;'>By - </td>
                	    </tr>
                	    <tr><td colspan='2'>&nbsp;</td></tr>
                	    <tr>
                	        <td width='50%' valign='top' align='left'>
                    	        <table cellpadding='3' cellspacing='0'>
                        	        <tr><td>".$this->myEmpId."</td></tr>
                        	        <tr><td><b>$sender[name]</b></td></tr>
                                	<tr><td><i>$designation</i></td></tr>
                                	<tr><td>$dept</td></tr>
                            	</table>
                	        </td>
                	        <td width='50%' valign='center' align='right'>&nbsp;</td>
                	    </tr>
                	</tbody>
        	        <tfoot>
        	            <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='".$this->web_url."'>EMS</a> &nbsp;</b></td></tr>
        	        </tfoot>
                </table>";
                
                if($this->mailer->sendMail($subject, $emailBody, $receiver, $sender )) {
                    ;
                }
            }            
        }
        
	    $this->data["sub_title"] = "Evaluation Form";
	    $this->view('evaluation_message', $this->data);
    }
    
    public function approve($eid = ""){
	    if(!$this->data['isAdmin']) {
            $this->data["status_array"] = $this->status_array;
            $this->data["title"] = "ABC";
            $this->data["sub_title"] = "ABC";
            $this->data["message"] = "You have no privilege to access this page!";
            $this->load->view('not_found', $this->data);
            return;
	    }

        $insert_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : "";
    
        $ary = array(
            'admin_comments',
            'admin_sig_date'
        );
        foreach ($ary as $val) {
            $data[$val] = isset($_POST[$val]) ? $_POST[$val] : '';
        }
        $data['admin_id'] = $this->myEmpId;
        $data['status'] = 'E';
    
        $ses = unserialize(urldecode($_SESSION["evaluation"]));
        $ses = array_merge($ses, $data);
        $_SESSION["evaluation"] = urlencode(serialize($ses));
    
        // update to database
        $flag = $this->evaluation_model->updateEval($insert_id, $data);
    
        // sent mail to Employee
        $receiver = array();
        $receiver[] = $this->user_model->getBriefInfo($ses['manager_id']);
        $receiver[] = $this->user_model->getBriefInfo($eid);
    
        $sender = array();
        $sender['name'] = $this->data["myInfo"]->userName;
        $sender['email'] = $this->data["myInfo"]->email;
        $subject = "Evaluation approved by admin";
    
        $designation = $this->data['myInfo']->userDesignation;
        $dept = $this->data['myInfo']->userDepartment;
        $time = date('h:i:s A');
        $day = date('l');
        $emailBody = "<table>
        <thead style='width:100%; color:white;' bgcolor= '#3C8DBC' >
        <tr><th colspan=2>".COMPANY_PREFIX." Staff</th></tr>
        </thead>
        <tbody>
        <tr>
        <td width='50%' valign='top' align='left'>
        <table cellpadding='3' cellspacing='0'>
        <tr><td>" . $this->myEmpId . "</td></tr>
        <tr><td><b><a href='" . $this->web_url . "user/detail/" . $this->data["myInfo"]->userId . "'>$sender[name]</a></b></td></tr>
	        <tr><td><i>$designation</i></td></tr>
	        <tr><td>$dept</td></tr>
	        </table>
	        </td>
	        <td width='50%' valign='center' align='right'>
	        <a style='font: bold 15px; text-decoration: none; background-color: #798B9F; color: black; padding: 10px 30px; border: 1px solid #333333;' href='" . $this->web_url . "evaluation/form/$eid/1/$insert_id'>Show Evaluation Form</a>
	        </td>
	        </tr>
	        <tr height='40'><td colspan='2'>has approved the evalution form on $day at $time. and given <br><b>Comments:</b>$data[admin_comments]</td></tr>
	        </tbody>
	        <tfoot>
	        <tr><td colspan='2' align='right' style='border-top:1px solid #D5D1B6; color:white;' bgcolor='#3C8DBC'>Powered by - <a href='" . $this->web_url . "'>EMS</a> &nbsp;</b></td></tr>
	        </tfoot>
    	</table>";

        if ($this->mailer->sendMail($subject, $emailBody, $receiver, $sender)) {
            $this->data["message"] = "<span style='color:#68a358;'>Evaluation Approved by Admin</span>";
        } else {
            $this->data["message"] = "<span style='color:#C98383;'>Failed to Send Evaluation Approved Email to Staff</span>";
        }

        $this->data["sub_title"] = "Evaluation Form";
        $this->view('evaluation_message', $this->data);
    }
    
    public function details($empId=''){
        $this->isLoggedIn();
//print_r($_SESSION);
        $isSelf = false;        
        $isManager = false;
        $isAdmin = false;
        $isManagement = false;
        
        if ($this->session->IsManagement($this->myEmpId)) {
           $isManagement = true;
        } elseif($this->session->IsAdmin($this->myEmpId)) {
           $isAdmin = true;
        } elseif($this->session->IsManager($this->myEmpId)) {
           $isManager = true;
        } elseif ($this->myEmpId == $empId) {
           $isSelf = true;
        }
        
        if(!$isSelf && !$isManager && !$isAdmin && !$isManagement) {           
            $this->data["status_array"] = $this->status_array;
            $this->data["title"] = "Evaluations";
            $this->data["sub_title"] = "Access Denied";
            $this->data["message"] = "You have no privilege to access this page!";
            $this->load->view('not_found', $this->data);
            return;
        }
        
        if(empty($empId)) $empId = $this->myEmpId;
        $user = $this->user_model->detail($empId);
        $dept_code = $this->evaluation_model->getDeptCode($this->myEmpId);
        
        if ($isManager && $user['dept_code'] != $dept_code){
            $this->data["status_array"] = $this->status_array;
            $this->data["title"] = "Evaluations";
            $this->data["sub_title"] = "Access Denied";
            $this->data["message"] = "You have no privilege to access this page!";
            $this->load->view('not_found', $this->data);
            return;
        }

        if(empty($user)) echo "not found";

        $this->data['status_array'] =  $this->status_array;
        $this->data['ArchiveV'] = $user['archive'];
        
        $this->data['id'] = $user['id'];
        $this->data['emp_id'] = $user['emp_id'];
        $this->data['name'] = $user['name'];
        $this->data['grade'] = $user['grade'];
        $this->data['desig'] = $user['designation'];
        $this->data['dept_code'] = $user['dept_code'];
        $this->data['dept'] = $user['dept_name'];
        $this->data['jdate'] = $user['jdate'];
        $this->data['status'] = $user['status'];
        $this->data['sUser'] = $this->myEmpId;
        
        $this->data['dob'] = $user['dob'];
        $this->data['gen'] = $user['gender'];
        $this->data['resign_date'] = $user['resignation_date'];
        $this->data['image'] = $user['image'];
        $this->data['image_path'] = $this->getImagePath($user['image']);
        $this->data["title"] = $this->data['dept_code'];
        
        $this->data["evResult"] = $this->evaluation_model->get_user_eval($empId, $this->myEmpId);
        $manager = $this->session->getManagersByDeptCode($this->data['dept_code']);
        $this->data['isManager'] = in_array($this->myEmpId, $manager);
        $this->data['isAdmin'] = $this->session->IsAdmin($this->myEmpId);

        $this->data["sub_title"] = "Evaluations";
        $this->view('evaluation_detail', $this->data);
    }
    
    public function download($file_name = ''){
         
        //echo base_url()."assets/files/".$file_name;
         
        $headers = get_headers(base_url()."assets/files/".$file_name);
        $response_code = substr($headers[0], 9, 3);
    
        if( $response_code == "200"){
            //success
            $this->load->helper('download');
            $data = file_get_contents("./assets/files/".$file_name);
            //echo $data;die;
    
            force_download($file_name, $data);
    
        }else{
            echo "ERROR: File not Found!";
        }
    }
    
    public function getImagePath($image){    
        if(empty($image)) $image = "no_picture.gif";
        $imageURL = $this->web_url_main."assets/pictures/".$image;
         
        $headers = get_headers($imageURL);
        $response_code = substr($headers[0], 9, 3);
         
        if ($response_code != "200" ) {
            $imageURL = "";
            $imageSubPath = rtrim(base_url(), "/");
            $imgSubArr = explode("/", $imageSubPath);
            $loopLimit = count($imgSubArr);
            if ($loopLimit > 0){
                $imageSubPath = "";
                for ($i=0; $i<($loopLimit-1); $i++){
                    $imageSubPath .= $imgSubArr[$i] . "/";
                }
                $imageURL = $imageSubPath."pictures/".$image;
            }
            if (!empty($imageURL)){
                $headers = get_headers($imageURL);
                $response_code = substr($headers[0], 9, 3);
                if ($response_code != "200" ) {
                    $imageURL = $this->web_url_main."assets/pictures/no_picture.gif";
                }
            }else {
                $imageURL = $this->web_url_main."assets/pictures/no_picture.gif";
            }
        }
         
        return $imageURL;
    }
}