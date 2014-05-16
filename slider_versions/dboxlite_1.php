<?php 
function dboxlite_global_data_processor( $slides, $dboxlite_slider_curr,$out_echo,$set,$data=array() ){
	return '';
}
function dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr,$out_echo,$set,$data=array() ){
	global $dboxlite_slider,$default_dboxlite_slider_settings;
	$dboxlite_slider_css = dboxlite_get_inline_css($set);
	$html = '';
	$dboxlite_sldr_j = $i = 0;
	
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	
	//Timthumb
	$timthumb='0';
	if($dboxlite_slider_curr['timthumb']=='1'){
		$timthumb='1';
	}
	
	$slider_handle='';
	if ( is_array($data) and isset($data['slider_handle']) ) {
		$slider_handle=$data['slider_handle'];
	}
	
	foreach($posts as $post) {
		$id = $post_id = $post->ID;
		$post_title = get_post_meta($id, 'SlideTitle', true);
		if(empty($post_title)) {
			$post_title = stripslashes($post->post_title);
			$post_title = str_replace('"', '', $post_title);
		}
		//filter hook
		if (isset($post_id)) $post_title=apply_filters('dboxlite_post_title',$post_title,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
		$slider_content = $post->post_content;
		
		$dbox_sslider_nolink = get_post_meta($post_id,'dbox_sslider_nolink',true);
		
		$permalink = get_permalink($post_id);

		if($dbox_sslider_nolink=='1'){
		  $permalink='';
		}
		$dboxlite_sldr_j++;	
		
		
		
		$html .= '<li class="dboxlite_slideri">';
			
		if($dboxlite_slider_curr['show_content']=='1'){
			if ($dboxlite_slider_curr['content_from'] == "slider_content") {
				$slider_content = get_post_meta($post_id, 'slider_content', true);
			}
			if ($dboxlite_slider_curr['content_from'] == "excerpt") {
				$slider_content = $post->post_excerpt;
			}

			$slider_content = strip_shortcodes( $slider_content );

			$slider_content = stripslashes($slider_content);
			$slider_content = str_replace(']]>', ']]&gt;', $slider_content);
	
			$slider_content = str_replace("\n","<br />",$slider_content);
			$slider_content = strip_tags($slider_content, $dboxlite_slider_curr['allowable_tags']);
			
			if(!$dboxlite_slider_curr['content_limit'] or $dboxlite_slider_curr['content_limit'] == '' or $dboxlite_slider_curr['content_limit'] == ' ') 
			  $slider_excerpt = substr($slider_content,0,$dboxlite_slider_curr['content_chars']);
			else 
			  $slider_excerpt = dboxlite_slider_word_limiter( $slider_content, $limit = $dboxlite_slider_curr['content_limit'] );
			//filter hook
			$slider_excerpt=apply_filters('dboxlite_slide_excerpt',$slider_excerpt,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
			$slider_excerpt='<span '.$dboxlite_slider_css['dboxlite_slider_span'].' class="dboxlite-content-span"> '.$slider_excerpt.'</span>';
		}
		else{
		    $slider_excerpt='';
		}
		//filter hook
		$slider_excerpt=apply_filters('dboxlite_slide_excerpt_html',$slider_excerpt,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
			
		//All images
		$dbox_media = get_post_meta($post_id,'dbox_media',true);
		if(!isset($dboxlite_slider_curr['img_pick'][0])) $dboxlite_slider_curr['img_pick'][0]='';
		if(!isset($dboxlite_slider_curr['img_pick'][2])) $dboxlite_slider_curr['img_pick'][2]='';
		if(!isset($dboxlite_slider_curr['img_pick'][3])) $dboxlite_slider_curr['img_pick'][3]='';
		if(!isset($dboxlite_slider_curr['img_pick'][5])) $dboxlite_slider_curr['img_pick'][5]='';
		
		if($dboxlite_slider_curr['img_pick'][0] == '1'){
		 $custom_key = array($dboxlite_slider_curr['img_pick'][1]);
		}
		else {
		 $custom_key = '';
		}
		
		if($dboxlite_slider_curr['img_pick'][2] == '1'){
		 $the_post_thumbnail = true;
		}
		else {
		 $the_post_thumbnail = false;
		}
		
		if($dboxlite_slider_curr['img_pick'][3] == '1'){
		 $attachment = true;
		 $order_of_image = $dboxlite_slider_curr['img_pick'][4];
		}
		else{
		 $attachment = false;
		 $order_of_image = '1';
		}
		
		if($dboxlite_slider_curr['img_pick'][5] == '1'){
			 $image_scan = true;
		}
		else {
			 $image_scan = false;
		}
		
		if($dboxlite_slider_curr['crop'] == '0'){
		 $extract_size = 'full';
		}
		elseif($dboxlite_slider_curr['crop'] == '1'){
		 $extract_size = 'large';
		}
		elseif($dboxlite_slider_curr['crop'] == '2'){
		 $extract_size = 'medium';
		}
		else{
		 $extract_size = 'thumbnail';
		}
		
		//Slide link anchor attributes
		$a_attr='';$imglink='';$a_attr_img='';
		$a_attr=get_post_meta($post_id,'dbox_link_attr',true);	
		if( empty($a_attr) and isset( $dboxlite_slider_curr['a_attr'] ) ) $a_attr=$dboxlite_slider_curr['a_attr'];
		$a_attr_img=$a_attr;
		$default_image=(isset($dboxlite_slider_curr['default_image']))?($dboxlite_slider_curr['default_image']):('false');
		$image_title_text=(isset($dboxlite_slider_curr['image_title_text']))?($dboxlite_slider_curr['image_title_text']):('0');
		
		$img_args = array(
			'custom_key' => $custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => $extract_size,
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => $default_image,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'dboxlite_slider_thumbnail',
			'image_scan' => $image_scan,
			'height' => false,
			'echo' => false,
			'permalink' => $permalink,
			'timthumb'=>$timthumb,
			'style'=> $dboxlite_slider_css['dboxlite_slider_thumbnail'],
			'a_attr'=> $a_attr_img,
			'imglink'=>$imglink,
			'width'=>$dboxlite_slider_curr['width'],
			'image_title_text'=>$image_title_text
		);
			
//Added for video -Start
		$dboxlite_ytburl=get_post_meta($post_id, '_dbox_youtubeurl', true);
                $dboxlite_mpurl=get_post_meta($post_id, '_dbox_mp4url', true);
                $dboxlite_weburl=get_post_meta($post_id, '_dbox_webmurl', true);
                $dboxlite_ogurl=get_post_meta($post_id, '_dbox_oggurl', true);
		$dboxlite_vshortcode=get_post_meta($post_id, '_dbox_video_shortcode', true);
//added for video -End
		
		if( empty($dbox_media) or $dbox_media=='' or !($dbox_media) ) {  
			$dboxlite_large_image=dboxlite_sslider_get_the_image($img_args);
		}
		else{
			$dboxlite_large_image=$dbox_media;
		}
		//filter hook
	
		$dboxlite_large_image=apply_filters('dboxlite_large_image',$dboxlite_large_image,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
//Added for getting src of image	
		$html .= $dboxlite_large_image;	
		
		//Added for video -Start
		if(!empty($dboxlite_vshortcode)){
			$shortcode_html=do_shortcode($dboxlite_vshortcode);
			//die($vhtml);
			$html.='<div class="dboxlite_video">'.$shortcode_html.'</div>';
		}
		$vdohtml=dboxlite_slide_video($slider_handle.$dboxlite_sldr_j, $dboxlite_ytburl, $dboxlite_mpurl, $dboxlite_weburl, $dboxlite_ogurl, $dboxlite_slider_curr['width'], $dboxlite_slider_curr['height']);

			if(!empty($vdohtml))
				$html.=$vdohtml;		
			
		//added for video -End
		
		$cleardiv='<div class="sldr_clearlt"></div><div class="sldr_clearrt"></div>';
		$more='';
		if($dboxlite_slider_curr['show_content']=='1' && !empty($permalink)){
			$more_name=$dboxlite_slider_curr['more'];
			if($more_name and !empty($more_name) ){
				$more= '<span class="more"><a href="'.$permalink.'" '.$a_attr.'>'.$dboxlite_slider_curr['more'].'</a></span>';
			}
		}
		$dbox_meta='';
		if(!empty($more)) $dbox_meta='<span class="dboxlite-meta">'.$more.'</span>';
		
		if ($dboxlite_slider_curr['image_only'] == '1') { 
			$html .= $cleardiv.'</li>';
		}
		else {
			if($permalink!='') {
				$slide_title = '<h2 '.$dboxlite_slider_css['dboxlite_slider_h2'].'><a href="'.$permalink.'" '.$dboxlite_slider_css['dboxlite_slider_h2_a'].' '.$a_attr.'>'.$post_title.'</a></h2>';
				//filter hook
				$slide_title=apply_filters('dboxlite_slide_title_html',$slide_title,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css,$post_title);
				
				$html .= '<div class="textshow"></div><div class="dboxlite_text" '.$dboxlite_slider_css['dboxlite_text'].'> '.$slide_title.'<div class="dboxlite-content">'.$slider_excerpt.$dbox_meta.'</div><div class="texthide"></div>'.'
					</div>'.$cleardiv.'</li>'; 
			}
			else{
				$slide_title = '<h2 '.$dboxlite_slider_css['dboxlite_slider_h2'].'>'.$post_title.'</h2>';
				//filter hook
				$slide_title=apply_filters('dboxlite_slide_title_html',$slide_title,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css,$post_title);
				$html .='<div class="textshow"></div><div class="dboxlite_text" '.$dboxlite_slider_css['dboxlite_text'].'> '.$slide_title.'<div class="dboxlite-content">'.$slider_excerpt.$dbox_meta.'</div>
					<div class="texthide"></div></div>'.$cleardiv.'</li>';    
			}
		}
	  $i++;
	}
	
	
	//filter hook
	$html=apply_filters('dboxlite_extract_html',$html,$dboxlite_sldr_j,$posts,$dboxlite_slider_curr);
	if($out_echo == '1') {
	   echo $html;
	}
	$r_array = array( $dboxlite_sldr_j, $html);
	$r_array=apply_filters('dboxlite_r_array',$r_array,$posts, $dboxlite_slider_curr,$set);
	return $r_array;	
}
function get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data=array()){
	global $dboxlite_slider,$default_dboxlite_slider_settings;
	//die("Test: ".$r_array[0]);	
	$dboxlite_sldr_j = $r_array[0];
	
	$dboxlite_slider_css = dboxlite_get_inline_css($set);
	$html='';
	
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}

	if ($dboxlite_slider_curr['bg'] == '1') { $dboxlite_slideri_bg = "transparent";} else { $dboxlite_slideri_bg = $dboxlite_slider_curr['bg_color']; }
	wp_enqueue_script( 'dboxlite', dboxlite_slider_plugin_url( 'js/dboxlite.js' ),array('jquery'), DBOXLITE_SLIDER_VER, false); 
	
	//for dboxlite navigation dots span
	$spn='';	
	for($s=0;$s<$dboxlite_sldr_j;$s++)
	{
	if($s==0) $spn.="<span class='dboxlite_nav_dot_current' ></span>";
	else $spn.="<span ></span>";
	}
	//for autoplay	
	if($dboxlite_slider_curr['disable_autoplay']=='1') $autoplay="false";
	else $autoplay="true";
	//Added for replacing hyphen by underscore in slide handle
	$html.='<script type="text/javascript"> 
			jQuery(document).ready(function($) {
				var dbox_slider = dboxlite_slider_init({"handle":"#dbox_slider","options":{"direction" : "'.$dboxlite_slider_curr['direction_rotation'].'","perspective" : "1200","cubes" : "'.$dboxlite_slider_curr['cc'].'","randomize" : false,"disperseFactor" : "0","timeFactor" : "150","speed" : "'.($dboxlite_slider_curr['speed']*100).'","autoplay" : '.$autoplay.',"interval": "'.($dboxlite_slider_curr['interval']*1000).'","fallbackeffect":"slideup"},"navbg":"'.$dboxlite_slider_curr['navarr_bgcolor'].'","navbghover":"'.$dboxlite_slider_curr['navarr_bgcolor_hover'].'","playbtcolor":"'.$dboxlite_slider_curr['playbt_color'].'","playbtcolorhover":"'.$dboxlite_slider_curr['playbt_color_hover'].'","dot_bg":"'.$dboxlite_slider_curr['navdot_color'].'","dot_fill":"'.$dboxlite_slider_curr['currnavdot_color'].'","dotsz":"'.$dboxlite_slider_curr['dotsize'].'","show_text_onhover":"'.$dboxlite_slider_curr['show_content_hover'].'","slider_width":"'.$dboxlite_slider_curr['width'].'","plugin_path":"'.dboxlite_slider_plugin_url().'","hide_content":"0"});
				dbox_slider.init();
			});';

	do_action('dboxlite_global_script',$slider_handle,$dboxlite_slider_curr);

	$html.='</script> <noscript><p><strong>'. $dboxlite_slider['noscript'] .'</strong></p></noscript>';
	$html.='<div id="dbox_slider_wrap" class="dboxlite_slider dboxlite_slider_set '.$slider_handle.'_wrap" '.$dboxlite_slider_css['dboxlite_slider'].' tabindex="100">
		<ul id="dbox_slider" class="dboxlite_slider_handle">
			'.$r_array[1].'
		</ul>
		<div class="dboxlite_shadow"></div>

				<div class="dboxlite_nav_arrows">
					<a href="#" class="dboxlite_next" '.$dboxlite_slider_css['nav_next'].'>Next</a>
					<a href="#" class="dboxlite_prev" '.$dboxlite_slider_css['nav_prev'].'>Previous</a>
				</div>

				<div class="dboxlite_nav_dots" '.$dboxlite_slider_css['nav_dots'].'>'.$spn.'</div>
				<div class="dboxlite_nav_options" '.$dboxlite_slider_css['nav_options'].'>
					<span class="dboxlite_play" '.$dboxlite_slider_css['nav_play'].'>Play</span>
					<span class="dboxlite_pause" '.$dboxlite_slider_css['nav_pause'].'>Pause</span>
				</div>
		</div>';
	$html=apply_filters('dboxlite_slider_html',$html,$r_array,$dboxlite_slider_curr,$set);
	if($echo == '1')  {echo $html;}
	else { return $html; }
}

function dboxlite_carousel_posts_on_slider($max_posts, $offset=0, $slider_id = '1',$out_echo = '1',$set='',$data=array()) {
 global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	$post_table = $table_prefix."posts";
	$rand = $dboxlite_slider_curr['rand'];
	if(isset($rand) and $rand=='1'){
	  $orderby = 'RAND()';
	}
	else {
	  $orderby = 'a.slide_order ASC, a.date DESC';
	}
	
	$posts = $wpdb->get_results("SELECT * FROM 
	                             $table_name a LEFT OUTER JOIN $post_table b 
								 ON a.post_id = b.ID 
								 WHERE (b.post_status = 'publish' OR (b.post_type='attachment' AND b.post_status = 'inherit')) AND a.slider_id = '$slider_id'  
	                             ORDER BY ".$orderby." LIMIT $offset, $max_posts", OBJECT);
	
	$r_array=dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr, $out_echo, $set, $data );
	return $r_array;
}

if(!function_exists('get_dbox_slider')){
	function get_dbox_slider($slider_id='', $set='', $offset=0,$data=array()) {
		global $dboxlite_slider,$default_dboxlite_slider_settings;  
		$set='';
	 	$dboxlite_slider_options='dbox_slider_options'.$set;
	    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
		if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
		foreach($default_dboxlite_slider_settings as $key=>$value){
			if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
		}	
	
		 if($dboxlite_slider['multiple_sliders'] == '1' and is_singular() and (empty($slider_id) or !isset($slider_id))){
			global $post;
			$post_id = $post->ID;
			$slider_id = get_dboxlite_slider_for_the_post($post_id);
		 }
		if(empty($slider_id) or !isset($slider_id))  $slider_id = '1';
		if( !$offset or empty($offset) or !is_numeric($offset)  ) $offset=0;
		if(!empty($slider_id)){
			$data['slider_id']=$slider_id;
			$slider_handle='dboxlite_slider_'.$slider_id;
			$data['slider_handle']=$slider_handle;
			$r_array = dboxlite_carousel_posts_on_slider($dboxlite_slider_curr['no_posts'], $offset, $slider_id, '0', $set, $data); 
			get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data);
		} //end of not empty slider_id condition
	}
}

//For displaying category specific posts in chronologically reverse order
function dboxlite_carousel_posts_on_slider_category($max_posts='5', $catg_slug='', $offset=0, $out_echo = '1', $set='', $data=array() ) {
    global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	
	
	global $wpdb, $table_prefix;
	
	if (!empty($catg_slug)) {
		$category = get_category_by_slug($catg_slug);
		$slider_cat = $category->term_id;
		}
	else {
		$category = get_the_category();
		$slider_cat = $category[0]->cat_ID;
	}
	
	$rand = $dboxlite_slider_curr['rand'];
	if(isset($rand) and $rand=='1') $orderby = '&orderby=rand';
	else $orderby = '';
	
	//extract the posts
	$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset.'&category='.$slider_cat.$orderby);
	
	$r_array=dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr, $out_echo,$set,$data );
	return $r_array;
}
if(!function_exists('get_dbox_slider_category')){
function get_dbox_slider_category($catg_slug='', $set='', $offset=0, $data=array()) {
    global $dboxlite_slider,$default_dboxlite_slider_settings;  
	$set='';
 	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	

	if( !$offset or empty($offset) or !is_numeric($offset)  ) $offset=0;
   	$slider_handle='dboxlite_slider_'.$catg_slug;
    $data['slider_handle']=$slider_handle;
	$r_array = dboxlite_carousel_posts_on_slider_category($dboxlite_slider_curr['no_posts'], $catg_slug, $offset, '0', $set, $data); 
	get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data);
	} 
}

