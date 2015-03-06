function embed(){
	$("a#dodajObrazUrl").after('<input name="embedUrl" type="text" class="pole" placeholder="Podaj url">');
	$("a#dodajObrazUrl").remove();
}

function dodajKsiazke(){
var opis = '**Tytuł:** ' + $('input#tytul').val() + "\n" +
		'**Autor**: ' + $('input#autor').val() + "\n" +
		'**Gatunek**: ' + $('input#gatunek').val() +  "\n" +
		gwiazdki($("input[type=range]").val()) + "\n" +
		"\n" + $('textarea[name=recenzja]').val();

	$('textarea[name=tresc]').val(opis + "\n\n" + $('textarea[name=tresc]').val());

	$('input#licznik').val(parseInt($('input#licznik').val()) + 1);

	$('input#tytul').val('');
	$('input#autor').val('');
	$('input#gatunek').val('');
	$('textarea[name=recenzja]').val('');
	$('label#punkty').text('Ocena');

	var txt_tresc = $('textarea[name=tresc]');
	if(txt_tresc.length)
		txt_tresc.scrollTop(txt_tresc[0].scrollHeight - txt_tresc.height())
}

function dodajWpis(){
	//Sprawdzam czy jakaś książka została dodana
	if($('input#licznik').val() !== "0"){
		$('input#dodaj').attr('disabled', 'true');
		$('form').submit();
		$('form').toggle();
		$('.container').append('<img id="loading" src="assets/loading-wheel.gif">');
	}else{
		alert("Nie dodałeś żadnej książki?\nUpewnij się, że kliknąłeś \"Dodaj książkę\" pod formularzem z książką.");
	}
}

function gwiazdki(ile){
	var gwiazdki = '';

	for(var i = 0; i<ile; i++)
		gwiazdki += '★';

	for(var i = 0; i< (10-ile); i++)
		gwiazdki += '☆';

	return gwiazdki;
}

$("input[type=range]").change(function(){
	var newval=$(this).val();
	$('label#punkty').text(gwiazdki(newval));
});

