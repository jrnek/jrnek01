<?php

function base_url($url) {
	return CJrnek::Instance()->request->base_url . trim($url, '/');
}

function current_url() {
	return CJrnek::Instance()->request->current_url; 
}

function get_debug() {
	$jr = CJrnek::Instance();
	$html = "";
	if($jr->config['debug']['show']) {
		$html .= "<h2>Debuginformation</h2>";
		if($jr->config['debug']['jrnek']) {
			$html .= "<hr><p>The content of jrnek:</p><pre>" . htmlentities(print_r($jr, true)) . "</pre>";
		}
		
		if($jr->config['debug']['db-num-queries']) {
			$html .= "<hr><p>Number of queries made: " . $jr->db->GetNrQueries() . " st.</p>";
		}
		
		if($jr->config['debug']['db-queries']) {
			$html .="<hr><p>Following queries made:</p><p>" . 
				implode("<br />", $jr->db->GetQueries()) . "</p>";
		}
	}
	return $html;
}

function render_views() {
	return CJrnek::Instance()->views->Render();
}

function get_messages_from_session() {
	$msgs = CJrnek::Instance()->session->GetMessages();
	$html ="";
	if(!empty($msgs)) {
		foreach($msgs as $val) {
			$valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
			$class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
			$html .= "<div class='{$class}'>{$val['message']}</div>\n";
		}
	} 
	return $html;
}

function create_url($urlOrController=null, $method=null, $arguments=null) {
  return CJrnek::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}

function filter_data($data, $filter) {
	return CMContent::Filter($data, $filter);
}

function login_menu() {
	$jr = CJrnek::Instance();
	$items ="";
	if($jr->user->IsAuthenticated()) {
		$items .= "<img style='padding-top: 5px;' src='". get_gravatar(12) . "'/>  | ";
		$items .= "<a href='" . create_url('user/profile') . "'>" . 
					$jr->user->GetAcronym() . "</a> | ";
		if($jr->user->IsAdmin()) {
			$items .= "<a href='" . create_url('acp') . "'>acp</a> | ";
		}
		$items .= "<a href='" . create_url('user/logout') . "'>Logout</a>";
	} else {
		$items = "<a href='". create_url('user/login') . "'>Login</a>";
	}
	return $items;
}

function get_gravatar($size=null) {
	return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CJrnek::Instance()->user['email'])))
		. '.jpg?' . ($size ?  "s=$size" : null);
}
