<?php

function form(){
	echo '<form method="post" action="?dodaj">
	<input id="licznik" name="dystans[]" type="hidden" class="pole" placeholder="Wpisz dystans" value="0">
	<input id="tytul" name="tutul" type="text" class="pole" placeholder="Tytuł" autofocus="autofocus">
	<input id="autor" name="autor" type="text" class="pole" placeholder="Autor">
	<input id="gatunek" name="gatunek" type="text" class="pole" placeholder="Gatunek">

	<label id="punkty">Ocena</label>
	<input id="punkty" type="range" name="punkty" class="pole" min="0" max="10">

	<textarea name="recenzja" placeholder="Kilka słów o książce" rows="10"></textarea>

	<a id="dodajDystans" href="#dodajKsiazke" onclick="dodajKsiazke()"><div> Dodaj książkę</div></a>


	<textarea name="tresc" placeholder="Treść wpisu" rows="10">'.((isset($_POST['tresc']))?$_POST['tresc']: '').'</textarea>
	<br />
	<a id="dodajObrazUrl" href="#dodajObrazUrl" onclick="embed()"><div id="dodajObrazUrl"> Dodaj obraz </div></a>
	</br>
	<a id="dodajObrazUrl" href="#dodajObrazUrl" onclick="embed()"><div id="dodajObrazUrl"> Dodaj obraz </div></a>
	<!-- <input id="dodaj" class="btn" type="submit" value="Dodaj wpis" onclick="dodajWpis()" />-->
	<a id="dodajWpis" href="#dodajWpis" onclick="dodajWpis()"><div id="dodajWpis"> Dodaj wpis </div></a>
	<label style="top: 8px; position: relative;"><input type="checkbox" name="reklama" value="true"';

	if(isset($_COOKIE['reklama']) && $_COOKIE['reklama']) echo 'checked';

	echo ' /> Czy chcesz dodać informację o tym skrypcie?</label></form>';

}