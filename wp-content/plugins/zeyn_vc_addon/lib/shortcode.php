<?php
defined('ABSPATH') or die();

if(shortcode_exists('dt_map'))
    remove_shortcode('dt_map');

add_shortcode('dt_map', 'vc_dtmap_shortcode');

function vc_dtmap_shortcode($atts, $content = null){

    global $dt_el_id;

    extract( shortcode_atts( array(
        'lat'=>-7.2852292,
        'lang'=>112.6809869,
        'zoom'=>7,
        'zoomcontrol'=>true,
        'pancontrol'=>true,
        'streetcontrol'=>true,
        'scrollcontrol'=>true,
        'height'=>'100%',
        'width'=>'',
        'style'=>'pastel',
        'marker'=>'default',
        'image_marker'=>'',
        'title'=>''
    ), $atts ) );


    if(!isset($dt_el_id) || empty($dt_el_id)){
        $dt_el_id=0;
    }

    $dt_el_id++;
    if(vc_is_inline()){

        $dt_el_id=time().rand(1,99);
    }

    if(!is_admin()){
       wp_enqueue_script('gmap',"https://maps.googleapis.com/maps/api/js?v=3.exp",array('jquery'));
    }

    $mapStyle=array(
        'shades'=>'[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]}]',
        'midnight'=>'[{"featureType":"water","stylers":[{"color":"#021019"}]},{"featureType":"landscape","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"transit","stylers":[{"color":"#146474"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]}]',
        'bluewater'=>'[{"featureType":"water","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"landscape","stylers":[{"color":"#f2f2f2"}]},{"featureType":"road","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]}]',
        'lightmonochrome'=>'[{"featureType":"water","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67},{"visibility":"simplified"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8},{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":10},{"lightness":69},{"visibility":"on"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"hue":"#2c2e33"},{"saturation":7},{"lightness":19},{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2},{"visibility":"simplified"}]}]',
        'neutralblue'=>'[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#193341"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#2c5a71"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#29768a"},{"lightness":-37}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#406d80"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#406d80"}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#3e606f"},{"weight":2},{"gamma":0.84}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"weight":0.6},{"color":"#1a3541"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#2c5a71"}]}]',
        'avocadoworld'=>'[{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#aee2e0"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#abce83"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#769E72"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#7B8758"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#EBF4A4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#8dab68"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#5B5B3F"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ABCE83"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#A4C67D"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#9BBF72"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#EBF4A4"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#87ae79"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#7f2200"},{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"visibility":"on"},{"weight":4.1}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#495421"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"visibility":"off"}]}]',
        'nature'=>'[{"featureType":"landscape","stylers":[{"hue":"#FFA800"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#53FF00"},{"saturation":-73},{"lightness":40},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FBFF00"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#00FFFD"},{"saturation":0},{"lightness":30},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#00BFFF"},{"saturation":6},{"lightness":8},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#679714"},{"saturation":33.4},{"lightness":-25.4},{"gamma":1}]}]',
        'pastel'=>'[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":60}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"lightness":30}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ef8c25"},{"lightness":40}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#b6c54c"},{"lightness":40},{"saturation":-40}]},{}]'
        );


    $mapOptions=array();
    $mapOptions['zoom']='zoom: '.$zoom;
    $markerOption="";

    if($marker!=='default'){

        $image_url=DETHEME_VC_DIR_URL.'images/map_marker.png';

        if($image_marker){

            $imageMarker = wp_get_attachment_image_src(trim($image_marker),'full',false); 
            if(!empty($imageMarker[0])){
                        $image_url=$imageMarker[0];
            }

        }

        $markerOption='var iconMarker = {url: \''.$image_url.'\'};';

    }

    if(!$zoomcontrol){$mapOptions['zoomControl']='zoomControl:false';}
    if(!$pancontrol){$mapOptions['panControl']='panControl:false';}
    if(!$streetcontrol){$mapOptions['streetViewControl']='streetViewControl:false';}
    if(!$scrollcontrol){$mapOptions['scrollwheel']='scrollwheel:false';}


    $compile="<div id=\"map-canvas".$dt_el_id."\" class=\"google-map\" style=\"height:".$height.((!empty($width))?";width:".$width."":"")."\"></div>";


    $compile.='<script type="text/javascript">';
    $compile.='jQuery(document).ready(function($) {
                try {
                    var map,center = new google.maps.LatLng('.$lat.','.$lang.'),'.(isset($mapStyle[$style])?"style=".$mapStyle[$style].",":"").'
                    mapOptions = {center: center,mapTypeControl: false,'.@implode(',',$mapOptions).(isset($mapStyle[$style])?",styles:style":"").'};
                    '.$markerOption.'
                    
                    map = new google.maps.Map(document.getElementById(\'map-canvas'.$dt_el_id.'\'),mapOptions);
                    var marker = new google.maps.Marker({
                        position: center,
                        map: map,
                      '.(!empty($markerOption)?"icon: iconMarker":"").'  
                    });
                    
                } catch ($err) {
                }
        });
</script>'."\n";




   return $compile;




}

if(shortcode_exists('dt_iconbox'))
    remove_shortcode('dt_iconbox');

add_shortcode('dt_iconbox', 'vc_iconbox_shortcode');

