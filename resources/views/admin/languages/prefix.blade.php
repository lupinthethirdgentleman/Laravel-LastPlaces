@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span>Setting {{ $prefix }}</span>
	</div>
	<div class="mws-panel-body no-padding">
	
		{{ Form::open(['role' => 'form','url' => 'admin/settings/prefix/'.$prefix,'class' => 'mws-form']) }}
	
			<div class="mws-form-inline">
			<?php $i = 0;
		if(!empty($result)){
			foreach ($result AS $setting) {
				$text_extention 	= 	'';
				$key				= 	$setting['key'];
				$keyE 				= 	explode('.', $key);
				$keyTitle 			= 	$keyE['1'];
		
				$label = $keyTitle;
				if ($setting['title'] != null) {
					$label = $setting['title'];
				}

				$inputType = 'text';
				if ($setting['input_type'] != null) {
					$inputType = $setting['input_type'];
				} ?>
				
				{{ Form::hidden("Setting[$i]['type']",$inputType) }}
				{{ Form::hidden("Setting[$i]['id']",$setting['id']) }}
				{{ Form::hidden("Setting[$i]['key']",$setting['key']) }}
			<?php 
				
				switch($inputType){
					case 'checkbox':
			?>	
				
				<div class="mws-form-row">
					<label class="mws-form-label" style="width:300px;"><?php echo $label; ?></label>
					<div class="mws-form-item clearfix">
						<ul class="mws-form-list inline">
							<?php 	
								$checked = ($setting['value'] == 1 )? true: false;
								$val	 = (!empty($setting['value'])) ? $setting['value'] : 0;
							?>
							{{ Form::checkbox("Setting[$i]['value']",$val,$checked) }} 
						</ul>
					</div>
				</div>
			<?php
					break;	
					case 'textarea':	
					case 'text':
					
			?>
				
				<div class="mws-form-row">
					<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
					<div class="mws-form-item">
						{{ Form::{$inputType}("Setting[$i]['value']",$setting['value'], ['class' => 'small']) }} 
					</div>
				</div>
			<?php
					break;	
					default:
				?>
				
				<div class="mws-form-row">
					<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
					<div class="mws-form-item">
						{{ Form::textarea("Setting[$i]['value']",$setting['value'], ['class' => 'small']) }} 
					</div>
				</div>
			<?php	
					break;
						
				}
				$i++;
			}
		}
		?>	
			</div>
			<div class="mws-button-row">
				<input type="submit" value="Submit" class="btn btn-danger">
			</div>
		{{ Form::close() }} 
	</div>    	
</div>

@stop
