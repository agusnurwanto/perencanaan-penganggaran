<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rw extends Aksara
{
	function __construct()
	{
		parent::__construct();
		
		$this->_id_kel								= (3 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_kel'));
		
		if(!in_array(get_userdata('group_id'), array(1, 3)))
		{
			throw_exception(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(!$this->_id_kel)
		{
			throw_exception(301, 'Silakan pilih Kelurahan terlebih dahulu.', base_url('musrenbang/kelurahan'));
		}
		
		$this->set_theme('backend')
		//->set_upload_path('musrenbang')
		->set_permission();
	}
	
	public function index()
	{
		$approval_max								= $this->model
		->query
		('
			SELECT
			ROUND(Count(ref__rw.id) * 4 * 75 / 100) AS max_approve_kelurahan
			FROM
			ref__rw
			WHERE
			ref__rw.id_kel = ' . $this->_id_kel. '
		')
		->row('max_approve_kelurahan');
		$file_scan									= $this->model->select('file')->get_where('ta__musrenbang_kelurahan_berkas', array('id_kel' => $this->_id_kel), 1)->row('file');
		$file_scan									= json_decode($file_scan);
		if($file_scan)
		{
			$file_scan								= key($file_scan);
		}
		if($file_scan)
		{
			$this->add_action('toolbar', '../../../uploads/kelurahan/' . $file_scan, 'Lihat Berkas', 'btn-primary ajax', 'fa fa-search', null, true);
		}
		if($this->_id_kel)
		{
			$this->add_action('toolbar', '../scan', 'Upload Scan', 'btn-info ajax', 'fa fa-qrcode', array('id_kel' => $this->_id_kel));
		}
		
		$header										= $this->model->select('ref__kecamatan.kecamatan, ref__kelurahan.nama_kelurahan')->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')->get_where('ref__kelurahan', array('ref__kelurahan.id' => $this->_id_kel), 1)->row();
		//$input_usulan								= $this->model->get_where('ta__musrenbang', array('flag' => 1, 'variabel_usulan' => NULL, 'id_kel' => $this->_id_kel))->num_rows();
		$usulan_rw									= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'pengusul =' => 1))->num_rows();		
		$usulan_diverifikasi						= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'flag >' => 0, 'pengusul' => 1))->num_rows();
		//echo $this->model->last_query();exit;
		$usulan_ditolak								= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'flag =' => 2))->num_rows();
		$usulan_kelurahan							= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'pengusul' => 2, 'jenis_usulan' => 2 ))->num_rows();
		$usulan_diterima							= $usulan_diverifikasi - $usulan_ditolak;
		$maksimal_kelurahan							= 20 ;
		$selisih_kelurahan							= $maksimal_kelurahan - $usulan_kelurahan ;
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Kecamatan
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
							' . $header->kecamatan . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Kelurahan
						</label>
						<label class="control-label col-md-8 col-xs-8 text-sm text-uppercase no-margin">
							' . $header->nama_kelurahan . '
						</label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan RW
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_rw . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Max Diterima
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $approval_max . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan Diterima
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_diterima . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan Ditolak
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_ditolak . '
							</b>
						</label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Maks. Usulan Kelurahan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $maksimal_kelurahan . '
							</b>
						</label> 
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan Kelurahan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_kelurahan . '
							</b>
						</label>
					</div>
					<div class="row border-bottom">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Tersisa
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $selisih_kelurahan . '
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
				'kelurahan'							=> 'Kelurahan'
			)
		)
		->set_title('Silakan pilih RW')
		->set_icon('mdi mdi-smog')
		->unset_action('create, read, update, delete, print, export, pdf')
		->set_field('rw', 'hyperlink', 'musrenbang/kelurahan/data', array('id_rw' => 'id'))
		->unset_column('id, id_kel, nama')
		->column_order('rw')
		->where('ref__rw.id_kel', $this->_id_kel)
		->order_by('rw')
		->render('ref__rw');
	}
}
