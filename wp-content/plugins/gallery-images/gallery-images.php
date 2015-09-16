<?php

/*
Plugin Name: Huge IT Image Gallery
Plugin URI: http://huge-it.com/wordpress-gallery/
Description: Huge-IT Gallery images is perfect for using for creating various portfolios within various views. 
Version: 10.0
Author: Huge-IT
Author: http://huge-it.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/




add_action('media_buttons_context', 'add_gallery_my_custom_button');


add_action('admin_footer', 'add_gallery_inline_popup_content');
add_action( 'wp_ajax_huge_it_video_gallery_ajax', 'huge_it_image_gallery_ajax_callback' );
add_action( 'wp_ajax_nopriv_huge_it_video_gallery_ajax', 'huge_it_image_gallery_ajax_callback' );




function huge_it_image_gallery_ajax_callback(){
	if(!function_exists('get_video_gallery_id_from_url')) {
		function get_video_gallery_id_from_url($url){
		if(strpos($url,'youtube') !== false || strpos($url,'youtu') !== false){ 
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
				return array ($match[1],'youtube');
			}
		}else {
			$vimeoid =  explode( "/", $url );
			$vimeoid =  end($vimeoid);
			return array($vimeoid,'vimeo');
		}
	}
	}
	if(!function_exists('youtube_or_vimeo')) {
			function youtube_or_vimeo($videourl){
		if(strpos($videourl,'youtube') !== false || strpos($videourl,'youtu') !== false){   
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videourl, $match)) {
				return 'youtube';
			}
		}
		elseif(strpos($videourl,'vimeo') !== false && strpos($videourl,'video') !== false) {
			$explode = explode("/",$videourl);
			$end = end($explode);
			if(strlen($end) == 8)
				return 'vimeo';
		}
		return 'image';
	}
	}
if(!function_exists('get_huge_image')) {
        function get_huge_image($image_url,$img_prefix) {
            //if(huge_it_copy_image_to_small($image_url,$image_prefix,$cropwidth)) {
                $pathinfo = pathinfo($image_url);
                $upload_dir = wp_upload_dir();
                $url_img_copy = $upload_dir["url"].'/'.$pathinfo["filename"].$img_prefix.'.'.$pathinfo["extension"];
                $img_abs_path = $url_img_copy;
                $img_abs_path= parse_url($url_img_copy, PHP_URL_PATH);
                $img_abs_path =  $_SERVER['DOCUMENT_ROOT'].$img_abs_path;
                if(file_exists($img_abs_path))
                return $url_img_copy; else
            //}
             return $image_url;
        }
    }
////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['task']) && $_POST['task']=="load_images_content"){
        global $wpdb;
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = $_POST["page"];
        $num=$_POST['perpage'];
        $start = $page * $num - $num; 
        $idofgallery=$_POST['galleryid'];
         $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itgallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d",$idofgallery,$start,$num);
       $page_images=$wpdb->get_results($query);
        $output = '';
        foreach($page_images as $key=>$row)
    {
        $link = $row->sl_url;
        $video_name=$row->name;
        $id=$row->id;
        $descnohtml=strip_tags($row->description);
        $result = substr($descnohtml, 0, 50);
        ?>
        
            
                <?php 
                    $imagerowstype=$row->sl_type;
                    if($row->sl_type == ''){$imagerowstype='image';}
                    switch($imagerowstype){
                        case 'image':
                ?>                                  
                            <?php $imgurl=explode(";",$row->image_url); ?>
                           <?php    if($row->image_url != ';'){ 
                            $video='<img id="wd-cl-img'.$key.'" src="'.$imgurl[0].'" alt="" />';
                             } else {
                            $video='<img id="wd-cl-img'.$key.'" src="images/noimage.jpg" alt="" />';
                            
                            } ?>

                <?php
                        break;
                        case 'video':
                ?>
                        <?php
                            $videourl=get_video_gallery_id_from_url($row->image_url);
                            if($videourl[1]=='youtube'){
                                    if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                                
                                $video='<img src="'.$thumb_pic.'" alt="" />';                             
                            
                                }else {
                                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                            
                                $video='<img src="'.$imgsrc.'" alt="" />';
                            
                            }
                        ?>
                <?php
                        break;
                    }
                ?>
           
                
                <?php if($_POST['showbutton']=='on'){
                    if ($row->link_target=="on"){
                        $target='target="_blank"';
                    }else{
                        $target='';
                    }
                        
                    
                    $button='<div class="button-block"><a href="'.$row->sl_url.'"'.$target.' >'.$_POST['linkbutton'].'</a></div>';
                }else{
                   $button=''; 
                } ?>
            
          
       
      
    
    <?php
            

            $output.='<div class="element_'.$idofgallery.' " tabindex="0" data-symbol="'.$video_name.'"  data-category="alkaline-earth">';
            $output.='<input type="hidden" class="pagenum" value="'.$page.'" />';
            $output.='<div class="image-block_'.$idofgallery.'">';
            $output.=$video;
            $output.='<div class="gallery-image-overlay"><a href="#'.$id.'"></a></div>';
            //$output.='<div style="clear:both;"></div>';
            $output.='</div>';
            $output.='<div class="title-block_'.$idofgallery.'">';
            $output.='<h3>'.$video_name.'</h3>';
            $output.=$button;
            $output.='</div>';
            $output.='</div>';
                
            
        
     }
        echo json_encode(array("success"=>$output));
        die();
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['task']) && $_POST['task']=="load_images_lightbox"){
        global $wpdb;
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = $_POST["page"];
        $num=$_POST['perpage'];
        $start = $page * $num - $num; 
        $idofgallery=$_POST['galleryid'];
         $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itgallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d",$idofgallery,$start,$num);
       $page_images=$wpdb->get_results($query);
        $output = '';
        foreach($page_images as $key=>$row)
    {
        $link = $row->sl_url;
        $video_name=$row->name;
        $descnohtml=strip_tags($row->description);
        $result = substr($descnohtml, 0, 50);
        ?>
        
            
                <?php 
                    $imagerowstype=$row->sl_type;
                    if($row->sl_type == ''){$imagerowstype='image';}
                    switch($imagerowstype){
                        case 'image':
                ?>                                  
                            <?php $imgurl=explode(";",$row->image_url); ?>
                            <?php  
                             if($row->image_url != ';'){ 
                            $video='<a href="'.$imgurl[0].'" title="'.$video_name.'"><img id="wd-cl-img'.$key.'" src="'.$imgurl[0].'" alt="'.$video_name.'" /></a>';
                            } 
                            else { 
                            $video='<img id="wd-cl-img'.$key.'" src="images/noimage.jpg" alt="" />';
                           
                            } ?>

                <?php
                        break;
                        case 'video':
                ?>
                        <?php
                            $videourl=get_video_gallery_id_from_url($row->image_url);
                            if($videourl[1]=='youtube'){
                                    if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                                
                                $video='<a class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/'.$videourl[0].'" title="'.$video_name.'">
                                            <img src="'.$thumb_pic.'" alt="'.$video_name.'" />
                                            <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';                             
                            
                                }else {
                                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                            
                                $video='<a class="vimeo huge_it_videogallery_item group1" href="http://player.vimeo.com/video/'.$videourl[0].'" title="'.$video_name.'">
                                    <img src="'.$imgsrc.'" alt="" />
                                    <div class="play-icon '.$videourl[1].'-icon"></div>
                                </a>';
                            
                            }
                        ?>
                <?php
                        break;
                    }
                ?>
            
          
         <?php if($row->name!=""){
                if ($row->link_target=="on"){
                   $target= 'target="_blank"';
                }else{
                    $target= '';
                }
               $linkimg='<a href="'.$link.'"'.$target.'>'.$video_name.'</a>';
            
            } ?>
      
    
    <?php
            
            
            $output.='<div class="element_'.$idofgallery.'" tabindex="0" data-symbol="'.$video_name.'"  data-category="alkaline-earth">';
            $output.='<input type="hidden" class="pagenum" value="'.$page.'" />';
            $output.='<div class="image-block_'.$idofgallery.'">';
            $output.=$video;
            $output.='<div class="title-block_'.$idofgallery.'">';
            $output.=$linkimg;
            $output.='</div>';
            //$output.='<div style="clear:both;"></div>';
            $output.='</div>';
            $output.='</div>';
           
    
            
        
     }
        echo json_encode(array("success"=>$output));
        die();
    }
}

////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['task']) && $_POST['task']=="load_image_justified"){
        global $wpdb;
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = $_POST["page"];
        $num=$_POST['perpage'];
        $start = $page * $num - $num; 
        $idofgallery=$_POST['galleryid'];
         $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itgallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d",$idofgallery,$start,$num);
       
        $output = '';
        $page_images=$wpdb->get_results($query);
        foreach($page_images as $key=>$row){
            //var_dump($icon);
            $video_name=$row->name;
           
         $videourl=get_video_gallery_id_from_url($row->image_url);
         $imgurl=explode(";",$row->image_url);
         $image_prefix = "_huge_it_small_gallery";

         $imagerowstype=$row->sl_type; 
                    if($row->sl_type == ''){$imagerowstype='image';}
                    
                    switch($imagerowstype){
                        case 'image': 
                                 if($row->image_url != ';'){ 
                                    $imgperfix=get_huge_image($imgurl[0],$image_prefix);
                                       $video= '<a class="group1" href="'.$imgurl[0].'" title="'.$video_name.'">
                                            <img  id="wd-cl-img'.$key.'" alt="'.$video_name.'" src="'.$imgperfix.'"/>
                                        </a>
                                        <input type="hidden" class="pagenum" value="'.$page.'" />';?>
                                <?php } else { 
                                       $video= '<img alt="'.$video_name.'" id="wd-cl-img'.$key.'" src="images/noimage.jpg"  />
                                        <input type="hidden" class="pagenum" value="'.$page.'" />';
                                
                                } ?>
                        
                                                      
                         
                    <?php 
                        break;
                        case 'video':

            if($videourl[1]=='youtube'){
                if(empty($row->thumb_url)){
                                            $thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
                                        }else{
                                            $thumb_pic=$row->thumb_url;
                                        }
                $video = '<a class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/'.$videourl[0].'" title="'.$video_name.'">
                                                <img  src="'.$thumb_pic.'" alt="'.$video_name.'" />
                                                <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';
            }else {

                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                    
                                    if(empty($row->thumb_url)){
                                        $imgsrc=$hash[0]['thumbnail_large'];
                                    }else{
                                        $imgsrc=$row->thumb_url;
                                    }
                $video = '<a class="vimeo huge_it_videogallery_item group1" href="http://player.vimeo.com/video/'.$videourl[0].'" title="'.$video_name.'">
                                                <img alt="'.$video_name.'" src="'.$imgsrc.'"/>
                                                <div class="play-icon '.$videourl[1].'-icon"></div>
                                        </a>';
            }
                break;
            }
            
            


            $output .=$video.'<input type="hidden" class="pagenum" value="'.$page.'" />';
                
            
        
        }
        echo json_encode(array("success"=>$output));
        die();
    }
}
////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['task']) && $_POST['task']=="load_image_thumbnail"){
        global $wpdb;
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = $_POST["page"];
        $num=$_POST['perpage'];
        $start = $page * $num - $num; 
        $idofgallery=$_POST['galleryid'];
         $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itgallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d",$idofgallery,$start,$num);
       
        $output = '';
        $page_images=$wpdb->get_results($query);
        foreach($page_images as $key=>$row){
            //var_dump($icon);
            $video_name=$row->name;
            $imgurl=explode(";",$row->image_url); 
            $image_prefix = "_huge_it_small_gallery";
            $videourl=get_video_gallery_id_from_url($row->image_url);


         $imagerowstype=$row->sl_type; 
                    if($row->sl_type == ''){$imagerowstype='image';}
                    
                    switch($imagerowstype){
                        case 'image': 
                        $imgperfix=get_huge_image($imgurl[0],$image_prefix);
                                                      
                         $video='<a class="group1" href="'.$row->image_url.'" title="'.$video_name.'"></a>
                            <img  src="'.$imgperfix.'" alt="'.$video_name.'" />';
                     
                        break;
                        case 'video':
                    
                            
                                
                                if($videourl[1]=='youtube'){?>
                                    <a class="youtube huge_it_gallery_item group1"  href="https://www.youtube.com/embed/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"></a>
                                    <img alt="<?php echo $row->name; ?>" src="http://img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"  />              
                                <?php
                                }else {
                                    $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                    $imgsrc=$hash[0]['thumbnail_large'];
                                ?>
                                    <a class="vimeo huge_it_gallery_item group1" href="http://player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo $row->name; ?>"></a>
                                    <img alt="<?php echo $row->name; ?>" src="<?php echo $imgsrc; ?>"  />
                                <?php
                                }
                            ?>
                    <?php
                        break;
                    }
                    ?>
            
<?php

            $output .='
                <li class="huge_it_big_li">
                    <input type="hidden" class="pagenum" value="'.$page.'" />
                        '.$video.'

                    <div class="overLayer"></div>
                    <div class="infoLayer">
                        <ul>
                            <li>
                                <h2>
                                    '.$video_name.'
                                </h2>
                            </li>
                            <li>
                                <p>
                                    '.$_POST['thumbtext'].'
                                </p>
                            </li>
                        </ul>
                    </div>
                    
                </li>
            ';
        
        }
        echo json_encode(array("success"=>$output));
        die();
    }
}
///////////////////////////////////////////////////////////////////////////////////////////
    if(isset($_POST['task']) && $_POST['task']=="load_blog_view"){
        global $wpdb;
    $page = 1;
    if(!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page']>0){
        $page = $_POST["page"];
        $num=$_POST['perpage'];
        $start = $page * $num - $num; 
        $idofgallery=$_POST['galleryid'];
         $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itgallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d",$idofgallery,$start,$num);
       
        $output = '';
        $page_images=$wpdb->get_results($query);
       foreach($page_images as $key=>$row)
    {
        
        $img_src=$row->image_url;
        $img_name=$row->name;
        $img_desc=$row->description;
    
            if($_POST['position']==1){


            $output .='
               <div class="view9_container">
                        <input type="hidden" class="pagenum" value="'.$page.'" />
                        <img class="view9_img" src="'.$img_src.'">
                        <h1 class="new_view_title">'.$img_name.'</h1>
                        <div class="new_view_desc">'.$img_desc.'</div>
                </div>

        <div class="clear"></div>
            ';
        }elseif($_POST['position']==2){


            $output .='
                <div class="view9_container">
            <input type="hidden" class="pagenum" value="'.$page.'" />
            <h1 class="new_view_title">'.$img_name.'</h1>
            <img class="view9_img" src="'.$img_src.'">
            <div class="new_view_desc">'.$img_desc.'</div>
        </div>
        <div class="clear"></div>
            ';
            }elseif($_POST['position']==3){


            $output .='
                <div class="view9_container">
                    <input type="hidden" class="pagenum" value="'.$page.'" />
                    <h1 class="new_view_title">'.$img_name.'</h1>
                    <div class="new_view_desc">'.$img_desc.'</div>
                    <img class="view9_img" src="'.$img_src.'">
                </div>
                <div class="clear"></div>
            ';
            }
            }
        }
        echo json_encode(array("success"=>$output));
        die();
    }
}



function add_gallery_my_custom_button($context) {
  

  $img = plugins_url( '/images/post.button.png' , __FILE__ );
  

  $container_id = 'huge_it_gallery';
  

  $title = 'Select Huge IT gallery to insert into post';

  $context .= '<a class="button thickbox" title="Select gallery to insert into post"    href="#TB_inline?width=400&inlineId='.$container_id.'">
		<span class="wp-media-buttons-icon" style="background: url('.$img.'); background-repeat: no-repeat; background-position: left bottom;"></span>
	Add gallery
	</a>';
  
  return $context;
}

function add_gallery_inline_popup_content() {
?>
<script type="text/javascript">
				jQuery(document).ready(function() {
				  jQuery('#hugeitgalleryinsert').on('click', function() {
				  	var id = jQuery('#huge_it_gallery-select option:selected').val();
			
				  	window.send_to_editor('[huge_it_gallery id="' + id + '"]');
					tb_remove();
				  })
				});
</script>

<div id="huge_it_gallery" style="display:none;">
  <h3>Select Huge IT Gallery to insert into post</h3>
  <?php 
  	  global $wpdb;
	  $query="SELECT * FROM ".$wpdb->prefix."huge_itgallery_gallerys order by id ASC";
			   $shortcodegallerys=$wpdb->get_results($query);
			   ?>

 <?php 	if (count($shortcodegallerys)) {
							echo "<select id='huge_it_gallery-select'>";
							foreach ($shortcodegallerys as $shortcodegallery) {
								echo "<option value='".$shortcodegallery->id."'>".$shortcodegallery->name."</option>";
							}
							echo "</select>";
							echo "<button class='button primary' id='hugeitgalleryinsert'>Insert gallery</button>";
						} else {
							echo "No slideshows found", "huge_it_gallery";
						}
						?>
	
</div>
<?php
}
///////////////////////////////////shortcode update/////////////////////////////////////////////


add_action('init', 'hugesl_gallery_do_output_buffer');
function hugesl_gallery_do_output_buffer() {
        ob_start();
}
add_action('init', 'gallery_lang_load');

function gallery_lang_load()
{
    load_plugin_textdomain('sp_gallery', false, basename(dirname(__FILE__)) . '/Languages');

}


function huge_it_gallery_images_list_shotrcode($atts)
{
    extract(shortcode_atts(array(
        'id' => 'no huge_it gallery',
    
    ), $atts));




    return huge_it_gallery_images_list($atts['id']);

}


/////////////// Filter gallery


function gallery_after_search_results($query)
{
    global $wpdb;
    if (isset($_REQUEST['s']) && $_REQUEST['s']) {
        $serch_word = htmlspecialchars(($_REQUEST['s']));
        $query = str_replace($wpdb->prefix . "posts.post_content", gen_string_gallery_search($serch_word, $wpdb->prefix . 'posts.post_content') . " " . $wpdb->prefix . "posts.post_content", $query);
    }
    return $query;

}

add_filter('posts_request', 'gallery_after_search_results');


function gen_string_gallery_search($serch_word, $wordpress_query_post)
{
    $string_search = '';

    global $wpdb;
    if ($serch_word) {
        $rows_gallery = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itgallery_gallerys WHERE (description LIKE %s) OR (name LIKE %s)", '%' . $serch_word . '%', "%" . $serch_word . "%"));

        $count_cat_rows = count($rows_gallery);

        for ($i = 0; $i < $count_cat_rows; $i++) {
            $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[$i]->id . '" details="1" %\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[$i]->id . '" details="1"%\' OR ';
        }
		
        $rows_gallery = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itgallery_gallerys WHERE (name LIKE %s)","'%" . $serch_word . "%'"));
        $count_cat_rows = count($rows_gallery);
        for ($i = 0; $i < $count_cat_rows; $i++) {
            $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[$i]->id . '" details="0"%\' OR ' . $wordpress_query_post . ' LIKE \'%[huge_it_gallery id="' . $rows_gallery[$i]->id . '" details="0"%\' OR ';
        }

        $rows_single = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "huge_itgallery_images WHERE name LIKE %s","'%" . $serch_word . "%'"));

        $count_sing_rows = count($rows_single);
        if ($count_sing_rows) {
            for ($i = 0; $i < $count_sing_rows; $i++) {
                $string_search .= $wordpress_query_post . ' LIKE \'%[huge_it_gallery_Product id="' . $rows_single[$i]->id . '"]%\' OR ';
            }

        }
    }
    return $string_search;
}


///////////////////// end filter


add_shortcode('huge_it_gallery', 'huge_it_gallery_images_list_shotrcode');




function   huge_it_gallery_images_list($id)
{

    require_once("Front_end/gallery_front_end_view.php");
    require_once("Front_end/gallery_front_end_func.php");
    if (isset($_GET['product_id'])) {
        if (isset($_GET['view'])) {
            if ($_GET['view'] == 'huge_itgallery') {
                return showPublishedgallery_1($id);
            } else {
                return front_end_single_product($_GET['product_id']);
            }
        } else {
            return front_end_single_product($_GET['product_id']);
        }
    } else {
        return showPublishedgallery_1($id);
    }
}




add_filter('admin_head', 'huge_it_gallery_ShowTinyMCE');
function huge_it_gallery_ShowTinyMCE()
{
    // conditions here
    wp_enqueue_script('common');
    wp_enqueue_script('jquery-color');
    wp_print_scripts('editor');
    if (function_exists('add_thickbox')) add_thickbox();
    wp_print_scripts('media-upload');
    if (version_compare(get_bloginfo('version'), 3.3) < 0) {
        if (function_exists('wp_tiny_mce')) wp_tiny_mce();
    }
    wp_admin_css();
    wp_enqueue_script('utils');
    do_action("admin_print_styles-post-php");
    do_action('admin_print_styles');
}

function all_frontend_scripts_and_styles() {
    wp_register_script( 'jquery.colorbox-js', plugins_url('/js/jquery.colorbox.js', __FILE__), array('jquery'),'1.0.0',true  ); 
    wp_enqueue_script( 'jquery.colorbox-js' );
    wp_register_script( 'gallery-all-js', plugins_url('/js/gallery-all.js', __FILE__), array('jquery'),'1.0.0',true  ); 
    wp_enqueue_script( 'gallery-all-js' );
    wp_register_script( 'hugeitmicro-min-js', plugins_url('/js/jquery.hugeitmicro.min.js', __FILE__), array('jquery'),'1.0.0',true  ); 
    wp_enqueue_script( 'hugeitmicro-min-js' );
    
    wp_register_style( 'gallery-all-css', plugins_url('/style/gallery-all.css', __FILE__) );   
    wp_enqueue_style( 'gallery-all-css' );
    wp_register_style( 'style2-os-css', plugins_url('/style/style2-os.css', __FILE__) );   
    wp_enqueue_style( 'style2-os-css' );
    wp_register_style( 'lightbox-css', plugins_url('/style/lightbox.css', __FILE__) );   
    wp_enqueue_style( 'lightbox-css' );
    wp_register_style( 'fontawesome-css', plugins_url('/style/css/font-awesome.css', __FILE__) );   
    wp_enqueue_style( 'fontawesome-css' );
}
add_action('wp_enqueue_scripts', 'all_frontend_scripts_and_styles');


add_action('admin_menu', 'huge_it_gallery_options_panel');
function huge_it_gallery_options_panel()
{
    $page_cat = add_menu_page('Theme page title', 'Huge IT Gallery', 'manage_options', 'gallerys_huge_it_gallery', 'gallerys_huge_it_gallery', plugins_url('images/huge_it_galleryLogoHover -for_menu.png', __FILE__));
    $page_option = add_submenu_page('gallerys_huge_it_gallery', 'General Options', 'General Options', 'manage_options', 'Options_gallery_styles', 'Options_gallery_styles');
    $lightbox_options = add_submenu_page('gallerys_huge_it_gallery', 'Lightbox Options', 'Lightbox Options', 'manage_options', 'Options_gallery_lightbox_styles', 'Options_gallery_lightbox_styles');
	add_submenu_page('gallerys_huge_it_gallery', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'huge_it__gallery_featured_plugins', 'huge_it__gallery_featured_plugins');

	add_action('admin_print_styles-' . $page_cat, 'huge_it_gallery_admin_script');
    add_action('admin_print_styles-' . $page_option, 'huge_it_gallery_option_admin_script');
    add_action('admin_print_styles-' . $lightbox_options, 'huge_it_gallery_option_admin_script');
}

function huge_it__gallery_featured_plugins()
{
	include_once("admin/huge_it_featured_plugins.php");
}


function gallery_sliders_huge_it_slider()
{

    require_once("admin/gallery_slider_func.php");
    require_once("admin/gallery_slider_view.php");
    if (!function_exists('print_html_nav'))
        require_once("gallery_function/html_gallery_func.php");


    if (isset($_GET["task"]))
        $task = $_GET["task"]; 
    else
        $task = '';
    if (isset($_GET["id"]))
        $id = $_GET["id"];
    else
        $id = 0;
    global $wpdb;
    switch ($task) {

        case 'add_cat':
            add_slider();
            break;
		case 'add_shortcode_post':
            add_shortcode_post();
            break;
		case 'popup_posts':
            if ($id)
                popup_posts($id);
            break;
		case 'gallery_video':
            if ($id)
                gallery_video($id);
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itgallery_gallerys");
                gallery_video($id);
            }
            break;
        case 'edit_cat':
            if ($id)
                editslider($id);
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itgallery_gallerys");
                editslider($id);
            }
            break;

        case 'save':
            if ($id)
                apply_cat($id);
        case 'apply':
            if ($id) {
                apply_cat($id);
                editslider($id);
            } 
            break;
        case 'remove_cat':
            removeslider($id);
            showslider();
            break;
        default:
            showslider();
            break;
    }
}

function gallery_Options_slider_styles()
{
    require_once("admin/gallery_slider_options_func.php");
    require_once("admin/gallery_slider_options_view.php");
    if (isset($_GET['task']))
        if ($_GET['task'] == 'save')
            save_styles_options();
    showStyles();
}

//////////////////////////Huge it Slider ///////////////////////////////////////////

function huge_it_gallery_admin_script()
{
	wp_enqueue_media();
	wp_enqueue_style("jquery_ui", "http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css", FALSE);
	wp_enqueue_style("jquery_ui", plugins_url("style/jquery-ui.css", __FILE__), FALSE);
	wp_enqueue_style("admin_css", plugins_url("style/admin.style.css", __FILE__), FALSE);
	wp_enqueue_script("admin_js", plugins_url("js/admin.js", __FILE__), FALSE);
}


function huge_it_gallery_option_admin_script()
{
	wp_enqueue_script("jquery_old", "http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js", FALSE);
	wp_enqueue_script("simple_slider_js",  plugins_url("js/simple-slider.js", __FILE__), FALSE);
	wp_enqueue_style("simple_slider_css", plugins_url("style/simple-slider_sl.css", __FILE__), FALSE);
	wp_enqueue_style("admin_css", plugins_url("style/admin.style.css", __FILE__), FALSE);
	wp_enqueue_script("admin_js", plugins_url("js/admin.js", __FILE__), FALSE);
	wp_enqueue_script('param_block2', plugins_url("elements/jscolor/jscolor.js", __FILE__));
}


function gallerys_huge_it_gallery()
{

    require_once("admin/gallery_func.php");
    require_once("admin/gallery_view.php");
    if (!function_exists('print_html_nav'))
        require_once("gallery_function/html_gallery_func.php");


    if (isset($_GET["task"]))
        $task = $_GET["task"]; 
    else
        $task = '';
    if (isset($_GET["id"]))
        $id = $_GET["id"];
    else
        $id = 0;
    global $wpdb;
    switch ($task) {

        case 'add_cat':
            add_gallery();
            break;
		case 'gallery_video':
            if ($id)
                gallery_video($id);
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itgallery_gallerys");
                gallery_video($id);
            }
            break;
        case 'edit_cat':
            if ($id)
                editgallery($id);
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_itgallery_gallerys");
                editgallery($id);
            }
            break;

        case 'save':
            if ($id)
                apply_cat($id);
        case 'apply':
            if ($id) {
                apply_cat($id);
                editgallery($id);
            } 
            break;
        case 'remove_cat':
            removegallery($id);
            showgallery();
            break;
        default:
            showgallery();
            break;
    }


}


function Options_gallery_styles()
{
    require_once("admin/gallery_Options_func.php");
    require_once("admin/gallery_Options_view.php");
    if (isset($_GET['task']))
        if ($_GET['task'] == 'save')
            save_styles_options();
    showStyles();
}
function Options_gallery_lightbox_styles()
{
    require_once("admin/gallery_lightbox_func.php");
    require_once("admin/gallery_lightbox_view.php");
    if (isset($_GET['task']))
        if ($_GET['task'] == 'save')
            save_styles_options();
    showStyles();
}



/**
 * Huge IT Widget
 */
