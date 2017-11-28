<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2017 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: metatags.php
-----------------------------------------------------
 Назначение: управление метатегами по сайту
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
  die("Hacking attempt!");
}

if( $member_id['user_group'] != 1  ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}


$start_from = intval( $_REQUEST['start_from'] );
$news_per_page = 50;

if( $start_from < 0 ) $start_from = 0;

if ($_REQUEST['searchword']) {
  
  $searchword = htmlspecialchars( strip_tags( stripslashes( trim( urldecode ( $_REQUEST['searchword'] ) ) ) ), ENT_COMPAT, $config['charset'] );
  
} else $searchword = "";

if ($searchword) $urlsearch = "&searchword={$searchword}"; else $urlsearch = "";


function clear_url_for_metatags ($a) {
	if (!$a) return '';
	
	if (strpos($a, "//") === 0) $a = "http:".$a;
	$a = parse_url($a);
	
	if ($a['query']) $a = $a['path'].'?'.$a['query']; else $a = $a['path'];
	
	$a = preg_replace( '#[/]+#i', '/', $a );
	
	if($a[0] != '/') $a = '/'.$a;
	
	return $a;
}

if ($_GET['action'] == "delete") {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	$id = intval ( $_GET['id'] );

	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '107', '')" );
	$db->query( "DELETE FROM " . PREFIX . "_metatags WHERE id='{$id}'" );

	@unlink( ENGINE_DIR . '/cache/system/metatags.php' );
	header( "Location: ?mod=metatags&start_from={$start_from}{$urlsearch}" );
	die();

}

if ($_POST['action'] == "mass_delete") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	if( !$_POST['selected_url'] ) {
		msg( "error", $lang['mass_error'], $lang['redirects_err_4'], "?mod=metatags&start_from={$start_from}{$urlsearch}" );
	}

	foreach ( $_POST['selected_url'] as $id ) {
		$id = intval($id);
		$db->query( "DELETE FROM " . PREFIX . "_metatags WHERE id='{$id}'" );
	}

	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '107', '')" );

	@unlink( ENGINE_DIR . '/cache/system/metatags.php' );
	header( "Location: ?mod=metatags&start_from={$start_from}{$urlsearch}" );
	die();

}

if ($_GET['action'] == "add") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	$url = clear_url_for_metatags(strip_tags( stripslashes( trim( convert_unicode( $_REQUEST['url'], $config['charset'] )))));
	$url = str_ireplace( "document.cookie", "d&#111;cument.cookie", $url );
	$url = preg_replace( "/javascript:/i", "j&#1072;vascript:", $url );
	$url = preg_replace( "/data:/i", "d&#1072;ta:", $url );
	$url = @$db->safesql( $url );

	$_REQUEST['meta_title'] = trim( convert_unicode( $_REQUEST['meta_title'], $config['charset'] ));
	$_REQUEST['descr'] = trim( convert_unicode( $_REQUEST['descr'], $config['charset'] ));
	$_REQUEST['keywords'] = trim( convert_unicode( $_REQUEST['keywords'], $config['charset'] ));
	$headers = create_metatags('');
	
	if (!$url) {
		msg( "error", $lang['opt_error'], $lang['meta_err'], "?mod=metatags&start_from={$start_from}" );
	}

	$row = $db->super_query( "SELECT `url` FROM " . PREFIX . "_metatags WHERE `url` = '{$url}'" );

	if( $row['url'] ) {
		msg( "error", $lang['opt_error'], $lang['redirects_err_3'], "?mod=metatags&start_from={$start_from}" );
	}
	
	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '105', '{$url}')" );
	$db->query( "INSERT INTO " . PREFIX . "_metatags (`url`, `title`, `description`, `keywords`) values ('{$url}', '{$headers['title']}', '{$headers['description']}', '{$headers['keywords']}')" );

	@unlink( ENGINE_DIR . '/cache/system/metatags.php' );
	header( "Location: ?mod=metatags" );
	die();
}

