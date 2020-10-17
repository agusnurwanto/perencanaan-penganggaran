<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * XHR > Notification
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Notification extends Aksara
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		return make_json
		(
			array
			(
				'ssh'								=> $this->_ssh(),
				'rka'								=> $this->_rka(),
				'kak'								=> $this->_kak()
			)
		);
	}
	
	private function _ssh()
	{
		$query										= $this->model->select
		('
			count(id) AS total
		')
		->get_where
		(
			'ref__standar_harga',
			array
			(
				'approve'							=> 0
			)
		)
		->row('total');
		
		return $query;
	}
	
	private function _rka()
	{
		$query										= $this->model->select
		('
			count(ta__asistensi.id) as total
		')
		->group_by('id_keg_sub')
		->where
		(
			array
			(
				'ta__asistensi.comments !='			=> ''
			)
		)
		->count_all_results('ta__asistensi');
		
		return $query;
	}
	
	private function _kak()
	{
		$query										= $this->model->select
		('
			count(ta__asistensi_kak.id) AS total
		')
		->group_by('id_keg_sub')
		->where
		(
			array
			(
				'ta__asistensi_kak.comments !='		=> ''
			)
		)
		->count_all_results('ta__asistensi_kak');
		
		return $query;
	}
}
