<?php include public_path().'/class/inc-head.php';
	//Page margins // กำหนดระยะขอบ
	$xls->getActiveSheet()->getPageMargins()->setTop(0.5); 
	$xls->getActiveSheet()->getPageMargins()->setRight(0.5); 
	$xls->getActiveSheet()->getPageMargins()->setLeft(0.5); 
	$xls->getActiveSheet()->getPageMargins()->setBottom(0.5);
	
	//:: SET COLUMN WIDTH :://
	$xls->getActiveSheet()->getColumnDimension('A')->setWidth(13);
	$xls->getActiveSheet()->getColumnDimension('B')->setWidth(11);
	$xls->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$xls->getActiveSheet()->getColumnDimension('D')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('E')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('F')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('H')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('I')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('J')->setWidth(12);
	$xls->getActiveSheet()->getColumnDimension('K')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('L')->setWidth(12);
	$xls->getActiveSheet()->getColumnDimension('M')->setWidth(12);
	$xls->getActiveSheet()->getColumnDimension('N')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$xls->getActiveSheet()->getColumnDimension('P')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	$xls->getActiveSheet()->getColumnDimension('R')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('S')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('T')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('U')->setWidth(10);
	$xls->getActiveSheet()->getColumnDimension('V')->setWidth(10);

	// :: MERGE CELL :://


	// :: SET TEXT ALIGN :://
	$xls->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$xls->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(16)->setBold(true);

	//:: SET STYLE BORDER SHEET :://
	$xls->getActiveSheet()->getStyle('A1:V1')->applyFromArray( $style_head_title );

	// :: SET DETAIL SHEET :: //
	
	$xls->getActiveSheet()->getStyle('A1')->getFont()->setSize(24)->setBold(true);
	$xls->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(14)->setBold(true);
	
	$xls->getActiveSheet()->SetCellValue( 'A1', 'Order#' );
	$xls->getActiveSheet()->SetCellValue( 'B1', 'Client ID' );
	$xls->getActiveSheet()->SetCellValue( 'C1', 'Name' );
	$xls->getActiveSheet()->SetCellValue( 'D1', 'Dep' );
	$xls->getActiveSheet()->SetCellValue( 'E1', 'Supplier' );
	$xls->getActiveSheet()->SetCellValue( 'F1', 'Ticket' );
	$xls->getActiveSheet()->SetCellValue( 'G1', 'Type' );
	$xls->getActiveSheet()->SetCellValue( 'H1', 'Spect' );
	$xls->getActiveSheet()->SetCellValue( 'I1', 'QTY' );
	$xls->getActiveSheet()->SetCellValue( 'J1', 'Unit price' );
	$xls->getActiveSheet()->SetCellValue( 'K1', 'Fee' );
	$xls->getActiveSheet()->SetCellValue( 'L1', 'Amount' );
	$xls->getActiveSheet()->SetCellValue( 'M1', 'Total' );
	$xls->getActiveSheet()->SetCellValue( 'N1', 'Less' );
	$xls->getActiveSheet()->SetCellValue( 'O1', 'Del' );
	$xls->getActiveSheet()->SetCellValue( 'P1', 'PC' );
	$xls->getActiveSheet()->SetCellValue( 'Q1', 'Accm' );
	$xls->getActiveSheet()->SetCellValue( 'R1', 'Pmt' );
	$xls->getActiveSheet()->SetCellValue( 'S1', 'Bank' );
	$xls->getActiveSheet()->SetCellValue( 'T1', 'Time' );
	$xls->getActiveSheet()->SetCellValue( 'U1', 'Received' );
	$xls->getActiveSheet()->SetCellValue( 'V1', 'Paid' );
	
	
	/*:: START DETAIL DESIGN TABLE ::*/
		if($orders){
			foreach($orders AS $row){
				$pmt 		= Lib::pmtDate( $row->pmt );
				$customer = json_decode($row->customer);
				$contactinfo = App\Models\OrderHead::contactinfo($row->customer);
				//$total = $row->amount;
			
				$day[$pmt][] = $pmt;
				if( count($day[$pmt]) == 1){
					$pc 	=  0;
					$accm 	=  0;
				}
				
				$pc 	+= $row->qty;
				$accm 	+= $row->amount;
				$total  = $row->unit_price * $row->qty;

				// Payment detail //	
				$pays = App\Models\Payment::orderQuery($row->id);
				$paid 		= $pays ? ( $pays->bank == 'omise' ? $pays->received : $pays->paid ) : '-';
				$received 	= $pays ? ( $pays->bank == 'omise' ? ( $pays->received - ($pays->received * 3 / 100 ) ) : $pays->received ) : '-';
				
				$totalPrice = $row->total_price;

				++$rows ;
				$xls->getActiveSheet()->getStyle("A$rows:V$rows")->applyFromArray( $all_border );			
				$xls->getActiveSheet()->getStyle("A$rows:B$rows")->getAlignment()->setWrapText(true)
									  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)
									  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$xls->getActiveSheet()->getStyle("J$rows")->getNumberFormat()->setFormatCode('#,##0.00');
				$xls->getActiveSheet()->getStyle("L$rows:N$rows")->getNumberFormat()->setFormatCode('#,##0.00');
				$xls->getActiveSheet()->getStyle("Q$rows")->getNumberFormat()->setFormatCode('#,##0.00');
				$xls->getActiveSheet()->getStyle("U$rows:V$rows")->getNumberFormat()->setFormatCode('#,##0.00');
									  
				
				$xls->getActiveSheet()->SetCellValue("A$rows",$row->invoice);			
				$xls->getActiveSheet()->SetCellValue("B$rows",$row->client_id);
				$xls->getActiveSheet()->SetCellValue("C$rows",$customer ? $customer->first_name .' ' . $customer->last_name : '' );
				$xls->getActiveSheet()->SetCellValue("D$rows",Lib::dateDep($row->dep) != FALSE ? Lib::dateDep($row->dep) : '');
				$xls->getActiveSheet()->SetCellValue("E$rows",$row->order_no);
				$xls->getActiveSheet()->SetCellValue("F$rows",$row->ticket);
				$xls->getActiveSheet()->SetCellValue("G$rows",isset($code[$row->type]) ? $code[$row->type] : $row->type );
				$xls->getActiveSheet()->SetCellValue("H$rows",isset($code[$row->spect]) ? $code[$row->spect] : $row->spect );
				$xls->getActiveSheet()->SetCellValue("I$rows",$row->qty);
				$xls->getActiveSheet()->SetCellValue("J$rows",$row->unit_price);
				$xls->getActiveSheet()->SetCellValue("K$rows",$row->fee);
				$xls->getActiveSheet()->SetCellValue("L$rows",$row->amount);
				$xls->getActiveSheet()->SetCellValue("M$rows",$total);
				$xls->getActiveSheet()->SetCellValue("N$rows",$row->less);
				$xls->getActiveSheet()->SetCellValue("O$rows",$row->delivery);
				$xls->getActiveSheet()->SetCellValue("P$rows",$pc);
				$xls->getActiveSheet()->SetCellValue("Q$rows",$accm);
				$xls->getActiveSheet()->SetCellValue("R$rows",$pmt);
				$xls->getActiveSheet()->SetCellValue("S$rows",$pays ? $pays->bank : '');
				$xls->getActiveSheet()->SetCellValue("T$rows",$pays ? $pays->time : '');
				$xls->getActiveSheet()->SetCellValue("U$rows",$received );
				$xls->getActiveSheet()->SetCellValue("V$rows",$paid );
			}
		}

	
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
	$objWriter->save($excel); 
