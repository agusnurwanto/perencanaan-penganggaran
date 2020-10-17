<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rw extends Aksara
{
	function __construct()
	{
		parent::__construct();
		
		$this->_id_kel								= $this->input->get('id_kel');
		
		if(1 != get_userdata('group_id'))
		{
			throw_exception(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(!$this->_id_kel)
		{
			throw_exception(301, 'Silakan pilih Kelurahan terlebih dahulu.', base_url('musrenbang/usulan/kelurahan'));
		}
		
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$header										= $this->model->select('ref__kecamatan.kecamatan, ref__kelurahan.nama_kelurahan')->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')->get_where('ref__kelurahan', array('ref__kelurahan.id' => $this->_id_kel), 1)->row();
		$rw_total									= $this->model->get_where('ref__rw', array('id_kel' => $this->_id_kel))->num_rows();
		$input_left									= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'pengusul' => 1))->num_rows();
		$tersisa									= ($rw_total * 4) - $input_left;
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-6">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase mb-0">
							Kecamatan
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase mb-0">
							' . $header->kecamatan . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase mb-0">
							Kelurahan
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase mb-0">
							' . $header->nama_kelurahan . '
						</label>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase mb-0">
							Maksimal Input Usulan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase mb-0">
							<b>
								' . $rw_total * 4 . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase mb-0">
							Usulan Terinput
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase mb-0">
							<b>
								' . $input_left . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase mb-0">
							Input Tersisa
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase mb-0">
							<b>
								' . $tersisa . '
							</b>
						</label>
					</div>
				</div>
			</div>
		');
		
		$this->set_breadcrumb
		(
			array
			(
				'musrenbang'						=> 'Musrenbang',
				'usulan/kelurahan'							=> 'Kelurahan'
			)
		)
		->set_title('Silakan pilih RW')
		->set_icon('mdi mdi-smog')
		->unset_action('create, read, update, delete, print, pdf')
		->add_action('toolbar', '../scan', 'Upload Scan', 'btn-info ajax', 'fa fa-qrcode', array('id_kel' => $this->_id_kel))
		->set_field('rw', 'hyperlink', 'musrenbang/usulan/data', array('id_rw' => 'id'))
		->unset_column('id, id_kel, nama')
		->column_order('rw')
		->where('ref__rw.id_kel', $this->input->get('id_kel'))
		->order_by('rw')
		->render('ref__rw');
	}
}