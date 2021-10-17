<?php


class resource{
	
	public static $Motnhs = array(
		'1'=>'January',
		'2'=>'February',
		'3'=>'March',
		'4'=>'April',
		'5'=>'May',
		'6'=>'June',
		'7'=>'July',
		'8'=>'August',
		'9'=>'September',
		'10'=>'October',
		'11'=>'November',
		'12'=>'December'
	);
	
	public static $Days = array(
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9',
			'10',
			'11',
			'12',
			'13',
			'14',
			'15',
			'16',
			'17',
			'18',
			'19',
			'20',
			'21',
			'22',
			'23',
			'24',
			'25',
			'26',
			'27',
			'28',
			'29',
			'30',
			'31');
	
	
	public static $Years = array(
		'2005',
		'2006',
		'2007',
		'2008',
		'2009',
		'2010',
		'2011',
		'2012',
		'2013',
		'2014',
		'2015'
	);
	
	
	public static function Old1Month($curr){
		$old = 0;
		if($curr == 1){
			$old = 12;	
		}else{
			$old = $curr - 1;	
		}
		return $old;	
	}
	
	public static function currentTimeStamp(){
		
	}
	
	
}//$class

