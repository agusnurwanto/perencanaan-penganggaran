<?php
	//$results							= $resultss['field_data'];
	//$results							= json_decode(json_encode($results));
?>
<div class="container-fluid pt-3 pb-3">
	<table class="table table-sm table-bordered">
		<tr>
			<th class="text-center" colspan="6">
				<b>TIM ANGGARAN PEMERINTAH DAERAH</b>
			</th>
		</tr>
		<tr>
			<th class="text-center">
				NO.
			</th>
			<th class="text-center">
				NAMA
			</th>
			<th class="text-center">
				NIP
			</th>
			<th class="text-center">
				JABATAN
			</th>
			<th class="text-center">
				ACTION
			</th>
			<th class="text-center">
				TANDA TANGAN
			</th>
		</tr>
		<?php
			$CI							=& get_instance();
			foreach($tim_anggaran as $key => $val)
			{
				$id						= 'ttd_' . $val->id;
				$ttd					= $CI->get_ttd($val->id);
				echo '
					<tr>
						<td class="text-center">
							' . $val->kode . '
						</td>
						<td>
							' . $val->nama_tim . '
						</td>
						<td>
							' . $val->nip_tim . '
						</td>
						<td>
							' . $val->jabatan_tim . '
						</td>
						<td>
							' . (1 == get_userdata('group_id') || (15 == get_userdata('group_id') && get_userdata('sub_unit') == $val->id) ? '<a href="' . current_page('../verifikasi', array('req' => 'ttd', 'target' => 'ttd_' . $val->id)) . '" class="btn btn-toggle btn-sm ' . (isset($verified->$id) && 1 == $verified->$id ? 'active' : 'inactive') . ' --modal --prevent-remove">
								<span class="handle"></span>
							</a>' : null) . '
						</td>
						<td>
							<p class="verifikator-ttd text-center">
								' . (isset($verified->$id) && 1 == $verified->$id ? $ttd : null) . '
							</p>
						</td>
					</tr>
				';
			}
		?>
	</table>
</div>
