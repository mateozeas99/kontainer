<?php 
class Pacient
{
	private $_db=null,$_data;

	public function __construct($pacient=null)
	{
		$this->_db = DB::getInstance();

		if ($pacient) {
			$this->findById($pacient);
		}
	}

	public function create($fields)
	{

		if ($this->_db->insert('TPERSONA',$fields)) 
		{
			throw new Exception("Error Processing Request", 1);
		}
	}

	public function findById($pacient = null)
	{
		if ($pacient) {
			
			$data = $this->_db->get('TPERSONA',array('ID', '=', $pacient));

			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
			
		}
		return false;
		
	}
	public function findByCell($cell=null)
	{
		if ($cell) {
			$data = $this->_db->get('TPERSONA',array('CELL', '=', $cell));
			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function update($fields = array(),$id = null)
	{
		if ($id) {
			if ($this->_db->update('TPERSONA',$id,$fields)) {
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