if ($_GET['action'] == "edit") {

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$id = intval ( $_GET['id'] );
	$url = clear_url_for_metatags(strip_tags( stripslashes( trim( convert_unicode( $_REQUEST['url'], $config['charset'] )))));
	$url = str_ireplace( "document.cookie", "d&#111;cument.cookie", $url );
	$url = preg_replace( "/javascript:/i", "j&#1072;vascript:", $url );
	$url = preg_replace( "/data:/i", "d&#1072;ta:", $url );
	$url = @$db->safesql( $url );

	$_REQUEST['meta_title'] = trim( convert_unicode( $_REQUEST['meta_title'], $config['charset'] ));
	$_REQUEST['descr'] = trim( convert_unicode( $_REQUEST['descr'], $config['charset'] ));
	$_REQUEST['keywords'] = trim( convert_unicode( $_REQUEST['keywords'], $config['charset'] ));
	$headers = create_metatags('');

	if (!$url) {
		msg( "error", $lang['opt_error'], $lang['meta_err'], "?mod=metatags&start_from={$start_from}{$urlsearch}" );
	}
	
	$row = $db->super_query( "SELECT `url` FROM " . PREFIX . "_metatags WHERE `url` = '{$url}' AND id != '{$id}'" );

	if( $row['url'] ) {
		msg( "error", $lang['opt_error'], $lang['redirects_err_3'], "?mod=metatags&start_from={$start_from}{$urlsearch}" );
	}	

	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '106', '{$url}')" );
	$db->query( "UPDATE " . PREFIX . "_metatags SET `url`='{$url}', `title`='{$headers['title']}', `description`='{$headers['description']}', `keywords`='{$headers['keywords']}' WHERE id='{$id}'" );

	@unlink( ENGINE_DIR . '/cache/system/metatags.php' );
	header( "Location: ?mod=metatags&start_from={$start_from}{$urlsearch}" );
	die();
}

echoheader( "<i class=\"fa fa-tags position-left\"></i><span class=\"text-semibold\">{$lang['opt_metatags']}</span>", $lang['opt_metatags_br'] );

echo <<<HTML
<form action="?mod=metatags" method="get" name="navi" id="navi">
<input type="hidden" name="mod" value="metatags">
<input type="hidden" name="start_from" id="start_from" value="{$start_from}">
<input type="hidden" name="searchword" value="{$searchword}">
</form>
<form action="?mod=metatags" method="post" name="optionsbar" id="optionsbar">
<input type="hidden" name="mod" value="metatags">
<input type="hidden" name="user_hash" value="{$dle_login_hash}">
<input type="hidden" name="start_from" id="start_from" value="{$start_from}">
<div class="panel panel-default">
  <div class="panel-heading">
    {$lang['opt_metatags']}
	<div class="heading-elements">
		<div class="form-group has-feedback" style="width:250px;">
			<input name="searchword" type="search" class="form-control" placeholder="{$lang['search_field']}" onchange="document.optionsbar.start_from.value=0;" value="{$searchword}">
			<div class="form-control-feedback">
			    <a href="#" onclick="$(this).closest('form').submit();"><i class="fa fa-search text-size-base text-muted"></i></a>
			</div>
		</div>
	</div>
  </div>
HTML;

$i = $start_from+$news_per_page;

if ( $searchword ) {
  
  $searchword = @$db->safesql($searchword);
  $where = "WHERE `url` like '%$searchword%' OR `title` like '%$searchword%' OR `description` like '%$searchword%' OR `keywords` like '%$searchword%' ";
  
} else $where = "";

