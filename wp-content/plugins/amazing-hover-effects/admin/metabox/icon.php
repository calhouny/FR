<?php
 
function add_menu_icon_styles(){
?>
 
<style>
#adminmenu .menu-icon-hover-effect div.wp-menu-image:before {
content: '\f105';
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_menu_icon_styles' );
?>