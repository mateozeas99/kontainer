<?php
class User
{
	private $_db,
	$_data,
	$_sessionName,
	$_cookieName,
	$_isLoggedIn;

	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if (!$user) {
			if (Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);

				if ($this->find($user)) {
					$this->_isLoggedIn = true;
				}
				else
				{
					//Process Log out
				}
			}
		}
		else
		{
			$this->find($user);
		}
	}

	public function create($fields)
	{
		if ($this->_db->insert('TUSERS',$fields)) {
			throw new Exception("Error Processing Request", 1);
		}
	}

	public function find($user = null)
	{
		if ($user) {
			//Validar que username sea alfanumerico
			$field = (is_numeric($user))? 'ID' : 'USERNAME';
			
			$data = $this->_db->get('TUSERS',array($field, '=', $user));

			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
			
		}
		return false;
	}

	public function login($username = null,$password =null, $remember= false)
	{
		if (!$username && !$password && $this->exists()) {
				//Log user in
				Session::put($this->_sessionName,$this->data()->ID);
		}
		else
		{
			$user = $this->find($username);
			if ($user) {
				if($this->data()->PASSWORD === Hash::make($password,$this->data()->SALT))
				{
					Session::put($this->_sessionName, $this->data()->ID);
					if ($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('TUSERS_SESSIONS',array('USER_ID','=',$this->data()->ID));

						if (!$hashCheck->count()) {
							$this->_db->insert('TUSERS_SESSIONS',array(
								'user_id' => $this->data()->ID,
								'hash' => $hash
								));
						}
						else
						{
							$hash = $hashCheck->first()->HASH;
						}
					}
					$data = array("id"=>$this->data()->ID,"api_key"=>$this->data()->API_KEY,"hash"=>$hash);
					return $data;
				}
			}
			return false;
		}
	}

	public function data()
	{
		return $this->_data;
	}
	public function exists()
	{
		return (!empty($this->_data)) ? true : false;
	}
	public function logout()
	{
		$this->_db->delete('TUSERS_SESSIONS',array('USER_ID','=',$this->data()->ID));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function isLoggedIn()
	{
		return $this->_isLoggedIn;
	}

	public function update($fields = array(),$id = null)
	{
		if (!$id && $this->isLoggedIn()) {
			$id = $this->data()->ID;
		}

		if ($this->_db->update('TUSERS',$id,$fields)) {
			throw new Exception("Error Processing Request", 1);
			
		}
	}

	public function hasPermission($key)
	{
		
		$group = $this->_db->get('TGROUP',array('ID','=',$this->data()->GROUP));
		if ($group->count()) {
			$permissions = json_decode($group->first()->PERMISSION,true);
			
			if ($permissions[$key] ==true) {
				return true;
			}
		}
		return false;
	}

	public function hasSpeciality($key)
	{
		$sqlDoc = "SELECT SPECIALITY_ID FROM TDOCTORS WHERE USER_ID=?";
		$queryDoc = $this->_db->query($sqlDoc,array($this->data()->ID));

		if ($queryDoc->count()) {
			$specialityId = $queryDoc->first()->SPECIALITY_ID;
		}
		$sql = "SELECT NAME FROM TSPECIALITY WHERE ID=?";
		
		$query = $this->_db->query($sql,array($specialityId));

		if ($query->count()) {
			if($query->first()->NAME==$key)
			{
				return true;
			}
		}
		
		return false;
	}
}
?>