//For displaying recent posts in chronologically reverse order
function dboxlite_carousel_posts_on_slider_recent($max_posts='5', $offset=0, $out_echo = '1', $set='', $data=array()) {
    global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	
	
	$rand = $dboxlite_slider_curr['rand'];
	if(isset($rand) and $rand=='1')	  $orderby = '&orderby=rand';
	else  $orderby = '';
	//extract posts data
	$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset.$orderby);
	$r_array=dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr, $out_echo,$set,$data );
	return $r_array;
}
if(!function_exists('get_dbox_slider_recent')){
function get_dbox_slider_recent($set='', $offset=0, $data=array()) {
	global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$set='';
 	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	

	if( !$offset or empty($offset) or !is_numeric($offset)  ) $offset=0;
	$slider_handle='dboxlite_slider_recent';
	$data['slider_handle']=$slider_handle;
	$r_array = dboxlite_carousel_posts_on_slider_recent($dboxlite_slider_curr['no_posts'], $offset, '0', $set, $data);
	get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data);
	}
}
require_once (dirname (__FILE__) . '/shortcodes_1.php');
require_once (dirname (__FILE__) . '/widgets_1.php');

function dboxlite_slider_enqueue_scripts() {
	wp_enqueue_script( 'modernizr', dboxlite_slider_plugin_url( 'js/modernizr.js' ),'', DBOXLITE_SLIDER_VER, false);
	wp_enqueue_script( 'jquery.bpopup.min', dboxlite_slider_plugin_url( 'js/jquery.bpopup.min.js' ),'', DBOXLITE_SLIDER_VER, false);
	wp_enqueue_script( 'jquery.touchwipe', dboxlite_slider_plugin_url( 'js/jquery.touchwipe.js' ),array('jquery'), DBOXLITE_SLIDER_VER, false);
}

