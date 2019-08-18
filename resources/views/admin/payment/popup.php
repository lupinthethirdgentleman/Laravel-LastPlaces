{{ HTML::style('css/admin/button.css') 
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a data-dismiss="modal" class="close" href="javascript:void(0)">
			<span style="float:right" class="no-ajax" aria-hidden="true">x</span>
			<span class="sr-only no-ajax"></span></a>	
			<h4 class="modal-title" id="myModalLabel">
				User Pricing Detail
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
							<th>User Name</th>
							<td> <?php echo $value->name; ?></td>
						</tr>
						<tr>
							<th>User Email</th>
							<td> <?php echo $value->email; ?></td>
						</tr>

						<tr>
							<th>Address</th>
							<td> <?php echo $value->address; ?></td>
						</tr>
						
						<tr>
							<th>Phone</th>
							<td> <?php echo $value->phone; ?></td>
						</tr>

						<tr>
							<th>Booking Type</th>
							<td> <?php if($value->booking_type == 1){ echo "Package Booking"; }else{ echo "Tailored Booking"; } ?></td>
						</tr>

						<tr>
							<th>Trip Name</th>
							<td> <?php echo $value->trip_name; ?></td>
						</tr>

						<tr>
							<th>Package Trip Days</th>
							<td> <?php echo $value->trip_day; ?></td>
						</tr>


						<tr>
							<th>Payment Date</th>
							<td> 
							<?php 
								$date	= date(Config::get("Reading.date_format"),strtotime($value->created_at));
								echo $date; 
							?>
							</td>
						</tr>

						<tr>
							<th>Trip Price</th>
							<td> <?php echo Config::get("Site.currency").  $value->package_price; ?></td>
						</tr>

						<tr>
							<th>No. Of Traveller</th>
							<td> <?php echo $value->number_of_traveller; ?></td>
						</tr>

						<tr>
							<th>Tax Amount</th>
							<td> <?php echo Config::get("Site.currency") . $value->tax_amount; ?></td>
						</tr>

						<tr>
							<th>Amount Paid</th>
							<td><?php echo  Config::get("Site.currency").$value->amount;?></td>
						</tr>
						<?php if($value->booking_type == 2){ ?>
						<tr>
							<th>Departure City</th>
							<td><?php echo $value->departure_city;?></td>
						</tr>

						
						<tr>
							<th>Departure Country</th>
							<td><?php echo $value->departure_country;?></td>
						</tr>

						<tr>
							<th>Total Amount [ Tailored Booking ]</th>
							<td><?php echo Config::get("Site.currency") . $value->totalamount;?></td>
						</tr>
					<?php } ?>
				
						
						<?php if($value->transaction_id && $value->payment_status){?>
							<tr>
								<th>Transaction Id</th>
								<td><?php echo $value->transaction_id; ?></td>
							</tr>
							<tr>
								<th>Payment Status</th>
								<td><?php echo $value->payment_status; ?></td>
							</tr>							
						<?php } } } ?>
					</tbody>		
				</table>
			</div>
			<div class="clearfix">&nbsp;</div>
		</div>
	</div>
</div>
