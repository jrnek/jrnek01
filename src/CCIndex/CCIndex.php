<?php

class CCIndex implements IController {
	public function Index() {
		global $jr;
		$jr->data['title'] = "Index Controller";
		$jr->data['main'] = "Första sidan";
	}
}