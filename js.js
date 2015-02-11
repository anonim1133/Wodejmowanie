function embed(){
	$("a#dodajObrazUrl").after('<input name="embedUrl" type="text" class="pole" placeholder="Podaj url">');
	$("a#dodajObrazUrl").remove();
}

function dodajDystans(){
    $('<input name="dystans[]" type="text" class="pole" placeholder="Wpisz dystans">').insertBefore('#pierwszy');
}
