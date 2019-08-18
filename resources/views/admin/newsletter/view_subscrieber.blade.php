<div style=<?php echo (isset($result) && count($result) > 10) ? "height:350px" : ''; ?>>
<table class="mws-table mws-datatable">
	<tbody>
		<tr>
			<td style="tect-align:center"><b>User Email</b></td>
		</tr>
	</tbody>
	<tbody>
	<?php if(!empty($result)){ ?>
		@foreach($result as $record)
		<tr>
			<td>{{ $record }}</td>
		</tr>
		 @endforeach  
	</tbody>
</table>
<?php }else{ ?>
<table class="mws-table mws-datatable ">	
	<tr>
		<td align="center" width="100%"> {{ 'No Records Found' }}</td>
	</tr>	
<?php } ?>
</table>
</div>
