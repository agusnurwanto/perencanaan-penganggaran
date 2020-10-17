<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kecamatan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_id_kec								= (2 == get_userdata('group_id') ? get_userdata('sub_unit') : ($this->input->get('id_kec') ? $this->input->get('id_kec') : 'all'));
		if(!in_array(get_userdata('group_id'), array(1, 2)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('musrenbang'));
		}
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		if(1 == get_userdata('group_id'))
		{
			$this->add_filter($this->_filter());
		}
		if(1 == get_userdata('group_id') && 'all' != $this->_id_kec)
		{
			$kecamatan								= $this->model->select('kecamatan')->get_where('ref__kecamatan', array('id' => $this->_id_kec), 1)->row('kecamatan');
			$this->set_description
			('
				<div class="row">
					<div class="col-sm-3">
						<div class="row">
							<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
								Kecamatan
							</label>
							<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
								' . $kecamatan . '
							</label>
						</div>
					</div>
				</div>
			')
			->where('id_kec', $this->_id_kec);
		}
		elseif(2 == get_userdata('group_id'))
		{
			$this->where('id_kec', $this->_id_kec);
		}
		$this->set_title(phrase('silakan_pilih_kelurahan'))
		->set_icon('fa fa-institution')
		->set_field('kode', 'sprintf')
		->set_field('nama_kelurahan', 'hyperlink', 'musrenbang/p3bk/data', array('id_kec' => 'id_kec', 'id_kel' => 'id'))
		->unset_action('create, read, update, delete')
		->unset_column('id, id_kec, singkat_kelurahan, nama_lurah, nip_lurah, jabatan_lurah')
		->merge_content('{kode_ref__kecamatan}.{kode}')
		->column_order('kode_ref__kecamatan')
		->select('ref__kecamatan.kode')
		->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')
		->set_alias('kode_ref__kecamatan', 'Kode')
		->where('ref__kelurahan.id_kec !=', null)
		->order_by('ref__kecamatan.kode, ref__kelurahan.kode')
		->render('ref__kelurahan');
	}
	
	private function _filter()
	{
		$output										= null;
		$query										= $this->model->select('id, kode, kecamatan')->order_by('kode')->get('ref__kecamatan')->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->_id_kec ? ' selected' : '') . '>' . sprintf('%02d', $val['kode']) . '. ' . $val['kecamatan'] . '</option>';
			}
		}
		$output										= '
			<select name="id_kec" class="form-control input-sm bordered" placeholder="' . phrase('filter_berdasar_kecamatan') . '">
				<option value="all">' . phrase('semua_kecamatan') . '</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}