$result_count = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_metatags {$where}");
$all_count_news = $result_count['count'];


		// pagination

		$npp_nav = "";
		
		if( $all_count_news > $news_per_page ) {

			if( $start_from > 0 ) {
				$previous = $start_from - $news_per_page;
				$npp_nav .= "<li><a onclick=\"javascript:search_submit($previous); return(false);\" href=\"#\" title=\"{$lang['edit_prev']}\">&lt;&lt;</a></li>";
			}
			
			$enpages_count = @ceil( $all_count_news / $news_per_page );
			$enpages_start_from = 0;
			$enpages = "";
			
			if( $enpages_count <= 10 ) {
				
				for($j = 1; $j <= $enpages_count; $j ++) {
					
					if( $enpages_start_from != $start_from ) {
						
						$enpages .= "<li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a></li>";
					
					} else {
						
						$enpages .= "<li class=\"active\"><span>$j</span></li>";
					}
					
					$enpages_start_from += $news_per_page;
				}
				
				$npp_nav .= $enpages;
			
			} else {
				
				$start = 1;
				$end = 10;
				
				if( $start_from > 0 ) {
					
					if( ($start_from / $news_per_page) > 4 ) {
						
						$start = @ceil( $start_from / $news_per_page ) - 3;
						$end = $start + 9;
						
						if( $end > $enpages_count ) {
							$start = $enpages_count - 10;
							$end = $enpages_count - 1;
						}
						
						$enpages_start_from = ($start - 1) * $news_per_page;
					
					}
				
				}
				
				if( $start > 2 ) {
					
					$enpages .= "<li><a onclick=\"javascript:search_submit(0); return(false);\" href=\"#\">1</a></li> <li><span>...</span></li>";
				
				}
				
				for($j = $start; $j <= $end; $j ++) {
					
					if( $enpages_start_from != $start_from ) {
						
						$enpages .= "<li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$j</a></li>";
					
					} else {
						
						$enpages .= "<li class=\"active\"><span>$j</span></li>";
					}
					
					$enpages_start_from += $news_per_page;
				}
				
				$enpages_start_from = ($enpages_count - 1) * $news_per_page;
				$enpages .= "<li><span>...</span></li><li><a onclick=\"javascript:search_submit($enpages_start_from); return(false);\" href=\"#\">$enpages_count</a></li>";
				
				$npp_nav .= $enpages;
			
			}
			
			if( $all_count_news > $i ) {
				$how_next = $all_count_news - $i;
				if( $how_next > $news_per_page ) {
					$how_next = $news_per_page;
				}
				$npp_nav .= "<li><a onclick=\"javascript:search_submit($i); return(false);\" href=\"#\" title=\"{$lang['edit_next']}\">&gt;&gt;</a></li>";
			}
			
			$npp_nav = "<ul class=\"pagination pagination-sm\">".$npp_nav."</ul>";
		
		}
		
		// pagination

$i = 0;

