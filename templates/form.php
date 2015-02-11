<?php

function form(){
	echo '<form method="post" action="?dodaj">';

	if(isset($_POST['dystans']) &&count($_POST['dystans']) > 0)
		foreach($_POST['dystans'] as $dystans)
			echo '<input id="pierwszy" name="dystans[]" type="text" class="pole" placeholder="Wpisz dystans" autofocus="autofocus" value="'.$dystans.'">';
	else
		echo '<input id="pierwszy" name="dystans[]" type="text" class="pole" placeholder="Wpisz dystans" autofocus="autofocus">';

	echo '<a id="dodajDystans" href="#dodajDystans" onclick="dodajDystans()"><div> Dodaj dystans</div></a>
	<a id="dodajObrazUrl" href="#dodajObrazUrl" onclick="embed()"><div id="dodajObrazUrl"> Dodaj obraz z url</div></a>
	<textarea name="tresc"  style="width: 98%; border: 1px solid #d5d5d5; font-size: 14px; padding: 8px; font-family: \'Lato\';" placeholder="Opisz swoją wycieczkę" rows="10">'.((isset($_POST['tresc']))?$_POST['tresc']: '').'</textarea>
	<input class="btn" type="submit" value="Dodaj" /><label><input type="checkbox" name="reklama" value="true"';

	if(isset($_COOKIE['reklama']) && $_COOKIE['reklama']) echo 'checked';

	echo ' /> Czy chcesz dodać informację o tym skrypcie?</label></form>';
}