function vc_iconbox_shortcode($atts, $content = null) {

            global $dt_el_id;

            wp_enqueue_style('detheme-vc');
            wp_enqueue_style('scroll-spy');


            wp_register_script('jquery.appear',DETHEME_VC_DIR_URL."js/jquery.appear.js",array());
            wp_register_script('jquery.counto',DETHEME_VC_DIR_URL."js/jquery.counto.js",array());
            wp_register_script('dt-iconbox',DETHEME_VC_DIR_URL."js/dt_iconbox.js",array('jquery.appear','jquery.counto'));

            wp_enqueue_script('dt-iconbox');
            wp_enqueue_script('ScrollSpy');

            if (!isset($compile)) {$compile='';}

            extract( shortcode_atts( array(
                'iconbox_heading' => '',
                'color_heading'=>'',
                'button_link' => '',
                'button_text' => '',
                'icon_type' => '',
                'layout_type'=>'1',
                'target' => '_blank',
                'iconbox_text'=>'',
                'link' => '',
                'iconbox_number'=>100,
                'spy'=>'none',
                'css'=>'',
                'spydelay'=>300
            ), $atts ) );

            $content=(empty($content) && !empty($iconbox_text))?$iconbox_text:$content;

            if(!isset($dt_el_id) || empty($dt_el_id))
                $dt_el_id=0;

            $iconbox_number=(int)$iconbox_number;
            $color_heading=(!empty($color_heading))?" style=\"color:".$color_heading."\"":"";

            $content=wpb_js_remove_wpautop($content,true);


             $scollspy="";
             $spydly=0;
            if('none'!==$spy && !empty($spy)){

                $spydly=$spydly+(int)$spydelay;

                $scollspy='data-uk-scrollspy="{cls:\''.$spy.'\', delay:'.$spydly.'}"';

            }

            switch($layout_type){
                case '2':
                    $compile='<div class="dt-iconboxes-2 layout-'.$layout_type.'" '. $scollspy.'>
                        <div class="dt-section-icon hi-icon-wrap hi-icon-effect-5 hi-icon-effect-5a">'.((strlen($link)>0) ?"<a target='".$target."' href='".$link."'>":"").'<i class="hi-icon '.$icon_type.'"></i>'.((strlen($link)>0) ?"</a>":"").'</div>
                        <h4'.$color_heading.'>'.$iconbox_heading.'</h4>'.'<div class="dt-iconboxes-text">'.                 
                        ((!empty($content))?do_shortcode($content):"").'</div>
                        </div>';
                    break;
                case '3':
                    $compile='<div class="dt-iconboxes layout-'.$layout_type.'" '. $scollspy.'>
                        <span>'.((strlen($link)>0) ?"<a target='".$target."' href='".$link."'>":"").'<i class="'.$icon_type.'"></i>'.((strlen($link)>0) ?"</a>":"").'</span>
                        <h3 class="dt-counter">'.$iconbox_number.'</h3>
                        <h4'.$color_heading.'>'.$iconbox_heading.'</h4><div class="dt-iconboxes-text">
                        '.((!empty($content))?do_shortcode($content):"").'</div></div>';

                    break;
                case '4':
                    $compile='<div '. $scollspy.'><div class="dt-iconboxes-4 layout-'.$layout_type.'">
                        <div class="dt-section-icon hi-icon-wrap hi-icon-effect-5 hi-icon-effect-5d">'.((strlen($link)>0) ?"<a target='".$target."' href='".$link."'>":"").'<i class="hi-icon '.$icon_type.'"></i>'.((strlen($link)>0) ?"</a>":"").'</div>
                        <h4'.$color_heading.'>'.$iconbox_heading.'</h4>'.                 
                        '<div class="dt-iconboxes-text">'.((!empty($content))?do_shortcode($content):"").'</div>'.'
                        </div></div>';
                    break;
                case '5':
                    $compile='<div class="dt-iconboxes-5 layout-'.$layout_type.'" '. $scollspy.'>
                        <div class="dt-section-icon hi-icon-wrap hi-icon-effect-5 hi-icon-effect-5a">'.((strlen($link)>0) ?"<a target='".$target."' href='".$link."'>":"").'<i class="hi-icon '.$icon_type.'"></i>'.((strlen($link)>0) ?"</a>":"").'</div>
                        <h4'.$color_heading.'>'.$iconbox_heading.'</h4>'.                 
                        '<div class="dt-iconboxes-text">'.((!empty($content))?do_shortcode($content):"").'</div>'.'
                        </div>';
                    break;
                case '6':
                    $compile='<div class="dt-iconboxes layout-'.$layout_type.'" '. $scollspy.'>
                        '.((strlen($link)>0) ?"<a target='".$target."' href='".$link."'>":"").'<i class="'.$icon_type.'"></i>'.((strlen($link)>0) ?"</a>":"").'
                        <h4'.$color_heading.'>'.$iconbox_heading.'</h4><div class="dt-iconboxes-text">
                        '.((!empty($content))?do_shortcode($content):"").'</div></div>';

                    break;
                case '7':
                case '8':
                    $compile='<div class="dt-iconboxes layout-'.$layout_type.'" '. $scollspy.'>
                        '.((strlen($link)>0) ?"<a target='".$target."' href='".$link."'>":"").'<i class="'.$icon_type.'"></i>'.((strlen($link)>0) ?"</a>":"").'
                        <div class="text-box"><h4'.$color_heading.'>'.$iconbox_heading.'</h4>
                        '.((!empty($content))?do_shortcode($content):"").'</div></div>';

                    break;
                default:
                    $compile='<div class="dt-iconboxes layout-'.$layout_type.'" '. $scollspy.'>
                        <span>'.((strlen($link)>0) ?"<a target='".$target."' href='".$link."'>":"").'<i class="'.$icon_type.'"></i>'.((strlen($link)>0) ?"</a>":"").'</span>
                        <h4'.$color_heading.'>'.$iconbox_heading.'</h4><div class="dt-iconboxes-text">'.
                        ((!empty($content))?do_shortcode($content):"").'</div>
                    </div>';

                    break;
            }

            $dt_el_id++;
            $excss="";
            if(''!=$css){
                global $detheme_Style;
                $excss=vc_shortcode_custom_css_class($css);
                $detheme_Style[]=$css;
            }


            return "<div id=\"module_dt_iconboxes_".$dt_el_id."\" class=\"module_dt_iconboxes".(''!=$excss?" ".$excss:"")."\">".$compile."</div>";
}