if ( $all_count_news ) {

	$entries = "";

	$db->query("SELECT * FROM " . PREFIX . "_metatags {$where}ORDER BY id DESC LIMIT {$start_from},{$news_per_page}");

	while($row = $db->get_row()) {
	
		$menu_link = <<<HTML
        <div class="btn-group">
          <a href="#" class="dropdown-toggle nocolor" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-bars"></i><span class="caret"></span></a>
          <ul class="dropdown-menu text-left dropdown-menu-right">
            <li><a uid="{$row['id']}" href="?mod=metatags" class="editlink"><i class="fa fa-pencil-square-o position-left"></i>{$lang['word_ledit']}</a></li>
			<li class="divider"></li>
            <li><a uid="{$row['id']}" class="dellink" href="?mod=metatags"><i class="fa fa-trash-o position-left text-danger"></i> {$lang['word_ldel']}</a></li>
          </ul>
        </div>
HTML;
		$row['url'] = htmlspecialchars($row['url'], ENT_QUOTES, $config['charset'] );
		
		$entries .= "<tr>
        <td><div id=\"content_{$row['id']}\">{$row['url']}</div></td>
        <td><div id=\"title_{$row['id']}\" data-description=\"{$row['description']}\" data-keywords=\"{$row['keywords']}\">{$row['title']}</div></td>
        <td align=\"center\">{$menu_link}</td>
        <td><input name=\"selected_url[]\" value=\"{$row['id']}\" type=\"checkbox\" class=\"icheck\"></td>
        </tr>";


	}

	$db->free();

echo <<<HTML
 <div class="table-responsive">
    <table class="table table-xs table-hover">
      <thead>
      <tr>
        <th>{$lang['meta_param_2']}</th>
        <th>{$lang['meta_title']}</th>
        <th style="width: 70px">&nbsp;</th>
        <th style="width: 40px"><input class="icheck" type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:ckeck_uncheck_all()"></th>
      </tr>
      </thead>
	  <tbody>
		{$entries}
	  </tbody>
	</table>
	</div>
<div class="panel-footer">
	<div class="pull-left"><button class="btn bg-teal btn-sm btn-raised" type="button" onclick="addLink()"><i class="fa fa-plus-circle position-left"></i>{$lang['add_links_meta']}</button></div>
	<div class="pull-right">
	<select class="uniform position-left" name="action">
	<option value="">{$lang['edit_selact']}</option>
	<option value="mass_delete">{$lang['edit_seldel']}</option>
	</select><input class="btn bg-brown-600 btn-sm btn-raised" type="submit" value="{$lang['b_start']}">
	</div>
</div>
HTML;


}  else {

if($where) $lang['meta_not_found'] = $lang['redirects_not_found_1'];

echo <<<HTML
<div class="panel-body">
<table width="100%">
    <tr>
        <td style="height:50px;"><div align="center"><br /><br />{$lang['meta_not_found']}<br /><br></a></div></td>
    </tr>
</table>
</div>
<div class="panel-footer">
	<button class="btn bg-teal btn-sm btn-raised" type="button" onclick="addLink()"><i class="fa fa-plus-circle position-left"></i>{$lang['add_links_meta']}</button>
</div>

HTML;

}

if (!$config['allow_own_meta']) {

	$module_disabled = "<div class=\"alert alert-warning alert-styled-left alert-arrow-left alert-component\">{$lang['module_disabled']}</div>";

} else $module_disabled = "";

echo <<<HTML
</div>
<div class="mb-20">{$npp_nav}</div>
</form>

<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">{$lang['opt_metahelp']}</div>{$module_disabled}

<script language="javascript" type="text/javascript">  
<!--

	$(function() {
		$('.table').find('tr > td:last-child').find('input[type=checkbox]').on('change', function() {
			if($(this).is(':checked')) {
				$(this).parents('tr').addClass('warning');
			}
			else {
				$(this).parents('tr').removeClass('warning');
			}
		});
	});
	
    function search_submit(prm){
      document.navi.start_from.value=prm;
      document.navi.submit();
      return false;
    }

	function ckeck_uncheck_all() {
	    var frm = document.optionsbar;
	    for (var i=0;i<frm.elements.length;i++) {
	        var elmnt = frm.elements[i];
	        if (elmnt.type=='checkbox') {
	            if(frm.master_box.checked == true){ elmnt.checked=false; $(elmnt).parents('tr').removeClass('warning');}
	            else{ elmnt.checked=true; $(elmnt).parents('tr').addClass('warning'); }
	        }
	    }
	    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
	    else{ frm.master_box.checked = true; }
		
		$(frm.master_box).parents('tr').removeClass('warning');
		
		$.uniform.update();
	}
	
	function addLink() {
		var b = {};
	
		b[dle_act_lang[3]] = function() { 
						$(this).dialog("close");						
				    };
	
		b[dle_act_lang[2]] = function() { 
						if ( $("#dle-promt-url").val().length < 1) {
							 $("#dle-promt-url").addClass('ui-state-error');
						} else {
							var url = $("#dle-promt-url").val();
							var title = $("#dle-promt-title").val();
							var description = $("#dle-promt-description").val();
							var keywords = $("#dle-promt-keywords").val();

							$(this).dialog("close");
							$("#dlepopup").remove();

							document.location='?mod=metatags&user_hash={$dle_login_hash}&action=add&url=' + encodeURIComponent(url) + '&meta_title=' + encodeURIComponent(title)+ '&descr=' + encodeURIComponent(description)+ '&keywords=' + encodeURIComponent(keywords);

						}				
					};

		$("#dlepopup").remove();

		$("body").append("<div id='dlepopup' title='{$lang['add_links_meta']}' style='display:none'>{$lang['meta_param_1']}<br><input type='text' name='dle-promt-url' id='dle-promt-url' class='classic' style='width:100%;' value=''/><br><br>{$lang['meta_title']}<br /><input type='text' name='dle-promt-title' id='dle-promt-title' class='classic' style='width:100%;' value=''/><br><br>{$lang['meta_descr']}<br /><input type='text' name='dle-promt-description' id='dle-promt-description' class='classic' style='width:100%;' value=''/><br><br>{$lang['meta_keys']}<br /><textarea name='dle-promt-keywords' id='dle-promt-keywords' class='classic' style='width:100%;' rows='3'></textarea></div>");
	
		$('#dlepopup').dialog({
			autoOpen: true,
			width: 600,
			resizable: false,
			buttons: b
		});

	}