add_action( 'wp_enqueue_scripts', 'dboxlite_slider_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'dboxlite_slider_enqueue_scripts' );

function dboxlite_slider_enqueue_styles() {	
  global $post, $dboxlite_slider, $wp_registered_widgets,$wp_widget_factory;
  if(is_singular()) {
	 $dbox_slider_style = get_post_meta($post->ID,'_dbox_slider_style',true);
	 if((is_active_widget(false, false, 'dboxlite_sslider_wid', true) or isset($dboxlite_slider['shortcode']) ) and (!isset($dbox_slider_style) or empty($dbox_slider_style))){
	   $dbox_slider_style='default';
	 }	
	 if (!isset($dbox_slider_style) or empty($dbox_slider_style) ) {
	     wp_enqueue_style( 'dboxlite_slider_headcss', dboxlite_slider_plugin_url( 'css/skins/'.$dboxlite_slider['stylesheet'].'/style.css' ),
		false, DBOXLITE_SLIDER_VER, 'all');
	 }
     else {
	     wp_enqueue_style( 'dboxlite_slider_headcss', dboxlite_slider_plugin_url( 'css/skins/'.$dbox_slider_style.'/style.css' ),
		false, DBOXLITE_SLIDER_VER, 'all');
	}
  }
  else {
     $dbox_slider_style = $dboxlite_slider['stylesheet'];
	wp_enqueue_style( 'dboxlite_slider_headcss', dboxlite_slider_plugin_url( 'css/skins/'.$dbox_slider_style.'/style.css' ),
		false, DBOXLITE_SLIDER_VER, 'all');
  }
}
add_action( 'wp', 'dboxlite_slider_enqueue_styles' );

//admin settings
function dboxlite_slider_admin_scripts() {
global $dboxlite_slider;
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( isset($_GET['page']) && ('dboxlite-slider-admin' == $_GET['page'] or 'dboxlite-slider-settings' == $_GET['page'] )  ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'dboxlite_slider_admin_js', dboxlite_slider_plugin_url( 'js/admin.js' ),
			array('jquery'), DBOXLITE_SLIDER_VER, false);
		wp_enqueue_style( 'dboxlite_slider_admin_css', dboxlite_slider_plugin_url( 'css/admin.css' ),
			false, DBOXLITE_SLIDER_VER, 'all');
		wp_enqueue_script( 'jquery.touchwipe', dboxlite_slider_plugin_url( 'js/jquery.touchwipe.js' ),array('jquery'), DBOXLITE_SLIDER_VER, false); 
		wp_enqueue_style( 'dboxlite_slider_admin_head_css', dboxlite_slider_plugin_url( 'css/skins/'.$dboxlite_slider['stylesheet'].'/style.css' ),false, DBOXLITE_SLIDER_VER, 'all');
	}
  }
}

