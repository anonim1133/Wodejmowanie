<?php

class Endomondo {
	
	private $dystans;
	private $czas;
	private $srednia;
	private $max;
	private $kalorie;
    private $tetno;
	
	public function __construct($url){

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

        preg_match("/https?:\/\/(www|app)\.endomondo\.com\/users\/(\d+)\/workouts\/(\d+)/",$url,$out);
        if(count($out) == 4){
            return 'https://www.endomondo.com/rest/v1/users/'.$out[2].'/workouts/'.$out[3];
        }

        preg_match("/https?:\/\/(www|app)\.endomondo\.com\/workouts\/(\d+)\/(\d+)/",$url,$out);
        if(count($out) == 4){
            return 'https://www.endomondo.com/rest/v1/users/'.$out[3].'/workouts/'.$out[2];
        }

        $trescBledu = "Użyj linku endomondo w jednym z poniższych formatów:<br/>";
        $trescBledu .= "https://www.endomondo.com/users/17823172/workouts/419854310<br/>https://www.endomondo.com/workouts/419854310/17823172<br/>http://app.endomondo.com/workouts/419854310/17823172";
        $trescBledu .= "<br/>Jeśli mimo tego masz działający link w innym formacie, możesz poprosić <a href=\"http://www.wykop.pl/ludzie/Robuz/\">@Robuz</a> o uwzględnienie go w skrypcie";
        throw new Exception($trescBledu);

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
