<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rincian_objek extends Aksara
{
	private $_table									= 'ref__standar_harga_5';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('objek');
		
		if($this->input->post('get_last_insert') && $this->input->post('primary'))
		{
			return $this->_get_last_insert();
		}
	}
	
	public function index()
	{
		if($this->_primary)
		{
			/* ambil objek berdasar id */
			$query									= $this->model
			->select
			('
				ref__standar_harga_1.kd_standar_harga_1,
				ref__standar_harga_1.uraian AS akun,
				ref__standar_harga_2.kd_standar_harga_2,
				ref__standar_harga_2.uraian AS kelompok,
				ref__standar_harga_3.kd_standar_harga_3,
				ref__standar_harga_3.uraian AS jenis,
				ref__standar_harga_4.id,
				ref__standar_harga_4.kd_standar_harga_4,
				ref__standar_harga_4.uraian AS objek
			')
			->join
			(
				'ref__standar_harga_3',
				'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3'
			)
			->join
			(
				'ref__standar_harga_2',
				'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2'
			)
			->join
			(
				'ref__standar_harga_1',
				'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
			)
			->get_where
			(
				'ref__standar_harga_4',
				array
				(
					'ref__standar_harga_4.id'			=> $this->_primary
				),
				1
			)
			->row();
			
			/* cek apabila jenis tersedia / valid */
			if($query)
			{
				$this->set_description
				('
					<div class="row text-sm">
						<label class="col-6 col-sm-2 text-muted text-uppercase mb-0">
							Akun
						</label>
						<label class="col-2 col-sm-1 mb-0">
							' . $query->kd_standar_harga_1 . '
						</label>
						<label class="col-4 col-sm-3 mb-0">
							' . $query->akun . '
						</label>
						<label class="col-6 col-sm-2 text-muted text-uppercase mb-0">
							Kelompok
						</label>
						<label class="col-2 col-sm-1 mb-0">
							' . $query->kd_standar_harga_1 . '.' . $query->kd_standar_harga_2 . '
						</label>
						<label class="col-4 col-sm-3 mb-0">
							' . $query->kelompok . '
						</label>
					</div>
					<div class="row text-sm border-bottom">
						<label class="col-6 col-sm-2 text-muted text-uppercase">
							Jenis
						</label>
						<label class="col-2 col-sm-1">
							' . $query->kd_standar_harga_1 . '.' . $query->kd_standar_harga_2 . '.' . $query->kd_standar_harga_3 . '
						</label>
						<label class="col-4 col-sm-3">
							' . $query->jenis . '
						</label>
						<label class="col-6 col-sm-2 text-muted text-uppercase">
							Objek
						</label>
						<label class="col-2 col-sm-1">
							' . $query->kd_standar_harga_1 . '.' . $query->kd_standar_harga_2 . '.' . $query->kd_standar_harga_3 . '.' . $query->kd_standar_harga_4 . '
						</label>
						<label class="col-4 col-sm-3">
							' . $query->objek . '
						</label>
					</div>
				')
				->where('id_standar_harga_4', $query->id)
				->set_default('id_standar_harga_4', $query->id)
				;
			}
			$this
			->unset_column('id_standar_harga_4')
			->unset_field('id_standar_harga_4')
			->select('ref__standar_harga_1.kd_standar_harga_1, ref__standar_harga_2.kd_standar_harga_2, ref__standar_harga_3.kd_standar_harga_3, ref__standar_harga_4.kd_standar_harga_4')
			->join('ref__standar_harga_4', 'ref__standar_harga_4.id = ref__standar_harga_5.id_standar_harga_4')
			->join('ref__standar_harga_3', 'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3')
			->join('ref__standar_harga_2', 'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2')
			->join('ref__standar_harga_1', 'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1')
			;
		}
		else
		{
			$this
			
			/* relasi ke table lain berdasar id_standar_harga_3 */
			->set_relation
			(
				'id_standar_harga_4',
				'ref__standar_harga_4.id',
				'{ref__standar_harga_1.kd_standar_harga_1}.{ref__standar_harga_2.kd_standar_harga_2}.{ref__standar_harga_3.kd_standar_harga_3}.{ref__standar_harga_4.kd_standar_harga_4} - {ref__standar_harga_4.uraian AS objek}',
				array
				(
					'ref__standar_harga_4.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__standar_harga_3',
						'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3'
					),
					array
					(
						'ref__standar_harga_2',
						'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2'
					),
					array
					(
						'ref__standar_harga_1',
						'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
					)
				),
				array
				(
					'ref__standar_harga_1.kd_standar_harga_1'	=> 'ASC',
					'ref__standar_harga_2.kd_standar_harga_2'	=> 'ASC',
					'ref__standar_harga_3.kd_standar_harga_3'	=> 'ASC',
					'ref__standar_harga_4.kd_standar_harga_4'	=> 'ASC'
				)
			)
			/* atur validasi karena id_standar_harga_3 tidak dipilih */
			->set_validation('id_standar_harga_4', 'required|numeric')
		
			->unset_column('id, id_standar_harga_4, tahun')
			->unset_field('id, tahun')
			;
		}
		
		$this->set_title('Standar Rincian Objek')
		->set_icon('mdi mdi-auto-upload')
		
		->unset_column('id, id_standar_harga_4, jenis, tahun')
		->unset_field('id, id_standar_harga_4, tahun')
		
		/* penyesuaian breadcrumb */
		->set_breadcrumb
		(
			array
			(
				'master/standar/akun'			=> 'Akun',
				'../kelompok'					=> 'Kelompok',
				'../jenis'						=> 'Jenis',
				'../objek'						=> 'Objek'
			)
		)
		->unset_view('id, tahun')
		->unset_truncate('uraian')
		
		/* tambah kolom filter berdasar jenis */
		->add_filter($this->_filter())
		
		/* order kolom */
		->column_order('kd_standar_harga_1, uraian')
		
		->field_order('id_standar_harga_4, kd_standar_harga_5, uraian')
		->set_field
		(
			array
			(
				'kd_standar_harga_5'			=> 'last_insert',
				'uraian'						=> 'textarea'
			)
		)
		
		/* set kolom sebagai hyperlink ke modul di bawahnya */
		->set_field('uraian', 'hyperlink', 'master/standar/sub_rincian_objek', array('rincian_objek' => 'id'))
		
		/* set kolom sebagai hyperlink untuk mem-filter berdasar data yang diklik */
		//->set_field('jenis', 'hyperlink', 'standar/objek', array('id_standar_harga_3' => 'id_standar_harga_3'))
		
		/* menambah class untuk kolom tertentu */
		//->add_class('id_standar_harga_3', 'get-last-insert')
		->add_class
		(
			array
			(
				'id_standar_harga_4'			=> 'get-last-insert',
				'uraian'						=> 'autofocus'
			)
		)
		
		/* pengelompokan konten dalam satu field */
		->merge_content('{kd_standar_harga_1}.{kd_standar_harga_2}.{kd_standar_harga_3}.{kd_standar_harga_4}.{kd_standar_harga_5}', 'Kode')
		
		/* penyesuaian validasi */
		->set_validation
		(
			array
			(
				'kd_standar_harga_5'				=> 'required|numeric|is_unique[ref__standar_harga_5.kd_standar_harga_5.id.' . $this->input->get('id') . '.id_standar_harga_4.' . ($this->_primary ? $this->_primary : $this->input->post('id_standar_harga_4')) . ']',
				'uraian'						=> 'required|xss_clean'
			)
		)
		
		/* penyesuaian alias untuk label */
		->set_alias
		(
			array
			(
				'id_standar_harga_4'							=> 'Objek'
			)
		)
		
		/* set value default sehingga pengguna tidak dapat mengubah */
		->set_default('tahun', get_userdata('year'))
		
		->where('tahun', get_userdata('year'))
		->order_by
		(
			array
			(
				'kd_standar_harga_1'							=> 'ASC',
				'kd_standar_harga_2'						=> 'ASC',
				'kd_standar_harga_3'						=> 'ASC',
				'kd_standar_harga_4'						=> 'ASC',
				'kd_standar_harga_5'				=> 'ASC'
			)
		)
		->render($this->_table);
	}
	
	/**
	 * Mengambil kode terakhir berdasar dropdown terpilih
	 */
	private function _get_last_insert()
	{
		$id_standar_harga_4									= null;
		if($this->input->get('id'))
		{
			$id_standar_harga_4								= $this->model->select('id_standar_harga_4')->get_where('ref__standar_harga_5', array('id' => $this->input->get('id')), 1)->row('id_standar_harga_4');
		}
		
		$query										= $this->model->select_max('kd_standar_harga_5')->get_where('ref__standar_harga_5', array('id_standar_harga_4' => $this->input->post('primary')), 1)->row('kd_standar_harga_5');
		
		if($query)
		{
			if($id_standar_harga_4 != $this->input->post('primary'))
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
			ref__standar_harga_1.kd_standar_harga_1,
			ref__standar_harga_2.kd_standar_harga_2,
			ref__standar_harga_3.kd_standar_harga_3,
			ref__standar_harga_4.id,
			ref__standar_harga_4.kd_standar_harga_4,
			ref__standar_harga_4.uraian AS objek
		')
		->join
		(
			'ref__standar_harga_3',
			'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3'
		)
		->join
		(
			'ref__standar_harga_2',
			'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2'
		)
		->join
		(
			'ref__standar_harga_1',
			'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
		)
		->get_where
		(
			'ref__standar_harga_4',
			array
			(
				'ref__standar_harga_4.tahun'			=> get_userdata('year')
			)
		)
		->result();
		if($query)
		{
			$option									= '<option value="all">Semua Objek</option>';
			foreach($query as $key => $val)
			{
				$option								.= '<option value="' . $val->id . '"' . ($val->id == $this->_primary ? ' selected' : null) . '>' . $val->kd_standar_harga_1 . '.' . $val->kd_standar_harga_2 . '.' . $val->kd_standar_harga_3 . '.' . $val->kd_standar_harga_4 . ' - ' . $val->objek . '</option>';
			}
			
			return '
				<select name="objek" class="form-control" placeholder="Berdasar Objek">
					' . $option . '
				</select>
			';
		}
	}
}
