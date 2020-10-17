<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Objek extends Aksara
{
	private $_table									= 'ref__standar_harga_4';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('jenis');
		
		if($this->input->post('get_last_insert') && $this->input->post('primary'))
		{
			return $this->_get_last_insert();
		}
	}
	
	public function index()
	{
		if($this->_primary)
		{
			/* ambil jenis berdasar id */
			$query									= $this->model
			->select
			('
				ref__standar_harga_1.kd_standar_harga_1,
				ref__standar_harga_1.uraian AS akun,
				ref__standar_harga_2.kd_standar_harga_2,
				ref__standar_harga_2.uraian AS kelompok,
				ref__standar_harga_3.id,
				ref__standar_harga_3.kd_standar_harga_3,
				ref__standar_harga_3.uraian AS jenis
			')
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
				'ref__standar_harga_3',
				array
				(
					'ref__standar_harga_3.id'			=> $this->_primary
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
						<label class="col-12 col-sm-2 text-muted text-uppercase">
							Jenis
						</label>
						<label class="col-2 col-sm-1">
							' . $query->kd_standar_harga_1 . '.' . $query->kd_standar_harga_2 . '.' . $query->kd_standar_harga_3 . '
						</label>
						<label class="col-10 col-sm-9">
							' . $query->jenis . '
						</label>
					</div>
				')
				->where('id_standar_harga_3', $query->id)
				->set_default('id_standar_harga_3', $query->id)
				;
			}
			$this
			->unset_column('id, id_standar_harga_3, tahun')
			->unset_field('id, id_standar_harga_3, tahun')
			->select('ref__standar_harga_1.kd_standar_harga_1, ref__standar_harga_2.kd_standar_harga_2, ref__standar_harga_3.kd_standar_harga_3')
			->join('ref__standar_harga_3', 'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3')
			->join('ref__standar_harga_2', 'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2')
			->join('ref__standar_harga_1', 'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1')
			;
		}
		else
		{
			$this
			->unset_column('id, tahun')
			->unset_field('id, tahun')
			
			/* atur validasi karena id_standar_harga_3 tidak dipilih */
			->set_validation('id_standar_harga_3', 'required|numeric')
		
			/* relasi ke table lain berdasar id_standar_harga_3 */
			->set_relation
			(
				'id_standar_harga_3',
				'ref__standar_harga_3.id',
				'{ref__standar_harga_1.kd_standar_harga_1}.{ref__standar_harga_2.kd_standar_harga_2}.{ref__standar_harga_3.kd_standar_harga_3} - {ref__standar_harga_3.uraian AS jenis}',
				array
				(
					'ref__standar_harga_3.tahun'				=> get_userdata('year')
				),
				array
				(
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
					'ref__standar_harga_3.kd_standar_harga_3'	=> 'ASC'
				)
			)
			;
		}
		
		$this->set_title('Standar Objek')
		->set_icon('mdi mdi-auto-upload')
		
		->unset_column('id, id_standar_harga_3, tahun')
		->unset_field('id, id_standar_harga_3, tahun')
		
		/* penyesuaian breadcrumb */
		->set_breadcrumb
		(
			array
			(
				'master/standar/akun'				=> 'Akun',
				'../kelompok'						=> 'Kelompok',
				'../jenis'							=> 'Jenis'
			)
		)
		->unset_view('id, tahun')
		->unset_truncate('uraian')
		
		/* tambah kolom filter berdasar jenis */
		->add_filter($this->_filter())
		
		/* order kolom */
		->column_order('kd_standar_harga_1, uraian, jenis')
		
		->field_order('id_standar_harga_3, kd_standar_harga_4, objek')
		->set_field
		(
			array
			(
				'kd_standar_harga_4'				=> 'last_insert',
				'uraian'							=> 'textarea'
			)
		)
		
		/* set kolom sebagai hyperlink ke modul di bawahnya */
		->set_field('uraian', 'hyperlink', 'master/standar/rincian_objek', array('objek' => 'id'))
		
		/* set kolom sebagai hyperlink untuk mem-filter berdasar data yang diklik */
		//->set_field('jenis', 'hyperlink', 'standar/objek', array('id_standar_harga_3' => 'id_standar_harga_3'))
		
		/* menambah class untuk kolom tertentu */
		//->add_class('id_standar_harga_3', 'get-last-insert')
		->add_class
		(
			array
			(
				'id_standar_harga_3'				=> 'get-last-insert',
				'uraian'							=> 'autofocus'
			)
		)
		
		/* pengelompokan konten dalam satu field */
		->merge_content('{kd_standar_harga_1}.{kd_standar_harga_2}.{kd_standar_harga_3}.{kd_standar_harga_4}', 'Kode')
		
		/* penyesuaian validasi */
		->set_validation
		(
			array
			(
				'kd_standar_harga_4'				=> 'required|numeric|is_unique[ref__standar_harga_4.kd_standar_harga_4.id.' . $this->input->get('id') . '.id_standar_harga_3.' . ($this->_primary ? $this->_primary : $this->input->post('id_standar_harga_3')) . ']',
				'uraian'							=> 'required|xss_clean'
			)
		)
		
		/* penyesuaian alias untuk label */
		->set_alias
		(
			array
			(
				'id_standar_harga_3'				=> 'Jenis'
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
				'kd_standar_harga_2'				=> 'ASC',
				'kd_standar_harga_3'				=> 'ASC',
				'kd_standar_harga_4'				=> 'ASC'
			)
		)
		->render($this->_table);
	}
	
	/**
	 * Mengambil kode terakhir berdasar dropdown terpilih
	 */
	private function _get_last_insert()
	{
		$id_standar_harga_3									= null;
		if($this->input->get('id'))
		{
			$id_standar_harga_3								= $this->model->select('id_standar_harga_3')->get_where('ref__standar_harga_4', array('id' => $this->input->get('id')), 1)->row('id_standar_harga_3');
		}
		
		$query										= $this->model->select_max('kd_standar_harga_4')->get_where('ref__standar_harga_4', array('id_standar_harga_3' => $this->input->post('primary')), 1)->row('kd_standar_harga_4');
		
		if($query)
		{
			if($id_standar_harga_3 != $this->input->post('primary'))
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
			ref__standar_harga_3.id,
			ref__standar_harga_3.kd_standar_harga_3,
			ref__standar_harga_3.uraian AS jenis
		')
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
		->order_by('kd_standar_harga_1 ASC, kd_standar_harga_2 ASC, kd_standar_harga_3 ASC')
		->get_where
		(
			'ref__standar_harga_3',
			array
			(
				'ref__standar_harga_3.tahun'			=> get_userdata('year')
			)
		)
		->result();
		if($query)
		{
			$option									= '<option value="all">Semua Jenis</option>';
			foreach($query as $key => $val)
			{
				$option								.= '<option value="' . $val->id . '"' . ($val->id == $this->_primary ? ' selected' : null) . '>' . $val->kd_standar_harga_1 . '.' . $val->kd_standar_harga_2 . '.' . $val->kd_standar_harga_3 . ' - ' . $val->jenis . '</option>';
			}
			
			return '
				<select name="jenis" class="form-control" placeholder="Berdasar Jenis">
					' . $option . '
				</select>
			';
		}
	}
}