add_action( 'admin_init', 'dboxlite_slider_admin_scripts' );

function dboxlite_slider_admin_head() {
global $dboxlite_slider;
if ( is_admin() ){ // admin actions
// Sliders & Settings page only
    if ( isset($_GET['page']) && ('dboxlite-slider-admin' == $_GET['page'] or 'dboxlite-slider-settings' == $_GET['page']) ) {
	  $sliders = dboxlite_ss_get_sliders(); 
		global $dboxlite_slider;
		$dboxlite_slider_options='dbox_slider_options';
		$dboxlite_slider_curr=get_option($dboxlite_slider_options);
		$active_tab=(isset($dboxlite_slider_curr['active_tab']))?$dboxlite_slider_curr['active_tab']:0;
		if ( isset($_GET['page']) && ('dboxlite-slider-admin' == $_GET['page']) ){ if(isset($_POST['active_tab']) ) $active_tab=$_POST['active_tab'];else $active_tab = 0;}
		if(empty($active_tab)){$active_tab=0;}
	?>
		<script type="text/javascript">
            // <![CDATA[
        jQuery(document).ready(function() {
                jQuery(function() {
					jQuery("#slider_tabs").tabs({fx: { opacity: "toggle", duration: 300}, active: <?php echo $active_tab;?> }).addClass( "ui-tabs-vertical-left ui-helper-clearfix" );jQuery( "#slider_tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
				<?php 	if ( isset($_GET['page']) && (( 'dboxlite-slider-settings' == $_GET['page']) or ('dboxlite-slider-admin' == $_GET['page']) ) ) { ?>
					jQuery( "#slider_tabs" ).on( "tabsactivate", function( event, ui ) { jQuery( "#dboxlite_activetab, .dboxlite_activetab" ).val( jQuery( "#slider_tabs" ).tabs( "option", "active" ) ); });
				<?php 	}
				foreach($sliders as $slider){ ?>
                    jQuery("#sslider_sortable").sortable();
                    jQuery("#sslider_sortable").disableSelection();
			    <?php } ?>
                });
        });
		
        function confirmRemove(){
            var agree=confirm("This will remove selected Posts/Pages from Slider.");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmRemoveAll(){
            var agree=confirm("Remove all Posts/Pages from DboxLite Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmSliderDelete(){
            var agree=confirm("Delete this Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function slider_checkform ( form ){
          if (form.new_slider_name.value == "") {
            alert( "Please enter the New Slider name." );
            form.new_slider_name.focus();
            return false ;
          }
          return true ;
        }
        </script>
<?php
   } //Sliders page only
   
   // Settings page only
  if ( isset($_GET['page']) && 'dboxlite-slider-settings' == $_GET['page']  ) {
		wp_print_scripts( 'farbtastic' );
		wp_print_styles( 'farbtastic' );
?>
<script type="text/javascript">
	// <![CDATA[
jQuery(document).ready(function() {
		jQuery('#colorbox_1').farbtastic('#color_value_1');
		jQuery('#color_picker_1').click(function () {
           if (jQuery('#colorbox_1').css('display') == "block") {
		      jQuery('#colorbox_1').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_1').fadeIn("slow"); }
        });
		var colorpick_1 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_1 == true) {
    			return; }
				jQuery('#colorbox_1').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_1 = false;
		});
//for second color box
		jQuery('#colorbox_2').farbtastic('#color_value_2');
		jQuery('#color_picker_2').click(function () {
           if (jQuery('#colorbox_2').css('display') == "block") {
		      jQuery('#colorbox_2').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_2').fadeIn("slow"); }
        });
		var colorpick_2 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_2 == true) {
    			return; }
				jQuery('#colorbox_2').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_2 = false;
		});
//for third color box
		jQuery('#colorbox_3').farbtastic('#color_value_3');
		jQuery('#color_picker_3').click(function () {
           if (jQuery('#colorbox_3').css('display') == "block") {
		      jQuery('#colorbox_3').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_3').fadeIn("slow"); }
        });
		var colorpick_3 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_3 == true) {
    			return; }
				jQuery('#colorbox_3').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_3 = false;
		});
//for fourth color box
		jQuery('#colorbox_4').farbtastic('#color_value_4');
		jQuery('#color_picker_4').click(function () {
           if (jQuery('#colorbox_4').css('display') == "block") {
		      jQuery('#colorbox_4').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_4').fadeIn("slow"); }
        });
		var colorpick_4 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_4 == true) {
    			return; }
				jQuery('#colorbox_4').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_4 = false;
		});
//for fifth color box
		jQuery('#colorbox_5').farbtastic('#color_value_5');
		jQuery('#color_picker_5').click(function () {
           if (jQuery('#colorbox_5').css('display') == "block") {
		      jQuery('#colorbox_5').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_5').fadeIn("slow"); }
        });
		var colorpick_5 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_5 == true) {
    			return; }
				jQuery('#colorbox_5').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_5 = false;
		});
