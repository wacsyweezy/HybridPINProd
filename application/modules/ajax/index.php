<?php
@session_start();
include('class.php');
if(!isset($_POST['task'])) {
	die();
}
if(isset($_POST['task']) && $_POST['task']=="add-to-cart") {
	ajax::add_to_cart($_POST['item'], $_POST['qty'], $_POST['price']);	
}
if(isset($_POST['task']) && $_POST['task']=="remove-item") {
	ajax::remove_item($_POST['item']);	
}
if(isset($_POST['task']) && $_POST['task']=="remove-all") {
	ajax::remove_all_item();	
}
if(isset($_POST['task']) && $_POST['task']=="place-order") {
	ajax::place_order();	
}
?>