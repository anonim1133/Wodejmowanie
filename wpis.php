<?php require_once 'wykop.php'; require_once 'endomondo.php';

//Piękna fukncja, na swoim miejscu. Ma za zadanie wyłapanie wpisanego tekstu podczasy gdy wystąpi błąd, aby ten nie przepadł.



class Wpis {
//zmienne prywatne
	private $api;
	private $blad = '';
	private $ostatnia;
	private $tresc = '';
	private $embed = '';
	private $reklama = False;
	private $odleglosci = array();

	private $tag;
	private $round;
	private $return_url;
	
//konstruktor
	function __construct(){

		global $settings;

		$this->tag = $settings->getTag();
		$this->round = $settings->getRound();
		$this->return_url = $settings->getReturnUrl();

		$this->api = new WykopApi($settings->getApiKey(), $settings->getApiSecret());
		
		if(isset($_GET['connectData'])){//Jeżeli są dane, to loguje
			$logowanie = $this->api->handleConnectData();
			
			//Ustawiamy czas ciasteczka na tydzień(?) aby uniknąć problemów z przeterminowanymi danymi.
			$_SESSION['klucz'] = $logowanie['token'];
			$_SESSION['znak'] = $logowanie['sign'];
		
			$answer = $this->api->doRequest('profile/index/'.$logowanie['login']);
			if(!$this->api->isValid()){
				echo '<p id="error">'.$this->api->getError().'</p>';
			}else{
				$_SESSION['login'] = $answer['login'];
				$_SESSION['kolor'] = $answer['author_group'];
				$_SESSION['avatar'] = $answer['avatar_med'];
			}
		}

		if(!isset($_SESSION['login']) && !isset($_SESSION['klucz']))
			header('Location: '.$this->api->getConnectUrl($settings->getReturnUrl()));
		else{
			$answer = $this->api->doRequest('user/login', array('login' => $_SESSION['login'], 'accountkey' => $_SESSION['klucz']));
			if(!$this->api->isValid()) echo '<p id="error">'.$this->api->getError().'</p>';
		
			$_SESSION['userkey'] = $answer['userkey'];
		}
	}
//sekcja funkcji prywatnych
	private function ostatniaOdleglosc(){
		$result = $this->api->doRequest('tag/entries/'.$this->tag);
		preg_match('/=(.+)/', $result['items'][0]['body'], $wynik);
		$wynik = $wynik[1];

		$wynik = preg_replace('/[^\.\,0-9]+/', '', $wynik);
		$wynik = preg_replace('/[\,]+/', '.', $wynik);

		if($wynik <= 0 && $this->tag == 'rowerowyrownik')
			$wynik = (int)file_get_contents('http://adammik.eu/rr/ostatniPoprawnyWynik.txt');

		if($this->round) $this->ostatnia = (int)$wynik;
		else $this->ostatnia = (real)$wynik;
	}
//Sekcja publiczna
	public function reklamaOn(){
		$this->reklama = true;
	}
	
