<?php ob_start(); session_start(); /* ini_set('display_errors', '0'); */

require 'settings.php';
$settings = new Settings();

require('wpis.php');
require('templates/form.php');

$wpis = new Wpis();

//wylogowanie
if(isset($_GET['wyloguj'])){
	$wpis->wyloguj();
}

//logowanie
if(isset($_SESSION['login']))
	$login = $_SESSION['login'];
else $login = 'nieznajomy';

if(isset($_SESSION['avatar']))
	$avatar = $_SESSION['avatar'];
else $avatar = 'http://c3397992.d.cdn02.imgwykop.pl/avatar_def,q48.gif';



include 'templates/head.php';

include 'templates/body.php';

include 'templates/footer.php';

?>

