<?php

class HtmlModel {
	public static function getHeader($view) {
 	  $str  = $view->render("layout/header.phtml");

	  return $str;
	}
}