function vc_dt_team_custom_item($atts, $content = null){

        wp_enqueue_style('detheme-vc');

        if (!isset($compile)) {$compile='';}

        extract(shortcode_atts(array(
            'title' => '',
            'sub_title' => '',
            'text' => '',
            'layout_type'=>'fix',
            'image_url'=>'',
            'facebook'=>'',
            'twitter'=>'',
            'gplus'=>'',
            'pinterest'=>'',
            'linkedin'=>'',
            'website'=>'',
            'email'=>'',
            'spy'=>'none',
            'scroll_delay'=>300,
        ), $atts));

        $scollspy="";
        if('none'!==$spy && !empty($spy)){
            wp_enqueue_style('scroll-spy');
            wp_enqueue_script('ScrollSpy');

            $scollspy='data-uk-scrollspy="{cls:\''.$spy.'\', delay:'.$scroll_delay.'}"';

        }

        $social_lists="<ul class=\"profile-scocial\">".
            (($facebook)?"<li><a href=\"".$facebook."\" target=\"_blank\"><i class=\"icon-facebook-squared\"></i></a></li>":"").
            (($twitter)?"<li><a href=\"".$twitter."\" target=\"_blank\"><i class=\"icon-twitter-squared\"></i></a></li>":"").
            (($gplus)?"<li><a href=\"".$gplus."\" target=\"_blank\"><i class=\"icon-gplus-squared\"></i></a></li>":"").
            (($linkedin)?"<li><a href=\"".$linkedin."\" target=\"_blank\"><i class=\"icon-linkedin-squared\"></i></a></li>":"").
            (($pinterest)?"<li><a href=\"".$pinterest."\" target=\"_blank\"><i class=\"icon-pinterest-squared\"></i></a></li>":"").
            (($website)?"<li><a href=\"".$website."\" target=\"_blank\"><i class=\"icon-globe\"></i></a></li>":"").
            (($email)?"<li><a href=mailto:".$email." target=\"_blank\"><i class=\"icon-mail-alt\"></i></a></li>":"").
            "</ul>";


        if('fix'==$layout_type){


            if(!empty($image_url)){
                $image = wp_get_attachment_image_src($image_url,'full',false); 
                $image_url=$image[0];
            }




            $compile='<div class="left-item"><img src="'.$image_url.'" alt="" /></div>
            <div class="right-item"><h2 class="profile-title">'.$title.'</h2><hr/><h3 class="profile-position">'.$sub_title.'</h3>
            '.(!empty($text)?'<div class="text">'.$text.'</div>':"").$social_lists.'
            </div>';

        }
        else{

            if(!empty($image_url)){
                $image = wp_get_attachment_image_src($image_url,'full',false); 
                $image_url=$image[0];
            }

        $compile='<div class="profile">
                <figure>
                    <div class="top-image">
                        <img src="'.$image_url .'" class="img-responsive" alt=""/>
                    </div>
                    <figcaption>
                        <h3><span class="profile-heading">'.$title.'</span></h3>
                        <span class="profile-subheading">'.$sub_title.'</span>
                        '.(!empty($text)?'<p>'.$text.'</p>':"");

         $compile.= $social_lists.'<div class="figcap"></div>
                    </figcaption>
                </figure>
            </div>';


        }



    return  $compile='<div class="dt_team_custom_item '.$layout_type.'" '.$scollspy.'>'.$compile.'</div>';

}

add_shortcode('dt_team_custom_item','vc_dt_team_custom_item');


function dt_progressbar_item_shortcode($atts, $content = null)

{


    if(is_admin()){

    }else{
        wp_register_script('jquery.appear',DETHEME_VC_DIR_URL."js/jquery.appear.js",array());
        wp_register_script('jquery.counto',DETHEME_VC_DIR_URL."js/jquery.counto.js",array());
        wp_register_script('dt-chart',DETHEME_VC_DIR_URL."js/chart.js",array('jquery.appear','jquery.counto'));
        wp_enqueue_script('dt-chart');
    }
    
    extract( shortcode_atts( array(
        'title'=>''
    ), $atts ) );





    if (!isset($compile)) {$compile='';}



    extract(shortcode_atts(array(

        'icon_type' => '',
        'width' => '',
        'title' => '',
        'unit' => '',
        'color'=>'#1abc9c',
        'bg'=>'#ecf0f1',
        'value' => '10',

    ), $atts));


    if(vc_is_inline()){

        $id="bar_".time()."_".rand(1,99);
    }

    $compile.='<div '.((vc_is_inline())?"id=\"".$id."\" ":"").'class=\'progress_bar\'>
                            <i class="'.$icon_type.'"></i>
                            <div class="progress_info">
                            <h4 class=\'progress_title\'>'.$title.'</h4>
                            <span class=\'progress_number\''.((vc_is_inline())?' style="opacity:1;"':"").'><span>'.$value.'</span></span><span class="progres-unit">'.$unit.'</span>
                            </div>
                            <div '.((vc_is_inline())?'style="background:'.$bg.';"  ':"").'class=\'progress_content_outer\'>
                                <div data-percentage=\''.$value.'\' '.((vc_is_inline())?'style="background:'.$color.';width:'.$value.'%"  ':"").'data-active="'.$color.'" data-nonactive="'.$bg.'" class=\'progress_content\'></div>
                           </div>
                </div>';


    $compile = "<div class='progress_bars'>".$compile."</div>";

    return $compile;

}


