<?php
require_once("Rest.inc.php");
require_once 'core/init.php';
class API extends REST {

	public $data = "";
	public function __construct(){
		parent::__construct();				// Init parent contructor
	}
	/*
	 * Public method for access api.
	 * This method dynmically call the method based on the query string
	 *
	 */
	public function processApi(){
		$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
		if((int)method_exists($this,$func) > 0)
			$this->$func();
		else
			$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
	}
	/*
	 *	API SINAPTICA
	 *
	 *
	 *
	 */
	private function login(){
		// Cross validation if the request method is POST else it will return "Not Acceptable" status
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}
		$jsonUser =json_decode($this->get_json_post());
		$user = new User();
		$remember = false;
		if($jsonUser->remember == 'on')
			$remember = true;
		$login = $user->login($jsonUser->username,$jsonUser->password,$remember);
		if($login)
		{
			// If success everythig is good send header as "OK" and user details
			$this->response($this->json($login), 200);
		}
		else
		{
			$error = array('code' => '666','msg' => 'Usuario o contraseña inválidos');
			$this->response($this->json($error), 204);
		}
		// If invalid inputs "Bad Request" status message and reason
		$error = array('code' => "666", "msg" => "Invalid Email address or Password");
		$this->response($this->json($error), 400);
	}
	private function users(){
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();

		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$username = $this->_request['username'];
			if($username==null)
			{
				$sql="SELECT TU.USERNAME, TU.NAME, TU.LAST, TU.EMAIL, TG.NAME AS 'GROUP' FROM TUSERS TU, TGROUP TG WHERE TU.GROUP=TG.ID";
				$users = DB::getInstance()->query($sql,array());
				if($users->count())
				{
					$results = $users->results();
					foreach ($results as $rlt) {
						$result[] = $rlt;
					}
					// If success everythig is good send header as "OK" and return list of users in JSON format
					$this->response($this->json($result), 200);
					//$this->response($user->data()->API_KEY, 200);
				}
				else
				{
					$error = array('code' => "666", "msg" => "No rows");
					$this->response($this->json($error), 204);	// If no records "No Content" status
				}
			}
			else
			{
				$sql="SELECT TU.USERNAME, TU.NAME, TU.LAST, TU.EMAIL, TG.NAME AS 'GROUP' FROM TUSERS TU, TGROUP TG WHERE TU.GROUP=TG.ID AND TU.USERNAME=?";
				//$sql="SELECT USERNAME, NAME, LAST, EMAIL FROM TUSERS WHERE USERNAME=?";
				$users = DB::getInstance()->query($sql,array($username));
				if($users->count())
				{
					$results = $users->results();
					foreach ($results as $rlt) {
						$result[] = $rlt;
					}
					// If success everythig is good send header as "OK" and return list of users in JSON format
					$this->response($this->json($result), 200);
				}
				else
				{
					$error = array('code' => "666", "msg" => "No rows");
					$this->response($this->json($error), 204);	// If no records "No Content" status
				}
			}
		}
		else
		{
			$this->response('',401);	// If authori
		}

	}
	private function deleteUser(){
		// Cross validation if the request method is DELETE else it will return "Not Acceptable" status
		if($this->get_request_method() != "DELETE"){
			$this->response('',406);
		}
		$id = (int)$this->_request['id'];
		$this->response($id,200);	// If no records "No Content" status
	}
	private function hasPermission()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();

		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$username = $this->_request['username'];
			if($username==null)
			{
				$error = array('code' => "666", "msg" => "No username");
				$this->response($this->json($error), 204);	// If no records "No Content" status
			}
			else
			{
				$sql = "SELECT PERMISSION FROM TGROUP TG, TUSERS TU WHERE TG.ID=TU.GROUP AND TU.USERNAME=?";
				$permission=DB::getInstance()->query($sql,array($username));
				$result[]=$permission->first()->PERMISSION;
				$this->response($this->json($result), 200);
			}
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	private function createUser()
	{
		// Cross validation if the request method is POST else it will return "Not Acceptable" status
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{

			$jsonUser =json_decode($this->get_json_post());
			$this->response($jsonUser->password,200);

		}
		else
		{
			$this->response('',401);	// If authori
		}
	}

	private function modules()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			//QUERY TO GET ALL THE MODULES AND THE DEVICES
			$devices = array();
			$query = 'SELECT * FROM TMODULES';
			$params = array();
			$id = $this->_request['id'];
			if($id!=null){
				$query.=' WHERE ID=?';
				array_push($params, $id);
			}
			$resp = DB::getInstance()->query($query,$params);
			if($resp->count())
			{
				$modules=$resp->results();
				foreach($modules as $module){
					$tags=explode(",", $module->TAGS);
					$types=explode(",", $module->TYPE_LO);
					for($i=0;$i<count($tags)-1;$i++){
						$status=0;
						$status_query='SELECT VALUE2 VALUE, MAX(DATE_LOG) DATE_LOG FROM TLOG WHERE DEVICE='.$module->DIRECTION.' AND VALUE='.($i+1).' GROUP BY VALUETWO ORDER BY DATE_LOG DESC LIMIT 1';
						$status_resp = DB::getInstance()->query($status_query,array());
						if($status_resp->count()){
							$status=$status_resp->first()->VALUE;
						}
						array_push($devices, array('ID'=>$module->ID, 'DIRECTION'=>$module->DIRECTION, 'TAG'=>$tags[$i], 'TYPE'=>$module->TYPE, 'MIN'=>$module->MIN, 'MAX'=>$module->MAX, 'STATUS'=>$status));
					}
				}
			}
			$this->response($this->json($devices),200);	
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	
	private function events()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$events = array();
			$query = 'SELECT * FROM TDEVENTS';
			$params = array();
			$id = $this->_request['id'];
			if($id!=null){
				$query.=' WHERE ID=?';
				array_push($params, $id);
			}
			$resp = DB::getInstance()->query($query, $params);
			if($resp->count()){
				$results=$resp->results();
				foreach($results as $event){
					$actions = explode(";", $event->ACTIONS);
					
					$hours_query = 'SELECT DATE_FORMAT(HOUR, \'%H:%i\') HOUR FROM THOUREVENTS WHERE EVENT=?';
					$hours_resp = DB::getInstance()->query($hours_query,array($event->ID));
					$hours = array();
					if($hours_resp->count()){
						$hours_results=$hours_resp->results();
						foreach($hours_results as $hour){
							array_push($hours, $hour->HOUR);
						}
					}
					$days_query = 'SELECT DAY from TDAYSEVENTS WHERE EVENT=?';
					$days_resp = DB::getInstance()->query($days_query,array($event->ID));
					$days = array();
					if($days_resp->count()){
						$days_results=$days_resp->results();
						foreach($days_results as $day){
							array_push($days, $day->DAY);
						}
					}
					array_push($events, array('id'=>$event->ID,'name'=>$event->NAME,'actions'=>$actions, 'hours'=>$hours, 'days'=>$days));
				}
			}
			$this->response($this->json($events),200);	
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	
	private function createEvent()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$json =json_decode($this->get_json_post());
			$id = $json->id;
			if($id!=null){
				$query = 'UPDATE TDEVENTS set NAME=?, ACTIONS=? WHERE ID=?';
				$params = array($json->name, $json->actions, $id);
				DB::getInstance()->query($query, $params);
			}else{
				$query = 'INSERT INTO TDEVENTS (NAME, ACTIONS) values (?,?)';
				$params = array($json->name, $json->actions);
				DB::getInstance()->query($query, $params);
			}
			$this->response('',200);	
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	
	private function deleteEvent()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$id = $this->_request['id'];
			if($id!=null){
				$query = 'DELETE FROM TDEVENTS WHERE ID=?';
				DB::getInstance()->query($query, array($id));
				$query = 'DELETE FROM THOUREVENTS WHERE ID=?';
				DB::getInstance()->query($query, array($id));
				$query = 'DELETE FROM TDAYSEVENTS WHERE ID=?';
				DB::getInstance()->query($query, array($id));
			}
			$this->response('',200);	
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	
	private function execEvent()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$id = $this->_request['id'];
			if($id!=null){
				$query = 'SELECT * FROM TDEVENTS WHERE ID=?';
				$resp=DB::getInstance()->query($query, array($id));
				if($resp->count()){
					$event = $resp->first();
					$actions = explode(';', $event->ACTIONS);
					$query='SELECT ID FROM TDEVICES WHERE MAC=\''.$_SERVER['REMOTE_ADDR'].'\'';
					$resp = DB::getInstance()->query($query,array());
					if($resp->count()){
						$ipId = $resp->first()->ID;
					}else{
						$ipId = '99';
					}
					$command = '';
					for($i=0;$i<count($actions)-1;$i++){
						$command .= $actions[$i].' 1 '.$ipId.';';
					}
					$file = fopen("../../soap/status.txt", "a") or die("Unable to open file!");
					fwrite($file,$command);
					fclose($file);
				}
			}	
			$this->response($this->json(array('executed',$command)),200);
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	
	private function sensors()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$sensors = array();
			$query = 'SELECT LS.SENSOR FROM TLOGSENSORS LS ORDER BY LS.DATE_LOG_SENSOR DESC LIMIT 1';
			$params = array();
			$resp = DB::getInstance()->query($query, $params);
			if($resp->count()){
				$results=$resp->results();
				foreach($results as $sensor){
					array_push($sensors, array('value'=>$sensor->SENSOR));
				}
			}
			$this->response($this->json($sensors),200);	
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	
	private function cameras()
	{
		// Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			$cameras = array();
			$query = 'SELECT * FROM TCAMERA';
			$params = array();
			$resp = DB::getInstance()->query($query, $params);
			if($resp->count()){
				$results=$resp->results();
				foreach($results as $camera){
					array_push($cameras, array('id'=>$camera->ID, 'tag'=>$camera->TAG, 'ip'=>$camera->IP, 'port'=>$camera->PORT, 'user'=>$camera->USERNAME, 'password'=>$camera->PASSWORD));
				}
			}
			$this->response($this->json($cameras),200);	
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	private function status(){
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if($this->get_request_method() != "GET"){
            $this->response('',406);
        }
        $userAuth = $this->get_auth_user();
        $userPwd = $this->get_auth_pw();
        $user = new User($userAuth);
        if($userPwd===$user->data()->API_KEY)
        {
    		$modules_query='SELECT DIRECTION FROM TMODULES';
            $params = array();
            $resp = DB::getInstance()->query($modules_query, $params);
            if($resp->count()){
                    $results=$resp->results();
                    $query='';
                    $first=true;
                    foreach ($results as $result) {
                        if(!$first){
                            $query = $query . ' union all ';
                        }
                        	$query = $query . 'SELECT M.DIRECTION, M.TYPE, L.VALUE, L.VALUETWO, L.VALUETREE, L.VALUEFOUR FROM TLOG L, TMODULES M WHERE L.DEVICE=M.DIRECTION AND L.DEVICE='.$result->DIRECTION.' AND L.DATE_LOG = (SELECT MAX(DATE_LOG) FROM TLOG WHERE DEVICE='.$result->DIRECTION.')';
                        	$first=false;
                    }
            }
            $resp = DB::getInstance()->query($query, $params);
            if($resp->count())
            {
                    $results=$resp->results();
                    $devices = array();
                    foreach ($results as $result) {
                            array_push($devices, array('address'=>$result->DIRECTION, 'magnetic'=>$result->VALUE, 'movement'=>$result->VALUETWO, 'extra'=>$result->VALUETREE, 'status'=>$result->VALUEFOUR));
                    }
            }
            $this->response($this->json($devices),200);
            //$this->response($this->json(array('query'=>$query)),200);
        }
        else
        {
        	$this->response('',401); 
        }
    }
	private function send()
	{
		// Cross validation if the request method is POST else it will return "Not Acceptable" status
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		//if(true)
		{
			//EXAMPLE {"id":"4","device":"1","value":"50","extra":{"value":"0","value1":"0"}}
			$jsonSend =json_decode($this->get_json_post());
			$query='SELECT ID FROM TDEVICES WHERE MAC=\''.$_SERVER['REMOTE_ADDR'].'\'';
			
			$resp = DB::getInstance()->query($query,array());
			if($resp->count())
			{
				$ipId = $resp->first()->id;
			}
			else
			{
				$ipId='99';
			}
			
			$command = $jsonSend->id.' '.$jsonSend->device.' '.$jsonSend->value.' 1 '.$ipId;
			$file = fopen("../../soap/status.txt", "a") or die("Unable to open file!");
			fwrite($file,$command.';');
			fclose($file);
			//$this->response($query,200);	
			$this->response($this->get_json_post(),200);
		}
		else
		{
			$this->response('',401);	// If authori
		}
	}
	private function alarm()
	{
		// Cross validation if the request method is POST else it will return "Not Acceptable" status
		if($this->get_request_method() != "POST"){
			$this->response('',406);
		}
		$userAuth = $this->get_auth_user();
		$userPwd = $this->get_auth_pw();
		$user = new User($userAuth);
		if($userPwd===$user->data()->API_KEY)
		{
			//EXAMPLE {"id":"41","command":"98","password":"123456"}
			$jsonSend =json_decode($this->get_json_post());
			if($jsonSend->command==98 || $jsonSend->command==99)
			{
				$command = $jsonSend->id.' '.$jsonSend->command.' 1 1 1';
				$file = fopen("../../soap/status.txt", "a") or die("Unable to open file!");
				fwrite($file,$command.';');
				fclose($file);
				$result = array('code' => "98",'status' => "0");
				$this->response($this->json($result), 200);
			}
			else if($jsonSend->command==97)
			{
				$query = 'SELECT * FROM TALARM WHERE ID_DEVICE=? AND PASSWORD=?';
				$params = array($jsonSend->id,$jsonSend->password);
				$resp = DB::getInstance()->query($query, $params);
				if($resp->count()){
					$command = $jsonSend->id.' '.$jsonSend->command.' 1 1 1';
					$file = fopen("../../soap/status.txt", "a") or die("Unable to open file!");
					fwrite($file,$command.';');
					fclose($file);
					$result = array('code' => "97",'status' => "0");
					$this->response($this->json($result), 200);
				}
				else
				{
					$this->response($this->get_json_post(),204);
				}
			}
			else if($jsonSend->command==90)
			{
				//CONTROL ALARM

				$result = array('code' => '90','status' => '1', 'magnetics' => array('magRadio1'=>'0','magRadio2'=>'1','magRadio3'=>'0','magRadio4'=>'0','magRadio5'=>'0','magRadio6'=>'0','magRadio7'=>'0'));
				$this->response($this->json($result), 200);
			}

			
			//$this->response($query,200);	
			
		}
	}
	/*
	 *	Encode array into JSON
	*/
	private function json($data){
		if(is_array($data)){
			return json_encode($data);
		}
	}
}

// Initiiate Library

$api = new API;
$api->processApi();
?>
