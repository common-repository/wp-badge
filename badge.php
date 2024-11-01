<?php

/*
Plugin Name: WP Badge 
Plugin URI: http://www.stefandes.com/wordpress-plugin/plugin-wp-badge-personalized-badge-wordpress-blog/
Description: WP Badge is a wordpress plugin that adds a personalized badge on your blog (Follow me style).With this plugin you can configure the link, the text and the position of the button.
Author: Stefan Des
Version: 1.0.0 
Author URI: http://www.stefandes.com
*/

function wp_badge_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

function activate_wp_badge() {
	$wp_badge_opts1 = get_option('wp_badge_options');
	$wp_badge_opts2 =array();
	if ($wp_badge_opts1) {
	    $wp_badge = $wp_badge_opts1 + $wp_badge_opts2;
		update_option('wp_badge_options',$wp_badge);
	}
	else {
		$wp_badge_opts1 = array(	'link'=>'',
		                            'top'=>'200',
									'align'=>'right',
									'msg'=>'Your Text Here',
									'color'=>'59B7FF',
									'textcolor'=>'FFFFFF',
									'textsize'=>'14',
									'textfont'=>'Verdana',
									'bordercolor'=>'FFFFFF'
						);	
		$wp_badge = $wp_badge_opts1 + $wp_badge_opts2;
		add_option('wp_badge_options',$wp_badge);		
	}
}

global $wp_badge_nett;
$wp_badge_nett = array( 'link',  'msg');


						  
register_activation_hook( __FILE__, 'activate_wp_badge' );
global $wp_badge;
$wp_badge = get_option('wp_badge_options');
define("wp_badge_VER","1.0",false);
function wp_badge_scripts() {
global $wp_badge;
	//wp_enqueue_style( 'wp_badge_css_file', wp_badge_url( 'badge-style.css' ), false, false, false);
	
}
add_action( 'init', 'wp_badge_scripts' );
add_action( 'init', 'badge_admin_warnings' );

function badge_admin_warnings() {
	global $wp_badge;
	
		function badge_warning() {
		global $wp_badge;
		if ( !$wp_badge['link'] ) {
			echo '<div id="badge-warning" class="updated fade"><p><strong>Please configure WP Badge Plugin</strong>You must <a href="options-general.php?page=badge.php">enter your URL</a> for it to work.</p></div>';
			}
		}
	
		function badge_wrong_settings(){
		global $wp_badge;
		if ( substr($wp_badge[link], 0, 4) != "http" && $wp_badge['link'] != ""){
			echo '<div id="badge-warning" class="updated fade"><p><strong>WP Badge plugin is not properly configured.</strong>The <a href="options-general.php?page=badge.php">URL</a> must begin with http.</p></div>';
			}
		}
add_action('admin_notices', 'badge_warning');
add_action('admin_notices', 'badge_wrong_settings');
return;
}


