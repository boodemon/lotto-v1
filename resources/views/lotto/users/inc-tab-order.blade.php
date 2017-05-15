@include('lostrip.customer.inc-index-widget',['floating' => App\Models\OrderHead::counter('floating') ,
												'new' 	=> App\Models\OrderHead::counter('new'),
												'help' 	=> App\Models\OrderHead::counter('help') ,
												'wl' 	=> App\Models\OrderHead::counter('wl') ])
<table id="tb-order" class="table table-striped table-bordered responsive-utilities jambo_table">
	<thead>
		<tr class="headings">
			<th>Status</th>
			<th>Client ID</th>
			<th>Name</th>
			<th>Ticket</th>
			<th>Type</th>
			<th>Spect</th>
			<th>QTY</th>
			<th>Unit price</th>
			<th>Fee</th>
			<th>Amount</th>
			<th>Total</th>
			<th>Pmt</th>
			<th>Bank</th>
			<th>Time</th>
			<th>Received</th>
			<th class="w80">#</th>
		</tr>
	</thead>
	<tbody>
	@if($rows)
	<?php 
		$rw = [];
	?>
		@foreach($rows as $row)
		<?php 
				$status 	= $row->status;
				$wcStatus 	= $row->wc_status;
				$sale 		= $row->sale_id;
				$acc 		= $row->account_id;
				if($row->status < 2 ){
					if($row->sale_id != 0 && $row->account_id == 0){
						$class='color-red';
					}elseif($row->sale_id == 0 && $row->account_id != 0){
						$class='color-green';
					}else{
						$class='';
					}
				}else{
					$class = '';
				}
				
				$total  = $row->unit_price * $row->qty;
				
				// display tool tip user process //
				$logs = App\Models\Logs::where('ref_id',$row->id)->first();
				$tagtitle = 	( $row->sale_id 	!== 0 ? '<br/> Sale #' . 		App\User::field( $row->sale_id ) : '' )
							  . ( $row->account_id 	!== 0 ? '<br/> Account #'. 	App\User::field( $row->account_id) : '')
							  . ( $row->op_id 		!== 0 ? '<br/> Operation #'. 	App\User::field( $row->op_id) : '' )
							  . '<br/> Updated #'. date('d/m/Y H:i',strtotime($row->updated_at));
							  
				// Rule display icon first row //		  
				$rw[$row->id][] = $row->id;
				$no = count($rw[$row->id]);
				
				// Remark display 			
				$remark 	= App\Models\Remark::title($row->id);
				$rmTitle 	= App\Models\Remark::display($row->id);
				
				// Payment detail //	
				$pays = App\Models\Payment::orderQuery($row->id);
				
				// Row color from order status //
				$trClass = '';
				if( $status <= 1 ){
					if( $pays && $pays->bank == 'omise'){
						$trClass = 'tr-omise';
					}
				}
				
				if($status <= 0 && $status >= 2){
					if($pays->sale_id != 0 && $pays->account_id == 0){
						$trClass='tr-sale';
					}elseif($pays->sale_id == 0 && $pays->account_id != 0){
						$trClass='tr-account';
					}
				}
				
				if($status == 9){
					$trClass = 'tr-floating';
				}
				
				if($row->sale_status == 'help'){
					$trClass = 'tr-help';
				}
				
				$cancel = $row->sale_status == 'cancel' ? ' #' . $row->remark : '';
				$customer = json_decode($row->customer);
				$contactinfo = App\Models\OrderHead::contactinfo($row->customer) . ( !empty( $row->note ) ? '<br/><strong>Note : </strong> '. $row->note : '' );
				$meta 		 = App\Models\OrderList::htmlMeta($row->meta);
			?>
			
			<tr class="{{ $trClass }}">
				<td>
					@if($no == 1)
					{!! Lib::icon( $row->wc_status, $tagtitle ) !!}
					@if( $remark )
						<a href="#{{ $row->client_id }}" data-text="{{ $rmTitle }}" class="remark-note" title="{{ $remark }}" data-html="true" ><i class="fa fa-file-text-o"></i></a>
					@endif
					{!! $row->sale_status != '' ? Lib::icon( $row->sale_status,$cancel )  : '' !!}
					
					@endif
				</td>
				<td><div class="single" title="{{ $row->client_id }}">{{ $no == 1 ? $row->client_id  : ''}}</div></td>
				<td><div class="single contactinfo" title="{{ $contactinfo  }}" data-html="true">{{ ( $no == 1 && $customer ) ? $customer->first_name .' ' . $customer->last_name  : ''}}</div></td>
				<td><div class="single ticket" title="{{ $row->sku . ( !empty($row->name) ? '<br/>#'. ucfirst($row->name) : '' ) . ( $meta ? '<br/>' .$meta : '' ) }}" data-html="true">{{ $row->ticket }}</div></td>
				<td><div class="single" title="{{ isset($code[$row->type]) ? $code[$row->type] : $row->type }}">{{ isset($code[$row->type]) ? $code[$row->type] : $row->type }}</div></td>
				<td><div class="single" title="{{ isset($code[$row->spect]) ? $code[$row->spect] : $row->spect }}">{{ isset($code[$row->spect]) ? $code[$row->spect] : $row->spect }}</div></td>
				<td><div class="single" title="{{ $row->qty }}">{{ $row->qty }}</div></td>
				<td><div class="single text-right" title="{{ Lib::nb( $row->unit_price ) }}">{{ Lib::nb( $row->unit_price ,0,'' ) }}</div></td>
				<td><div class="single" title="{{ Lib::nb( $row->fee ,2 ) }}">{{ Lib::nb( $row->fee ,0,'' ) }}</div></td>
				<td><div class="single text-right" title="{{ Lib::nb( $row->amount) }}">{{ Lib::nb( $row->amount) }}</div></td>
				<td><div class="single text-right" title="{{ Lib::nb($total) }}">{{  $no == 1 ? Lib::nb($row->total_price,2) : '' }}</div></td>
				<td><div class="single text-center" title="{{ Lib::dateThai( $row->pmt ) }}">{{  $no == 1 ? Lib::pmtDate( $row->pmt ) : '' }}</div></td>
				<td class="{{ $class }}"><div class="single" title="{{ $pays ? $pays->bank : '' }}">{{ $no == 1 ?  ( $pays ? strtoupper($pays->bank) : '' ) : '' }}</div></td>
				<td class="{{ $class }}"><div class="single" title="{{ $pays ? $pays->time : '' }}">{{ $no == 1 ?  ( $pays ? $pays->time : '' ) : '' }}</div></td>
				<td class="{{ $class }}"><div class="single text-right" title="{{ Lib::nbshow( $pays ? $pays->received : 0 ) }}">{{ $no == 1 ? Lib::nbshow(  $pays ? $pays->received : 0 , 2 )  : '' }}</div></td>
				<td class="action">
					@if($no == 1)
					@if($logs)
					<a href="{{ $row->id}}" data-client="{{ $row->client_id }}" title="History logs" class="order-logs color-gray"><i class="fa fa-history"></i></a>
					@endif
					@if( ( $level == 'sale'  ) )
						<a href="{{url('customers/' . $row->id . '/edit'  )}}" title="Edit order {{ $tagtitle }}" data-html="true"><span class="glyphicon glyphicon-edit {{ ($row->status <= 0 && $row->status >= 4 ) ? $color[$row->status] : $row->wc_status }} icon-edit  fs-16"></span></a>&nbsp;
					@endif
					@endif
				</td>
			</tr>
			@endforeach
		@endif
		</tbody>
	</table>
	