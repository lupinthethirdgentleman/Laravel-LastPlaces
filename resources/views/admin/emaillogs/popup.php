{{ HTML::style('css/admin/button.css') 
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<a data-dismiss="modal" class="close" href="javascript:void(0)">
			<span style="float:right" class="no-ajax" aria-hidden="true">x</span>
			<span class="sr-only no-ajax"></span></a>	
			<h4 class="modal-title" id="myModalLabel">
				<?php echo trans("messages.system_management.email_detail"); ?>
			</h4>
		</div>
		
		<div class="modal-body">
			<div class="mws-panel-body no-padding dataTables_wrapper">
				<table class="mws-table mws-datatable" style="border:1px solid #cccccc;" >
					<tbody>
					<?php 
					if(!empty($result)){  
						foreach($result as $value){ ?>
						<tr>
							<th><?php echo trans("messages.system_management.email_to"); ?></th>
							<td data-th='<?php echo trans("messages.system_management.email_to"); ?>'> <?php echo $value->email_to;  ?></td>
						</tr>
						<tr>
							<th><?php echo trans("messages.system_management.email_from"); ?></th>
							<td data-th='<?php echo trans("messages.system_management.email_from"); ?>'><?php  echo $value->email_from; ?></td>
						</tr>
						<tr>
							<th><?php echo trans("messages.system_management.subject"); ?></th>
							<td data-th='<?php echo trans("messages.system_management.subject"); ?>'><?php echo  $value->subject; ?></td>
						</tr>
						<tr>
							<th valign='top'><?php echo trans("messages.system_management.message"); ?></th>
							<td data-th='<?php echo trans("messages.system_management.message"); ?>' class="email-tdp"><?php  echo  $value->message; ?></td>
						</tr>
					<?php }	} ?>
					</tbody>		
				</table>
			</div>
			<div class="clearfix">&nbsp;</div>
		</div>
	</div>
</div>
