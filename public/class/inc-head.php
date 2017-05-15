<?php 
	// :: SET EXCEL FORM :://
	$xls->getProperties()->setCreator('Customer order ');
	$xls->getProperties()->setLastModifiedBy("Customer order ");
	$xls->getProperties()->setTitle('Customer order ');
	$xls->getProperties()->setSubject('Customer order' );
	$xls->getProperties()->setDescription('Customer order ');

	//$label=$xls->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)->setBold(true);
	$xls->getActiveSheet()->getDefaultStyle()->getFont()->setName("Browallia New")->setSize(14);
	$xls->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$xls->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	$xls->getActiveSheet()->getPageSetup()->setFitToWidth(1); 
	$xls->getActiveSheet()->getPageSetup()->setFitToHeight(0);

	//:: Set Array Style :://
	$default_border = array(
		'style' => PHPExcel_Style_Border::BORDER_THIN,
		'color' => array('rgb'=>'999999')
	);
	$bold_border2 = array(
		'style' => PHPExcel_Style_Border::BORDER_HAIR,
		'color' => array('rgb'=>'999999')
	);
	$bold_border = array(
		'style' => PHPExcel_Style_Border::BORDER_THICK,
		'color'	=> array('rgb'=>'000000')
	);
	$style_header = array(
		'borders' => array(
			'top'	 => $bold_border,
			'bottom' => $default_border,
			'left'	 => $default_border,
			'right'	 => $default_border
		),
	);
	$bgwhite = array(
			'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'ffffff')
		),
	);
	
	$style_head_title = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => '666666')
		),
		'font'  => array(
			'color' => array('rgb' => 'FFFFFF'),
		)
	);
	
	$all_border = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
	);
	
	$standard_border = array(
		'borders' => array(
			'top'	 => $default_border,
			'bottom' => $default_border,
			'left'	 => $default_border,
			'right'	 => $default_border
		),
	);
	$standard_border_bold = array(
		'borders' => array(
			'top'	 => $bold_border,
			'bottom' => $bold_border,
			'left'	 => $bold_border,
			'right'	 => $bold_border
		),
	);
	
	$border_left_top = array(
		'borders' => array(
			'top'	 => $default_border,
			'left'	 => $default_border,
		),
	);

	
	$border_left_bottom = array(
		'borders' => array(
			'bottom' => $default_border,
			'left'	 => $default_border,
		),
	);

	
	$border_top_rignt = array(
		'borders' => array(
			'top'	 => $default_border,
			'right'	 => $default_border
		),
	);	

	$border_bottom_rignt = array(
		'borders' => array(
			'bottom' => $default_border,
			'right'	 => $default_border
		),
	);

	$border_left = array(
		'borders' => array(
			'left'	 => $default_border
		),
	);

	$border_rignt = array(
		'borders' => array(
			'right'	 => $default_border
		),
	);

	$border_top = array(
		'borders' => array(
			'top'	 => $default_border,
		),
	);	$border_bottom = array(
		'borders' => array(
			'bottom' => $default_border,
		),
	);
	
	$fontred = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 16
	));
	