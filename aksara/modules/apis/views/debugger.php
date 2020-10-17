<div class="container-fluid pt-3 pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--api-debug">
		<div class="row mb-3">
			<div class="col-md-10">
				<div class="input-group">
					<select name="method" class="form-control mb-3" style="max-width:100px">
						<option value="GET">
							GET
						</option>
						<option value="POST">
							POST
						</option>
						<option value="PUT">
							PUT
						</option>
						<option value="DELETE">
							DELETE
						</option>
					</select>
					<input type="text" name="url" class="form-control mb-3" placeholder="Enter request URL" />
					<div class="input-group-append">
						<button type="button" class="btn btn-outline-secondary mb-3" onclick="jExec($('#rest-parameter').toggleClass('d-none'))">
							Params
						</button>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<button type="submit" class="btn btn-primary btn-block">
					<i class="mdi mdi-send"></i>
					Send
				</button>
			</div>
		</div>
		
		<div id="rest-parameter" class="d-none">
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<a class="nav-item nav-link active" data-toggle="tab" href="#params-headers" role="tab">
						Headers
					</a>
					<a class="nav-item nav-link" data-toggle="tab" href="#params-body" role="tab">
						Body
					</a>
				</div>
			</nav>
			<div class="tab-content pt-3 pb-3" id="nav-tabContent1">
				<div class="tab-pane fade show active" id="params-headers" role="tabpanel">
					<div class="row">
						<div class="text-muted col-6 col-md-4">
							<div class="form-group">
								<input type="text" name="header_key[]" class="form-control form-control-sm param-header-key" placeholder="Key" />
							</div>
						</div>
						<div class="text-muted col-6 col-md-6 pl-0">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="header_value[]" class="form-control form-control-sm param-header-value" placeholder="Value" />
									<div class="input-group-append">
										<button type="button" class="btn btn-secondary btn-sm" onclick="jExec($(this).closest('.row').remove())">
											<i class="mdi mdi-window-close"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="button" class="btn btn-secondary btn-sm --add-parameter" data-parameter="header">
						<i class="mdi mdi-plus"></i>
						Add Parameter
					</button>
				</div>
				<div class="tab-pane fade" id="params-body" role="tabpanel">
					<div class="row">
						<div class="text-muted col-6 col-md-4">
							<div class="form-group">
								<input type="text" name="body_key[]" class="form-control form-control-sm param-body-key" placeholder="Key" />
							</div>
						</div>
						<div class="text-muted col-6 col-md-6 pl-0">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="body_value[]" class="form-control form-control-sm param-body-value" placeholder="Value" />
									<div class="input-group-append">
										<button type="button" class="btn btn-secondary btn-sm" onclick="jExec($(this).closest('.row').remove())">
											<i class="mdi mdi-window-close"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="button" class="btn btn-secondary btn-sm --add-parameter" data-parameter="body">
						<i class="mdi mdi-plus"></i>
						Add Parameter
					</button>
				</div>
			</div>
		</div>
	</form>
		
	<nav>
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<a class="nav-item nav-link active" data-toggle="tab" href="#results-pretty" role="tab">
				<i class="mdi mdi-code-braces"></i>
				Response
			</a>
		</div>
	</nav>
	<div class="tab-content" id="nav-tabContent2">
		<div class="tab-pane fade show active" id="results-pretty" role="tabpanel">
			<pre class="code border" data-language="json"></pre>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		$('.--add-parameter').on('click', function(e)
		{
			var initial					= $(this).attr('data-parameter');
			e.preventDefault(),
			$(
				'<div class="row">' +
					'<div class="text-muted col-6 col-md-4">' +
						'<div class="form-group">' +
							'<input type="text" name="' + initial + '_key[]" class="form-control form-control-sm param-' + initial + '-key" placeholder="Key" />' +
						'</div>' +
					'</div>' +
					'<div class="text-muted col-6 col-md-6 pl-0">' +
						'<div class="form-group">' +
							'<div class="input-group">' +
								'<input type="text" name="' + initial + '_value[]" class="form-control form-control-sm param-' + initial + '-value" placeholder="Value" />' +
								'<div class="input-group-append">' +
									'<button type="button" class="btn btn-secondary btn-sm" onclick="jExec($(this).closest(\'.row\').remove())">' +
										'<i class="mdi mdi-window-close"></i>' +
									'</button>' +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>'
			)
			.insertBefore($(this))
		}),
		
		$('.--api-debug').on('submit', function(e)
		{
			e.preventDefault();
			if(!$(this).find('input[name=url]').val())
			{
				$('pre.code').text(JSON.stringify({error: "No request URL given"}, null, 4))
				.highlight
				({
					source: false,
					zebra: true,
					indent: 'tab'
				});
				return;
			}
			
			var header							= {},
				body							= {},
				method							= $(this).find('select[name=method]').val(),
				parameter						= new FormData(this);
			
			$('.param-header-key').each(function(num, value)
			{
				var key							= $(this).val(),
					val							= $('.param-header-value:eq(' + num + ')').val();
				if(val)
				{
					header[key]					= val;
				}
			}),
			
			$('.param-body-key').each(function(num, value)
			{
				var key							= $(this).val(),
					val							= $('.param-body-value:eq(' + num + ')').val();
				if(val)
				{
					body[key]					= val;
				}
			}),
			
			$.ajax
			({
				url: $(this).find('input[name=url]').val(),
				method: method,
				data: body,
				headers: header,
				beforeSend: function()
				{
					$('pre.code').text(''),
					$('.result-html').html('')
				}
			})
			.done(function(response)
			{
				if(typeof response !== 'object')
				{
					response					= {
						error: 'The response isn\'t not a valid object'
					};
				}
				$('pre.code').text(JSON.stringify(response, null, 4))
				.highlight
				({
					source: false,
					zebra: true,
					indent: 'tab'
				})
			})
			.fail(function(status, text, message)
			{
				$('pre.code').text(JSON.stringify(status, null, 4))
				.highlight
				({
					source: false,
					zebra: true,
					indent: 'tab'
				})
			})
		})
	})
</script>