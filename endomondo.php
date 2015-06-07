<?php

class Endomondo {
	
	private $dystans;
	private $czas;
	private $srednia;
	private $max;
	private $kalorie;
	
	public function __construct($url){

        $url = 'https://www.endomondo.com/rest/v1'.substr($url,strpos($url,'/users'));

        $json = file_get_contents($url);

        $data = json_decode($json);


        $this->set('dystans',sprintf("%01.2f", $data->distance));

        if($data->duration > 3600){
            $czas = gmdate('H:i:s',$data->duration);
        }else{
            $czas = gmdate('i:s',$data->duration);
        }

        $this->set('czas',  $czas);

        $this->set('srednia',sprintf("%01.2f", $data->speed_avg));

        $this->set('max',sprintf("%01.2f", $data->speed_max));

        $this->set('kalorie',$data->calories);

        $hr = 0;
        foreach($data->laps->metric as $i){
            $hr+=$i->average_heart_rate;
        }

        $avg_hr = $hr/count($data->laps->metric);
        $this->set('tetno',sprintf("%01.2f", $avg_hr));


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
			return $this->dystans;
	}
	
	public function getCzas(){
		return $this->czas;
	}
	
	public function getSrednia(){
        return $this->srednia;
	}
	
	public function getMax(){
		return $this->max;
	}
	
	public function getKalorie(){
		return $this->kalorie;
	}

    public function getTetno(){
        return $this->tetno;
    }
}

?>
