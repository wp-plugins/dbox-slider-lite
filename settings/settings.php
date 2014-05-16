<?php // Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'dboxlite_slider_settings');
  add_action( 'admin_init', 'register_dboxlite_settings' ); 
} 
// function for adding settings page to wp-admin
function dboxlite_slider_settings() {
    // Add a new submenu under Options:
	add_menu_page( 'DboxLite Slider', 'DboxLite Slider', 'manage_options','dboxlite-slider-admin', 'dboxlite_slider_create_multiple_sliders');
	add_submenu_page('dboxlite-slider-admin', 'DboxLite Sliders', 'Sliders', 'manage_options', 'dboxlite-slider-admin', 'dboxlite_slider_create_multiple_sliders');
	add_submenu_page('dboxlite-slider-admin', 'DboxLite Slider Settings', 'Settings', 'manage_options', 'dboxlite-slider-settings', 'dboxlite_slider_settings_page');
	}
require_once (dirname (__FILE__) . '/sliders.php');
// This function displays the page content for the DboxLite Slider Options submenu
function dboxlite_slider_settings_page() {
global $dboxlite_slider,$default_dboxlite_slider_settings;
$scounter='';
$cntr = '';

$new_settings_msg='';

//Reset Settings
if (isset ($_POST['dboxlite_reset_settings_submit'])) {
	if ( $_POST['dboxlite_reset_settings']!='n' ) {
	  $dboxlite_reset_settings=$_POST['dboxlite_reset_settings'];
	  $options='dbox_slider_options';
	  $optionsvalue=get_option($options);
		$new_settings_value=$default_dboxlite_slider_settings;
	$new_settings_value['setname']=$optionsvalue['setname'];
	update_option($options,$new_settings_value);
	}
}


$group='dboxlite-slider-group';
$dboxlite_slider_options='dbox_slider_options';
$dboxlite_slider_curr=get_option($dboxlite_slider_options);
$curr = 'Default';

foreach($default_dboxlite_slider_settings as $key=>$value){
	if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
}
?>

<div class="wrap" style="clear:both;">

<h2 class="top_heading"><?php _e('DboxLite Slider Settings: ','dboxlite-slider'); echo '<span>'.$curr.'</span>'; ?> </h2>
<div class="svilla_cl"></div>
<?php echo $new_settings_msg;?>
<?php 
if ($dboxlite_slider_curr['disable_preview'] != '1'){
?>
<div id="settings_preview"><h2 class="heading"><?php _e('Preview','dboxlite-slider'); ?></h2> 
<?php 
if ($dboxlite_slider_curr['preview'] == "0")
	get_dbox_slider('','');
elseif($dboxlite_slider_curr['preview'] == "1")
	get_dbox_slider_category($dboxlite_slider_curr['catg_slug'],'');
else
	get_dbox_slider_recent();
?></div>
<?php } ?>

<?php echo $new_settings_msg;?>

<div id="dboxlite_settings" style="float:left;width:70%;">
<form method="post" action="options.php" id="dboxlite_slider_form" name="dboxlite_slider_form">
<?php settings_fields($group); ?>

<?php
if(!isset($cntr) or empty($cntr)){}
else{?>
	<table class="form-table">
		<tr valign="top">
		<th scope="row"><h3><?php _e('Setting Set Name','dboxlite-slider'); ?></h3></th>
		<td><h3><input type="text" name="<?php echo $dboxlite_slider_options;?>[setname]" id="dboxlite_slider_setname" class="regular-text" value="<?php echo $dboxlite_slider_curr['setname']; ?>" /></h3></td>
		</tr>
	</table>
<?php }
?>

<div id="slider_tabs">
        <ul class="ui-tabs">
			<li class="green"><a href="#basic">Basic</a></li>
			<li class="pink"><a href="#slides">Slides</a></li>
			<li class="yellow"><a href="#slider_nav">Navigation</a></li>
			<li class="orange"><a href="#preview">Preview</a></li>
			<li class="asbestos"><a href="#cssvalues">Generated CSS</a></li>
        </ul>

<div id="basic">
<div class="sub_settings">
<h2 class="sub-heading">
<?php _e('Basic Settings','dboxlite-slider'); ?></h2> 
<p><?php _e('A set of very basic settings/options','dboxlite-slider'); ?></p> 

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Direction of Rotation','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[direction_rotation]" >
<option value="h" <?php if ($dboxlite_slider_curr['direction_rotation'] == "h"){ echo "selected";}?> ><?php _e('Horizontal','dboxlite-slider'); ?></option>
<option value="v" <?php if ($dboxlite_slider_curr['direction_rotation'] == "v"){ echo "selected";}?> ><?php _e('Vertical','dboxlite-slider'); ?></option>
<option value="r" <?php if ($dboxlite_slider_curr['direction_rotation'] == "r"){ echo "selected";}?> ><?php _e('Both Horizontal and Vertical','dboxlite-slider'); ?></option></select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Speed','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[speed]" id="dboxlite_slider_speed" class="small-text" value="<?php echo $dboxlite_slider_curr['speed']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Disable Autoplay','dboxlite-slider'); ?></th>
<td><input name="<?php echo $dboxlite_slider_options;?>[disable_autoplay]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['disable_autoplay']); ?>  />
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Time between Transition','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[interval]" id="dboxlite_slider_time" class="small-text" value="<?php echo $dboxlite_slider_curr['interval']; ?>" />&nbsp;<?php _e('sec','dboxlite_slider');?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Max. Number of Posts in the DboxLite Slider','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[no_posts]" id="dboxlite_slider_no_posts" class="small-text" value="<?php echo $dboxlite_slider_curr['no_posts']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Break each Slide into','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[cc]" class="small-text">
<option value="1" <?php if ($dboxlite_slider_curr['cc'] == "1"){ echo "selected";}?> ><?php _e('1','dboxlite-slider'); ?></option>
<option value="3" <?php if ($dboxlite_slider_curr['cc'] == "3"){ echo "selected";}?> ><?php _e('3','dboxlite-slider'); ?></option>
<option value="5" <?php if ($dboxlite_slider_curr['cc'] == "5"){ echo "selected";}?> ><?php _e('5','dboxlite-slider'); ?></option>
<option value="7" <?php if ($dboxlite_slider_curr['cc'] == "7"){ echo "selected";}?> ><?php _e('7','dboxlite-slider'); ?></option>
<option value="9" <?php if ($dboxlite_slider_curr['cc'] == "9"){ echo "selected";}?> ><?php _e('9','dboxlite-slider'); ?></option>
<option value="11" <?php if ($dboxlite_slider_curr['cc'] == "11"){ echo "selected";}?> ><?php _e('11','dboxlite-slider'); ?></option>
<option value="13" <?php if ($dboxlite_slider_curr['cc'] == "13"){ echo "selected";}?> ><?php _e('13','dboxlite-slider'); ?></option>
<option value="15" <?php if ($dboxlite_slider_curr['cc'] == "15"){ echo "selected";}?> ><?php _e('15','dboxlite-slider'); ?></option></select>&nbsp;<?php _e('cubes','dboxlite_slider');?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Max. Slider Width','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[width]" id="dboxlite_slider_width" class="small-text" value="<?php echo $dboxlite_slider_curr['width']; ?>" />&nbsp;<?php _e('px','dboxlite-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Max. Slider Height','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[height]" id="dboxlite_slider_height" class="small-text" value="<?php echo $dboxlite_slider_curr['height']; ?>" />&nbsp;<?php _e('px','dboxlite-slider'); ?></td>
</tr>

</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m">
<h2 class="sub-heading"><?php _e('Miscellaneous','dboxlite-slider'); ?></h2> 

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Retain these html tags','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[allowable_tags]" class="regular-text code" value="<?php echo $dboxlite_slider_curr['allowable_tags']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Continue Reading Text','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[more]" class="regular-text code" value="<?php echo $dboxlite_slider_curr['more']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Slide Link (\'a\' element) attributes  ','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[a_attr]" class="regular-text code" value="<?php echo htmlentities( $dboxlite_slider_curr['a_attr'] , ENT_QUOTES); ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('eg. target="_blank" rel="external nofollow"','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Randomize Slides in Slider','dboxlite-slider'); ?></th>
<td><input name="<?php echo $dboxlite_slider_options;?>[rand]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['rand']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('check this if you want the slides added to appear in random order.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<!-- Code added by sampada- Start -->
<tr valign="top">
<th scope="row"><?php _e('Show Content on Hover of Slide','dboxlite-slider'); ?></th>
<td><input name="<?php echo $dboxlite_slider_options;?>[show_content_hover]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['show_content_hover']); ?>  />
</td>
</tr>
<!-- Code added by sampada - End -->

<?php if(!isset($cntr) or empty($cntr)){?>

<tr valign="top">
<th scope="row"><?php _e('Minimum User Level to add Post to the Slider','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[user_level]" >
<option value="manage_options" <?php if ($dboxlite_slider_curr['user_level'] == "manage_options"){ echo "selected";}?> ><?php _e('Administrator','dboxlite-slider'); ?></option>
<option value="edit_others_posts" <?php if ($dboxlite_slider_curr['user_level'] == "edit_others_posts"){ echo "selected";}?> ><?php _e('Editor and Admininstrator','dboxlite-slider'); ?></option>
<option value="publish_posts" <?php if ($dboxlite_slider_curr['user_level'] == "publish_posts"){ echo "selected";}?> ><?php _e('Author, Editor and Admininstrator','dboxlite-slider'); ?></option>
<option value="edit_posts" <?php if ($dboxlite_slider_curr['user_level'] == "edit_posts"){ echo "selected";}?> ><?php _e('Contributor, Author, Editor and Admininstrator','dboxlite-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Text to display in the JavaScript disabled browser','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[noscript]" class="regular-text code" value="<?php echo $dboxlite_slider_curr['noscript']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('DboxLite Slider Styles to Use on Other than Post/Pages','dboxlite-slider'); ?> <small><?php _e('(i.e. for index.php,category.php,archive.php etc)','dboxlite-slider'); ?></small></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[stylesheet]" >
<?php 
$directory = DBOXLITE_SLIDER_CSS_DIR;
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { ?>
      <option value="<?php echo $file;?>" <?php if ($dboxlite_slider_curr['stylesheet'] == $file){ echo "selected";}?> ><?php echo $file;?></option>
 <?php  } }
    closedir($handle);
}
?>
</select>
</td>
<?php } ?>

<?php if(!isset($cntr) or empty($cntr)){?>
<tr valign="top">
<th scope="row"><?php _e('Multiple Slider Feature','dboxlite-slider'); ?></th>
<td><label for="dboxlite_slider_multiple"> 
<input name="<?php echo $dboxlite_slider_options;?>[multiple_sliders]" type="checkbox" id="dboxlite_slider_multiple" value="1" <?php checked("1", $dboxlite_slider_curr['multiple_sliders']); ?> /> 
 <?php _e('Enable Multiple Slider Function on Edit Post/Page','dboxlite-slider'); ?></label></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Create "SliderVilla Slides" Custom Post Type','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[custom_post]" >
<option value="0" <?php if ($dboxlite_slider_curr['custom_post'] == "0"){ echo "selected";}?> ><?php _e('No','dboxlite-slider'); ?></option>
<option value="1" <?php if ($dboxlite_slider_curr['custom_post'] == "1"){ echo "selected";}?> ><?php _e('Yes','dboxlite-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Remove DboxLite Slider Metabox on','dboxlite-slider'); ?></th>
<td>
<select name="<?php echo $dboxlite_slider_options;?>[remove_metabox][]" multiple="multiple" size="3" style="min-height:6em;">
<?php 
$args=array(
  'public'   => true
); 
$output = 'objects'; // names or objects, note names is the default
$post_types=get_post_types($args,$output); $remove_post_type_arr=$dboxlite_slider_curr['remove_metabox'];
if(!isset($remove_post_type_arr) or !is_array($remove_post_type_arr) ) $remove_post_type_arr=array();
		foreach($post_types as $post_type) { ?>
                  <option value="<?php echo $post_type->name;?>" <?php if(in_array($post_type->name,$remove_post_type_arr)){echo 'selected';} ?>><?php echo $post_type->labels->name;?></option>
                <?php } ?>
</select>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('You can select single/multiple post types using Ctrl+Mouse Click. To deselect a single post type, use Ctrl+Mouse Click','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<?php } ?>

<tr valign="top">
<th scope="row"><?php _e('Enable FOUC','dboxlite-slider'); ?></th>
<td><input name="<?php echo $dboxlite_slider_options;?>[fouc]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['fouc']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('check this if you would not want to disable Flash of Unstyled Content in the slider when the page is loaded.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<?php if(!isset($cntr) or empty($cntr)){?>

<tr valign="top">
<th scope="row"><?php _e('Custom Styles','dboxlite-slider'); ?></th>
<td><textarea name="<?php echo $dboxlite_slider_options;?>[css]"  rows="5" class="regular-text code"><?php echo $dboxlite_slider_curr['css']; ?></textarea>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('custom css styles that you would want to be applied to the slider elements.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<?php } ?>

</table>
</div>

</div> <!--Basic -->

<div id="slides">

<div class="sub_settings">
<h2 class="sub-heading"><?php _e('Slide Image','dboxlite-slider'); ?></h2> 
<p><?php _e('Customize the looks of the Slide Image','dboxlite-slider'); ?></p> 
<table class="form-table">

<tr valign="top"> 
<th scope="row"><?php _e('Image Pick Preferences','dboxlite-slider'); ?> <small><?php _e('(The first one is having priority over second, the second having priority on third and so on)','dboxlite-slider'); ?></small></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Image Pick Sequence','dboxlite-slider'); ?> <small><?php _e('(The first one is having priority over second, the second having priority on third and so on)','dboxlite-slider'); ?></small> </span></legend> 
<input name="<?php echo $dboxlite_slider_options;?>[img_pick][0]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['img_pick'][0]); ?>  /> <?php _e('Use Custom Field/Key','dboxlite-slider'); ?> &nbsp; &nbsp; 
<input type="text" name="<?php echo $dboxlite_slider_options;?>[img_pick][1]" class="text" value="<?php echo $dboxlite_slider_curr['img_pick'][1]; ?>" /> <?php _e('Name of the Custom Field/Key','dboxlite-slider'); ?>
<br />
<input name="<?php echo $dboxlite_slider_options;?>[img_pick][2]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['img_pick'][2]); ?>  /> <?php _e('Use Featured Post/Thumbnail (Wordpress 3.0 +  feature)','dboxlite-slider'); ?>&nbsp; <br />
<input name="<?php echo $dboxlite_slider_options;?>[img_pick][3]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['img_pick'][3]); ?>  /> <?php _e('Consider Images attached to the post','dboxlite-slider'); ?> &nbsp; &nbsp; 
<input type="text" name="<?php echo $dboxlite_slider_options;?>[img_pick][4]" class="small-text" value="<?php echo $dboxlite_slider_curr['img_pick'][4]; ?>" /> <?php _e('Order of the Image attachment to pick','dboxlite-slider'); ?> &nbsp; <br />
<input name="<?php echo $dboxlite_slider_options;?>[img_pick][5]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['img_pick'][5]); ?>  /> <?php _e('Scan images from the post, in case there is no attached image to the post','dboxlite-slider'); ?>&nbsp; 
</fieldset></td> 
</tr> 

<tr valign="top">
<th scope="row"><?php _e('Wordpress Image Extract Size','dboxlite-slider'); ?>
</th>
<td><select name="<?php echo $dboxlite_slider_options;?>[crop]" id="dboxlite_slider_img_crop" >
<option value="0" <?php if ($dboxlite_slider_curr['crop'] == "0"){ echo "selected";}?> ><?php _e('Full','dboxlite-slider'); ?></option>
<option value="1" <?php if ($dboxlite_slider_curr['crop'] == "1"){ echo "selected";}?> ><?php _e('Large','dboxlite-slider'); ?></option>
<option value="2" <?php if ($dboxlite_slider_curr['crop'] == "2"){ echo "selected";}?> ><?php _e('Medium','dboxlite-slider'); ?></option>
<option value="3" <?php if ($dboxlite_slider_curr['crop'] == "3"){ echo "selected";}?> ><?php _e('Thumbnail','dboxlite-slider'); ?></option>
</select>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('This is for fast page load, in case you choose \'Custom Size\' setting from below, you would not like to extract \'full\' size image from the media library. In this case you can use, \'medium\' or \'thumbnail\' image. This is because, for every image upload to the media gallery WordPress creates four sizes of the same image. So you can choose which to load in the slider and then specify the actual size.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Enable Image Cropping (using timthumb)','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[timthumb]" >
<option value="0" <?php if ($dboxlite_slider_curr['timthumb'] == "0"){ echo "selected";}?> ><?php _e('No','dboxlite-slider'); ?></option>
<option value="1" <?php if ($dboxlite_slider_curr['timthumb'] == "1"){ echo "selected";}?> ><?php _e('Yes','dboxlite-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Make pure Image Slider','dboxlite-slider'); ?></th>
<td><input name="<?php echo $dboxlite_slider_options;?>[image_only]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['image_only']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('check this to convert DboxLite Slider to Image Slider with no content','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Enable image title text on hover','dboxlite-slider'); ?></th>
<td><input name="<?php echo $dboxlite_slider_options;?>[image_title_text]" type="checkbox" value="1" <?php checked('1', $dboxlite_slider_curr['image_title_text']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If enabled, whenever user hovers the Slide Image, the image title attribute will be displayed.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Default Image','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[default_image]" id="dboxlite_slider_default_image" class="regular-text code" value="<?php echo $dboxlite_slider_curr['default_image']; ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('Enter the url of the default image i.e. the image to be displayed if there is no image available for the slide. By default, the url is <br />','dboxlite-slider');echo '<span style="color:#0000ff;">'.$dboxlite_slider_curr['default_image'].'</span>';?>
	</div>
</span>
</td>
</tr>

</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m">
<h2 class="sub-heading"><?php _e('Slide/Post Title','dboxlite-slider'); ?></h2> 
<p><?php _e('Customize the looks of the title of each of the sliding post here','dboxlite-slider'); ?></p> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Font','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[ptitle_font]" id="dboxlite_slider_ptitle_font" >
<option value="Arial,Helvetica,sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
<option value="Verdana,Geneva,sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
<option value="Tahoma,Geneva,sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "Tahoma,Geneva,sans-serif"){ echo "selected";}?> >Tahoma,Geneva,sans-serif</option>
<option value="Trebuchet MS,sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "Trebuchet MS,sans-serif"){ echo "selected";}?> >Trebuchet MS,sans-serif</option>
<option value="'Century Gothic','Avant Garde',sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Century Gothic','Avant Garde',sans-serif"){ echo "selected";}?> >'Century Gothic','Avant Garde',sans-serif</option>
<option value="'Arial Narrow',sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Arial Narrow',sans-serif"){ echo "selected";}?> >'Arial Narrow',sans-serif</option>
<option value="'Arial Black',sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Arial Black',sans-serif"){ echo "selected";}?> >'Arial Black',sans-serif</option>
<option value="'Gills Sans MT','Gills Sans',sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Gills Sans MT','Gills Sans',sans-serif"){ echo "selected";} ?> >'Gills Sans MT','Gills Sans',sans-serif</option>
<option value="'Times New Roman',Times,serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Times New Roman',Times,serif"){ echo "selected";}?> >'Times New Roman',Times,serif</option>
<option value="Georgia,serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "Georgia,serif"){ echo "selected";}?> >Georgia,serif</option>
<option value="Garamond,serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "Garamond,serif"){ echo "selected";}?> >Garamond,serif</option>
<option value="'Century Schoolbook','New Century Schoolbook',serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Century Schoolbook','New Century Schoolbook',serif"){ echo "selected";}?> >'Century Schoolbook','New Century Schoolbook',serif</option>
<option value="'Bookman Old Style',Bookman,serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Bookman Old Style',Bookman,serif"){ echo "selected";}?> >'Bookman Old Style',Bookman,serif</option>
<option value="'Comic Sans MS',cursive" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Comic Sans MS',cursive"){ echo "selected";}?> >'Comic Sans MS',cursive</option>
<option value="'Courier New',Courier,monospace" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Courier New',Courier,monospace"){ echo "selected";}?> >'Courier New',Courier,monospace</option>
<option value="'Copperplate Gothic Bold',Copperplate,fantasy" <?php if ($dboxlite_slider_curr['ptitle_font'] == "'Copperplate Gothic Bold',Copperplate,fantasy"){ echo "selected";}?> >'Copperplate Gothic Bold',Copperplate,fantasy</option>
<option value="Impact,fantasy" <?php if ($dboxlite_slider_curr['ptitle_font'] == "Impact,fantasy"){ echo "selected";}?> >Impact,fantasy</option>
<option value="sans-serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "sans-serif"){ echo "selected";}?> >sans-serif</option>
<option value="serif" <?php if ($dboxlite_slider_curr['ptitle_font'] == "serif"){ echo "selected";}?> >serif</option>
<option value="cursive" <?php if ($dboxlite_slider_curr['ptitle_font'] == "cursive"){ echo "selected";}?> >cursive</option>
<option value="monospace" <?php if ($dboxlite_slider_curr['ptitle_font'] == "monospace"){ echo "selected";}?> >monospace</option>
<option value="fantasy" <?php if ($dboxlite_slider_curr['ptitle_font'] == "fantasy"){ echo "selected";}?> >fantasy</option>
</select>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('This value will be fallback font if Google web font value is specified below','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Google Web Font','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[ptitle_fontg]" id="dboxlite_slider_ptitle_fontg" value="<?php echo htmlentities($dboxlite_slider_curr['ptitle_fontg'], ENT_QUOTES); ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('eg. enter value like Open+Sans or Oswald or Open+Sans+Condensed:300 etc.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Color','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[ptitle_fcolor]" id="color_value_3" value="<?php echo $dboxlite_slider_curr['ptitle_fcolor']; ?>" />&nbsp; <img id="color_picker_3" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_3"></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Size','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[ptitle_fsize]" id="dboxlite_slider_ptitle_fsize" class="small-text" value="<?php echo $dboxlite_slider_curr['ptitle_fsize']; ?>" />&nbsp;<?php _e('px','dboxlite-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[ptitle_fstyle]" id="dboxlite_slider_ptitle_fstyle" >
<option value="bold" <?php if ($dboxlite_slider_curr['ptitle_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','dboxlite-slider'); ?></option>
<option value="bold italic" <?php if ($dboxlite_slider_curr['ptitle_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','dboxlite-slider'); ?></option>
<option value="italic" <?php if ($dboxlite_slider_curr['ptitle_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','dboxlite-slider'); ?></option>
<option value="normal" <?php if ($dboxlite_slider_curr['ptitle_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','dboxlite-slider'); ?></option>
</select>
</td>
</tr>
</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m">
<h2 class="sub-heading"><?php _e('Slide Content','dboxlite-slider'); ?></h2> 
<p><?php _e('Customize the looks of the content of each of the sliding post here','dboxlite-slider'); ?></p> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Show content/description below title','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[show_content]" >
<option value="1" <?php if ($dboxlite_slider_curr['show_content'] == "1"){ echo "selected";}?> ><?php _e('Yes','dboxlite-slider'); ?></option>
<option value="0" <?php if ($dboxlite_slider_curr['show_content'] == "0"){ echo "selected";}?> ><?php _e('No','dboxlite-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[content_font]" id="dboxlite_slider_content_font" >
<option value="Arial,Helvetica,sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
<option value="Verdana,Geneva,sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
<option value="Tahoma,Geneva,sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "Tahoma,Geneva,sans-serif"){ echo "selected";}?> >Tahoma,Geneva,sans-serif</option>
<option value="Trebuchet MS,sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "Trebuchet MS,sans-serif"){ echo "selected";}?> >Trebuchet MS,sans-serif</option>
<option value="'Century Gothic','Avant Garde',sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "'Century Gothic','Avant Garde',sans-serif"){ echo "selected";}?> >'Century Gothic','Avant Garde',sans-serif</option>
<option value="'Arial Narrow',sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "'Arial Narrow',sans-serif"){ echo "selected";}?> >'Arial Narrow',sans-serif</option>
<option value="'Arial Black',sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "'Arial Black',sans-serif"){ echo "selected";}?> >'Arial Black',sans-serif</option>
<option value="'Gills Sans MT','Gills Sans',sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "'Gills Sans MT','Gills Sans',sans-serif"){ echo "selected";} ?> >'Gills Sans MT','Gills Sans',sans-serif</option>
<option value="'Times New Roman',Times,serif" <?php if ($dboxlite_slider_curr['content_font'] == "'Times New Roman',Times,serif"){ echo "selected";}?> >'Times New Roman',Times,serif</option>
<option value="Georgia,serif" <?php if ($dboxlite_slider_curr['content_font'] == "Georgia,serif"){ echo "selected";}?> >Georgia,serif</option>
<option value="Garamond,serif" <?php if ($dboxlite_slider_curr['content_font'] == "Garamond,serif"){ echo "selected";}?> >Garamond,serif</option>
<option value="'Century Schoolbook','New Century Schoolbook',serif" <?php if ($dboxlite_slider_curr['content_font'] == "'Century Schoolbook','New Century Schoolbook',serif"){ echo "selected";}?> >'Century Schoolbook','New Century Schoolbook',serif</option>
<option value="'Bookman Old Style',Bookman,serif" <?php if ($dboxlite_slider_curr['content_font'] == "'Bookman Old Style',Bookman,serif"){ echo "selected";}?> >'Bookman Old Style',Bookman,serif</option>
<option value="'Comic Sans MS',cursive" <?php if ($dboxlite_slider_curr['content_font'] == "'Comic Sans MS',cursive"){ echo "selected";}?> >'Comic Sans MS',cursive</option>
<option value="'Courier New',Courier,monospace" <?php if ($dboxlite_slider_curr['content_font'] == "'Courier New',Courier,monospace"){ echo "selected";}?> >'Courier New',Courier,monospace</option>
<option value="'Copperplate Gothic Bold',Copperplate,fantasy" <?php if ($dboxlite_slider_curr['content_font'] == "'Copperplate Gothic Bold',Copperplate,fantasy"){ echo "selected";}?> >'Copperplate Gothic Bold',Copperplate,fantasy</option>
<option value="Impact,fantasy" <?php if ($dboxlite_slider_curr['content_font'] == "Impact,fantasy"){ echo "selected";}?> >Impact,fantasy</option>
<option value="sans-serif" <?php if ($dboxlite_slider_curr['content_font'] == "sans-serif"){ echo "selected";}?> >sans-serif</option>
<option value="serif" <?php if ($dboxlite_slider_curr['content_font'] == "serif"){ echo "selected";}?> >serif</option>
<option value="cursive" <?php if ($dboxlite_slider_curr['content_font'] == "cursive"){ echo "selected";}?> >cursive</option>
<option value="monospace" <?php if ($dboxlite_slider_curr['content_font'] == "monospace"){ echo "selected";}?> >monospace</option>
<option value="fantasy" <?php if ($dboxlite_slider_curr['content_font'] == "fantasy"){ echo "selected";}?> >fantasy</option>
</select>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('This value will be fallback font if Google web font value is specified below','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Google Web Font','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[content_fontg]" id="dboxlite_slider_content_fontg" value="<?php echo htmlentities($dboxlite_slider_curr['content_fontg'], ENT_QUOTES); ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('eg. enter value like Open+Sans or Oswald or Open+Sans+Condensed:300 etc.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Color','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[content_fcolor]" id="color_value_5" value="<?php echo $dboxlite_slider_curr['content_fcolor']; ?>" />&nbsp; <img id="color_picker_5" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_5"></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Size','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[content_fsize]" id="dboxlite_slider_content_fsize" class="small-text" value="<?php echo $dboxlite_slider_curr['content_fsize']; ?>" />&nbsp;<?php _e('px','dboxlite-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[content_fstyle]" id="dboxlite_slider_content_fstyle" >
<option value="bold" <?php if ($dboxlite_slider_curr['content_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','dboxlite-slider'); ?></option>
<option value="bold italic" <?php if ($dboxlite_slider_curr['content_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','dboxlite-slider'); ?></option>
<option value="italic" <?php if ($dboxlite_slider_curr['content_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','dboxlite-slider'); ?></option>
<option value="normal" <?php if ($dboxlite_slider_curr['content_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','dboxlite-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Pick content From','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[content_from]" id="dboxlite_slider_content_from" >
<option value="slider_content" <?php if ($dboxlite_slider_curr['content_from'] == "slider_content"){ echo "selected";}?> ><?php _e('Slider Content Custom field','dboxlite-slider'); ?></option>
<option value="excerpt" <?php if ($dboxlite_slider_curr['content_from'] == "excerpt"){ echo "selected";}?> ><?php _e('Post Excerpt','dboxlite-slider'); ?></option>
<option value="content" <?php if ($dboxlite_slider_curr['content_from'] == "content"){ echo "selected";}?> ><?php _e('From Content','dboxlite-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Maximum content size (in words)','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[content_limit]" id="dboxlite_slider_content_limit" class="small-text" value="<?php echo $dboxlite_slider_curr['content_limit']; ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('if specified will override the \'Maximum Content Size in Chracters\' setting below','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Maximum content size (in characters)','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[content_chars]" id="dboxlite_slider_content_chars" class="small-text" value="<?php echo $dboxlite_slider_curr['content_chars']; ?>" />&nbsp;<?php _e('characters','dboxlite-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Content Background Color','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[bg_color]" id="color_value_1" value="<?php echo $dboxlite_slider_curr['bg_color']; ?>" />&nbsp; <img id="color_picker_1" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_1"></div> <br />
<label for="dboxlite_slider_bg"><input name="<?php echo $dboxlite_slider_options;?>[bg]" type="checkbox" id="dboxlite_slider_bg" value="1" <?php checked('1', $dboxlite_slider_curr['bg']); ?>  /><?php _e(' Use Transparent Background','dboxlite-slider'); ?></label> </td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Content Background Opacity','dboxlite-slider'); ?></th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[bg_opacity]" id="dboxlite_slider_bg_opacity" class="small-text" value="<?php echo $dboxlite_slider_curr['bg_opacity']; ?>" /></td>
</tr>

</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

</div> <!--#slides-->

<div id="slider_nav">
<div class="sub_settings">
<h2 class="sub-heading"><?php _e('Navigation Buttons','dboxlite-slider'); ?></h2> 

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Button Width','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[pn_width]" id="dboxlite_slider_pn_width" class="small-text" value="<?php echo $dboxlite_slider_curr['pn_width']; ?>" />&nbsp;<?php _e('px','dboxlite-slider'); ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Background Color','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[navarr_bgcolor]" id="color_value_2" value="<?php echo $dboxlite_slider_curr['navarr_bgcolor']; ?>" />&nbsp; <img id="color_picker_2" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_2">
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Background Color on Hover','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[navarr_bgcolor_hover]" id="color_value_4" value="<?php echo $dboxlite_slider_curr['navarr_bgcolor_hover']; ?>" />&nbsp; <img id="color_picker_4" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_4">
</td>
</tr>


</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings">
<h2 class="sub-heading"><?php _e('Navigation Dots','dboxlite-slider'); ?></h2> 

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Background Color','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[navdot_color]" id="color_value_7" value="<?php echo $dboxlite_slider_curr['navdot_color']; ?>" />&nbsp; <img id="color_picker_7" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_7">
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Fill Color of Current Dot','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[currnavdot_color]" id="color_value_12" value="<?php echo $dboxlite_slider_curr['currnavdot_color']; ?>" />&nbsp; <img id="color_picker_12" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_12">
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Size','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[dotsize]" class="small-text" value="<?php echo $dboxlite_slider_curr['dotsize']; ?>" />&nbsp;<?php _e('px','dboxlite-slider'); ?>
</td>
</tr>

</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings">
<h2 class="sub-heading"><?php _e('Navigation Play/Pause','dboxlite-slider'); ?></h2> 

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Background Color','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[playbt_color]" id="color_value_10" value="<?php echo $dboxlite_slider_curr['playbt_color']; ?>" />&nbsp; <img id="color_picker_10" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_10">
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Background Color on Hover','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[playbt_color_hover]" id="color_value_11" value="<?php echo $dboxlite_slider_curr['playbt_color_hover']; ?>" />&nbsp; <img id="color_picker_11" src="<?php echo dboxlite_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','dboxlite-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_11">
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Size','dboxlite-slider'); ?> </th>
<td><input type="text" name="<?php echo $dboxlite_slider_options;?>[playbt_size]" class="small-text" value="<?php echo $dboxlite_slider_curr['playbt_size']; ?>" />&nbsp;<?php _e('px','dboxlite-slider'); ?>
</td>
</tr>

</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

</div> <!--#slider_nav-->

<div id="preview">
<div class="sub_settings">
<h2 class="sub-heading"><?php _e('Preview on Settings Panel','dboxlite-slider'); ?></h2> 

<table class="form-table">

<tr valign="top"> 
<th scope="row"><label for="dboxlite_slider_disable_preview"><?php _e('Disable Preview Section','dboxlite-slider'); ?></label></th> 
<td> 
<input name="<?php echo $dboxlite_slider_options;?>[disable_preview]" type="checkbox" id="dboxlite_slider_disable_preview" value="1" <?php checked("1", $dboxlite_slider_curr['disable_preview']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If disabled, the \'Preview\' of Slider on this Settings page will be removed.','dboxlite-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('DboxLite Template Tag for Preview','dboxlite-slider'); ?></th>
<td><select name="<?php echo $dboxlite_slider_options;?>[preview]" id="dboxlite_slider_preview">
<option value="2" <?php if ($dboxlite_slider_curr['preview'] == "2"){ echo "selected";}?> ><?php _e('Recent Posts Slider','dboxlite-slider'); ?></option>
<option value="1" <?php if ($dboxlite_slider_curr['preview'] == "1"){ echo "selected";}?> ><?php _e('Category Slider','dboxlite-slider'); ?></option>
<option value="0" <?php if ($dboxlite_slider_curr['preview'] == "0"){ echo "selected";}?> ><?php _e('Custom Slider','dboxlite-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top"> 
<th scope="row"><?php _e('Preview Slider Params','dboxlite-slider'); ?></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Preview Slider Params','dboxlite-slider'); ?></span></legend> 
<label for="<?php echo $dboxlite_slider_options;?>[catg_slug]" style="width:45%;"><?php _e('Category Slug in case of Category Slider','dboxlite-slider'); ?></label>
<input type="text" name="<?php echo $dboxlite_slider_options;?>[catg_slug]" id="dboxlite_slider_catslug" class="regular-text code" style="width:100px;" value="<?php echo $dboxlite_slider_curr['catg_slug']; ?>" style="width:45%;" /> 
</fieldset></td> 
</tr> 

</table>
<p class="submit">
<input type="submit" class="button-primary" id="preview_save" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m">
<h2 class="sub-heading"><?php _e('Shortcode','dboxlite-slider'); ?></h2> 
<p><?php _e('Paste the below shortcode on Page/Post Edit Panel to get the slider as shown in the above Preview','dboxlite-slider'); ?></p><br />
<?php if($cntr=='') $s_set='1'; else $s_set=$cntr;
if ($dboxlite_slider_curr['preview'] == "0") 
	$preview='[dboxslider]';
elseif($dboxlite_slider_curr['preview'] == "1")
	$preview='[dboxcategory catg_slug="'.$dboxlite_slider_curr['catg_slug'].'"]';
else
	$preview='[dboxrecent]';
echo $preview;
?>
</div>

<div class="sub_settings_m">
<h2 class="sub-heading"><?php _e('Template Tag','dboxlite-slider'); ?></h2> 
<p><?php _e('Paste the below template tag in your theme template file like index.php or page.php at required location to get the slider as shown in the above Preview','dboxlite-slider'); ?></p><br />
<?php 
if ($dboxlite_slider_curr['preview'] == "0")
	echo '<code>&lt;?php if(function_exists("get_dbox_slider")){get_dbox_slider();}?&gt;</code>';
elseif($dboxlite_slider_curr['preview'] == "1")
	echo '<code>&lt;?php if(function_exists("get_dbox_slider_category")){get_dbox_slider_category($catg_slug="'.$dboxlite_slider_curr['catg_slug'].'");}?&gt;</code>';
else
	echo '<code>&lt;?php if(function_exists("get_dbox_slider_recent")){get_dbox_slider_recent();}?&gt;</code>';
?>
</div>

</div><!-- preview tab ends-->

<div id="cssvalues">
<div class="sub_settings">
<h2 class="sub-heading"><?php _e('CSS Generated thru these settings','dboxlite-slider'); ?></h2> 
<p><?php _e('Save Changes for the settings first and then view this data. You can use this CSS in your \'custom\' stylesheets if you use other than \'default\' value for the Stylesheet folder.','dboxlite-slider'); ?></p> 
<?php $dboxlite_slider_css = dboxlite_get_inline_css($cntr,$echo='1'); ?>
<div style="font-family:monospace;font-size:13px;background:#ddd;">
.dboxlite_slider_set<?php echo $cntr;?>{<?php echo $dboxlite_slider_css['dboxlite_slider'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_text{<?php echo $dboxlite_slider_css['dboxlite_text'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_slider_thumbnail{<?php echo $dboxlite_slider_css['dboxlite_slider_thumbnail'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_text h2{<?php echo $dboxlite_slider_css['dboxlite_slider_h2'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_text h2 a{<?php echo $dboxlite_slider_css['dboxlite_slider_h2_a'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite-content-span{<?php echo $dboxlite_slider_css['dboxlite_slider_span'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_next{<?php echo $dboxlite_slider_css['nav_next'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_prev{<?php echo $dboxlite_slider_css['nav_prev'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_nav_dots{<?php echo $dboxlite_slider_css['nav_dots'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_nav_options{<?php echo $dboxlite_slider_css['nav_options'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_play{<?php echo $dboxlite_slider_css['nav_play'];?>} <br />
.dboxlite_slider_set<?php echo $cntr;?> .dboxlite_pause{<?php echo $dboxlite_slider_css['nav_pause'];?>} <br />
</div>
</div>
</div> <!--#cssvalues-->

<div class="svilla_cl"></div><div class="svilla_cr"></div>

</div> <!--end of tabs -->

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
<input type="hidden" name="<?php echo $dboxlite_slider_options;?>[active_tab]" id="dboxlite_activetab" value="<?php echo $dboxlite_slider_curr['active_tab']; ?>" />
<input type="hidden" name="set" id="set" value="<?php echo $s_set;?>" />
<input type="hidden" name="embed_code" id="embed_code" value="" />
<input type="hidden" name="<?php echo $dboxlite_slider_options;?>[new]" id="dboxlite_new_set" value="0" />
<input type="hidden" name="<?php echo $dboxlite_slider_options;?>[popup]" id="dboxlitepopup" value="<?php echo $dboxlite_slider_curr['popup']; ?>" />
<input type="hidden" name="oldnew" id="oldnew" value="<?php echo $dboxlite_slider_curr['new']; ?>" />
<input type="hidden" name="hidden_preview" id="hidden_preview" value="<?php echo $dboxlite_slider_curr['preview']; ?>" />
<input type="hidden" name="hidden_category" id="hidden_category" value="<?php echo $dboxlite_slider_curr['catg_slug']; ?>" />
</form>
<!-- Added for shortcode to show on save of settings-->
<div id="saveResult"></div>
<div id="uploadprogress"></div>
<!-- -->

<!--Form to reset Settings set-->
<form style="float:left;" action="" method="post">
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Reset Settings to','dboxlite-slider'); ?></th>
<td><select name="dboxlite_reset_settings" id="dboxlite_slider_reset_settings" >
<option value="n" selected ><?php _e('None','dboxlite-slider'); ?></option>
<option value="g" ><?php _e('Global Default','dboxlite-slider'); ?></option>
</select>
</td>
</tr>
</table>

<p class="submit">
<input name="dboxlite_reset_settings_submit" type="submit" class="button-primary" value="<?php _e('Reset Settings') ?>" />
</p>
<!-- <div id="popup">
        <span class="button b-close"><span>X</span></span>
        If you can't get it up use
    </div>-->

</form>

<div class="svilla_cl"></div>

</div> <!--end of float left -->

<div id="poststuff" class="metabox-holder has-right-sidebar" style="float:left;width:28%;max-width:350px;min-width:inherit;">
<?php $url = dboxlite_sslider_admin_url( array( 'page' => 'dboxlite-slider-admin' ) );?>
<!---->
<script type="text/javascript">

jQuery(document).ready(function($) {
<?php if(isset($_GET['settings-updated'])) { if($_GET['settings-updated'] == 'true' and $dboxlite_slider_curr['popup'] == '1' ) { 
?>
jQuery('#saveResult').html("<div id='popup'><div class='modal_shortcode'>Quick Embed Shortcode</div><span class='button b-close'><span>X</span></span></div>");
				jQuery('#popup').append('<div class="modal_preview"><?php echo $preview;?></div>');				
				jQuery('#popup').bPopup({
		    			opacity: 0.6,
					position: ['35%', '35%'],
		    			positionStyle: 'fixed', //'fixed' or 'absolute'			
					onClose: function() { return true; }
				});

<?php }} ?>

	jQuery('#dboxlite_slider_form').submit(function(event) { 
			//event.preventDefault();
			/* Added for validations - Start */			
			var slider_speed=jQuery("#dboxlite_slider_speed").val();
			if(slider_speed=='' || slider_speed <= 0 || isNaN(slider_speed)) {
				alert("Speed should be a number greater than 0!"); 
				jQuery("#dboxlite_slider_speed").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_speed').offset().top-50}, 600);
				return false;
			}	
			var slider_time=jQuery("#dboxlite_slider_time").val();
			if(slider_time=='' || slider_time <= 0 || isNaN(slider_time)) {
				alert("Transition interval should be a number greater than 0!"); 
				jQuery("#dboxlite_slider_time").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_time').offset().top-50}, 600);
				return false;
			}	
			var slider_no_posts=jQuery("#dboxlite_slider_no_posts").val();
			if(slider_no_posts=='' || slider_no_posts <= 0 || isNaN(slider_no_posts)) {
				alert("Max. Number of Posts in the DboxLite Slider should be greater than 0!"); 
				jQuery("#dboxlite_slider_no_posts").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_no_posts').offset().top-50}, 600);
				return false;
			}
			
			var slider_width=jQuery("#dboxlite_slider_width").val();
			if(slider_width=='' || slider_width <= 0 || isNaN(slider_width)) {
				alert("Max. Slider Width should be greater than 0"); 
				jQuery("#dboxlite_slider_width").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_width').offset().top-50}, 600);
				return false;
			}
			var slider_height=jQuery("#dboxlite_slider_height").val();
			if(slider_height=='' || slider_height <= 0 || isNaN(slider_height)) {
				alert("Max. Slider Height should be greater than 0"); 
				jQuery("#dboxlite_slider_height").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_height').offset().top-50}, 600);
				return false;
			}
			/* Added for validations - End */
			var slider_preview = jQuery("#dboxlite_slider_preview").val(),
			    slider_catslug=jQuery("#dboxlite_slider_catslug").val(),
			    set=jQuery("#set").val();
			   
			if(slider_preview == "1" && slider_catslug == ''){
				alert("Category slug should be mentioned whose posts you want to display in slider");
				jQuery("#dboxlite_slider_catslug").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_catslug').offset().top-50}, 600);
				return false;
			}

  				
			var prev=jQuery("#dboxlite_slider_preview").val(),
			    hiddenpreview=jQuery("#hidden_preview").val(),
			    new_save=jQuery("#oldnew").val(),
			    hiddencatslug=jQuery("#hidden_category").val();
			    if(hiddenpreview != prev || new_save=='1' || slider_catslug != hiddencatslug ) jQuery('#dboxlitepopup').val("1");					
			else jQuery('#dboxlitepopup').val("0");	
		});
	});

</script>

<div style="margin:0 0 10px 0;"> 
<a href="http://slidervilla.com/dbox/" class="classname">Get Dbox PRO</a>
</div>

<div class="postbox" style="margin:0 0 10px 0;"> 
	<h3 class="hndle"><span></span><?php _e('Quick Embed Shortcode','dboxlite-slider'); ?></h3> 
	<div class="inside" id="shortcodeview">
	<?php if($cntr=='') $s_set='1'; else $s_set=$cntr;
	if ($dboxlite_slider_curr['preview'] == "0")
		echo '[dboxslider]';
	elseif($dboxlite_slider_curr['preview'] == "1")
		echo '[dboxcategory catg_slug="'.$dboxlite_slider_curr['catg_slug'].'"]';
	else
		echo '[dboxrecent]';
	?>
</div></div>

<div class="postbox" style="margin:10px 0;"> 
	<h3 class="hndle"><span></span><?php _e('Quick Embed Template Tag','dboxlite-slider'); ?></h3> 
	<div class="inside">
	<?php 
	if ($dboxlite_slider_curr['preview'] == "0")
		echo '<code>&lt;?php if( function_exists("get_dbox_slider") ){ get_dbox_slider(); } ?&gt;</code>';
	elseif($dboxlite_slider_curr['preview'] == "1")
		echo '<code>&lt;?php if( function_exists( "get_dbox_slider_category" ) ){ get_dbox_slider_category( $catg_slug="'.$dboxlite_slider_curr['catg_slug'].'"); } ?&gt;</code>';
	else
		echo '<code>&lt;?php if( function_exists( "get_dbox_slider_recent" ) ){ get_dbox_slider_recent(); } ?&gt;</code>';
	?>
</div></div>

<!---->
<form style="margin-right:10px;font-size:14px;width:100%;" action="" method="post">
<a href="<?php echo $url; ?>" title="<?php _e('Go to Sliders page where you can re-order the slide posts, delete the slides from the slider etc.','dboxlite-slider'); ?>" class="svilla_button svilla_gray_button"><?php _e('Go to Sliders Admin','dboxlite-slider'); ?></a>
</form>


	<div class="postbox"> 
		<div style="background:#eee;line-height:200%"><a style="text-decoration:none;font-weight:bold;font-size:100%;color:#990000" href="http://guides.slidervilla.com/dboxlite-slider/" title="Click here to read how to use the plugin and frequently asked questions about the plugin" target="_blank"> ==> Usage Guide and General FAQs</a></div>
	</div>
          
	<div class="postbox"> 
	  <h3 class="hndle"><span><?php _e('About this Plugin:','dboxlite-slider'); ?></span></h3> 
	  <div class="inside">
		<ul>
		<li><a href="http://slidervilla.com/dbox-lite/" title="<?php _e('DboxLite Slider Homepage','dboxlite-slider'); ?>
" ><?php _e('Plugin Homepage','dboxlite-slider'); ?></a></li>
		<li><a href="http://support.slidervilla.com/" title="<?php _e('Support Forum','dboxlite-slider'); ?>
" ><?php _e('Support Forum','dboxlite-slider'); ?></a></li>
		<li><a href="http://guides.slidervilla.com/dboxlite-slider/" title="<?php _e('Usage Guide','dboxlite-slider'); ?>
" ><?php _e('Usage Guide','dboxlite-slider'); ?></a></li>
		<li><strong>Current Version: 1.0</strong></li>
		</ul> 
	  </div> 
	</div> 

                 
</div> <!--end of poststuff --> 

<div style="clear:left;"></div>
<div style="clear:right;"></div>

</div> <!--end of float wrap -->
<?php	
}
function register_dboxlite_settings() { // whitelist options
	register_setting( 'dboxlite-slider-group', 'dbox_slider_options' );
}
?>
