<?php defined('BASEPATH') OR exit('No direct script access allowed');

$extra_toolbar										= null;
$has_option											= false;
$primary											= array();
if($results->extra_action)
{
	foreach($results->extra_action as $key => $val)
	{
		if('toolbar' == $val->placement)
		{
			$extra_toolbar							.= '
				<a href="' . go_to($val->url, $val->parameter) . '" class="btn btn-sm ' . ($val->class ? $val->class : 'btn-default ajax') . '"' . (isset($val->new_tab) ? ' target="_blank"' : null) . '>
					<i class="' . ($val->icon ? $val->icon : 'mdi mdi-link') . '"></i>
					' . $val->label . '
				</a>
			';
		}
		elseif('option' == $val->placement)
		{
			$has_option								= true;
		}
	}
}
?>
<div class="container-fluid">
	<div class="row pt-1 pb-1 alias-table-toolbar border-bottom">
		<div class="col">
			<div class="btn-group btn-group-sm">
				<?php if(!in_array('create', $results->unset_action)) { ?>
					<a href="<?php echo go_to('create'); ?>" class="btn btn-primary --btn-create <?php echo (isset($modal_html) ? '--modal' : '--open-modal-form'); ?>">
						<i class="mdi mdi-plus"></i>
						&nbsp;
						<span class="hidden-xs hidden-sm">
							<?php echo phrase('create'); ?>
						</span>
					</a>
				<?php } ?>
				<?php echo (isset($extra_toolbar) ? $extra_toolbar : null); ?>
				<?php if(!in_array('export', $results->unset_action)) { ?>
					<a href="<?php echo go_to('export'); ?>" class="btn btn-success --btn-export" target="_blank">
						<i class="mdi mdi-file-excel"></i>
						&nbsp;
						<span class="hidden-xs hidden-sm">
							<?php echo phrase('export'); ?>
						</span>
					</a>
				<?php } ?>
				<?php if(!in_array('print', $results->unset_action)) { ?>
					<a href="<?php echo go_to('print'); ?>" class="btn btn-warning --btn-print" target="_blank">
						<i class="mdi mdi-printer"></i>
						&nbsp;
						<span class="hidden-xs hidden-sm">
							<?php echo phrase('print'); ?>
						</span>
					</a>
				<?php } ?>
				<?php if(!in_array('pdf', $results->unset_action)) { ?>
					<a href="<?php echo go_to('pdf'); ?>" class="btn btn-info --btn-pdf" target="_blank">
						<i class="mdi mdi-file-pdf"></i>
						&nbsp;
						<span class="hidden-xs hidden-sm">
							<?php echo phrase('pdf'); ?>
						</span>
					</a>
				<?php } ?>
				<?php if(!in_array('delete', $results->unset_action)) { ?>
					<a href="<?php echo go_to('delete'); ?>" class="btn btn-danger disabled d-none --open-delete-confirm" data-toggle="tooltip" title="<?php echo phrase('delete_checked'); ?>" data-bulk-delete="true">
						<i class="mdi mdi-trash-can-outline"></i>
					</a>
				<?php } ?>
			</div>
		</div>
		<div class="col<?php echo (!isset($results->filter) || !$results->filter ? '-4' : null); ?>">
			<form action="<?php echo go_to(null, array('per_page' => null)); ?>" method="GET" class="--xhr-form">
				<?php
					if($this->input->get())
					{
						foreach($this->input->get() as $key => $val)
						{
							if(in_array($key, array('token', 'xtoken', 'q', 'per_page', 'column'))) continue;
							
							echo '<input type="hidden" name="' . $key . '" value="' . $val . '" />';
						}
					}
				?>
				<div class="input-group input-group-sm">
					
					<?php echo (isset($results->filter) ? $results->filter : null); ?>
					<input type="text" name="q" class="form-control" placeholder="<?php echo phrase('keyword_to_search'); ?>" value="<?php echo $this->input->get('q'); ?>" role="autocomplete" />
					<select name="column" class="form-control">
						<option value="all"><?php echo phrase('all_columns'); ?></option>
						<?php
							foreach($results->columns as $key => $val)
							{
								echo '
									<option value="' . $val->field . '"' . ($val->field == $this->input->get('column') ? ' selected' : null) . '>
										' . $val->label . '
									</option>
								';
							}
						?>
					</select>
					<span class="input-group-append">
						<button type="submit" class="btn btn-primary">
							<i class="mdi mdi-magnify"></i>
						</button>
					</span>
				</div>
			</form>
		</div>
	</div>
	<div class="table-responsive alias-table-index">
		<table class="table table-sm table-hover">
			<thead>
				<tr>
					<?php
						$colspan					= (!in_array('delete', $results->unset_action) ? 2 : 1);
						if($has_option || !in_array('read', $results->unset_action) || !in_array('update', $results->unset_action) || !in_array('delete', $results->unset_action) || !in_array('print', $results->unset_action) || !in_array('pdf', $results->unset_action))
						{
							echo (!in_array('delete', $results->unset_action) ? '
								<th width="1" class="border-top-0">
									<input type="checkbox" role="checker" data-parent="table" class="bulk-delete" />
								</th>
							' : '') . '
								<th width="1" class="border-top-0">
									' . phrase('options') . '
								</th>
							';
						}
						foreach($results->columns as $key => $val)
						{
							echo '
								<th class="border-top-0' . ('right' == $val->align ? ' text-right' : null) . '">
									<a href="' . go_to(null, array('order' => $val->field, 'sort' => get_userdata('sortOrder'))) . '" class="--xhr' . ($val->field == $this->input->get('order') ? ' text-primary' : ' text-default') . '">
										<i class="mdi mdi-sort-' . ($val->field == $this->input->get('order') && 'asc' == $this->input->get('sort') ? 'ascending' : 'descending') . ' float-right' . ($val->field == $this->input->get('order') ? ' text-primary' : ' text-muted') . '"></i>
										' . $val->label . '
									</a>
								</th>
							';
							$colspan++;
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					if($total > 0)
					{
						foreach($results->table_data as $key => $val)
						{
							$extra_option					= null;
							$extra_dropdown					= null;
							$reading						= true;
							$updating						= true;
							$deleting						= true;
							if($results->extra_action)
							{
								foreach($results->extra_action as $extra_key => $extra_val)
								{
									$url					= array
									(
										'q'					=> null,
										'per_page'			=> null
									);
									if(isset($extra_val->parameter))
									{
										foreach($extra_val->parameter as $uri_key => $uri_val)
										{
											$url[$uri_key]	= (isset($val->$uri_val->original) ? $val->$uri_val->original : $uri_val);
										}
									}
									if('option' == $extra_val->placement)
									{
										$class				= null;
										$label				= null;
										$icon				= null;
										if(isset($extra_val->new_tab) && is_object($extra_val->new_tab))
										{
											if(isset($extra_val->new_tab->restrict))
											{
												$id			= key((array) $extra_val->new_tab->restrict);
												if(in_array($val->$id->original, $extra_val->new_tab->restrict->$id)) continue;
											}
											else
											{
												$original	= $extra_val->new_tab->key;
												if(isset($extra_val->new_tab->key) && isset($extra_val->new_tab->value) && isset($val->$original->original) && $val->$original->original == $extra_val->new_tab->value)
												{
													$class	= (isset($extra_val->new_tab->class) ? $extra_val->new_tab->class : null);
													$label	= (isset($extra_val->new_tab->label) ? $extra_val->new_tab->label : null);
													$icon	= (isset($extra_val->new_tab->icon) ? $extra_val->new_tab->icon : null);
												}
											}
										}
										$extra_option		.= '
											<a href="' . go_to($extra_val->url, $url) . '" class="btn btn-xs ' . ($class ? $class : ($extra_val->class ? $extra_val->class : 'btn-secondary --xhr')) . '" data-toggle="tooltip" title="' . ($label ? $label : $extra_val->label) . '"' . (isset($extra_val->new_tab) && !is_object($extra_val->new_tab) ? ' target="_blank"' : null) . '>
												<i class="' . ($icon ? $icon : ($extra_val->icon ? $extra_val->icon : 'mdi mdi-link')) . '"></i>
											</a>
										';
									}
									elseif('dropdown' == $extra_val->placement)
									{
										$extra_dropdown		.= '
											<a href="' . go_to($extra_val->url, $url) . '" class="list-group-item pt-1 pr-0 pb-1 pl-0 ' . ($extra_val->class ? $extra_val->class : '--xhr') . '"' . (isset($extra_val->new_tab) ? ' target="_blank"' : null) . '>
												<i class="' . ($extra_val->icon ? $extra_val->icon : 'mdi mdi-link') . '" style="width:22px"></i>
												' . $extra_val->label . '
											</a>
										';
									}
								}
							}
							
							$primary_key					= array();
							$token							= $results->security_token[$key]->hash;
							$columns						= null;
							foreach($val as $field => $params)
							{
								if($params->primary)
								{
									$primary_key[$field]	= $params->primary;
									if(isset($results->unset_read->$field) && is_array($results->unset_read->$field) && in_array($params->original, $results->unset_read->$field))
									{
										$reading			= false;
									}
									if(isset($results->unset_update->$field) && is_array($results->unset_update->$field) && in_array($params->original, $results->unset_update->$field))
									{
										$updating			= false;
									}
									if(isset($results->unset_delete->$field) && is_array($results->unset_delete->$field) && in_array($params->original, $results->unset_delete->$field))
									{
										$deleting			= false;
									}
								}
								
								if($params->hidden) continue;
								
								$columns					.= '
									<td id="__c_' . $field . '">
										' . $params->content . '
									</td>
								';
							}
							
							$primary						= array_merge($primary_key, array('token' => $token));
							$document_token					= array_merge($primary_key, array('r' => 'document'));
							$options						= (!in_array('delete', $results->unset_action) ? '
								<td>
									' . ($deleting ? '<input type="checkbox" name="bulk_delete[]" class="checker-children" value="' . htmlspecialchars(json_encode($primary_key)) . '" />' : '') . '
								</td>
								' : '') . '
								<td>
									<div class="btn-group">
										' . ($reading && !in_array('read', $results->unset_action) ? '
											<a href="' . go_to('read', $primary_key) . '" class="btn btn-primary btn-xs --open-modal-read" data-toggle="tooltip" title="' . phrase('read') . '">
												<i class="mdi mdi-magnify"></i>
											</a>
										' : null) . '
										' . ($updating && !in_array('update', $results->unset_action) ? '
											<a href="' . go_to('update', $primary_key) . '" class="btn btn-info btn-xs ' . (isset($modal_html) ? '--modal' : '--open-modal-form') . '" data-toggle="tooltip" title="' . phrase('update') . '">
												<i class="mdi mdi-square-edit-outline"></i>
											</a>
										' : null) . '
										' . $extra_option . '
										' . ($extra_dropdown || ($reading && !in_array('print', $results->unset_action)) || ($reading && !in_array('pdf', $results->unset_action)) ? '
											<button type="button" class="btn btn-xs btn-warning toggle-tooltip" style="padding-right:3px;padding-left:3px" data-title="' . phrase('more_options') . '" data-toggle="popover" data-trigger="focus" data-content=\'<div class="list-group list-group-flush">'  . $extra_dropdown . ($reading && !in_array('print', $results->unset_action) ? '<a href="' . go_to('print', $document_token) . '" class="list-group-item pt-1 pr-0 pb-1 pl-0" target="_blank"><i class="mdi mdi-printer" style="width:22px"></i>' . phrase('print') . '</a>' : null) . '' . ($reading && !in_array('pdf', $results->unset_action) ? '<a href="' . go_to('pdf', $document_token) . '" class="list-group-item pt-1 pr-0 pb-1 pl-0"  target="_blank"><i class="mdi mdi-file-pdf text-danger" style="width:22px"></i>' . phrase('pdf') . '</a>' : null) . '</div>\'" data-container="body" data-html="true">
												<i class="mdi mdi-chevron-down mdi-1x font-weight-bold"></i>
											</button>
										' : null) . '
										' . ($deleting && !in_array('delete', $results->unset_action) ? '
											<a href="' . go_to('delete', $primary_key) . '" class="btn btn-danger btn-xs --open-delete-confirm" data-toggle="tooltip" title="' . phrase('delete') . '">
												<i class="mdi mdi-trash-can-outline"></i>
											</a>
										' : null) . '
									</div>
								</td>
							';
							echo '
								<tr id="item__' . $token . '">
									' . ($has_option || !in_array('read', $results->unset_action) || !in_array('update', $results->unset_action) || !in_array('delete', $results->unset_action) || !in_array('print', $results->unset_action) || !in_array('pdf', $results->unset_action) ? $options : null) . '
									' . $columns . '
								</tr>
							';
						}
					}
					else
					{
						echo '
							<tr class="no-hover">
								<td colspan="' . $colspan . '">
									<div class="text-center pt-5 pb-5">
										<i class="mdi mdi-text mdi-5x text-muted"></i>
										<br />
										<p class="lead text-muted">
											' . phrase('no_matching_record_were_found') . '
										</p>
									</div>
								</td>
							</tr>
						';
					}
				?>
			</tbody>
		</table>
	</div>
	<div class="row alias-pagination border-top pt-2">
		<div class="col-12">
		<?php echo $this->template->pagination($pagination); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
		/**
		 * Add the form format into the local storage
		 */
		<?php if(!isset($modal_html)) { ?>if($.inArray('create', <?php echo json_encode($results->unset_action); ?>) === -1)
		{
			/* generate the response data */
			$.ajax
			({
				method: 'POST',
				url: '<?php echo go_to('create', $query_string); ?>',
				data:
				{
					prefer: 'modal'
				},
				beforeSend: function()
				{
					sessionStorage.setItem('form', '')
				}
			})
			.done(function(response)
			{
				sessionStorage.setItem('form', JSON.stringify(response));
			})
		}<?php } ?>

		/**
		 * Add the view format into the local storage
		 */
		if($.inArray('read', <?php echo json_encode($results->unset_action); ?>) === -1)
		{
			$.ajax
			({
				method: 'POST',
				url: '<?php echo go_to('read', $primary); ?>',
				data:
				{
					prefer: 'modal'
				},
				beforeSend: function()
				{
					sessionStorage.setItem('read', '')
				}
			})
			.done(function(response)
			{
				sessionStorage.setItem('read', JSON.stringify(response));
			})
		}
	})
</script>