//for sixth color box
//for seventh color box
		jQuery('#colorbox_7').farbtastic('#color_value_7');
		jQuery('#color_picker_7').click(function () {
           if (jQuery('#colorbox_7').css('display') == "block") {
		      jQuery('#colorbox_7').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_7').fadeIn("slow"); }
        });
		var colorpick_7 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_7 == true) {
    			return; }
				jQuery('#colorbox_7').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_7 = false;
		});
//for eighth color box
		/*jQuery('#colorbox_8').farbtastic('#color_value_8');
		jQuery('#color_picker_8').click(function () {
           if (jQuery('#colorbox_8').css('display') == "block") {
		      jQuery('#colorbox_8').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_8').fadeIn("slow"); }
        });
		var colorpick_8 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_8 == true) {
    			return; }
				jQuery('#colorbox_8').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_8 = false;
		});*/

//for tenth color box
		jQuery('#colorbox_10').farbtastic('#color_value_10');
		jQuery('#color_picker_10').click(function () {
           if (jQuery('#colorbox_10').css('display') == "block") {
		      jQuery('#colorbox_10').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_10').fadeIn("slow"); }
        });
		var colorpick_10 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_10 == true) {
    			return; }
				jQuery('#colorbox_10').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_10 = false;
		});
//for eleventh color box
		jQuery('#colorbox_11').farbtastic('#color_value_11');
		jQuery('#color_picker_11').click(function () {
           if (jQuery('#colorbox_11').css('display') == "block") {
		      jQuery('#colorbox_11').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_11').fadeIn("slow"); }
        });
		var colorpick_11 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_11 == true) {
    			return; }
				jQuery('#colorbox_11').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_11 = false;
		});
