<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Lock_kegiatan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		
		$this->set_theme('backend');
		$this->set_permission();
		$this->permission->must_ajax();
		
		$this->set_method('index');
		
		$this->_primary								= $this->input->get('id');
	}
	
	public function index()
	{
		$query										= $this->model->select('lock_kegiatan')->get_where('ta__kegiatan', array('id' => $this->_primary), 1)->row('lock_kegiatan');
		if(1 == $query)
		{
			if($this->model->update('ta__kegiatan', array('lock_kegiatan' => 0), array('id' => $this->_primary), 1))
			{
				$button								= 'btn-success';
				$icon								= 'mdi-lock';
				$label								= 'Lock';
			}
		}
		else
		{
			if($this->model->update('ta__kegiatan', array('lock_kegiatan' => 1), array('id' => $this->_primary), 1))
			{
				$button								= 'btn-outline-success';
				$icon								= 'mdi-lock-open';
				$label								= 'Unlock';
			}
		}
		
		return make_json
		(
			array
			(
				'button'							=> $button,
				'icon'								=> $icon,
				'label'								=> $label
			)
		);
	}
}