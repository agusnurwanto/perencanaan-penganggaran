<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Kelompok extends Aksara
{
	private $_table									= 'ref__standar_harga_2';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('akun');
		
		if($this->input->post('get_last_insert') && $this->input->post('primary'))
		{
			return $this->_get_last_insert();
		}
	}
	
	public function index()
	{
		if($this->_primary)
		{
			/* ambil Akun berdasar id */
			$query									= $this->model
			->select
			('
				id,
				kd_standar_harga_1,
				uraian
			')
			->get_where
			(
				'ref__standar_harga_1',
				array
				(
					'id'							=> $this->_primary
				),
				1
			)
			->row();
			
			/* cek apabila akun tersedia / valid */
			if($query)
			{
				$this->set_description
				('
					<div class="row text-sm border-bottom">
						<label class="col-12 col-sm-2 text-muted text-uppercase">
							Akun
						</label>
						<label class="col-2 col-sm-1">
							' . $query->kd_standar_harga_1 . '
						</label>
						<label class="col-10 col-sm-9">
							' . $query->uraian . '
						</label>
					</div>
				')
				->where('id_standar_harga_1', $query->id)
				->set_default('id_standar_harga_1', $query->id);
			}
			
			$this
			->unset_column('id, id_standar_harga_1, tahun')
			->unset_field('id, id_standar_harga_1, tahun')
			->column_order('kd_standar_harga_1, uraian')
			->field_order('kd_standar_harga_2, uraian')
			->select('ref__standar_harga_1.kd_standar_harga_1')
			->join('ref__standar_harga_1', 'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1');
		}
		else
		{
			$this
			->unset_column('id, id_standar_harga_1, tahun')
			->unset_field('id, tahun')
			->column_order('kd_standar_harga_1, uraian')
			->field_order('id_standar_harga_1, kd_standar_harga_2, uraian')
		
			/* relasi ke table lain berdasar id_standar_harga_1 */
			->set_relation
			(
				'id_standar_harga_1',
				'ref__standar_harga_1.id',
				'{ref__standar_harga_1.kd_standar_harga_1} - {ref__standar_harga_1.uraian}',
				array
				(
					'ref__standar_harga_1.tahun'		=> get_userdata('year')
				)
			)
			
			/* atur validasi karena id_kelompok tidak dipilih */
			->set_validation('id_standar_harga_1', 'required|numeric')
			;
		}
		
		$this->set_title('Standar Kelompok')
		->set_icon('mdi mdi-sitemap')
		->unset_column('id, tahun')
		->unset_field('id, tahun')
		->unset_view('id, tahun')
		
		/* tambah kolom filter berdasar akun */
		->add_filter($this->_filter())
		
		/* penyesuaian breadcrumb */
		->set_breadcrumb
		(
			array
			(
				'standar/akun'						=> 'Akun'
			)
		)
		
		->set_field
		(
			array
			(
				'kd_standar_harga_2'				=> 'last_insert',
				'uraian'							=> 'textarea'
			)
		)
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus'
			)
		)
		
		/* pengelompokan konten dalam satu field */
		->merge_content('{kd_standar_harga_1}.{kd_standar_harga_2}', 'Kode')
		
		/* set kolom sebagai hyperlink ke modul di bawahnya */
		->set_field('uraian', 'hyperlink', 'master/standar/jenis', array('kelompok' => 'id'))
		
		/* penyesuaian validasi */
		->set_validation
		(
			array
			(
				'kode'								=> 'required|numeric|is_unique[ref__standar_harga_2.kd_standar_harga_2.id.' . $this->input->get('id') . ']',
				'kelompok'							=> 'required|xss_clean'
			)
		)
		
		/* penyesuaian alias untuk label */
		->set_alias
		(
			array
			(
				'id_standar_harga_1'				=> 'Akun'
			)
		)
		
		/* set value default sehingga pengguna tidak dapat mengubah */
		->set_default('tahun', get_userdata('year'))
		
		->where('tahun', get_userdata('year'))
		
		->order_by
		(
			array
			(
				'kd_standar_harga_1'				=> 'ASC',
				'kd_standar_harga_2'				=> 'ASC'
			)
		)
		->render($this->_table);
	}
	
	/**
	 * Mengambil kd_standar_harga_2 terakhir berdasar dropdown terpilih
	 */
	private function _get_last_insert()
	{
		$id_standar_harga_1							= null;
		if($this->input->get('id'))
		{
			$id_standar_harga_1								= $this->model->select('id_standar_harga_1')->get_where('ta__standar_kelompok', array('id' => $this->input->get('id')), 1)->row('id_standar_harga_1');
		}
		
		$query										= $this->model->select_max('kd_standar_harga_2')->get_where('ta__standar_kelompok', array('id_standar_harga_1' => $this->input->post('primary')), 1)->row('kd_standar_harga_2');
		
		if($query)
		{
			if($id_standar_harga_1 != $this->input->post('primary'))
			{
				$query								= $query + 1;
			}
		}
		else
		{
			$query									= 1;
		}
		
		return make_json
		(
			array
			(
				'javascript'						=> '$("#kode_input").val(' . $query . ')'
			)
		);
	}
	
	/**
	 * Kolom Filter
	 */
	private function _filter()
	{
		$query										= $this->model
		->select
		('
			id,
			kd_standar_harga_1,
			uraian
		')
		->order_by('kd_standar_harga_1 ASC')
		->get_where
		(
			'ref__standar_harga_1',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result();
		if($query)
		{
			$option									= '<option value="all">Semua Akun</option>';
			foreach($query as $key => $val)
			{
				$option								.= '<option value="' . $val->id . '"' . ($val->id == $this->_primary ? ' selected' : null) . '>' . $val->kd_standar_harga_1 . ' - ' . $val->uraian . '</option>';
			}
			
			return '
				<select name="akun" class="form-control" placeholder="Berdasar Akun">
					' . $option . '
				</select>
			';
		}
	}
}