if(shortcode_exists('dt_progressbar_item'))
    remove_shortcode('dt_progressbar_item');

add_shortcode('dt_progressbar_item', 'dt_progressbar_item_shortcode');


function dt_circlebar_item_shortcode($atts, $content = null){


    wp_register_script('jquery.appear',DETHEME_VC_DIR_URL."js/jquery.appear.js",array());
    wp_register_script('jquery.counto',DETHEME_VC_DIR_URL."js/jquery.counto.js",array());
    wp_register_script('dt-chart',DETHEME_VC_DIR_URL."js/chart.js",array('jquery.appear','jquery.counto'));


    wp_enqueue_script('dt-chart');

    if (!isset($compile)) {$compile='';}



    extract(shortcode_atts(array(

        'unit' => '',
        'title' => '',
        'item_number'=>'1',
        'value' => '10',
        'size'=>'',
        'color'=>'#19bd9b',
        'bg'=>''

    ), $atts));

    $compile.='<div class="dt_circlebar">
                    <div class=\'pie_chart_holder normal\'>
                            <canvas class="doughnutChart" data-noactive="'.$bg.'" data-size="'.$size.'" data-unit="'.$unit.'" data-active="'.$color.'" data-percent=\''.$value.'\'></canvas>
                    </div>
                    <h4 class="pie-title">'.$title.'</h4>
                    <div class="pie-description"></div>
                </div>';

    return $compile;



}


if(shortcode_exists('dt_circlebar_item'))
    remove_shortcode('dt_circlebar_item');

add_shortcode('dt_circlebar_item', 'dt_circlebar_item_shortcode');


if(shortcode_exists('dt_twitter_slider'))
    remove_shortcode('dt_twitter_slider');

function dt_twitter_slider_shortcode($atts, $content = null){

    global $dt_el_id;

    wp_enqueue_script('dt-chart');

    if (!isset($compile)) {$compile='';}

    if(!isset($dt_el_id)){$dt_el_id=0;}

    $dt_el_id++;



    extract(shortcode_atts(array(

        'twitteraccount' => 'detheme',
        'numberoftweets' => 4,
        'dateformat'=>'%b. %d, %Y',
        'twittertemplate' => '{{date}}<br />{{tweet}}',
        'isautoplay'=>1,
        'transitionthreshold'=>500

    ), $atts));

        $twittertemplate=preg_replace('/\n/', '', trim($twittertemplate));

        if(!is_admin()){

            wp_enqueue_script( 'tweetie', DETHEME_VC_DIR_URL. 'lib/twitter_slider/tweetie.js', array( 'jquery' ), '1.0', false);
            wp_localize_script( 'tweetie', 'tweetie_i18nLocale', array(
              'loading'=>__('Loading...','detheme'),
            ) );

            wp_register_style('owl.carousel',DETHEME_VC_DIR_URL."css/owl_carousel.css",array());
            wp_enqueue_style('owl.carousel');
            wp_register_script( 'owl.carousel', DETHEME_VC_DIR_URL . 'js/owl.carousel.js', array('jquery'), '1.29', false );
            wp_enqueue_script('owl.carousel');

        }

        $compile.='<div id="dt_twitter_'.$dt_el_id.'" class="dt-twitter-slider"></div>';
        $compile.='<script type="text/javascript">';
        $compile.='jQuery(document).ready(function($) {
                \'use strict\';
                
                $(\'#dt_twitter_'.$dt_el_id.'\').twittie({
                    element_id: \'dt_twitter_slider_'.$dt_el_id.'\',
                    username: \''.$twitteraccount.'\',
                    count: '.$numberoftweets.',
                    hideReplies: false,
                    dateFormat: \''.$dateformat.'\',
                    template: \''.$twittertemplate.'\',
                    apiPath: \''. DETHEME_VC_DIR_URL. 'lib/twitter_slider/api/tweet.php\'
                },function(){
                    $(\'#dt_twitter_slider_'.$dt_el_id.'\').owlCarousel({
                        items       : 1, //10 items above 1000px browser width
                        itemsDesktop    : [1000,1], //5 items between 1000px and 901px
                        itemsDesktopSmall : [900,1], // 3 items betweem 900px and 601px
                        itemsTablet : [600,1], //2 items between 600 and 0;
                        itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
                        pagination  : true,
                        autoPlay    : ' . ($isautoplay?"true":"false") . ',
                        slideSpeed  : 200,
                        paginationSpeed  : ' . $transitionthreshold . '
                    });
                });
            });</script>'."\n";

    return $compile;


}

add_shortcode('dt_twitter_slider', 'dt_twitter_slider_shortcode');

