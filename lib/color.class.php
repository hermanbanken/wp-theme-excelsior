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
	
	/* Hex shift x steps. Better matching color than HSB shifting
	@see http://coding.smashingmagazine.com/2012/10/04/the-code-side-of-color/ */
	function shift($steps){
		return new Color(
			self::contain($this->a + $steps), 
			self::contain($this->b + $steps), 
			self::contain($this->c + $steps)
		);
	}
	/* Toggle colors for complements
	@see http://coding.smashingmagazine.com/2012/10/04/the-code-side-of-color/ */
	function complement($i = 1){
		switch($i){
			case 1: return new Color($this->c, $this->a, $this->b);
			case 2: return new Color($this->b, $this->c, $this->a);
			case 3: return new Color($this->a, $this->c, $this->b);
			case 4: return new Color($this->a, $this->b, $this->c);
			case 5: return new Color($this->b, $this->a, $this->c);
			case 6: return new Color($this->c, $this->b, $this->a);
			default: return $this;
		}
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
	
	static function fromHex($hex){
		if(substr($hex, 0, 1) == "#")
			$hex = substr($hex, 1);
		return new Color(hexdec(substr($hex, 0, 2)),hexdec(substr($hex, 2, 2)),hexdec(substr($hex, 4, 2)));
	}
}