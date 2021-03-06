<?php

function base_url($url = '') {
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

function render_views($region = 'default') {
	return CJrnek::Instance()->views->Render($region);
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
		$items .= "<div id='login'><img style='position: relative; top: 5px;' src='". get_gravatar(20) . "'/>  | ";
		$items .= "<a href='" . create_url('user/profile') . "'>" . 
					$jr->user->GetAcronym() . "</a> | ";
		if($jr->user->IsAdmin()) {
			$items .= "<a href='" . create_url('acp') . "'>acp</a> | ";
		}
		$items .= "<a href='" . create_url('user/logout') . "'>Logout</a></div>";
	} else {
		$items = "<div id='login'><a href='". create_url('user/login') . "'>Login</a></div>";
	}
	return $items;
}

//returns a gravatar
function get_gravatar($size=null) {
	return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CJrnek::Instance()->user['email'])))
		. '.jpg?' . ($size ?  "s=$size" : null);
}

//Checks if a specific region has content
function region_has_content($region='default' /*...*/) {
	return CJrnek::Instance()->views->RegionHasView(func_get_args());
}

//Returns the url to the theme catalog
function theme_url($url) {
	$jr = CJrnek::Instance();
	return $jr->themeUrl . "/{$url}";
}

function theme_parent_url($url) {
	$jr = CJrnek::Instance();
	return $jr->themeParentUrl . "/{$url}";
}

/**
 * Returns the main menu
 * 
 * Function returns a link to all the controllers that are enabled and not added
 * to the 'exclude from menu'-array. Also adds an id to the link to the current controller
 */

function get_main_nav() {
	$jr = CJrnek::Instance();
	$currCont = $jr->request->controller;
	$html = "";
	foreach($jr->config['controllers'] as $key=>$val) {
		$active = "";
		if($key == $currCont) {
			$active = 'id="active"';
		}
		if(!in_array($key, $jr->config['exclude_from_menu']) && $val['enabled']) {
			$html .= "<a {$active} href='". $jr->request->CreateUrl($key) . "'>". ucfirst($key) . "</a>";
		}
	}
	return $html;
}