if (is_plugin_active('detheme-portfolio/detheme_port.php')) {

function dt_portfolio_shortcode($atts, $content = null){

    global $dt_el_id,$dt_revealData,$detheme_config;

        extract(shortcode_atts(array(

        'portfolio_cat' => '',
        'portfolio_num' => 10,
        'speed'=>800,
        'autoplay'=>'0',
        'spy'=>'none',
        'scroll_delay'=>300,
        'layout'=>'carousel',
        'column'=>4,
        'desktop_column'=>4,
        'small_column'=>4,
        'tablet_column'=>2,
        'mobile_column'=>1,


    ), $atts));

    $queryargs = array(
            'post_type' => 'port',
            'no_found_rows' => false,
            'meta_key' => '_thumbnail_id',
            'posts_per_page'=>$portfolio_num,
            'compile'=>'',
            'script'=>''
        );

    if(!empty($portfolio_cat)){

            $queryargs['tax_query']=array(
                            array(
                                'taxonomy' => 'portcat',
                                'field' => 'id',
                                'terms' =>@explode(",",$portfolio_cat)
                            )
                        );

    }

    $query = new WP_Query( $queryargs );    
    $compile="";

    if ( $query->have_posts() ) :

        if('none'!==$spy && !empty($spy)){


            wp_enqueue_style('scroll-spy');
            wp_enqueue_script('ScrollSpy');
        }

        $spydly=0;
        $portspty=0;


        if(is_admin()){

            }else{

                wp_register_style('owl.carousel',DETHEME_VC_DIR_URL."css/owl_carousel.css",array());
                wp_enqueue_style('owl.carousel');


                wp_register_script( 'owl.carousel', DETHEME_VC_DIR_URL . 'js/owl.carousel.js', array('jquery'), '1.29', false );
                wp_enqueue_script('owl.carousel');

        }
        if(!isset($dt_el_id))
                $dt_el_id=0;

        $dt_el_id++;

        if(vc_is_inline()){

            $dt_el_id.="_".time().rand(0,100);
        }


        $widgetID="dt_portfolio".$dt_el_id;
        $modal_effect = (empty($detheme_config['dt-select-modal-effects'])) ? 'md-effect-15' : $detheme_config['dt-select-modal-effects'];

        $compile='<div class="dt-portfolio-container portfolio-type-'.(($layout=='carousel')?"image":"text").'">
        <div class="owl-carousel-navigation prev-button">
           <a class="btn btn-owl prev btn-color-secondary skin-dark">'.__('<i class="icon-left-open-big"></i>','detheme').'</a>
        </div>
        <div class="owl-carousel" id="'.$widgetID.'">';

                while ( $query->have_posts() ) : 
                
                    $query->the_post();
                    
                    $terms = get_the_terms(get_the_ID(), 'portcat' );
                    $term_lists=array();

                    if ( !empty( $terms ) ) {
      
                          foreach ( $terms as $term ) {
                            $cssitem[] =sanitize_html_class($term->slug, $term->term_id);
                            $term_lists[]="<a href=\"".get_term_link( $term)."\">".$term->name."</a>";
                          }

                    }



                    $imageId=get_post_thumbnail_id(get_the_ID());
                    $featured_image  = get_post( $imageId );



                    if (isset($featured_image->guid)) {
                        $imgurl = aq_resize($featured_image->guid, 0, 300,true);

                        $spydly=$spydly+(int)$scroll_delay;

                        $scollspy="";


                       if('none'!==$spy && !empty($spy) && $portspty < 5){

                            $scollspy='data-uk-scrollspy="{cls:\''.$spy.'\', delay:'.$spydly.'}"';
                        }



                            $compile.='<div class="portfolio-item" '.$scollspy.'>';


                        if('carousel'==$layout){

                           $compile.='<div class="post-image-container">'.((isset($imgurl) && !empty($imgurl))?'<div class="post-image">
                                    <img src="'.$imgurl.'" alt="'.get_the_title().'" /></div>':'').'
                                <div class="imgcontrol tertier_color_bg_transparent">
                                    <div class="portfolio-termlist">'.(count($term_lists)?@implode(', ',$term_lists):"").'</div>
                                    <div class="portfolio-title">'.get_the_title().'</div>
                                    <div class="imgbuttons">
                                        <a class="md-trigger btn icon-zoom-in secondary_color_button" data-modal="modal_portfolio_'.get_the_ID().'" onclick="return false;" '.
                                        'href="'.get_the_permalink().'"></a><a class="btn icon-link secondary_color_button " href="'.get_the_permalink().'"></a>
                                    </div>
                                </div>
                            </div>';
                        

                        }
                        else{

                           $compile.='<div class="post-image-container">'.((isset($imgurl) && !empty($imgurl))?'<div class="post-image">
                                    <img src="'.$imgurl.'" alt="'.get_the_title().'" /></div>':'').'
                                <div class="imgcontrol tertier_color_bg_transparent">
                                    <div class="imgbuttons">
                                        <a class="md-trigger btn icon-zoom-in secondary_color_button" data-modal="modal_portfolio_'.get_the_ID().'" onclick="return false;" '.
                                        'href="'.get_the_permalink().'"></a><a class="btn icon-link secondary_color_button " href="'.get_the_permalink().'"></a>
                                    </div>
                                </div>
                            </div>';

                            $compile.='<div class="portfolio-description">';
                            $compile.='<div class="portfolio-termlist">'.(count($term_lists)?@implode(', ',$term_lists):"").'</div>';
                            $compile.='<div class="portfolio-title">'.get_the_title().'</div>';
                            $compile.='<div class="portfolio-excerpt"><p>'.get_the_excerpt().'</p>';
                            $compile.='<a href="'.get_the_permalink().'" class="read_more" title="'.esc_attr(sprintf(__( 'Detail to %s', 'detheme' ), get_the_title())).'">'.__('Read more', 'detheme').'<i class="icon-right-dir"></i></a>';
                            $compile.='</div></div>';

                        }

                            $compile.='</div>';

                        $modalcontent='<div id="modal_portfolio_'.get_the_ID().'" class="popup-gallery md-modal '.$modal_effect.'">
                                <div class="md-content">'.($featured_image?'<img src="#" rel="'.$featured_image->guid.'" class="img-responsive" alt=""/>':"").'     
                                    <div class="md-description secondary_color_bg">'.get_the_excerpt().'</div>
                                    <button class="secondary_color_button md-close"><i class="icon-cancel"></i></button>
                                </div>
                            </div>';

                            array_push($dt_revealData,$modalcontent);


                        $portspty++;




                      }
                endwhile;

         $compile.="</div>
                     <div class=\"owl-carousel-navigation next-button\">
                       <a class=\"btn btn-owl next btn-color-secondary skin-dark\">".__('<i class="icon-right-open-big"></i>','detheme')."</a>
        </div></div>";

        $script='<script type="text/javascript">'."\n".'jQuery(document).ready(function($) {
            \'use strict\';'."\n".'
            var '.$widgetID.' = jQuery("#'.$widgetID.'");
            try{
           '.$widgetID.'.owlCarousel({
                items       : '.$column.', 
                itemsDesktop    : [1200,'.$desktop_column.'], 
                itemsDesktopSmall : [1023,'.$small_column.'], // 3 items betweem 900px and 601px
                itemsTablet : [768,'.$tablet_column.'], //2 items between 600 and 0;
                itemsMobile : [600,'.$mobile_column.'], // itemsMobile disabled - inherit from itemsTablet option
                pagination  : false,'.($autoplay?'autoPlay:true,':'')."
                slideSpeed  : ".$speed.",paginationSpeed  : ".$speed.",rewindSpeed  : ".$speed.",";
          $script.='});'."\n".'
    '.$widgetID.'.parents(\'.dt-portfolio-container\').find(".next").click(function(){
        '.$widgetID.'.trigger(\'owl.next\');
      });
    '.$widgetID.'.parents(\'.dt-portfolio-container\').find(".prev").click(function(){
        '.$widgetID.'.trigger(\'owl.prev\');
      });';

         $script.='}
            catch(err){}
        });</script>';

     $compile.=$script;   
    endif;
    
    wp_reset_query();
    return $compile;

}

if(shortcode_exists('dt_portfolio'))
    remove_shortcode('dt_portfolio');

   add_shortcode('dt_portfolio', 'dt_portfolio_shortcode');

}

function dt_optin_form_shortcode($atts, $content = null){


   extract(shortcode_atts(array(

        'title'=>'',
        'sub_title'=>'',
        'value'=>'',
        'footer_text'=>'',
        'css'=>'',
        'layout'=>'',
        'button'=>'button1',
        'button_text'=>__('Submit','detheme'),
        'button_text_color'=>'',
        'button_color'=>'',
        'font_size'=>'',
        'vertical_padding'=>'',
        'horizontal_padding'=>'',
        'button_radius'=>'',
        'email_label'=>__( 'Email Label', 'detheme' ),
        'name_label'=>__('Your name','detheme'),
        'button_font'=>'',
        'expanded'=>0,
        'button_align'=>'left',
        'label_align'=>'left',
        'input_radius'=>'',
        'input_vertical_padding'=>'',
        'input_horizontal_padding'=>'',
        'element_margin'=>'',
        'gradient'=>false,
        'gradient_color_to'=>''
    ), $atts));

   wp_register_script( 'optin', DETHEME_VC_DIR_URL . 'js/optin_form.js', array('jquery'), '', false );
   wp_enqueue_script('optin');

   global $detheme_Style,$dt_el_id;

   if(!isset($dt_el_id))
                $dt_el_id=0;

   $dt_el_id++;

  $customcss="";
  $code="";
  $compile="";

  $excss=vc_shortcode_custom_css_class($css);

    if(preg_match_all( '/{(.*)}/s', $css, $matches, PREG_SET_ORDER )){
        $customcss=$matches[0][1];
    }

    $buttoncss=$buttonHovercss=$inputcss=array();

    if(!empty($button_text_color)){
        $buttoncss[]="color:".$button_text_color;
    }
    if(!empty($button_color)){

        if($gradient && !empty($gradient_color_to)){

            $buttoncss[]="background:-webkit-linear-gradient(".$button_color.",".$gradient_color_to.")";
            $buttoncss[]="background:-moz-linear-gradient(".$button_color.",".$gradient_color_to.")";
            $buttoncss[]="background:-ms-linear-gradient(".$button_color.",".$gradient_color_to.")";
            $buttoncss[]="background:-o-linear-gradient(".$button_color.",".$gradient_color_to.")";
            $buttoncss[]="background:linear-gradient(".$button_color.",".$gradient_color_to.")";
        }
        else{
            $buttoncss[]="background-color:".$button_color;
        }

        $buttonHovercss[]="background-color:".darken($button_color,5);

    }
    if(!empty($font_size)){
        $buttoncss[]="font-size:".$font_size."px";
    }

    if(!empty($horizontal_padding)){
        $buttoncss[]="padding-left:".$horizontal_padding."px";
        $buttoncss[]="padding-right:".$horizontal_padding."px";
    }
    if(!empty($vertical_padding)){
        $buttoncss[]="height:".$vertical_padding."px";
        $inputcss[]="height:".$vertical_padding."px";
    }
    if($expanded){
        $buttoncss[]="width:100%";
    }

    if(!empty($button_radius)){
        $buttoncss[]="border-radius:".$button_radius."px";
    }

    if(!empty($button_font)){

        $font = 'http://fonts.googleapis.com/css?family=Droid+Sans%7COpen+Sans%7CTangerine%7CJosefin+Slab%7CArvo%7CLato%7CVollkorn%7CAbril+Fatface%7CUbuntu%7CPT+Sans%7CPT+Serif%7COld+Standard+TT';
        wp_enqueue_style('google-font', $font); 

        $buttoncss[]="font-family:".$button_font;
    }

    if(!empty($element_margin)){
       $element_margin=( preg_match('/horizontal/', $layout)?"padding-right:".$element_margin."px !important":"margin-bottom:".$element_margin."px !important");
    }

    if(!empty($input_horizontal_padding)){
        $inputcss[]="padding-left:".$input_horizontal_padding."px";
        $inputcss[]="padding-right:".$input_horizontal_padding."px";
    }

    if(!empty($label_align)){
        $inputcss[]="text-align:".$label_align;
    }

    if(!empty($input_radius)){
        $inputcss[]="border-radius:".$input_radius."px";
    }


      $formcode='<form role="form" class="'.$layout.( preg_match('/horizontal/', $layout)?" form-inline":"").'" id="dt_optin_form_'.$dt_el_id.'">';
      $formcode.=($layout=='vertical_email' || $layout=='horizontal_email')?"":"<div class=\"form-group field-wrap\"".((vc_is_inline())?" style=\"".$element_margin."\"":"").">";
      $formcode.='<input type="'.(($layout=='vertical_email' || $layout=='horizontal_email')?"hidden":"text").'"'.((vc_is_inline())?' style="'.@implode(";",$inputcss).'"':'').' class="dt_name form-control" name="dt_name" placeholder="'.$name_label.'" />';
      $formcode.=($layout=='vertical_email' || $layout=='horizontal_email')?"":"</div>";
      $formcode.='<div class="form-group field-wrap"'.((vc_is_inline())?" style=\"".$element_margin."\"":"").'><input type="text"'.((vc_is_inline())?' style="'.@implode(";",$inputcss).'"':'').' class="dt_email form-control" name="dt_email"  placeholder="'.$email_label.'" /></div>';
      $formcode.='<div class="form-group button-wrap" style="text-align:'.$button_align.'"><button class="form_connector_submit" '.((vc_is_inline())?'style="'.@implode(";",$buttoncss).'" ':"").'type="submit" value="'.$button_text.'" >'.$button_text.'</button></div>';
      $formcode.='</form>';


        if(''!==$customcss){

            $detheme_Style[]=".".$excss."{".$customcss."}";

            if(count($inputcss)){
                $detheme_Style[]=".".$excss." input,.".$excss." input.dt_name,.".$excss." input.dt_email{".@implode(";",$inputcss)."}";

            }

            $detheme_Style[]=".".$excss." .form_connector_submit{".@implode(";",$buttoncss)."}";

            if(count($buttonHovercss)){

                $detheme_Style[]=".".$excss." .form_connector_submit:hover{".@implode(";",$detheme_Style)."}";
            }

            if(!empty($element_margin)){

                $detheme_Style[]=".".$excss." .field-wrap{".$element_margin."}";
            }

        }else{

            $excss="vc_custom_optin_".$dt_el_id;

            if(count($inputcss)){
                $detheme_Style[]=".".$excss." input,.".$excss." input.dt_name,.".$excss." input.dt_email{".@implode(";",$inputcss)."}";
           }
            if(count($buttonHovercss)){
                $detheme_Style[]=".".$excss." .form_connector_submit:hover{".@implode(";",$buttonHovercss)."}";
            }

            if(!empty($element_margin)){
                $detheme_Style[]=".".$excss." .field-wrap{".$element_margin."}";
            }

            $detheme_Style[]=".".$excss." .form_connector_submit{".@implode(";",$buttoncss)."}";
        }

 
   $compile.="<div class=\"optin-form $excss $button\">";
        if(!empty($title)){ 
        $compile.='<h2 class="optin-heading">'.$title.'</h2>';
        }

        if(!empty($sub_title)){ 
        $compile.='<div class="optin-subheading">'.$sub_title.'</div>';
        } 

   $compile.='<div class="optin-content">'.$formcode.'</div>';

        if(!empty($footer_text)){ 
        $compile.='<div class="optin-footer">'.$footer_text.'</div>';
        } 

   $compile.='<div class="optin_code" >'.html_entity_decode($content).'</div>';
   $compile.="</div>";
   $compile.='<script type="text/javascript">var ajaxurl = \''.admin_url('admin-ajax.php').'\';</script>';

   return $compile;
  


}

add_shortcode('dt_optin_form', 'dt_optin_form_shortcode');

function dt_countdown_shortcode($atts, $content = null){

    global $dt_el_id,$detheme_Style;


   extract(shortcode_atts(array(

        'year'=>'',
        'month'=>'',
        'date'=>'',
        'time'=>date('H:s',current_time( 'timestamp', 0 )),
        'url'=>'',
        'countdown_box_color'=>'',
        'countdown_text_color'=>'',
        'countdown_label_color'=>'',
        'dot_color'=>''

    ), $atts));

    if(!stripos($time, ":")){
        $time.=":00";
    }


    $message=str_replace(array("\n","\t"),array("",""), do_shortcode($content));

    $current_offset = get_option('gmt_offset');

    $dateTo = "$month $date $year $time";

   $timeCurrent = (!empty($current_offset))?time()+($current_offset*3600):time();


   $timeTo= strtotime( $dateTo );

   $compile="";
   if(!isset($dt_el_id)) $dt_el_id=0;

   $dt_el_id++;


   if(vc_is_inline()){
        $dt_el_id.="_".time();
   }


   if( $timeCurrent > $timeTo ){

    if(!empty($url)){

        $compile.='<meta http-equiv="refresh" content="5;URL='.$url.'" />';
    }



   $compile.='<div class="dt-countdown">'.
   '<div id="countdown_'.$dt_el_id.'" class="countdown">'.$message.'</div>'.
   '</div>';


   }
   else{


     $suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    if(is_admin()){

    }else{

    wp_register_style('jquery.countdown',DETHEME_VC_DIR_URL."css/jquery.countdown.css",array(),false);
    wp_enqueue_style('jquery.countdown');

    wp_register_script('jquery.plugin',DETHEME_VC_DIR_URL."js/jquery.plugin".$suffix.".js",array('jquery'));
    wp_enqueue_script('jquery.plugin');
    wp_register_script('jquery.countdown',DETHEME_VC_DIR_URL."js/jquery.countdown".$suffix.".js",array('jquery.plugin'));
    wp_enqueue_script('jquery.countdown');

    wp_localize_script( 'jquery.countdown', 'i18countdown', array(
          'lbl_years'=>__('Years','detheme'),
          'lbl_months'=>__('Months','detheme'),
          'lbl_weeks'=>__('Weeks','detheme'),
          'lbl_days'=>__('Days','detheme'),
          'lbl_hours'=>__('Hours','detheme'),
          'lbl_minutes'=>__('Minutes','detheme'),
          'lbl_seconds'=>__('Seconds','detheme'),
          'lbl_year'=>__('Year','detheme'),
          'lbl_month'=>__('Month','detheme'),
          'lbl_week'=>__('Week','detheme'),
          'lbl_day'=>__('Day','detheme'),
          'lbl_hour'=>__('Hour','detheme'),
          'lbl_minute'=>__('Minute','detheme'),
          'lbl_second'=>__('Second','detheme'),
    ));

    }

    $until=$timeTo-$timeCurrent;

    $compile.="<script type=\"text/javascript\">".'jQuery(document).ready(function($) {'.
            '\'use strict\';'.
            '$(\'#countdown_'.$dt_el_id.'\').countdown({until: +'.$until.
                ((!empty($message) && empty($url))?",expiryText:'".$message."'":"").
                ((!empty($url))?",expiryUrl:'".$url."'":"").'}); '.
            '});</script>';


   $compile.='<div class="dt-countdown">'.
   '<div id="countdown_'.$dt_el_id.'" class="countdown"></div></div>';

       if(!empty($countdown_box_color)){

            $detheme_Style[]='#countdown_'.$dt_el_id.' .countdown-amount{border-color: '.$countdown_box_color.';}';
        }


        if(!empty($countdown_text_color)){
            $detheme_Style[]='#countdown_'.$dt_el_id.' .countdown-amount{color: '.$countdown_text_color.';}';
        }

        if(!empty($countdown_label_color)){
            $detheme_Style[]='#countdown_'.$dt_el_id.' .countdown-period{color: '.$countdown_label_color.';}';
        }

        if(vc_is_inline()){
            $compile.="<style type=\"text/css\">".@implode("\n",$detheme_Style)."</style>";
        }
    }

   return $compile;

}

add_shortcode('dt_countdown', 'dt_countdown_shortcode');



if(shortcode_exists('dt_social'))
    remove_shortcode('dt_social');

function vc_dt_social_shortcode($atts, $content = null){

    global $dt_el_id,$detheme_Style;

   if(!isset($dt_el_id)) $dt_el_id=0;

   $dt_el_id++;

   extract(shortcode_atts(array(

    'facebook'=>'',
    'twitter'=>'',
    'gplus'=>'',
    'pinterest'=>'',
    'linkedin'=>'',
    'color'=>'',
    'shape'=>'circle',
    'size'=>'medium',
    'bg_color'=>'',
    'align'=>'center'

    ), $atts));

   $compile="";
   $colorstyle="";
   $backgroundcolor="";


   if(vc_is_inline()){
       if(''!=$color){
            $colorstyle=" style=\"color:".$color.";\"";
       }
       if(''!=$bg_color){
            $backgroundcolor=" style=\"background-color:".$bg_color.";\"";
       }

   }
   else{

       if(''!=$color){
            $detheme_Style[]="#social-".$dt_el_id." li a{color:".$color.";}";
       }
       if(''!=$bg_color){
            $detheme_Style[]="#social-".$dt_el_id." li{background-color:".$bg_color.";}";
       }
    }

    $compile.="<ul id=\"social-".$dt_el_id."\" class=\"dt-social shape-".$shape." size-".$size." align-".$align."\">".
    (($facebook)?"<li".$backgroundcolor."><a href=\"".$facebook."\" target=\"_blank\"".$colorstyle."><i class=\"icon-facebook\"></i></a></li>":"").
    (($twitter)?"<li".$backgroundcolor."><a href=\"".$twitter."\" target=\"_blank\"".$colorstyle."><i class=\"icon-twitter\"></i></a></li>":"").
    (($gplus)?"<li".$backgroundcolor."><a href=\"".$gplus."\" target=\"_blank\"".$colorstyle."><i class=\"icon-gplus\"></i></a></li>":"").
    (($linkedin)?"<li".$backgroundcolor."><a href=\"".$linkedin."\" target=\"_blank\"".$colorstyle."><i class=\"icon-linkedin\"></i></a></li>":"").
    (($pinterest)?"<li".$backgroundcolor."><a href=\"".$pinterest."\" target=\"_blank\"".$colorstyle."><i class=\"icon-pinterest\"></i></a></li>":"").
    "</ul>";


   return  $compile;

}

add_shortcode('dt_social', 'vc_dt_social_shortcode');
?>