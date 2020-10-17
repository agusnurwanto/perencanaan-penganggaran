<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Copy_rka extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		if($this->input->post('copy_dari') && $this->input->post('copy_ke'))
		{
			return $this->_copy();
		}
		return make_json
		(
			array
			(
				'status'						=> 201,
				'html'							=> $this->_kegiatan()
			)
		);
	}
	
	private function _copy()
	{
		$this->form_validation->set_rules('copy_dari', 'Kegiatan copy dari', 'required');
		$this->form_validation->set_rules('copy_ke', 'Kegiatan copy ke', 'required|callback_not_equal_to');
		if($this->form_validation->run() === FALSE)
		{
			return generateMessages(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		
		$kegiatan_a								= $this->model->select('kegiatan')->get_where('ta__kegiatan', array('id' => $this->input->post('copy_dari')), 1)->row('kegiatan');
		$kegiatan_b								= $this->model->select('kegiatan')->get_where('ta__kegiatan', array('id' => $this->input->post('copy_ke')), 1)->row('kegiatan');
		
		$checker								= $this->model
		->select
		('
			ta__belanja.id,
			ta__belanja_sub.id as id_belanja_sub,
			ta__belanja_rinc.id as id_belanja_rinc'
		)
		->join('ta__belanja_sub', 'ta__belanja_sub.id_belanja = ta__belanja.id')
		->join('ta__belanja_rinc', 'ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id')
		->get_where
		(
			'ta__belanja',
			array
			(
				'ta__belanja.id_keg'			=> $this->input->post('copy_ke')
			),
			1
		)
		->num_rows();
		
		if($checker > 0 && !$this->input->post('confirmed'))
		{
			return make_json
			(
				array
				(
					'status'					=> 201,
					'html'						=> 'RKA untuk kegiatan ' . $kegiatan_b . ' sudah dibuat, apakah Anda yakin akan menghapus RKA tujuan dan meng-copy RKA dari kegiatan ' . $kegiatan_a . '?'
				)
			);
		}
		
		/* ta_belanja */
		$ta_belanja								= $this->model
		->query
		('
			SELECT
				ta__belanja.id,
				ta__belanja.id_keg,
				ta__belanja.id_rek_5,
				ta__belanja.id_sumber_dana
			FROM
				ta__belanja
			WHERE
				ta__belanja.id_keg = ' . $this->input->post('copy_dari') . '
		')
		->result();
		
		// set default
		$o_belanja								= array();
		$o_belanja_sub							= array();
		$o_belanja_rinc							= array();
		
		if($ta_belanja)
		{
			foreach($ta_belanja as $key => $val)
			{
				$value							= $val;
				// set id_keg
				$value->id_keg					= $this->input->post('copy_ke');
				// push array into default
				$o_belanja[]					= $value;
			}
			
			/* ta_belanja_sub */
			$ta_belanja_sub						= $this->model
			->query
			('
				SELECT
					ta__belanja_sub.id,
					belanja.id_belanja,
					ta__belanja_sub.kd_belanja_sub,
					ta__belanja_sub.uraian
				FROM
					ta__belanja_sub
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				LEFT JOIN
				(
					SELECT
						ta__belanja.id AS id_belanja,
						ta__belanja.id_rek_5
					FROM
						ta__belanja
					WHERE
						ta__belanja.id_keg = ' . $this->input->post('copy_ke') . '
				) AS belanja ON belanja.id_rek_5 = ta__belanja.id_rek_5
				WHERE
					ta__belanja.id_keg = ' . $this->input->post('copy_dari') . '
			')
			->result();
			
			if($ta_belanja_sub)
			{
				foreach($ta_belanja_sub as $key_2 => $val_2)
				{
					$value						= $val_2;
					// set id_keg
					$value->id_keg				= $this->input->post('copy_ke');
					// push array into default
					$o_belanja_sub[]			= $value;
				}
			}
			
			/* ta_belanja_rinc */
			$ta_belanja_rinc					= $this->model
			->query
			('
				SELECT
					ta__belanja_rinc.id,
					belanja_sub.id_belanja_sub,
					ta__belanja_rinc.id_standar_harga,
					ta__belanja_rinc.kd_belanja_rinc,
					ta__belanja_rinc.uraian,
					ta__belanja_rinc.vol_1,
					ta__belanja_rinc.vol_2,
					ta__belanja_rinc.vol_3,
					ta__belanja_rinc.satuan_1,
					ta__belanja_rinc.satuan_2,
					ta__belanja_rinc.satuan_3,
					ta__belanja_rinc.nilai,
					ta__belanja_rinc.vol_123,
					ta__belanja_rinc.satuan_123,
					ta__belanja_rinc.total
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				LEFT JOIN
				(
					SELECT
						ta__belanja_sub.id AS id_belanja_sub,
						ta__belanja_sub.kd_belanja_sub
					FROM
						ta__belanja_sub
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					WHERE
						ta__belanja.id_keg = ' . $this->input->post('copy_ke') . '
				) AS belanja_sub ON belanja_sub.kd_belanja_sub = ta__belanja_sub.kd_belanja_sub
				WHERE
					ta__belanja.id_keg = ' . $this->input->post('copy_dari') . '
			')
			->result();
			
			if($ta_belanja_rinc)
			{
				foreach($ta_belanja_rinc as $key_3 => $val_3)
				{
					$value				= $val_3;
					// set id_keg
					$value->id_keg		= $this->input->post('copy_ke');
					// push array into default
					$o_belanja_rinc[]	= $value;
				}
			}
			
			if($o_belanja && $o_belanja_sub && $o_belanja_rinc)
			{
				/*if($this->model->insert_batch('ta__belanja', $o_belanja, sizeof($o_belanja)))
				{
					if($this->model->insert_batch('ta__belanja_sub', $o_belanja_sub, sizeof($o_belanja_sub)))
					{
						if($this->model->insert_batch('ta__belanja_rinc', $o_belanja_rinc, sizeof($o_belanja_rinc)))
						{
						}
					}
				}*/
				return generateMessages(200, 'Sukses menyalin RKA dari kegiatan <b>' . $kegiatan_a . '</b> ke <b>' . $kegiatan_b . '</b>', current_page('../data'));
			}
			else
			{
				return generateMessages(404, 'RKA untuk kegiatan <b>' . $kegiatan_a . '</b> belum sampai ke tahap perincian, perintah copy ke kegiatan <b>' . $kegiatan_b . '</b> dibatalkan...', current_page('../data'));
			}
		}
		else
		{
			return generateMessages(404, 'RKA untuk kegiatan <b>' . $kegiatan_a . '</b> belum tersedia, perintah copy ke kegiatan <b>' . $kegiatan_b . '</b> dibatalkan...', current_page('../data'));
		}
	}
	
	public function not_equal_to($val = 0)
	{
		if($val == $this->input->post('copy_dari'))
		{
			$this->form_validation->set_message('not_equal_to', 'Kegiatan yang dipilih antara dari dan ke harus berbeda');
			return false;
		}
		return true;
	}
	
	private function _kegiatan()
	{
		$kegiatan								= $this->model
		->select
		('
			ref__program.kd_program,
			ta__kegiatan.id,
			ta__kegiatan.kd_keg,
			ta__kegiatan.kegiatan
		')
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->join
		(
			'ref__program',
			'ref__program.id = ta__program.id_prog'
		)
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__program.id_sub'			=> $this->input->get('id_sub')
			)
		)
		->result();
		
		$option									= null;
		if($kegiatan)
		{
			foreach($kegiatan as $key => $val)
			{
				$option							.= '<option value="' . $val->id . '">' . $val->kd_program . '.' . $val->kd_keg . ' - ' . $val->kegiatan . '</option>';
			}
		}
		
		$copy_dari								= '
			<select name="copy_dari" class="form-control bordered rounded" placeholder="Silakan pilih kegiatan">
				' . $option . '
			</select>
		';
		$copy_ke								= '
			<select name="copy_ke" class="form-control bordered rounded" placeholder="Silakan pilih kegiatan">
				' . $option . '
			</select>
		';
		return '
			<form action="' . current_page() . '" method="POST" class="submitForm" data-save="Copy RKA" data-saving="Menyalin..." data-alert="Tidak dapat menyalin RKA ke dalam kegiatan yang dipilih" data-icon="copy" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title">
								<i class="fa fa-copy"></i>
								Copy RKA
							</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label big-label text-muted text-uppercase">
											Copy dari
										</label>
										' . $copy_dari . '
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label big-label text-muted text-uppercase">
											Copy ke
										</label>
										' . $copy_ke . '
									</div>
								</div>
							</div>
							<div class="callback-status"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">
								<i class="fa fa-times"></i>
								Batal
							</button>
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-copy"></i>
								Copy RKA
							</button>
						</div>
					</div>
				</div>
			</form>
		';
	}
}