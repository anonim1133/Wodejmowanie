function embed(){
	$("a#dodajObrazUrl").after('<input name="embedUrl" type="text" class="pole" placeholder="Podaj url">');
	$("a#dodajObrazUrl").remove();
}

function dodajDystans(){
    $('<input name="dystans[]" type="text" class="pole" placeholder="Wpisz dystans">').insertBefore('#pierwszy');
}
function dodajWpis(){
	$('input#dodaj').attr('disabled', 'true');
	$('form').submit();
	$('.container').html('<img id="loading" src="assets/loading-wheel.gif">');
}