	public function dodajWpis(){
		$this->ostatniaOdleglosc();

		if($this->ostatnia<=0){
			$this->blad = 'Nie udało mi się odnaleźć ostatniego wyniku. Prawdopodobnie ostatni wpis jest błędny.';
			return FALSE;
		}
		
		if(count($this->odleglosci) == 0){
			$this->blad = 'Nie dodajemy pustych przejazdów';
			return FALSE;
		}
			
		if($this->round){
			$po = (int)$this->ostatnia;
			$dzialanie = number_format((int)$po, 0, ',', ' ');
		}else{
			$po = (real)$this->ostatnia;
			$dzialanie = number_format((real)$po, 2, ',', ' ');
		}

		$statystyki = "Statystyki z Endomondo:\n";

		foreach ($this->odleglosci as $odleglosc) {
			$po = $po - $odleglosc['dystans'];

			if($this->round) $dzialanie .= ' - '.$odleglosc['dystans'];
			else $dzialanie .= ' - '.number_format((float)$odleglosc['dystans'], 2, ',', '');
			
			if(isset($odleglosc['srednia']))
				$statystyki .= 'Dystans: '.$odleglosc['dystans']." km\nCzas: ".$odleglosc['czas']."\nŚrednia prędkość: ".$odleglosc['srednia']."\nMaksymalna prędkość: ".$odleglosc['max']."\nSpalonych kalorii: ".$odleglosc['kalorie']."\n\n";
		}

		if($this->round) $dzialanie .= ' = '.number_format((int)$po, 0, ',', ' ');
		else $dzialanie .= ' = '.number_format((real)$po, 2, ',', ' ');

		$tresc = $dzialanie."\n\n";
		
		if(isset($odleglosc['srednia']))
			$tresc .= $statystyki."\n\n";
		
		$tresc .= $this->tresc;
		$tresc .= " \n\n#".$this->tag;
		
		if($this->reklama){
			setcookie('reklama', 1, time()+3600*24*32);
			
			$tresc .= "\n\nWpis został dodany za pomocą skryptu [do odejmowania]($this->return_url)
			!Dzięki niemu unika się błędów w działaniach
			!Pobierany jest zawsze ostatni wynik
			!Zamiast dystansów można w pole 'Dystans' wkleić link do treningu Endomondo - zostaną dodane dodatkowe statystyki";
		}else{
			setcookie('reklama', 0, time()+3600*24*32);			
		}

		if(isset($_COOKIE['last']) && md5($tresc) == $_COOKIE['last']){
			$this->blad = "Duplikaty się dodaje, eh?<br /> Sprawdź na mirko, jeden wpis powinien był się dodać";
		}else{
			return $this->dodaj($tresc);
		}
	}

	function dodaj($tresc){
		setcookie('last', md5($tresc));

		$this->api->setUserKey($_SESSION['userkey']);
		$result = $this->api->doRequest('entries/add', array('body' => $tresc, 'embed' => $this->embed));

		if($this->api->isValid()) return (int)$result['id'];
		else{
			$this->blad = $this->getError();
			return FALSE;
		}
	}

	function ustawTresc($tresc){
		$this->tresc = trim($tresc);
	}
	
	function dodajOdleglosc($odleglosc){
		//$pattern = "/(^http:\/\/www.endomondo)|(^http:\/\/endomondo)|(^https:\/\/www.endomondo)|(^https:\/\/endomondo)/";
		$pattern = "/endomondo/";
		if(preg_match($pattern, $odleglosc)){
			$endo = new Endomondo($odleglosc);

			if($this->round){
				$this->odleglosci[] = array(
					'dystans' => (int)round($endo->getDystans()),
					'czas' => $endo->getCzas(),
					'srednia' => $endo->getSrednia(),
					'max' => $endo->getMax(),
					'kalorie' => $endo->getKalorie());
			}else{
				$this->odleglosci[] = array(
					'dystans' => (real)$endo->getDystans(),
					'czas' => $endo->getCzas(),
					'srednia' => $endo->getSrednia(),
					'max' => $endo->getMax(),
					'kalorie' => $endo->getKalorie());
			}
		}else{
			$odleglosc = preg_replace('/\,+/', '.', $odleglosc);
			if($odleglosc > 0){
				if($this->round && round($odleglosc) > 0){
					$this->odleglosci[] = array(
						'dystans' => (int)round($odleglosc));
				}else{
					$this->odleglosci[] = array(
						'dystans' => (real)$odleglosc);
				}
			}
		}
	}
	
	function dodajEmbed($url){
		$this->embed = $url;
	}
	
	function wyloguj(){
		if(isset($_SESSION['login']) && isset($_SESSION['klucz'])){
			session_unset();
		}

		$url = $_SERVER['HTTP_HOST'];

		$req_url = explode('?', $_SERVER['REQUEST_URI']);

		$url .= $req_url[0];

		header("refresh:1;url=http://".$url);
		
	}
	
	function zwrocBlad(){
		return $this->blad;
	}
	
	function __destruct(){
		
	}
}//Koniec klasy

?>
