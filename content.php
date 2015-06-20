<?php
//Jeżeli nie dodajemy, to wyświetlany formularz
if(!isset($_GET['dodaj'])){
	form();
}elseif(isset($_POST['dystans']) && strlen($_POST['dystans'][0]) > 0){
//Dodawanie wpisu

	//Zbieramy odległości
	foreach($_POST['dystans'] as $dystans)
		$wpis->dodajOdleglosc($dystans);

	//Ustawiamy treść wpisu
	$wpis->ustawTresc($_POST['tresc']);

	//Sprawdzamy, czy są jakieś media do dodania
	if(isset($_POST['embedUrl'])) $wpis->dodajEmbed($_POST['embedUrl']);

	//Sprawdzamy czy user chce rekomendowac skrypt innym
	if(isset($_POST['reklama']) && $_POST['reklama'] == 'true') $wpis->reklamaOn();

	//Dodajemy wpis;
	$id = $wpis->dodajWpis();

	//Sprawdzamy czy wystąpiły błędy
	if($id != FALSE && $wpis->zwrocBlad() == ''){

		//Jeżeli nie, przekierowujemy do nowo dodanego wpisu
		$url = "http://www.wykop.pl/wpis/$id";
		echo '<p class="sukces"> Wpis dodany! Zaraz powinno nastąpić przekierowanie, jeżeli tak się nie stanie, w link racz kliknąć panie: <a href="'.$url.'">wpis</a></p>';
		header("refresh:2;url=$url");
	}else{

		//Jeżeli wystąpił błąd, wyświetlamy raz jeszcze formularz, starając się zachować dane które user wprowadził
		echo '<p class="fail">Wystąpił jakiś błąd, to znaczy, że coś nie działa( ͡; ʖ̯ ͡; )
	 A tak poważnie, spróbuj raz jeszcze jeżeli wpis się nie dodał.<p class="fail_blad">' . $wpis->zwrocBlad() . '</p></p>';

		//Wrzuca nowy formularz, prawdopodobnie z danymi które były w nim przed wystąpieniem błędu
		form();
	}


}else{
	echo '<p class="fail">Coś poszło nie tak, ale nie wiem co ( ͡; ʖ̯ ͡; )
	<br />Być może zapomniałeś podać przejechanego dystansu?
	<br />Gdyż błąd zdaje się być po twojej stronie</p>';

	//Wrzuca nowy formularz, prawdopodobnie z danymi które były w nim przed wystąpieniem błędu
	form();
}
?>