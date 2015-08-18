<?php
class Disease
{
	private $_db=null,$_data;

	public function __construct($disease=null)
	{
		$this->_db = DB::getInstance();

		if ($disease) {
			$this->findById($disease);
		}
	}
	public function findById($disease = null)
	{
		if ($disease) {
			
			$data = $this->_db->get('TDISEASE',array('ID', '=', $disease));

			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
			
		}
		return false;
		
	}
	public function create($fields)
	{
		
		if ($this->_db->insert('TDISEASE',$fields)) 
		{
			throw new Exception("Error Processing Request", 1);
		}
	}
	public function update($fields = array(),$id = null)
	{
		if ($id) {
			if ($this->_db->update('TDISEASE',$id,$fields)) {
			throw new Exception("Error Processing Request", 1);
			
			}	
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
}
?>