class Huge_it_gallery_Widget extends WP_Widget {


	public function __construct() {
		parent::__construct(
	 		'Huge_it_gallery_Widget', 
			'Huge IT gallery', 
			array( 'description' => __( 'Huge IT gallery', 'huge_it_gallery' ), ) 
		);
	}

	
	public function widget( $args, $instance ) {
		extract($args);

		if (isset($instance['gallery_id'])) {
			$gallery_id = $instance['gallery_id'];

			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			echo do_shortcode("[huge_it_gallery id={$gallery_id}]");
			echo $after_widget;
		}
	}


	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['gallery_id'] = strip_tags( $new_instance['gallery_id'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}


	public function form( $instance ) {
		$selected_gallery = 0;
		$title = "";
		$gallerys = false;

		if (isset($instance['gallery_id'])) {
			$selected_gallery = $instance['gallery_id'];
		}

		if (isset($instance['title'])) {
			$title = $instance['title'];
		}

        

        
		?>
		<p>
			
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p>
				<label for="<?php echo $this->get_field_id('gallery_id'); ?>"><?php _e('Select gallery:', 'huge_it_gallery'); ?></label> 
				<select id="<?php echo $this->get_field_id('gallery_id'); ?>" name="<?php echo $this->get_field_name('gallery_id'); ?>">
				
				<?php
				 global $wpdb;
				$query="SELECT * FROM ".$wpdb->prefix."huge_itgallery_gallerys ";
				$rowwidget=$wpdb->get_results($query);
				foreach($rowwidget as $rowwidgetecho){
				?>
					<option <?php if($rowwidgetecho->id == $instance['gallery_id']){ echo 'selected'; } ?> value="<?php echo $rowwidgetecho->id; ?>"><?php echo $rowwidgetecho->name; ?></option>
					<?php } ?>
				</select>
		</p>
		<?php 
	}
}

add_action('widgets_init', 'register_Huge_it_gallery_Widget');  

function register_Huge_it_gallery_Widget() {  
    register_widget('Huge_it_gallery_Widget'); 
}



//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////               Activate gallery                     ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////


function huge_it_gallery_activate()
{
    global $wpdb;

/// creat database tables



    $sql_huge_itgallery_params = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itgallery_params`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `value` varchar(200) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ";


    $sql_huge_itgallery_images = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itgallery_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `gallery_id` varchar(200) DEFAULT NULL,
  `description` text,
  `image_url` text,
  `sl_url` varchar(128) DEFAULT NULL,
  `sl_type` text NOT NULL,
  `link_target` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  `published_in_sl_width` tinyint(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5";

    $sql_huge_itgallery_gallerys = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_itgallery_gallerys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `sl_height` int(11) unsigned DEFAULT NULL,
  `sl_width` int(11) unsigned DEFAULT NULL,
  `pause_on_hover` text,
  `gallery_list_effects_s` text,
  `description` text,
  `param` text,
  `sl_position` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` text,
   `huge_it_sl_effects` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ";



    $table_name = $wpdb->prefix . "huge_itgallery_params";
    $sql_1 = <<<query1
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES

/*############################## VIEW 0 Popup #####################################*/

('ht_view2_element_linkbutton_text', 'Link Button Text', 'Link Button Text', 'View More'),
('ht_view2_element_show_linkbutton', 'Show Link Button On Element', 'Show Link Button On Element', 'on'),
('ht_view2_element_linkbutton_color', 'Element Link Button Font Color', 'Element Link Button Font Color', 'ffffff'),
('ht_view2_element_linkbutton_font_size', 'Element Link Button Font Size', 'Element Link Button Font Size', '14'),
('ht_view2_element_linkbutton_background_color', 'Element Link Button Background Color', 'Element Link Button Background Color', '2ea2cd'),
('ht_view2_show_popup_linkbutton', 'Show Link Button On Popup', 'Show Link Button On Popup', 'on'),
('ht_view2_popup_linkbutton_text', 'Popup Link Button Text', 'Link Button Text', 'View More'),
('ht_view2_popup_linkbutton_background_hover_color', 'Link Button Background Hover Color', 'Link Button Background Hover Color', '0074a2'),
('ht_view2_popup_linkbutton_background_color', 'Link Button Background Color', 'Link Button Background Color', '2ea2cd'),
('ht_view2_popup_linkbutton_font_hover_color', 'Link Button Font Hover Color', 'Link Button Font Hover Color', 'ffffff'),
('ht_view2_popup_linkbutton_color', 'Element Link Button Font Color', 'Link Button Font Color', 'ffffff'),
('ht_view2_popup_linkbutton_font_size', 'Element Link Button Font Size', 'Link Button Font Size', '14'),
('ht_view2_description_color', 'Description Font Color', 'Description Font Color', '222222'),
('ht_view2_description_font_size', 'Description Font Size', 'Description Font Size', '14'),
('ht_view2_show_description', 'Show Description', 'Show Description', 'on'),
('ht_view2_thumbs_width', 'Thumbnails Width', 'Thumbnails Width', '75'),
('ht_view2_thumbs_height', 'Thumbnails Height', 'Thumbnails Height', '75'),
('ht_view2_thumbs_position', 'Thumbnails Position', 'Thumbnails Position', 'before'),
('ht_view2_show_thumbs', 'Show Thumbnails', 'Show Thumbnails', 'on'),
('ht_view2_popup_background_color', 'Popup Background Color', 'Popup Background Color', 'FFFFFF'),
('ht_view2_popup_overlay_color', 'Popup Overlay Color', 'Popup Overlay Color', '000000'),
('ht_view2_popup_overlay_transparency_color', 'Popup Overlay Transparency', 'Popup Overlay Transparency ', '70'),
('ht_view2_popup_closebutton_style', 'Popup Close Button Style', 'Popup Close Button Style', 'dark'),
('ht_view2_show_separator_lines', 'Show Separator Lines', 'Show Separator Lines','on'),
('ht_view2_show_popup_title', 'Show Popup Title', 'Show Popup Title','on'),
('ht_view2_element_title_font_size', 'Element Title Font Size', 'Element Title Font Size', '18'),
('ht_view2_element_title_font_color', 'Element Title Font Color', 'Element Title Font Color', '222222'),
('ht_view2_popup_title_font_size', 'Popup Title Font Size', 'Popup Title Font Size', '18'),
('ht_view2_popup_title_font_color', 'Popup Title Font Color', 'Popup Title Font Color', '222222'),
('ht_view2_element_overlay_color', 'Element Overlay Color', 'Element Overlay Color', 'FFFFFF'),
('ht_view2_element_overlay_transparency', 'Element Overlay Transparency', 'Element Overlay Transparency ', '70'),
('ht_view2_zoombutton_style', 'Zoom Button Style', 'Zoom Button Style','light'),
('ht_view2_element_border_width', 'Element Border Width', 'Element Border Width', '1'),
('ht_view2_element_border_color', 'Element Border Color', 'Element Border Color', 'dedede'),
('ht_view2_element_background_color', 'Element Background Color', 'Element Background Color', 'f9f9f9'),
('ht_view2_element_width', 'Block Width', 'Block Width', '275'),
('ht_view2_element_height', 'Block Height', 'Block Height', '160'),


/*############################## VIEW 1 SLIDER #####################################*/

('ht_view5_icons_style', 'Icons Style', 'Icons Style','dark'),
('ht_view5_show_separator_lines', 'Show Separator Lines', 'Show Separator Lines','on'),
('ht_view5_linkbutton_text', 'Link Button Text', 'Link Button Text', 'View More'),
('ht_view5_show_linkbutton', 'Show Link Button', 'Show Link Button', 'on'),
('ht_view5_linkbutton_background_hover_color', 'Link Button Background Hover Color', 'Link Button Background Hover Color', '0074a2'),
('ht_view5_linkbutton_background_color', 'Link Button Background Color', 'Link Button Background Color', '2ea2cd'),
('ht_view5_linkbutton_font_hover_color', 'Link Button Font Hover Color', 'Link Button Font Hover Color', 'ffffff'),
('ht_view5_linkbutton_color', 'Link Button Font Color', 'Link Button Font Color', 'ffffff'),
('ht_view5_linkbutton_font_size', 'Link Button Font Size', 'Link Button Font Size', '14'),
('ht_view5_description_color', 'Description Font Color', 'Description Font Color', '555555'),
('ht_view5_description_font_size', 'Description Font Size', 'Description Font Size', '14'),
('ht_view5_show_description', 'Show Description', 'Show Description', 'on'),
('ht_view5_thumbs_width', 'Thumbnails Width', 'Thumbnails Width', '75'),
('ht_view5_thumbs_height', 'Thumbnails Height', 'Thumbnails Hight', '75'),
('ht_view5_show_thumbs', 'Show Thumbnails', 'Show Thumbnails', 'on'),
('ht_view5_title_font_size', 'Title Font Size', 'Title Font Size', '16'),
('ht_view5_title_font_color', 'Title Font Color', 'Title Font Color', '0074a2'),
('ht_view5_main_image_width', 'Main Image Width', 'Main Image Width', '275'),
('ht_view5_slider_tabs_font_color', 'Slider Tabs Font Color', 'Slider Tabs Font Color', 'd9d99'),
('ht_view5_slider_tabs_background_color', 'Slider Tabs Background Color', 'Slider Tabs Background Color', '555555'),
('ht_view5_slider_background_color', 'Slider Background Color', 'Slider Background Color', 'f9f9f9'),

/*############################## VIEW 2 Lightbox-gallery #####################################*/

('ht_view6_title_font_size', 'Title Font Size', 'Title Font Size', '16'),
('ht_view6_title_font_color', 'Title Font Color', 'Title Font Color', '0074A2'),
('ht_view6_title_font_hover_color', 'Title Font Hover Color', 'Title Font Hover Color', '2EA2CD'),
('ht_view6_title_background_color', 'Title Background Color', 'Title Background Color', '000000'),
('ht_view6_title_background_transparency', 'Title Background Transparency', 'Title Background Transparency', '80'),
('ht_view6_border_radius', 'Image Border Radius', 'Image Border Radius', '3'),
('ht_view6_border_width', 'Image Border Width', 'Image Border Width', '0'),
('ht_view6_border_color', 'Image Border Color', 'Image Border Color', 'eeeeee'),
('ht_view6_width', 'Image Width', 'Image Width', '275'),

/*############################## Lightbox #####################################*/

('light_box_size', 'Light box size', 'Light box size', '17'),
('light_box_width', 'Light Box width', 'Light Box width', '500'),
('light_box_transition', 'Light Box Transition', 'Light Box Transition', 'elastic'),
('light_box_speed', 'Light box speed', 'Light box speed', '800'),
('light_box_href', 'Light box href', 'Light box href', 'False'),
('light_box_title', 'Light box Title', 'Light box Title', 'false'),
('light_box_scalephotos', 'Light box scalePhotos', 'Light box scalePhotos', 'true'),
('light_box_rel', 'Light Box rel', 'Light Box rel', 'false'),
('light_box_scrolling', 'Light box Scrollin', 'Light box Scrollin', 'false'),
('light_box_opacity', 'Light box Opacity', 'Light box Opacity', '20'),
('light_box_open', 'Light box Open', 'Light box Open', 'false'),
('light_box_overlayclose', 'Light box overlayClose', 'Light box overlayClose', 'true'),
('light_box_esckey', 'Light box escKey', 'Light box escKey', 'false'),
('light_box_arrowkey', 'Light box arrowKey', 'Light box arrowKey', 'false'),
('light_box_loop', 'Light box loop', 'Light box loop', 'true'),
('light_box_data', 'Light box data', 'Light box data', 'false'),
('light_box_classname', 'Light box className', 'Light box className', 'false'),
('light_box_fadeout', 'Light box fadeOut', 'Light box fadeOut', '300'),
('light_box_closebutton', 'Light box closeButton', 'Light box closeButton', 'false'),
('light_box_current', 'Light box current', 'Light box current', 'image'),
('light_box_previous', 'Light box previous', 'Light box previous', 'previous'),
('light_box_next', 'Light box next', 'Light box next', 'next'),
('light_box_close', 'Light box close', 'Light box close', 'close'),
('light_box_iframe', 'Light box iframe', 'Light box iframe', 'false'),
('light_box_inline', 'Light box inline', 'Light box inline', 'false'),
('light_box_html', 'Light box html', 'Light box html', 'false'),
('light_box_photo', 'Light box photo', 'Light box photo', 'false'),
('light_box_height', 'Light box height', 'Light box height', '500'),
('light_box_innerwidth', 'Light box innerWidth', 'Light box innerWidth', 'false'),
('light_box_innerheight', 'Light box innerHeight', 'Light box innerHeight', 'false'),
('light_box_initialwidth', 'Light box initialWidth', 'Light box initialWidth', '300'),
('light_box_initialheight', 'Light box initialHeight', 'Light box initialHeight', '100'),
('light_box_maxwidth', 'Light box maxWidth', 'Light box maxWidth', '768'),
('light_box_maxheight', 'Light box maxHeight', 'Light box maxHeight', '500'),
('light_box_slideshow', 'Light box slideshow', 'Light box slideshow', 'false'),
('light_box_slideshowspeed', 'Light box slideshowSpeed', 'Light box slideshowSpeed', '2500'),
('light_box_slideshowauto', 'Light box slideshowAuto', 'Light box slideshowAuto', 'true'),
('light_box_slideshowstart', 'Light box slideshowStart', 'Light box slideshowStart', 'start slideshow'),
('light_box_slideshowstop', 'Light box slideshowStop', 'Light box slideshowStop', 'stop slideshow'),
('light_box_fixed', 'Light box fixed', 'Light box fixed', 'true'),
('light_box_top', 'Light box top', 'Light box top', 'false'),
('light_box_bottom', 'Light box bottom', 'Light box bottom', 'false'),
('light_box_left', 'Light box left', 'Light box left', 'false'),
('light_box_right', 'Light box right', 'Light box right', 'false'),
('light_box_reposition', 'Light box reposition', 'Light box reposition', 'false'),
('light_box_retinaimage', 'Light box retinaImage', 'Light box retinaImage', 'true'),
('light_box_retinaurl', 'Light box retinaUrl', 'Light box retinaUrl', 'false'),
('light_box_retinasuffix', 'Light box retinaSuffix', 'Light box retinaSuffix', '@2x.$1'),
('light_box_returnfocus', 'Light box returnFocus', 'Light box returnFocus', 'true'),
('light_box_trapfocus', 'Light box trapFocus', 'Light box trapFocus', 'true'),
('light_box_fastiframe', 'Light box fastIframe', 'Light box fastIframe', 'true'),
('light_box_preloading', 'Light box preloading', 'Light box preloading', 'true'),
('lightbox_open_position', 'Lightbox open position', 'Lightbox open position', '5'),
('light_box_style', 'Light Box style', 'Light Box style', '1'),
('light_box_size_fix', 'Light Box size fix style', 'Light Box size fix style', 'false'),

/*############################## Huge IT Slider #####################################*/

('slider_crop_image', 'Slider crop image', 'Slider crop image', 'crop'),
('slider_title_color', 'Slider title color', 'Slider title color', '000000'),
('slider_title_font_size', 'Slider title font size', 'Slider title font size', '13'),
('slider_description_color', 'Slider description color', 'Slider description color', 'ffffff'),
('slider_description_font_size', 'Slider description font size', 'Slider description font size', '12'),
('slider_title_position', 'Slider title position', 'Slider title position', 'right-top'),
('slider_description_position', 'Slider description position', 'Slider description position', 'right-bottom'),
('slider_title_border_size', 'Slider Title border size', 'Slider Title border size', '0'),
('slider_title_border_color', 'Slider title border color', 'Slider title border color', 'ffffff'),
('slider_title_border_radius', 'Slider title border radius', 'Slider title border radius', '4'),
('slider_description_border_size', 'Slider description border size', 'Slider description border size', '0'),
('slider_description_border_color', 'Slider description border color', 'Slider description border color', 'ffffff'),
('slider_description_border_radius', 'Slider description border radius', 'Slider description border radius', '0'),
('slider_slideshow_border_size', 'Slider border size', 'Slider border size', '0'),
('slider_slideshow_border_color', 'Slider border color', 'Slider border color', 'ffffff'),
('slider_slideshow_border_radius', 'Slider border radius', 'Slider border radius', '0'),
('slider_navigation_type', 'Slider navigation type', 'Slider navigation type', '1'),
('slider_navigation_position', 'Slider navigation position', 'Slider navigation position', 'bottom'),
('slider_title_background_color', 'Slider title background color', 'Slider title background color', 'ffffff'),
('slider_description_background_color', 'Slider description background color', 'Slider description background color', '000000'),
('slider_title_transparent', 'Slider title has background', 'Slider title has background', 'on'),
('slider_description_transparent', 'Slider description has background', 'Slider description has background', 'on'),
('slider_slider_background_color', 'Slider slider background color', 'Slider slider background color', 'ffffff'),
('slider_dots_position', 'slider dots position', 'slider dots position', 'top'),
('slider_active_dot_color', 'slider active dot color', '', 'ffffff'),
('slider_dots_color', 'slider dots color', '', '000000'),
('slider_description_width', 'Slider description width', 'Slider description width', '70'),
('slider_description_height', 'Slider description height', 'Slider description height', '50'),
('slider_description_background_transparency', 'slider description background transparency', 'slider description background transparency', '70'),
('slider_description_text_align', 'description text-align', 'description text-align', 'justify'),
('slider_title_width', 'slider title width', 'slider title width', '30'),
('slider_title_height', 'slider title height', 'slider title height', '50'),
('slider_title_background_transparency', 'slider title background transparency', 'slider title background transparency', '70'),
('slider_title_text_align', 'title text-align', 'title text-align', 'right'),
('slider_title_has_margin', 'title has margin', 'title has margin', 'off'),
('slider_description_has_margin', 'description has margin', 'description has margin', 'off'),
('slider_show_arrows', 'Slider show left right arrows', 'Slider show left right arrows', 'on'),

/*############################## Thumbnail view #####################################*/

('thumb_image_behavior', 'Image Behavior', 'Image Behavior', 'on'),
('thumb_image_width', 'Image widht', 'Image widht', '240'),
('thumb_image_height', 'Image height', 'Image height', '150'),
('thumb_image_border_width', 'Image border width', 'Image border width', '1'),
('thumb_image_border_color', 'Image border color', 'Image border color', '444444'),
('thumb_image_border_radius', 'Image border Radius', 'Image border Radius', '5'),
('thumb_margin_image', 'Margin image', 'Margin image', '1'),
('thumb_title_font_size', 'Title font size', 'Title font size', '16'),
('thumb_title_font_color', 'Title font color', 'Title font color', 'FFFFFF'),
('thumb_title_background_color', 'Title background color', 'Title background color', 'CCCCCC'),
('thumb_title_background_transparency', 'Title Background Transparency', 'Title Background Transparency', '80'),
('thumb_box_padding', 'Box padding', 'Box padding', '28'),
('thumb_box_background', 'Box background', 'Box background', '333333'),
('thumb_box_use_shadow', 'Box use shadow', 'Box use shadow', 'on'),
('thumb_box_has_background', 'Box has background', 'Box has background', 'on');


query1;

    $table_name = $wpdb->prefix . "huge_itgallery_images";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` (`id`, `name`, `gallery_id`, `description`, `image_url`, `sl_url`, `sl_type`, `link_target`, `ordering`, `published`, `published_in_sl_width`) VALUES
(1, 'Rocky Balboa', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/1.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 0, 1, NULL),
(2, 'Skiing alone', '1', '<ul><li>lorem ipsumdolor sit amet</li><li>lorem ipsum dolor sit amet</li></ul><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/2.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 1, 1, NULL),
(3, 'Summer dreams', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '".plugins_url("Front_images/projects/3.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 2, 1, NULL),
(4, 'Mr. Cosmo Kramer', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><h7>Dolor sit amet</h7><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/4.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 3, 1, NULL),
(5, 'Edgar Allan Poe', '1', '<h6>Lorem Ipsum</h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/5.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 4, 1, NULL),
(6, 'Fix the moment !', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/6.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 5, 1, NULL),
(7, 'Lions eyes', '1', '<h6>Lorem Ipsum</h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '".plugins_url("Front_images/projects/7.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 6, 1, NULL),
(8, 'Photo with exposure', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '".plugins_url("Front_images/projects/8.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 7, 1, NULL),
(9, 'Travel with music', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '".plugins_url("Front_images/projects/9.jpg", __FILE__)."', 'http://huge-it.com/fields/order-website-maintenance/', 'image', 'on', 7, 1, NULL)";



    $table_name = $wpdb->prefix . "huge_itgallery_gallerys";


    $sql_3 = "

INSERT INTO `$table_name` (`id`, `name`, `sl_height`, `sl_width`, `pause_on_hover`, `gallery_list_effects_s`, `description`, `param`, `sl_position`, `ordering`, `published`, `huge_it_sl_effects`) VALUES
(1, 'My First Gallery', 375, 600, 'on', 'random', '4000', '1000', 'center', 1, '300', '5')";


    $wpdb->query($sql_huge_itgallery_params);
    $wpdb->query($sql_huge_itgallery_images);
    $wpdb->query($sql_huge_itgallery_gallerys);


    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itgallery_params")) {
        $wpdb->query($sql_1);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itgallery_images")) {
      $wpdb->query($sql_2);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_itgallery_gallerys")) {
      $wpdb->query($sql_3);
    }
	
	    $table_name = $wpdb->prefix . "huge_itgallery_params";
	    $sql_update_g1 = <<<query1
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('thumb_view_text', 'View Image Text', 'View Image Text', 'View Picture');

query1;
	
	$query="SELECT name FROM ".$wpdb->prefix."huge_itgallery_params";
	$update_p1=$wpdb->get_results($query);
	if(end($update_p1)->name=='thumb_box_has_background'){
		$wpdb->query($sql_update_g1);
	}
        
        $table_name = $wpdb->prefix . "huge_itgallery_params";
	    $sql_update_g2 = <<<query1
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('ht_view8_element_cssAnimation', 'Image CssAnimation', 'Image CssAnimation', 'false'),
('ht_view8_element_height', 'Element Hight', 'Element Hight', '120'),
('ht_view8_element_maxheight', 'Element MaxHight', 'Element MaxHight', '155'),
('ht_view8_element_show_caption', 'Show Caption', 'Show Caption', 'true'),
('ht_view8_element_padding', 'Element Padding', 'Element Padding', '0'),
('ht_view8_element_border_radius', 'Border Radius', 'Border Radius', '5'),
('ht_view8_icons_style', 'Icons Style', 'Icons Style', 'dark'),
('ht_view8_element_title_font_size', 'Element Title Font Size', 'Element Title Font Size', '13'),
('ht_view8_element_title_font_color', 'Element Title Font Color', 'Element Title Font Color', '3AD6FC'),
('ht_view8_popup_background_color', 'Popup background Color', 'Popup background Color', '000000'),
('ht_view8_popup_overlay_transparency_color', 'Popup Overlay Transparency Color', 'Popup Overlay Transparency Color', '0'),
('ht_view8_popup_closebutton_style', 'Popup Closebutton Style', 'Popup Closebutton Style', 'dark'),
('ht_view8_element_title_overlay_transparency', 'Element Overlay Transparency', 'Element Overlay Transparency', '90'),
('ht_view8_element_size_fix', 'Element Size Fix', 'Element Size Fix', 'false'),
('ht_view8_element_title_background_color', 'Element Title Background Color', 'Element Title Background Color', 'FF1C1C'),
('ht_view8_element_justify', 'Image Justify', 'Image Justify', 'true'),
('ht_view8_element_randomize', 'Image Randomize', 'Image Randomize', 'false'),
('ht_view8_element_animation_speed', 'Image Animation Speed', 'Image Animation Speed', '2000');      
query1;
            
        $query="SELECT name FROM ".$wpdb->prefix."huge_itgallery_params";
	$update_p2=$wpdb->get_results($query);
	if(end($update_p2)->name=='thumb_view_text'){
		$wpdb->query($sql_update_g2);
	}
        
        
$table_name = $wpdb->prefix . "huge_itgallery_params";
	    $sql_update_g3 = <<<query3
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('ht_view2_content_in_center', 'View2 Content In Center', 'View2 Content In Center', 'off'),
('ht_view6_content_in_center', 'View6 Content In Center', 'View6 Content In Center', 'off');
                    
query3;
        
        
        $query3="SELECT name FROM ".$wpdb->prefix."huge_itgallery_params";
	$update_p3=$wpdb->get_results($query3);
	if(end($update_p3)->name=='ht_view8_element_animation_speed'){
		$wpdb->query($sql_update_g3);
	}
$table_name = $wpdb->prefix . "huge_itgallery_params";
	    $sql_update_g4 = <<<query4
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('ht_view2_popup_full_width', 'Popup Image Full Width', 'Popup Image Full Width', 'on');                  
query4;
        
        
        $query4="SELECT name FROM ".$wpdb->prefix."huge_itgallery_params";
	$update_p4=$wpdb->get_results($query4);
	if(end($update_p4)->name=='ht_view6_content_in_center'){
		$wpdb->query($sql_update_g4);
	}

    ///////////////////new update//////////////////////

$table_name = $wpdb->prefix . "huge_itgallery_params";
        $sql_update_g5 = <<<query5
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('ht_view9_title_fontsize', 'Title Fontsize', 'Title Fontsize', '18'),
('ht_view9_title_color', 'Title Color', 'Title Color', 'FFFFFF'),
('ht_view9_desc_color', 'Description Color', 'Description Color', '000000'),
('ht_view9_desc_fontsize', 'Description Fontsize', 'Description Fontsize', '14'),
('ht_view9_element_title_show', 'Show/Hide Title', 'Show/Hide Title', 'true'),
('ht_view9_element_desc_show', 'Show/Hide Description', 'Show/Hide Description', 'true'),
('ht_view9_general_width', 'General Width', 'General Width', '100'),
('view9_general_position', 'General Position', 'General Position', 'center'),
('view9_title_textalign', 'General Textalign', 'General Textalign', 'left'),
('view9_desc_textalign', 'General Description Textalign', 'General Description Textalign', 'justify'),
('view9_image_position', 'Image Position', 'Image Position', '2'),
('ht_view9_title_back_color', 'Background Color', 'Background Color', '000000'),
('ht_view9_title_opacity', 'Title Opacity', 'Title Opacity', '70'),
('ht_view9_desc_opacity', 'Description Opacity', 'Description Opacity', '100'),
('ht_view9_desc_back_color', 'Background Color', 'Background Color', 'FFFFFF'),
('ht_view9_general_space', 'Space', 'Space', '0'),
('ht_view9_general_separator_size', 'Separator Size', 'Separator Size', '0'),
('ht_view9_general_separator_color', 'Separator Color', 'Separator Color', '010457'),
('view9_general_separator_style', 'Separator Style', 'Separator Style', 'dotted'),
('ht_view9_paginator_fontsize', 'Paginator Fontsize', 'Paginator Fontsize', '22'),
('ht_view9_paginator_color', 'Paginator Color', 'Paginator Color', '1046B3'),
('ht_view9_paginator_icon_color', 'Paginator Icons Color', 'Paginator Icons Color', '1046B3'),
('ht_view9_paginator_icon_size', 'Paginator Icons Size', 'Paginator Icons Size', '18'),
('view9_paginator_position', 'Paginator Position', 'Paginator Position', 'center');                 
query5;
        
        
        $query5="SELECT name FROM ".$wpdb->prefix."huge_itgallery_params";
    $update_p5=$wpdb->get_results($query5);
    if(end($update_p5)->name=='ht_view2_popup_full_width'){
        $wpdb->query($sql_update_g5);
    }

    ////////////////////////////////////////
    $table_name = $wpdb->prefix . "huge_itgallery_params";
        $sql_update_g6 = <<<query6
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES
('video_view9_loadmore_position', 'Load More Position', 'Load More Position', 'center'),
('video_ht_view9_loadmore_fontsize', 'Load More Font Size', 'Load More Font Size', '19'),
('video_ht_view9_button_color', 'Load More Button Color', 'Load More Button Color', '5CADFF'),
('video_ht_view9_loadmore_font_color', 'Load More Font Color', 'Load More Font Color', '000000'),
('video_ht_view9_loadmore_font_color', 'Load More Text', 'Load More Text', 'Load More'),
('loading_type', 'Loading GIF', 'Loading GIF', '2'),
('video_ht_view9_loadmore_text', 'Load More Text', 'Load More Text', 'View More'),
('video_ht_view8_paginator_position', 'Paginator Position', 'Paginator Position', 'center'),
('video_ht_view8_paginator_icon_size', 'Paginator Icons Size', 'Paginator Icons Size', '18'),
('video_ht_view8_paginator_icon_color', 'Paginator Icons Color', 'Paginator Icons Color', '26A6FC'),
('video_ht_view8_paginator_color', 'Paginator Color', 'Paginator Color', '26A6FC'),
('video_ht_view8_paginator_fontsize', 'Paginator Fontsize', 'Paginator Fontsize', '18'),
('video_ht_view8_loadmore_position', 'Load More Position', 'Load More Position', 'center'),
('video_ht_view8_loadmore_fontsize', 'Load More Font Size', 'Load More Font Size', '14'),
('video_ht_view8_button_color', 'Load More Button Color', 'Load More Button Color', '26A6FC'),
('video_ht_view8_loadmore_font_color', 'Load More Font Color', 'Load More Font Color', 'FF1C1C'),
('video_ht_view8_loading_type', 'Loading GIF', 'Loading GIF', '3'),
('video_ht_view8_loadmore_text', 'Load More Text', 'Load More Text', 'View More'),
('video_ht_view7_paginator_fontsize', 'Paginator Fontsize', 'Paginator Fontsize', '22'),
('video_ht_view7_paginator_color', 'Paginator Color', 'Paginator Color', '0A0202'),
('video_ht_view7_paginator_icon_color', 'Paginator Icons Color', 'Paginator Icons Color', '333333'),
('video_ht_view7_paginator_icon_size', 'Paginator Icons Size', 'Paginator Icons Size', '22'),
('video_ht_view7_paginator_position', 'Paginator Position', 'Paginator Position', 'center'),
('video_ht_view7_loadmore_position', 'Load More Position', 'Load More Position', 'center'),
('video_ht_view7_loadmore_fontsize', 'Load More Font Size', 'Load More Font Size', '19'),
('video_ht_view7_button_color', 'Load More Button Color', 'Load More Button Color', '333333'),
('video_ht_view7_loadmore_font_color', 'Load More Font Color', 'Load More Font Color', 'CCCCCC'),
('video_ht_view7_loading_type', 'Loading GIF', 'Loading GIF', '1'),
('video_ht_view7_loadmore_text', 'Load More Text', 'Load More Text', 'View More'),
('video_ht_view4_paginator_fontsize', 'Paginator Fontsize', 'Paginator Fontsize', '19'),
('video_ht_view4_paginator_color', 'Paginator Color', 'Paginator Color', 'FF2C2C'),
('video_ht_view4_paginator_icon_color', 'Paginator Icons Color', 'Paginator Icons Color', 'B82020'),
('video_ht_view4_paginator_icon_size', 'Paginator Icons Size', 'Paginator Icons Size', '21'),
('video_ht_view4_paginator_position', 'Paginator Position', 'Paginator Position', 'center'),
('video_ht_view4_loadmore_position', 'Load More Position', 'Load More Position', 'center'),
('video_ht_view4_loadmore_fontsize', 'Load More Font Size', 'Load More Font Size', '16'),
('video_ht_view4_button_color', 'Load More Button Color', 'Load More Button Color', '5CADFF'),
('video_ht_view4_loadmore_font_color', 'Load More Font Color', 'Load More Font Color', 'FF0D0D'),
('video_ht_view4_loading_type', 'Loading GIF', 'Loading GIF', '3'),
('video_ht_view4_loadmore_text', 'Load More Text', 'Load More Text', 'View More'),
('video_ht_view1_paginator_fontsize', 'Paginator Fontsize', 'Paginator Fontsize', '22'),
('video_ht_view1_paginator_color', 'Paginator Color', 'Paginator Color', '222222'),
('video_ht_view1_paginator_icon_color', 'Paginator Icons Color', 'Paginator Icons Color', 'FF2C2C'),
('video_ht_view1_paginator_icon_size', 'Paginator Icons Size', 'Paginator Icons Size', '22'),
('video_ht_view1_paginator_position', 'Paginator Position', 'Paginator Position', 'left'),
('video_ht_view1_loadmore_position', 'Load More Position', 'Load More Position', 'center'),
('video_ht_view1_loadmore_fontsize', 'Load More Font Size', 'Load More Font Size', '22'),
('video_ht_view1_button_color', 'Load More Button Color', 'Load More Button Color', 'FF2C2C'),
('video_ht_view1_loadmore_font_color', 'Load More Font Color', 'Load More Font Color', 'FFFFFF'),
('video_ht_view1_loading_type', 'Loading GIF', 'Loading GIF', '2'),
('video_ht_view1_loadmore_text', 'Load More Text', 'Load More Text', 'Load More'),
('video_ht_view9_loadmore_font_color_hover', 'Load More Font Hover', 'Load More Font Hover', 'D9D9D9'),
('video_ht_view9_button_color_hover', 'Load More Background Hover', 'Load More Background Hover', '8F827C'),
('video_ht_view8_loadmore_font_color_hover', 'Load More Font Hover', 'Load More Font Hover', 'FF4242'),
('video_ht_view8_button_color_hover', 'Load More Background Hover', 'Load More Background Hover', '0FEFFF'),
('video_ht_view7_loadmore_font_color_hover', 'Load More Font Hover', 'Load More Font Hover', 'D9D9D9'),
('video_ht_view7_button_color_hover', 'Load More Background Hover', 'Load More Background Hover', '8F827C'),
('video_ht_view4_loadmore_font_color_hover', 'Load More Font Hover', 'Load More Font Hover', 'FF4040'),
('video_ht_view4_button_color_hover', 'Load More Background Hover', 'Load More Background Hover', '99C5FF'),
('video_ht_view1_loadmore_font_color_hover', 'Load More Font Hover', 'Load More Font Hover', 'F2F2F2'),
('video_ht_view1_button_color_hover', 'Load More Background Hover', 'Load More Background Hover', '991A1A');                 
query6;
        
        
        $query6="SELECT name FROM ".$wpdb->prefix."huge_itgallery_params";
    $update_p6=$wpdb->get_results($query6);
    if(end($update_p6)->name=='view9_paginator_position'){
        $wpdb->query($sql_update_g6);
    }

      ////////////////////////////////////////
  $imagesAllFieldsInArray2 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itgallery_gallerys", ARRAY_A);
        $fornewUpdate = 0;
        foreach ($imagesAllFieldsInArray2 as $portfoliosField2) {
            if ($portfoliosField2['Field'] == 'display_type') {
                $fornewUpdate = 1;
            }
        }
        if($fornewUpdate != 1){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itgallery_gallerys ADD display_type integer DEFAULT '2' ");
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itgallery_gallerys ADD content_per_page integer DEFAULT '5' ");
        } 
        ///////////////////////////////////////////////////////////////////////
        $imagesAllFieldsInArray3 = $wpdb->get_results("DESCRIBE " . $wpdb->prefix . "huge_itgallery_images", ARRAY_A);
        $fornewUpdate2 = 0;
        foreach ($imagesAllFieldsInArray3 as $portfoliosField3) {

            if ($portfoliosField3['Field'] == 'sl_url'  &&  $portfoliosField3['Type'] == 'text') {
               $fornewUpdate2=1;
            }
        }
        if($fornewUpdate2 != 1){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."huge_itgallery_images CHANGE sl_url sl_url TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
           
        }  
}

register_activation_hook(__FILE__, 'huge_it_gallery_activate');