//inline styles
function wp_badge_css() {
  $e1="Hello ";
 // strlen($e1);
?>
<style type="text/css">
.wp_badge_c2 {

	background:#<?php global $wp_badge; echo $wp_badge[color]; ?>;
	top:<?php global $wp_badge; echo $wp_badge['top'];?>px;
	<?php global $wp_badge; echo $wp_badge['align'];?>:0px;
	

	border:0px solid #<?php global $wp_badge; echo $wp_badge['bordercolor'];?>;
	color:#<?php global $wp_badge; echo $wp_badge[textcolor]; ?>;
}



.wp_badge_c4 {

	background:#<?php global $wp_badge; echo $wp_badge[color]; ?>;
	top:<?php global $wp_followme; echo $wp_badge['top'];?>px;
	<?php global $wp_badge; echo $wp_badge['align'];?>:0px;


	border:0px solid #<?php global $wp_badge; echo $wp_badge['bordercolor'];?>;
	color:#<?php global $wp_followme; echo $wp_badge[textcolor]; ?>;
}
<?php global $wp_badge; $st=strlen($wp_badge['msg']); 
                $st=$st+substr_count($wp_badge['msg'],' ')-1;
?>

 .magical { 
			display:block; 
			position:absolute; top:15px;
			-webkit-transform: rotate(-90deg); 
			-moz-transform: rotate(-90deg);	
			position:fixed;	
			<?php echo $wp_badge['align'];?>:-<?php  if(empty($wp_badge['iconbgcolor'])) {if($st<=7){echo $st*2;} else if($st<=31){echo $st*3;} else if($st<=43){echo ($st*10)/3;} else{echo ($st*10.5)/3;}} else { echo $wp_badge['iconbgcolor']; }?>px;
top:<?php echo $wp_badge['top'];?>px;	
background-color:#<?php echo $wp_badge['color'];?>;
padding:5px;
font-size:13px;
font-weight:bold;
border:1px solid #<?php echo $wp_badge['bordercolor'];?>;
}


a.tab{ color:#<?php global $wp_badge; echo $wp_badge[textcolor]; ?>; text-decoration:none; background-color:#<?php echo $wp_badge['color'];?>;}
a.tab:hover{ color:#<?php global $wp_badge; echo $wp_badge[textcolor]; ?>; text-decoration:none; background-color:#<?php echo $wp_badge['color'];?>;}
			
.verticaltext{
font: bold 13px Arial;
width: 15px;
writing-mode: tb-rl;
<?php echo $wp_badge['align'];?>:0px;
top:<?php echo $wp_badge['top'];?>px;	
background-color:#<?php echo $wp_badge['color'];?>;
padding:5px;
font-size:13px;
font-weight:bold;
position:fixed;	
border:1px solid #<?php echo $wp_badge['bordercolor'];?>;
}
</style>

<?php
}
add_action('wp_head', 'wp_badge_css');







  


function show_badge() {
global $wp_badge;
function browser_info($agent=null) {
  $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape',
    'konqueror', 'gecko');
  $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
  $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
 if (!preg_match_all($pattern, $agent, $matches)) return array();
  $i = count($matches['browser'])-1;
  return array($matches['browser'][$i] => $matches['version'][$i]);
}
$ua = browser_info();
 
?>

<div style="position:relative;">
  
 <?php

  if(array_key_exists('msie', $ua))
        {//echo "i am i e browser"; 
		?>
		<div class="wp_badge_c4">
		<div class="verticaltext"><a href="<?php global $wp_badge; echo $wp_badge['link'];?>" class="tab"><?php global $wp_badge; echo $wp_badge['msg'];?>
</a></div>
		<?php
		 }
  else{
  
  $e1="hello";
 // echo " i am NOT in ioe ";
 ?>
  <div class="wp_badge_c2">
	 <div class="magical">
  <a href="<?php global $wp_badge; echo $wp_badge['link'];?>" class="tab" id="test" target="<?php global $wp_badge; if($wp_badge['icon']=='same window'){ echo "_self";}else{ echo "_blank";}; ?>"><?php global $wp_badge; echo $wp_badge['msg'];?>
</a></div>
		<?php
  }
 ?>

    
  </div>
</div>



<?php
}


add_action( 'get_footer', 'show_badge' );

function wp_badge_settings() {
    // Add a new submenu under Options:
    add_options_page('WP Badge', 'WP Badge', 9, basename(__FILE__), 'wp_badge_settings_page');
}

function wp_badge_admin_head() {
?>

<?php
}

add_action('admin_head', 'wp_badge_admin_head');

function wp_badge_settings_page() {
	require_once(ABSPATH.'/wp-admin/includes/plugin-install.php');

?>
<script src="<?php echo wp_badge_url('js/jscolor.js'); ?>" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
<div class="wrap">
<h2>WP Badge</h2>

<form  method="post" action="options.php">
<div id="poststuff" class="metabox-holder has-right-sidebar"> 

<div style="float:left;width:60%;">
<?php
settings_fields('wp-badge-group');
$wp_badge = get_option('wp_badge_options');
?>
<h2>Settings</h2> 

<div class="postbox">
<h3 style="cursor:pointer;"><span>WP Badge Options</span></h3>
<div>
<table class="form-table">


<tr valign="top" class="alternate"> 
		<th scope="row" style="width:20%;"><label for="wp_badge_options[link]" style="font-weight:bold;">Your URL</label></th> 
	<td>
	<input autocomplete="off" type="text" name="wp_badge_options[link]" value="<?php echo $wp_badge[link]; ?>" class="regular-text code" /> <br />(example : http://www.stefandes.com/wordpress-plugin/wp-badge/ )<br />
	<?php 
		if ( substr($wp_badge[link], 0, 4) != "http" && $wp_badge[link]){

		echo '<span style="color:red;">Error:</span> <strong>The Twitter URL must begin with <em>http</em></strong>';
		}
		if ( !$wp_badge[link] ){
		echo '<span style="color:red;">Error:</span> <strong>The URL cannot be blank</strong>';
		}
		
	?>
	</td>
</tr>

<tr valign="top" class="alternate"> 
		<th scope="row" style="width:20%;"><label for="wp_badge_options[msg]">Text of Button</label></th> 
	<td><input autocomplete="off" type="text" name="wp_badge_options[msg]" value="<?php echo $wp_badge[msg]; ?>" class="regular-text code" /></td>
</tr>

<tr valign="top"> 
		<th scope="row" style="width:20%;"><label for="wp_badge_options[color]">Background color</label></th> 
	<td><input autocomplete="off" type="text" name="wp_badge_options[color]" value="<?php echo $wp_badge[color]; ?>" id="iconbgall" class="color regular-text code" /></td>
</tr>



<tr valign="top" class="alternate"> 
		<th scope="row" style="width:20%;"><label for="wp_badge_options[textcolor]">Text color</label></th> 
	<td><input autocomplete="off" type="text" name="wp_badge_options[textcolor]" value="<?php echo $wp_badge[textcolor]; ?>" class="color regular-text code" /></td>
</tr>

<tr valign="top" class="alternate"> 
		<th scope="row" style="width:20%;"><label for="wp_badge_options[bordercolor]">Badge border color</label></th> 
	<td><input autocomplete="off" type="text" name="wp_badge_options[bordercolor]" value="<?php echo $wp_badge[bordercolor]; ?>" class="color regular-text code" /></td>
</tr>

<tr valign="top"> 
		<th scope="row" style="width:20%;"><label for="wp_badge_options[icon]">Window status for the link</label></th> 
	<td><input autocomplete="off" type="hidden" name="wp_badge_options[icon]" id="iconurl" value="<?php echo $wp_badge[icon]; ?>" class="regular-text code" /><select name="wp_badge_options[icon]">
<option value="new window" <?php if ($wp_badge['icon'] == "new window"){ echo "selected";}?> >new window</option>
<option value="same window" <?php if ($wp_badge['icon'] == "same window"){ echo "selected";}?> >same window</option>
</select></td>
</tr>

</table>
</div>
</div>

<h2>Positioning</h2> 
<table class="form-table">

<tr valign="top">
<th scope="row"><label for="wp_badge_options[align]">Alignment</label></th>
<td><select name="wp_badge_options[align]">
<option value="left" <?php if ($wp_badge['align'] == "left"){ echo "selected";}?> >Left</option>
<option value="right" <?php if ($wp_badge['align'] == "right"){ echo "selected";}?> >Right</option>
</select></td>
</tr>


<tr valign="top"> 
		<th scope="row" style="width:20%;"><label for="wp_badge_options[iconbgcolor]">Set Padding of Bedge Box from right side</label></th>
	<td><input  type="text" name="wp_badge_options[iconbgcolor]" value="<?php echo $wp_badge[iconbgcolor]; ?>"   />
	<br />
	For example You can put any numeric value to adjust space from side.<br />Important Note:- Please reduce the value of padding if your badge does't appear on page!
	</td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_badge_options[top]">Distance From Top</label></th> 
<td><input type="text" name="wp_badge_options[top]" class="small-text" value="<?php echo $wp_badge['top']; ?>" />&nbsp;px</td>
</tr>


</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</div>
</form>

   <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>WP Badge</span></h3>
			  <div class="inside">
                <ul>
                <li><a href="http://www.stefandes.com/wordpress-plugin/plugin-wp-badge-personalized-badge-wordpress-blog/" title="WP Badge plugin page" target="_blank">Plugin Homepage</a></li>
                <li><a href="http://www.stefandes.com" title="Web Marketing Service" target="_blank">Web Marketing</a></li>
				<li>Like this plugin? Make a donation!<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="10771834">
<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>
</li>
<li><a href="http://twitter.com/stefandes/" title="Follow Me on Twitter!" target="_blank">Follow Me on Twitter!</a></li>
               </ul> 
              </div> 
			</div> 
     </div>
     
  

</div> <!--end of poststuff -->


</div> <!--end of float wrap -->
<?php	
}
// adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'wp_badge_settings');
  add_action( 'admin_init', 'register_wp_badge_settings' ); 
} 
function register_wp_badge_settings() { // whitelist options
  register_setting( 'wp-badge-group', 'wp_badge_options' );
}

?>