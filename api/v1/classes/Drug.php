<?php
class Drug
{
	private $_db=null,$_data;

	public function __construct($drug=null)
	{
		$this->_db = DB::getInstance();

		if ($drug) {
			$this->findById($drug);
		}
	}
	public function findById($drug = null)
	{
		if ($drug) {
			
			$data = $this->_db->get('TMEDICAMENTO',array('ID', '=', $drug));

			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
			
		}
		return false;
		
	}
	public function create($fields)
	{

		if ($this->_db->insert('TMEDICAMENTO',$fields)) 
		{
			throw new Exception("Error Processing Request", 1);
		}
	}
	public function update($fields = array(),$id = null)
	{
		if ($id) {
			if ($this->_db->update('TMEDICAMENTO',$id,$fields)) {
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