//for twealth color box
jQuery('#colorbox_12').farbtastic('#color_value_12');
		jQuery('#color_picker_12').click(function () {
           if (jQuery('#colorbox_12').css('display') == "block") {
		      jQuery('#colorbox_12').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_12').fadeIn("slow"); }
        });
		var colorpick_12 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_12 == true) {
    			return; }
				jQuery('#colorbox_12').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_12 = false;
		});
});
function confirmSettingsCreate()
        {
            var agree=confirm("Create New Settings Set??");
            if (agree)
            return true ;
            else
            return false ;
}
function confirmSettingsDelete()
        {
            var agree=confirm("Delete this Settings Set??");
            if (agree)
            return true ;
            else
            return false ;
}
</script>
<style type="text/css">.color-picker-wrap {position: absolute;	display: none; background: #fff;border: 3px solid #ccc;	padding: 3px;z-index: 1000;}</style>
<?php
   } //for dboxlite slider option page
 }//only for admin
//Below css will add the menu icon for DboxLite Slider admin menu
?>
<style type="text/css">#adminmenu #toplevel_page_dboxlite-slider-admin div.wp-menu-image:before { content: "\f233"; }</style>
<?php
}
add_action('admin_head', 'dboxlite_slider_admin_head');

function dboxlite_get_inline_css($set='',$echo='0'){
    global $dboxlite_slider,$default_dboxlite_slider_settings;
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	
	global $post;
	if(is_singular()) {	$dbox_slider_style = get_post_meta($post->ID,'_dbox_slider_style',true);}
	if((is_singular() and ($dbox_slider_style == 'default' or empty($dbox_slider_style) or !$dbox_slider_style)) or (!is_singular() and $dboxlite_slider['stylesheet'] == 'default')  )	{ $default=true;	}
	else{ $default=false;}
	
	$dboxlite_slider_css=array();
	if($default){
		$style_start= ($echo=='0') ? 'style="':'';
		$style_end= ($echo=='0') ? '"':'';
	//dboxlite_slider
		$dboxlite_slider_css['dboxlite_slider']=$style_start.'max-width:'.$dboxlite_slider_curr['width'].'px;max-height:'.$dboxlite_slider_curr['height'].'px;height:100%;'.$style_end;
		
	//dboxlite_slideri
		//$dboxlite_slider_css['dboxlite_slideri']=$style_start.'height:'.$dboxlite_slider_curr['height'].'px;'.$style_end;

	//dboxlite_slider_h2	
		$ptitle_fontg=isset($dboxlite_slider_curr['ptitle_fontg'])?trim($dboxlite_slider_curr['ptitle_fontg']):'';
		if(!empty($ptitle_fontg)) 	{
			wp_enqueue_style( 'dboxlite_ptitle', 'http://fonts.googleapis.com/css?family='.$ptitle_fontg,array(),DBOXLITE_SLIDER_VER);
			$ptitle_fontg=dboxlite_get_google_font($ptitle_fontg);
			$ptitle_fontg='\''.$ptitle_fontg.'\''.',';
		}	
		if ($dboxlite_slider_curr['ptitle_fstyle'] == "bold" or $dboxlite_slider_curr['ptitle_fstyle'] == "bold italic" ){$ptitle_fweight = "bold";} else {$ptitle_fweight = "normal";}
		if ($dboxlite_slider_curr['ptitle_fstyle'] == "italic" or $dboxlite_slider_curr['ptitle_fstyle'] == "bold italic"){$ptitle_fstyle = "italic";} else {$ptitle_fstyle = "normal";}
		$dboxlite_slider_css['dboxlite_slider_h2']=$style_start.'clear:none;line-height:'. ($dboxlite_slider_curr['ptitle_fsize'] + 5) .'px;font-family:'. $ptitle_fontg . ' ' . $dboxlite_slider_curr['ptitle_font'].', Arial, Helvetica, sans-serif;font-size:'.$dboxlite_slider_curr['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';color:'.$dboxlite_slider_curr['ptitle_fcolor'].';'.$style_end;
		
	//dboxlite_slider_h2 a
		$dboxlite_slider_css['dboxlite_slider_h2_a']=$style_start.'font-family:'. $ptitle_fontg . ' ' . $dboxlite_slider_curr['ptitle_font'].';font-size:'.$dboxlite_slider_curr['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';color:'.$dboxlite_slider_curr['ptitle_fcolor'].';'.$style_end;

	//dboxlite_slider_span		
		$content_fontg=isset($dboxlite_slider_curr['content_fontg'])?trim($dboxlite_slider_curr['content_fontg']):'';
		if(!empty($content_fontg)) 	{
			wp_enqueue_style( 'dboxlite_content', 'http://fonts.googleapis.com/css?family='.$content_fontg,array(),DBOXLITE_SLIDER_VER);
			$content_fontg=dboxlite_get_google_font($content_fontg);
			$content_fontg='\''.$content_fontg.'\''.',';
		}	
		if ($dboxlite_slider_curr['content_fstyle'] == "bold" or $dboxlite_slider_curr['content_fstyle'] == "bold italic" ){$content_fweight= "bold";} else {$content_fweight= "normal";}
		if ($dboxlite_slider_curr['content_fstyle']=="italic" or $dboxlite_slider_curr['content_fstyle'] == "bold italic"){$content_fstyle= "italic";} else {$content_fstyle= "normal";}
		$dboxlite_slider_css['dboxlite_slider_span']=$style_start.'font-family:'. $content_fontg . ' '.$dboxlite_slider_curr['content_font'].', Arial, Helvetica, sans-serif;font-size:'.$dboxlite_slider_curr['content_fsize'].'px;font-weight:'.$content_fweight.';font-style:'.$content_fstyle.';color:'. $dboxlite_slider_curr['content_fcolor'].';'.$style_end;
		
	//dboxlite_slider_thumbnail
		$dboxlite_slider_css['dboxlite_slider_thumbnail']=$style_start.'width:'.$dboxlite_slider_curr['width'].'px;position: relative;max-height:'.($dboxlite_slider_curr['height']-60).'px;'.$style_end;
	
	
	//dboxlite_text
	if ($dboxlite_slider_curr['bg'] == '1') { $dboxlite_text_bg = "transparent";} else { $dboxlite_text_bg = $dboxlite_slider_curr['bg_color']; }
		$dboxlite_slider_css['dboxlite_text']=$style_start.'background-color:'.$dboxlite_text_bg.';opacity:'.$dboxlite_slider_curr['bg_opacity'].';-ms-filter: \'progid:DXImageTransform.Microsoft.Alpha(Opacity='.($dboxlite_slider_curr['bg_opacity']*100).')\';filter: alpha(opacity='.($dboxlite_slider_curr['bg_opacity']*100).');background-position: initial initial; background-repeat: initial initial;'.$style_end;
	
	//nav_next
	$dboxlite_slider_css['nav_next']=$style_start.'background: '.$dboxlite_slider_curr['navarr_bgcolor'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/next.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['pn_width'].'px;height:'.$dboxlite_slider_curr['pn_width'].'px;background-size:'.($dboxlite_slider_curr['pn_width']-10).'px;'.$style_end;

	//nav_prev
	$dboxlite_slider_css['nav_prev']=$style_start.'background: '.$dboxlite_slider_curr['navarr_bgcolor'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/prev.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['pn_width'].'px;height:'.$dboxlite_slider_curr['pn_width'].'px;background-size:'.($dboxlite_slider_curr['pn_width']-10).'px;'.$style_end;
	
	//nav_pause
	$dboxlite_slider_css['nav_pause']=$style_start.'background: '.$dboxlite_slider_curr['playbt_color'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/pause.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['playbt_size'].'px;height:'.$dboxlite_slider_curr['playbt_size'].'px;background-size:'.($dboxlite_slider_curr['playbt_size']-14).'px;'.$style_end;
	
	//nav_play
	$dboxlite_slider_css['nav_play']=$style_start.'background: '.$dboxlite_slider_curr['playbt_color'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/play.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['playbt_size'].'px;height:'.$dboxlite_slider_curr['playbt_size'].'px;background-size:'.($dboxlite_slider_curr['playbt_size']-14).'px;'.$style_end;

	//nav_options
	$dboxlite_slider_css['nav_options']=$style_start.'width:'.($dboxlite_slider_curr['playbt_size']+2).'px;height:'.($dboxlite_slider_curr['playbt_size']+2).'px;'.$style_end;
	
	//nav_dots	
	$dboxlite_slider_css['nav_dots']=$style_start.'height:'.($dboxlite_slider_curr['dotsize']+14).'px;'.$style_end;
}
	return $dboxlite_slider_css;
}
function dboxlite_slider_css() {
	global $dboxlite_slider;
	$css=$dboxlite_slider['css'];
	if($css and !empty($css)){
	?>
		<style type="text/css"><?php echo $css;?></style>
	<?php
	}
}
add_action('wp_head', 'dboxlite_slider_css');
add_action('admin_head', 'dboxlite_slider_css');
?>
