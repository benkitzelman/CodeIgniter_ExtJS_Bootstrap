<?php
class SampleStudent 
{
	public static function michael()
	{
		$s = new Student();
		
		$s->title = "Mr";
		$s->first_name = "Michael";
		$s->last_name = "Jordan";
		$s->student_number = "n274023";
		
		return $s;
	}
	
	public static function morris()
	{
		$s = new Student();
		
		$s->title = "Mr";
		$s->first_name = "Morris";
		$s->last_name = "Mickelwhite";
		$s->student_number = "n274024";
		
		return $s;
	}
}