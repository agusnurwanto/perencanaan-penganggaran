<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rencana_keuangan extends Aksara
{
	private $_table									= 'ta__rencana';
	private $_id_sub								= null;
	private $_id_keg								= null;
	private $_id_bel								= null;
	
	function __construct()
	{
		parent::__construct();
		if(!$this->input->get('id_belanja'))
		{
			return throw_exception(301, phrase('silakan_memilih_rekening_terlebih_dahulu'), go_to('../rekening'));
		}
		$this->_primary								= $this->input->get('id_belanja');
		$this->set_permission();
		$this->set_theme('backend');
		$this->insert_on_update_fail();
		$this->parent_module('anggaran/rekening');
		
	}
	
	public function index()
	{
		if(null != $this->input->post('token'))
		{
			$this->_validate_form();
		}
		else
		{
			$this
			->set_method('update')
			->set_primary('id_belanja')
			->set_title(phrase('rencana_keuangan'))
			->set_icon('fa fa-stackoverflow')
			->unset_action('create, read')
			->unset_field('id_belanja')
			->set_field
			(
				array
				(
					'jan'							=> 'price_format',
					'feb'							=> 'price_format',
					'mar'							=> 'price_format',
					'apr'							=> 'price_format',
					'mei'							=> 'price_format',
					'jun'							=> 'price_format',
					'jul'							=> 'price_format',
					'agt'							=> 'price_format',
					'sep'							=> 'price_format',
					'okt'							=> 'price_format',
					'nop'							=> 'price_format',
					'des'							=> 'price_format',
				)
			)
			->add_class
			(
				array
				(
					'jan'							=> 'sum_input',
					'feb'							=> 'sum_input',
					'mar'							=> 'sum_input',
					'apr'							=> 'sum_input',
					'mei'							=> 'sum_input',
					'jun'							=> 'sum_input',
					'jul'							=> 'sum_input',
					'agt'							=> 'sum_input',
					'sep'							=> 'sum_input',
					'okt'							=> 'sum_input',
					'nop'							=> 'sum_input',
					'des'							=> 'sum_input',
				)
			)
			->set_validation
			(
				array
				(
					'jan'							=> 'numeric',
					'feb'							=> 'numeric',
					'mar'							=> 'numeric',
					'apr'							=> 'numeric',
					'mei'							=> 'numeric',
					'jun'							=> 'numeric',
					'jul'							=> 'numeric',
					'agt'							=> 'numeric',
					'sep'							=> 'numeric',
					'okt'							=> 'numeric',
					'nop'							=> 'numeric',
					'des'							=> 'numeric',
				)
			)
		//	->set_template('form', 'form')
			//->set_default('id_belanja', $this->_primary)
			->set_output
			(
				array
				(
					'total'							=> $this->_total()
				)
			)
			->where('id_belanja', $this->_primary)
			->limit(1)
			->modal_size('modal-lg')
			->render($this->_table);
		}
	}
	
	private function _total()
	{
		return $this->model
		->select_sum('total')
		->join('ta__belanja_sub', 'ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub', 'INNER')
		->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja', 'INNER')
		->get_where('ta__belanja_rinci', array('ta__belanja.id' => $this->_primary))
		->row('total');
	}
	
	private function _validate_form()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('jan', phrase('keuangan_januari'), 'callback_validate_input');
		
		if($this->form_validation->run() === FALSE)
		{
			return throw_exception(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		else
		{
			$prepare								= array
			(
				'id_belanja'						=> $this->_primary,
				'jan'								=> str_replace(',', '', $this->input->post('jan')),
				'feb'								=> str_replace(',', '', $this->input->post('feb')),
				'mar'								=> str_replace(',', '', $this->input->post('mar')),
				'apr'								=> str_replace(',', '', $this->input->post('apr')),
				'mei'								=> str_replace(',', '', $this->input->post('mei')),
				'jun'								=> str_replace(',', '', $this->input->post('jun')),
				'jul'								=> str_replace(',', '', $this->input->post('jul')),
				'agt'								=> str_replace(',', '', $this->input->post('agt')),
				'sep'								=> str_replace(',', '', $this->input->post('sep')),
				'okt'								=> str_replace(',', '', $this->input->post('okt')),
				'nop'								=> str_replace(',', '', $this->input->post('nop')),
				'des'								=> str_replace(',', '', $this->input->post('des'))
			);
			$checker								= $this->db->select('id')->where('id_belanja', $this->_primary)->limit(1)->get($this->_table)->num_rows();
			if($checker > 0)
			{
				$execute							= $this->model->update($this->_table, $prepare, array('id_belanja' => $this->_primary));
			}
			else
			{
				$execute							= $this->model->insert($this->_table, $prepare);
			}
			if($execute)
			{
				$this->session->set_flashdata('success', phrase('data_was_successfully_updated'));
				return throw_exception(301, phrase('data_was_successfully_submitted'), go_to());
			}
			else
			{
				return throw_exception(500, phrase('something_went_wrong_while_submitting_your_data'));
			}
		}
	}
	
	public function validate_input()
	{
		$current_total								= $this->model
		->select_sum('ta__belanja_rinci.total', 'total_rekening')
		->join('ta__belanja_sub', 'ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub', 'INNER')
		->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja', 'INNER')
		->get_where('ta__belanja_rinci', array('ta__belanja.id' => $this->_primary))
		->row('total_rekening');
		$new_total									= str_replace(',', '', $this->input->post('jan')) + str_replace(',', '', $this->input->post('feb')) + str_replace(',', '', $this->input->post('mar')) + str_replace(',', '', $this->input->post('apr')) + str_replace(',', '', $this->input->post('mei')) + str_replace(',', '', $this->input->post('jun')) + str_replace(',', '', $this->input->post('jul')) + str_replace(',', '', $this->input->post('agt')) + str_replace(',', '', $this->input->post('sep')) + str_replace(',', '', $this->input->post('okt')) + str_replace(',', '', $this->input->post('nop')) + str_replace(',', '', $this->input->post('des'));
		if($new_total > $current_total)
		{
			$this->form_validation->set_message('validate_input', 'Nilai total pada keseluruhan kolom tidak boleh melebihi nilai per rekening. Maksimal total nilai adalah <b>Rp. ' . number_format($current_total) . '</b>.<br /><br />Jika Anda membagi rata nilai rekening pada kolom, silakan untuk mengubah nilai beberapa kolom sehingga jumlah keseluruhan tidak melebihi <b>Rp. ' . number_format($current_total) . '</b>.');
			return false;
		}
		return true;
	}
}