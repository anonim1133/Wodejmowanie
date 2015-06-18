<?php

class Endomondo {
	
	private $dystans;
	private $czas;
	private $srednia;
	private $max;
	private $kalorie;
    private $tetno;
	
	public function __construct($url){
//https\:\/\/www\.endomondo\.com\/users\/(\d+)\/workouts\/(\d+)



        $url = $this->api_url($url);

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
        if(property_exists($data,'calories'))
            $this->set('kalorie',$data->calories);
        else
            $this->set('kalorie',-1);

        $hr = 0;
        foreach($data->laps->metric as $i){
            $hr+=$i->average_heart_rate;
        }

        $avg_hr = $hr/count($data->laps->metric);
        $this->set('tetno',sprintf("%01.2f", $avg_hr));


	}


    private function api_url($url){

        preg_match("/https?:\/\/www\.endomondo\.com\/users\/(\d+)\/workouts\/(\d+)/",$url,$out);
        if(count($out) == 3){
            return 'https://www.endomondo.com/rest/v1/users/'.$out[1].'/workouts/'.$out[2];
        }

        preg_match("/https?:\/\/www\.endomondo\.com\/workouts\/(\d+)\/(\d+)/",$url,$out);
        if(count($out) == 3){
            return 'https://www.endomondo.com/rest/v1/users/'.$out[2].'/workouts/'.$out[1];
        }

        throw new Exception('Niepoprawny url');

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
