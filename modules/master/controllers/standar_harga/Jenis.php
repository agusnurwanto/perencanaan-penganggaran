<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Jenis extends Aksara
{
	private $_table									= 'ref__standar_harga_3';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('kelompok');
		
		if($this->input->post('get_last_insert') && $this->input->post('primary'))
		{
			return $this->_get_last_insert();
		}
	}
	
	public function index()
	{
		if($this->_primary)
		{
			/*if($this->_primary == "all")
			{
				$this->_primary						= '"%"';
			}*/
			/* ambil kelompok berdasar id */
			$query									= $this->model
			->select
			('
				ref__standar_harga_1.kd_standar_harga_1, ref__standar_harga_1.uraian AS akun,
				ref__standar_harga_2.id,
				ref__standar_harga_2.kd_standar_harga_2,
				ref__standar_harga_2.uraian AS kelompok
			')
			->join('ref__standar_harga_1', 'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1')
			//->like('ref__standar_harga_2.id', $this->_primary)
			->get_where
			(
				'ref__standar_harga_2',
				array
				(
					'ref__standar_harga_2.id'		=> $this->_primary
				),
				1
			)
			->row();
			
			/* cek apabila kelompok tersedia / valid */
			if($query)
			{
				$this->set_description
				('
					<div class="row text-sm border-bottom">
						<label class="col-6 col-sm-2 text-muted text-uppercase">
							Akun
						</label>
						<label class="col-2 col-sm-1">
							' . $query->kd_standar_harga_1 . '
						</label>
						<label class="col-4 col-sm-3">
							' . $query->akun . '
						</label>
						<label class="col-6 col-sm-2 text-muted text-uppercase">
							Kelompok
						</label>
						<label class="col-2 col-sm-1">
							' . $query->kd_standar_harga_1 . '.' . $query->kd_standar_harga_2 . '
						</label>
						<label class="col-4 col-sm-3">
							' . $query->kelompok . '
						</label>
					</div>
				')
				->where('id_standar_harga_2', $query->id)
				->set_default('id_standar_harga_2', $query->id);
			}
			$this
			->unset_column('id, id_standar_harga_2, tahun')
			->unset_field('id, id_standar_harga_2, tahun')
			->select('ref__standar_harga_1.kd_standar_harga_1, ref__standar_harga_2.kd_standar_harga_2')
			->join('ref__standar_harga_2', 'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2')
			->join('ref__standar_harga_1', 'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1');
		}
		else
		{
			$this
			->unset_column('id, id_standar_harga_2, kelompok, tahun')
			->unset_field('id, tahun')
			->field_order('id_standar_harga_2, kd_standar_harga_3, uraian')
			
			/* atur validasi karena id_standar_harga_2 tidak dipilih */
			->set_validation('id_standar_harga_2', 'required|numeric')
		
			/* relasi ke table lain berdasar id_standar_harga_2 */
			->set_relation
			(
				'id_standar_harga_2',
				'ref__standar_harga_2.id',
				'{ref__standar_harga_1.kd_standar_harga_1}.{ref__standar_harga_2.kd_standar_harga_2} - {ref__standar_harga_2.uraian AS kelompok}',
				array
				(
					'ref__standar_harga_2.tahun'		=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__standar_harga_1',
						'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
					)
				),
				array
				(
					'ref__standar_harga_1.kd_standar_harga_1'		=> 'ASC',
					'ref__standar_harga_2.kd_standar_harga_2' => 'ASC'
				)
			)
			;
		}
		
		$this->set_title('Standar Jenis')
		->set_icon('mdi mdi-format-list-numbered')
		->unset_view('id, tahun')
		
		/* tambah kolom filter berdasar kelompok */
		->add_filter($this->_filter())
		
		/* penyesuaian breadcrumb */
		->set_breadcrumb
		(
			array
			(
				'master/standar/akun'				=> 'Akun',
				'../kelompok'						=> 'Kelompok'
			)
		)
		->set_field
		(
			array
			(
				'kd_standar_harga_3'				=> 'last_insert',
				'uraian'							=> 'textarea'
			)
		)
		->add_class
		(
			array
			(
				'id_standar_harga_2'				=> 'get-last-insert',
				'uraian'								=> 'autofocus'
			)
		)
		
		/* set kolom sebagai hyperlink ke modul di bawahnya */
		->set_field('uraian', 'hyperlink', 'master/standar/objek', array('jenis' => 'id'))
		
		/* set kolom sebagai hyperlink untuk mem-filter berdasar data yang diklik */
		//->set_field('kelompok', 'hyperlink', 'standar/jenis', array('id_standar_harga_2' => 'id_standar_harga_2'))
		
		/* pengelompokan konten dalam satu field */
		->merge_content('{kd_standar_harga_1}.{kd_standar_harga_2}.{kd_standar_harga_3}', 'Kode')
		
		/* order kolom */
		->column_order('kd_standar_harga_1, uraian')
		
		/* order field */
		->field_order('id_standar_harga_2, kd_standar_harga_3, uraian')
		
		->unset_truncate('uraian')
		
		/* penyesuaian validasi */
		->set_validation
		(
			array
			(
				'kd_standar_harga_3'						=> 'required|numeric|is_unique[ref__standar_harga_3.kd_standar_harga_3.id.' . $this->input->get('id') . '.id_standar_harga_2.' . ($this->_primary ? $this->_primary : $this->input->post('id_standar_harga_2')) . ']',
				'uraian'								=> 'required|xss_clean'
			)
		)
		
		/* penyesuaian alias untuk label */
		->set_alias
		(
			array
			(
				'id_standar_harga_2'						=> 'Kelompok'
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
				'kd_standar_harga_3'						=> 'ASC'
			)
		)
		->render($this->_table);
	}
	
	/**
	 * Mengambil kd_standar_harga_3 terakhir berdasar dropdown terpilih
	 */
	private function _get_last_insert()
	{
		$id_standar_harga_2								= null;
		if($this->input->get('id'))
		{
			$id_standar_harga_2							= $this->model->select('id_standar_harga_2')->get_where('ref__standar_harga_3', array('id' => $this->input->get('id')), 1)->row('id_standar_harga_2');
		}
		
		$query										= $this->model->select_max('kd_standar_harga_3')->get_where('ref__standar_harga_3', array('id_standar_harga_2' => $this->input->post('primary')), 1)->row('kd_standar_harga_3');
		
		if($query)
		{
			if($id_standar_harga_2 != $this->input->post('primary'))
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
			ref__standar_harga_2.id,
			ref__standar_harga_2.kd_standar_harga_2,
			ref__standar_harga_2.uraian AS kelompok
		')
		->join
		(
			'ref__standar_harga_1',
			'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
		)
		->order_by('kd_standar_harga_1 ASC, kd_standar_harga_2 ASC')
		->get_where
		(
			'ref__standar_harga_2',
			array
			(
				'ref__standar_harga_2.tahun'		=> get_userdata('year')
			)
		)
		->result();
		if($query)
		{
			$option									= '<option value="all">Semua Kelompok</option>';
			foreach($query as $key => $val)
			{
				$option								.= '<option value="' . $val->id . '"' . ($val->id == $this->_primary ? ' selected' : null) . '>' . $val->kd_standar_harga_1 . '.' . $val->kd_standar_harga_2 . ' - ' . $val->kelompok . '</option>';
			}
			
			return '
				<select name="kelompok" class="form-control" placeholder="Berdasar Kelompok">
					' . $option . '
				</select>
			';
		}
	}
}
