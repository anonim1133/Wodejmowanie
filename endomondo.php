<?php

class Endomondo {
	
	private $dystans;
	private $czas;
	private $srednia;
	private $max;
	private $kalorie;
	
	public function __construct($url){
		$dom = new DomDocument();

		@$dom->loadHTML(file_get_contents($url));;

		$matches = $this->getElementsByClassName($dom, 'summary');
	
		$matches = $matches[count($matches)-1]->ownerDocument->saveXML($matches[count($matches)-1]);
	
		@$dom->loadHTML(ereg_replace('class', 'id', $matches));

		$li = $dom->getElementById('distance');
		$this->set('dystans', trim(explode("\n", $li->nodeValue)[2]));
	
		$li = $dom->getElementById('duration');
		$this->set('czas', trim(explode("\n", $li->nodeValue)[2]));
	
		$li = $dom->getElementById('avg-speed');
		$this->set('srednia', trim(explode("\n", $li->nodeValue)[2]));
	
		$li = $dom->getElementById('max-speed');
		@$this->set('max', trim(explode("\n", $li->nodeValue)[2]));
	
		$li = $dom->getElementById('calories');
		$this->set('kalorie', trim(explode("\n", $li->nodeValue)[2]));
	}
	
	private function set($var, $value) {
		$this->$var = $value;
	}
	
	private function getElementsByClassName(DOMDocument $DOMDocument, $ClassName){
		$Elements = $DOMDocument -> getElementsByTagName("*");
		$Matched = array();
	
		foreach($Elements as $node)
		{
			if( ! $node -> hasAttributes())
				continue;
	
			$classAttribute = $node -> attributes -> getNamedItem('class');
	
			if( ! $classAttribute)
				continue;
	
			$classes = explode(' ', $classAttribute -> nodeValue);
	
			if(in_array($ClassName, $classes))
				$Matched[] = $node;
		}
	
		return $Matched;
	}

	public function getDystans(){
		if(preg_match("/mi$/", $this->dystans) > 0)
			return number_format((real)$this->dystans*1.609344, 2, ',', ' ').' km';
		else
			return $this->dystans;
	}
	
	public function getCzas(){
		return $this->czas;
	}
	
	public function getSrednia(){
		if(preg_match("/min\/mi$/", $this->srednia) > 0){
			$t = number_format((real)$this->srednia/1.53, 2, ',', ' ');

			$split = explode(',', $t);

			$min = $split[0];
			$s = $split[1];

			if($s >= 60){
				$min++;
				$s -= 60;
			}

			if($s < 10)
				$s = '0'.$s;

			return $min.':'.$s.' min/km';
		}elseif(preg_match("/mph$/", $this->srednia) > 0)
			return number_format((real)$this->srednia*1.609344, 2, ',', ' ').' km/h';
		else
			return $this->srednia;
	}
	
	public function getMax(){
		if(preg_match("/min\/mi$/", $this->max) > 0){
			$t = number_format((real)$this->max/1.53, 2, ',', ' ');
			$split = explode(',', $t);

			$min = $split[0];
			$s = $split[1];

			if($s >= 60){
				$min++;
				$s -= 60;
			}

			if($s < 10)
				$s = '0'.$s;

			return $min.':'.$s.' min/km';
		}
		elseif(preg_match("/mi$/", $this->max) > 0)
			return number_format((real)$this->max*1.609344, 2, ',', ' ').' km';
		else
			return $this->max;
	}
	
	public function getKalorie(){
		return $this->kalorie;
	}
}

?>