$(function(){

		var old_link = '';

		$('.dellink').click(function(){

			old_link = $('#content_'+$(this).attr('uid')).text();
			var urlid = $(this).attr('uid');

		    DLEconfirm( '{$lang['redirects_del']} <b>&laquo;'+old_link+'&raquo;</b>', '{$lang['p_confirm']}', function () {

				document.location="?mod=metatags&start_from={$start_from}&user_hash={$dle_login_hash}{$urlsearch}&action=delete&id=" + urlid;

			} );

			return false;
		});


		$('.editlink').click(function(){

			var url = $('#content_'+$(this).attr('uid')).text();
			var urlid = $(this).attr('uid');
			var meta_title = $('#title_'+$(this).attr('uid')).text();
			meta_title = meta_title.replace(/'/g, "&#039;");
			var meta_description = $('#title_'+$(this).attr('uid')).data('description');
			var meta_keywords = $('#title_'+$(this).attr('uid')).data('keywords');
							
			var b = {};
		
			b[dle_act_lang[3]] = function() { 
							$(this).dialog("close");						
					    };
		
			b[dle_act_lang[2]] = function() { 
						if ( $("#dle-promt-url").val().length < 1) {
							 $("#dle-promt-url").addClass('ui-state-error');
						} else {
							var url = $("#dle-promt-url").val();
							var title = $("#dle-promt-title").val();
							var description = $("#dle-promt-description").val();
							var keywords = $("#dle-promt-keywords").val();
							
							$(this).dialog("close");
							$("#dlepopup").remove();
							
							document.location='?mod=metatags&user_hash={$dle_login_hash}{$urlsearch}&start_from={$start_from}&action=edit&id='+urlid+'&url=' + encodeURIComponent(url) + '&meta_title=' + encodeURIComponent(title)+ '&descr=' + encodeURIComponent(description)+ '&keywords=' + encodeURIComponent(keywords);
	
						}				
					};
	
			$("#dlepopup").remove();

			$("body").append("<div id='dlepopup' title='{$lang['add_links_meta']}' style='display:none'>{$lang['meta_param_1']}<br><input type='text' name='dle-promt-url' id='dle-promt-url' class='classic' style='width:100%;' value='"+url+"'/><br><br>{$lang['meta_title']}<br /><input type='text' name='dle-promt-title' id='dle-promt-title' class='classic' style='width:100%;' value='"+meta_title+"'/><br><br>{$lang['meta_descr']}<br /><input type='text' name='dle-promt-description' id='dle-promt-description' class='classic' style='width:100%;' value=\""+meta_description+"\"><br><br>{$lang['meta_keys']}<br /><textarea name='dle-promt-keywords' id='dle-promt-keywords' class='classic' style='width:100%;' rows='3'>"+meta_keywords+"</textarea></div>");
		
			$('#dlepopup').dialog({
				autoOpen: true,
				width: 600,
				resizable: false,
				buttons: b
			});

			return false;
		});

});
//-->
</script>
HTML;


echofooter();
?>