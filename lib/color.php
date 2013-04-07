<?php
	
class Color {
	public $a = 0;
	public $b = 0;
	public $c = 0;
	
	function __construct($a, $b, $c){
		$this->a = $a;
		$this->b = $b;
		$this->c = $c;
	}
	
	private static function contain($a){
		return max(0, min(255,$a));
	}
	
	function invert(){
		return new Color(255 - $this->a, 255 - $this->b, 255 - $this->c);
	}
	function invertLargest(){
		$peek = $this->locatePeek();
		return new Color(
			$peek === 0 ? 256/2 : $this->a,
			$peek === 1 ? 256/2 : $this->b,
			$peek === 2 ? 256/2 : $this->c
		);
	}
	
	function distance(Color $other){
		return (pow($this->a - $other->a,2)/255 + pow($this->b - $other->b,2)/255 + pow($this->c - $other->c,2)/255)/3/255;
	}
	
	static function random(){
		return new Color(rand(0, 255),rand(0, 255),rand(0, 255));
	}
	
	function diff($other){
		return new Color(
			$this->a - $other->a,
			$this->b - $other->b,
			$this->c - $other->c
		);
	}
	
	function to(Color $other, $where){
		$diff = $this->diff($other);
		return new Color(
			$this->a - $diff->a * $where,
			$this->b - $diff->b * $where,
			$this->c - $diff->c * $where
		);
	}
	
	static function white(){ return new Color(255,255,255); }
	static function black(){ return new Color(0,0,0); }
	
	function __toString(){
		$hex = "#";
		$hex .= str_pad(dechex($this->a), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($this->b), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($this->c), 2, "0", STR_PAD_LEFT);
		return $hex;
	}
	
	private function locatePeek(){
		$peek = 0;
		$color = -1;
		if(abs($this->a - 255/2) > $peek)
		{
			$peek = abs($this->a - 255/2); $color = 0;
		}
		if(abs($this->b - 255/2) > $peek)
		{
			$peek = abs($this->b - 255/2); $color = 1;
		}
		if(abs($this->c - 255/2) > $peek)
		{
			$peek = abs($this->c - 255/2); $color = 2;
		}
		return $color;
	}
}
	
?>