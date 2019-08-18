<?php
if(!empty($searching)){ ?>
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo (isset($display_serach)) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo (isset($display_serach)) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		<?php  
		echo $this->Form->create($model,array('autocomplete' => 'off'),'');
		echo $this->Form->hidden($model.".display");

		foreach($searching as $main_key => $fields){ 
			switch($main_key){
				case 'text':
					if(!empty($fields)){
						foreach($fields as $key => $val){ ?>
							<div class="mws-themer-section">
								<div id="mws-theme-presets-container" class="mws-themer-section">
									<label><?php echo $val; ?></label><br/>
									<?php  echo $this->Form->text($key); ?>
								</div>
							</div>
							<div class="mws-themer-separator"></div>
						<?php 
						}
					}
					break;
				case 'select':
					if(!empty($fields)){
						foreach($fields as $key => $val){ 
							$name	= $val['0'];
							$option	= $val['option'];
							?>
							<div class="mws-themer-section">
								<div id="mws-theme-presets-container" class="mws-themer-section">
									<label><?php echo $name; ?></label><br/>
									<?php  echo $this->Form->select($key,$option,array('empty' => 'plese select '.$name)); ?>
								</div>
							</div>
							<div class="mws-themer-separator"></div>
				<?php 	}
					}
					break;
				case 'datepicker':
					if(!empty($fields)){
						foreach($fields as $key => $val){ ?>
							<div class="mws-themer-section">
								<div id="mws-theme-presets-container" class="mws-themer-section">
									<label><?php echo $val; ?></label><br/>
									<?php  echo $this->Form->text($key,array('class' => 'datepicker larger','readonly','placeholder' => 'Select date')); ?>
								</div>
							</div>
							<div class="mws-themer-separator"></div>
						<?php 
						}
					}
					break;
			}
		}

		?>
		
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		
		<div class="mws-themer-section">
			<?php echo $this->Form->button(__('Search',true),array('action'=>'index','class'=>'btn btn-primary btn-small small','escape'=>false)); 
			
			if(isset($searching['reset'])) { 
				$slug	=	$searching['reset']['url'];
				echo $this->Html->link(__('Reset',true),array('action'=>'index',$slug),array('class'=>'btn btn-danger btn-small small','escape'=>false)); 
			}else{
				echo $this->Html->link(__('Reset',true),array('action'=>'index'),array('class'=>'btn btn-danger btn-small small','escape'=>false)); 
			} ?>
		</div>
		
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<?php } ?>