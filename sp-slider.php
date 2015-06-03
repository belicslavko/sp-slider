<?php
/*
Plugin Name: Simple Post Slider
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Belic Slavko
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/ 


/**
 *  Activation plugin
 */

 global $wpdb;
 
register_activation_hook(__FILE__, 'sp_slider_db_install');

register_deactivation_hook(__FILE__, 'sp_slider_db_uninstall');

define('SP_SLIDER_DB', $wpdb->prefix . 'sp_slider');

define('SP_SLIDER_POST_DB', $wpdb->prefix . 'sp_slider_posts');


/**
 *  Install DB
 */

global $db_version;
$db_version = '1.0';

function sp_slider_db_install()
{
    global $wpdb;
    global $db_version;

    $table_name = $wpdb->prefix . 'sp_slider';

    $table_post = $wpdb->prefix . 'sp_slider_posts';

    $charset_collate = '';

    if (!empty($wpdb->charset)) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if (!empty($wpdb->collate)) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    $sql_slider = "CREATE TABLE IF NOT EXISTS ".SP_SLIDER_DB." (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    time int(11) NOT NULL,
<<<<<<< Updated upstream
    post_type varchar(255) NOT NULL,    
=======
    post_type varchar(255) NOT NULL,
    wrap int(11) NOT NULL,
>>>>>>> Stashed changes
    PRIMARY KEY (id)
    ) $charset_collate";

    $sql_slider_post = "CREATE TABLE IF NOT EXISTS ".SP_SLIDER_POST_DB." (
      id int(11) NOT NULL AUTO_INCREMENT,
      slider_id int(11) NOT NULL,
      post_type varchar(255) NOT NULL,
      post_id int(11) NOT NULL,
      PRIMARY KEY (id)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_slider);
    dbDelta($sql_slider_post);

    add_option('db_version', $db_version);
}

/**
 *  Uninstall DB
 */

function sp_slider_db_uninstall()
{
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS " . SP_SLIDER_DB);
    $wpdb->query("DROP TABLE IF EXISTS " . SP_SLIDER_POST_DB);

}



/**
 *  Plugin css
 */

add_action('admin_head', 'sp_slider_css');

function sp_slider_css()
{
    echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('sp-slider/css/style.css') . '">';
}


/**
 *  Function for JS redirect
 *  @param  $url    url to redirect 
 */

function redirect_js($url){

    echo '<script type="text/javascript">';
    echo 'window.location.href="'.$url.'";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
    echo '</noscript>'; exit;


}

/**
 *  Add to admin menu
 */

add_action('admin_menu', 'sp_slider_page');

function sp_slider_page()
{
    add_menu_page('Post Slider', 'Post Slider', 'manage_options', 'sp-slider', 'sp_slider_index', 'dashicons-images-alt');

}

/**
 *  Route page
 */

function sp_slider_index()
{
    
    // index page view
<<<<<<< Updated upstream
    if(empty($_GET['sp-slider-route']) && empty($_POST['sp-slider-route'])){
=======
    if(!empty($_GET['route']) && !empty($_POST['route'])){
>>>>>>> Stashed changes
        
        sp_slider_list();
        
    }
    
    // edit slide page view
<<<<<<< Updated upstream
    if($_GET['sp-slider-route'] === 'edit'){
=======
    if($_GET['route'] === 'edit'){
>>>>>>> Stashed changes
    
        sp_slider_edit($_GET['slide-id']);
    
    }
    
    // create slide page view
<<<<<<< Updated upstream
    if($_GET['sp-slider-route'] === 'create'){
=======
    if($_GET['route'] === 'create'){
>>>>>>> Stashed changes
    
        sp_slider_create();
    
    }
    
    // edit slider
<<<<<<< Updated upstream
    if($_POST['sp-slider-route'] === 'edit'){
    
        sp_slider_edit_post($_POST['slide-id']);
=======
    if($_POST['route'] === 'edit'){
    
        sp_slider_edit($_POST['slide-id']);
>>>>>>> Stashed changes
    
    }
    
    // create slider
<<<<<<< Updated upstream
    if($_POST['sp-slider-route'] === 'create'){
    
        sp_slider_create_post();
=======
    if($_POST['route'] === 'create'){
    
        sp_slider_create();
>>>>>>> Stashed changes
    
    }
    
    // del slider
<<<<<<< Updated upstream
    if($_GET['sp-slider-route'] === 'del'){
=======
    if($_GET['route'] === 'del'){
>>>>>>> Stashed changes
    
        sp_slider_del($_GET['slide-id']);
    
    }
    
    
    
}

/**
 *  List page
 */

function sp_slider_list()
{
    global $wpdb;
    $slider = $wpdb->get_results( "SELECT * FROM " . SP_SLIDER_DB );
    ?>



        <h1><?php echo __( 'Simple Post Slider', 'sp-slider' ); ?></h1>

        <fieldset class='field-sp-slider-top mtop width50'>
            <legend ><?php echo __( 'List slider', 'sp-slider' ); ?></legend>
            
<<<<<<< Updated upstream
		<form action="admin.php?page=sp-slider&sp-slider-route=create" method="GET">
			
			<input type="hidden" name="page" value="sp-slider">
			<input type="hidden" name="sp-slider-route" value="create">
                            
                    <p>Select post type and create Slider</p>

		    <select name="post_type">
		        <?php $post_types = get_post_types( '', 'names' ); 

		            foreach ( $post_types as $post_type ) {

		                echo '<option>' . $post_type . '</option>';
		            }
		            ?>
		        
		    </select>

		<input type="submit" value="New Slider +">
		</form>
                
                <p>Slider list</p>

            <ul id="listSlider">
                <?php foreach($slider as $s){ ?>

                    <li>
                        <?php echo $s->name; ?> | [sp-post-slider id='<?php echo $s->id; ?>']
                        
                        <a class="edit" href="admin.php?page=sp-slider&sp-slider-route=edit&edit-slider=<?php echo $s->id; ?>">
                        Edit
                        </a>
                        
                        <a class="del" onclick="if (! confirm('Are you sure?')) return false;" href="admin.php?page=sp-slider&sp-slider-route=del&slide-id=<?php echo $s->id; ?>">
                            Del
                        </a>
                    </li>
=======
            <section>
                <?php $post_types = get_post_types( '', 'names' ); 

                    foreach ( $post_types as $post_type ) {

                        echo '<option value="">' . $post_type . '</option>';
                    }
                    ?>
                
            </section>
            <a href="admin.php?page=sp-slider&route=create">New Slider +</a>

            <ul>
                <?php foreach($slider as $s){ ?>

                    <li><?php echo $s->name; ?> | [sp-post-slider id='<?php echo $s->id; ?>'] <a href="admin.php?page=sp-slider&edit-slider=<?php echo $s->id; ?>">Edit</a></li>
>>>>>>> Stashed changes

                <?php } ?>
            </ul>

        </fieldset>

    


<?php    
}


/**
 *  Create slider view
 */

<<<<<<< Updated upstream
 function sp_slider_create()
 {
    $post_type = $_GET['post_type'];
    $args = array(	
	'orderby'          => 'date',
	'order'            => 'DESC',	
	//'post_type'        => $post_type,	
        );
    
    $posts_array = get_posts( $args ); 
    
    ?>
 
    <script>
    
    jQuery(document).ready(function () {
            jQuery("#filter").keyup(function () {

                // Retrieve the input field text and reset the count to zero
                var filter = jQuery(this).val(), count = 0;

                // Loop through the comment list
                jQuery(".selectable ul li").each(function () {

                    // If the list item does not contain the text phrase fade it out
                    if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                        jQuery(this).fadeOut();

                        // Show the list item if the phrase matches and increase the count by 1
                    } else {
                        jQuery(this).show();
                        count++;
                    }
                });

                // Update the count
                var numberItems = count;
                jQuery("#filter-count").text("Post found " + count);
            });
        });
        
    </script>
     
            
            <form action="admin.php?page=sp-slider&sp-slider-route=create" method="POST">
                
                <input type="hidden" name="sp-slider-route" value="create">
                    
                <input type="hidden" name="post_type" value="<?php echo $post_type; ?>">
                                 
                 <fieldset class='field-sp-slider-top width50 mtop'>
            <legend ><?php echo __( 'Slider settings', 'sp-slider' ); ?></legend>
                
                <div>
                    <label><?php echo __('Name', 'sp-slider'); ?></label>
                    <input type="text" name="name" class='form-input-tip' placeholder="Slider name">
                </div>
                
                <div>
                    <label><?php echo __('Time', 'sp-slider'); ?></label>
                    <input type="text" name="time" class='form-input-tip' placeholder="Time to delay between slides">
                </div>
                
                <div>                    
                    <input type="submit" value="<?php echo __('Create', 'sp-slider'); ?>">
                </div>               
                
                 </fieldset>
                 
                <fieldset class='field-sp-slider-top width50'>
                <legend ><?php echo __( 'Select multiple post', 'sp-slider' ); ?></legend>
                
                <div>
                            <label for="exampleInputFile"><?php echo __('Search post', 'none'); ?></label>
                            <input type="text" class="text-input" id="filter" value="" placeholder="<?php echo __('Type keyword(s) here', 'none'); ?>"/><span id="filter-count"></span>
                        </div>

                        <div>
                            <label
                                for="exampleInputFile"><?php echo __('Select post for home slider', 'none'); ?></label>

                            <div class="selectable">
                                <ul>
                                    <?php foreach ($posts_array as $post) { ?>

                                            <li><input type="checkbox" name="posts[]"
                                                       value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?>
                                            </li>

                                    <?php } ?>
                                </ul>
                            </div>

                        </div>
                
                
            
                <div class='mtop'>                    
                    <input type="submit" value="<?php echo __('Create', 'sp-slider'); ?>">
                </div>
                
            </fieldset>   
            </form>
 
      
 <?php
 }
 
 /**
  * Create slider POST
  */
 
 function sp_slider_create_post(){
    
    global $wpdb;
    
    if(!empty($_POST)){

        if(!empty($_POST['name'])){
            $name = $_POST['name'];
        }else{
            $name = '';
        }

        if(!empty($_POST['time'])){
            $time = $_POST['time'];
        }else{
            $time = '';
        }
        
        if(!empty($_POST['post_type'])){
            $post_type = $_POST['post_type'];
        }else{
            $post_type = '';
        }
       
        $posts = $_POST['posts'];
       

        $wpdb->insert(
            SP_SLIDER_DB,
            array(
                'name' => sanitize_text_field($name),
                'time' => sanitize_text_field($time),                
                'post_type' => sanitize_text_field($post_type)                
            )
        );
        
        /*
        // Print last SQL query string
        echo $wpdb->last_query . "<br>";
        // Print last SQL query result
        echo $wpdb->last_result . "<br>";
        // Print last SQL query Error
        echo $wpdb->last_error . "<br>";
        */
        
        $insert_id = $wpdb->insert_id;
        
        $wpdb->delete( SP_SLIDER_POST_DB, array( 'slider_id' => $insert_id ) );
        
        if(!empty($posts)){
            for($i=0; $i<count($posts);$i++){
                
                $wpdb->insert(
                SP_SLIDER_POST_DB,
                    array(
                        'slider_id' => intval($insert_id),
                        'post_id' => intval($posts[$i]),
                        'post_type' => sanitize_text_field($post_type)                
                    )
                );
            }
        }

    }
    
   redirect_js('admin.php?page=sp-slider');die('Loading...');
=======
 function sp_slider_create(){
>>>>>>> Stashed changes
    
 }

/**
 *  Edit slider view
 */

<<<<<<< Updated upstream
 function sp_slider_edit()
 {
    global $wpdb;
    
    $post_type = $_GET['post_type'];
    $slider_id = $_GET['edit-slider'];
    $args = array(	
	'orderby'          => 'date',
	'order'            => 'DESC',	
	'post_type'        => $post_type,	
        );
    
    $posts_array = get_posts( $args );
    
    $results_slider = $wpdb->get_row( 'SELECT * FROM '.SP_SLIDER_DB .' WHERE id = '.$slider_id, OBJECT );
    
    $post_in_slider = $wpdb->get_results( 'SELECT post_id FROM '.SP_SLIDER_POST_DB .' WHERE slider_id = '.$results_slider->id, OBJECT );
    
    $posts_select = array();

    foreach ($post_in_slider as $a) {
        $posts_select[] = $a->post_id;
    }
    
    ?>
 
     
            
            <form action="admin.php?page=sp-slider&sp-slider-route=create" method="POST">
                
                <input type="hidden" name="sp-slider-route" value="edit">
                    
                <input type="hidden" name="post_type" value="<?php echo $results_slider->post_type; ?>">
                
                <input type="hidden" name="slider_id" value="<?php echo $results_slider->id; ?>">
                                 
                 <fieldset class='field-sp-slider-top width50 mtop'>
            <legend ><?php echo __( 'Slider settings', 'sp-slider' ); ?></legend>
                
                <div>
                    <label><?php echo __('Name', 'sp-slider'); ?></label>
                    <input type="text" name="name" class='form-input-tip' value="<?php echo $results_slider->name; ?>">
                </div>
                
                <div>
                    <label><?php echo __('Time', 'sp-slider'); ?></label>
                    <input type="text" name="time" class='form-input-tip' value="<?php echo $results_slider->time; ?>">
                </div>               
                 
                
                <div>                    
                    <input type="submit" value="<?php echo __('Edit', 'sp-slider'); ?>">
                </div>               
                
                 </fieldset>
                 
                <fieldset class='field-sp-slider-top width50'>
                <legend ><?php echo __( 'Select multiple post', 'sp-slider' ); ?></legend>
                
                <div>
                            <label for="exampleInputFile"><?php echo __('Search post', 'none'); ?></label>
                            <input type="text" class="text-input" id="filter" value="" placeholder="<?php echo __('Type keyword(s) here', 'none'); ?>"/><span id="filter-count"></span>
                        </div>

                        <div>
                            <label
                                for="exampleInputFile"><?php echo __('Select post for home slider', 'none'); ?></label>

                            <div class="selectable">
                                <ul>
                                    <?php foreach ($posts_array as $post) { ?>

                                        <?php if (in_array($post->ID, $posts_select)) { ?>

                                            <li><input type="checkbox" name="posts[]" checked="true"
                                                       value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?>
                                            </li>

                                        <?php } else { ?>

                                            <li><input type="checkbox" name="posts[]"
                                                       value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?>
                                            </li>

                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>

                        </div>
            
                <div class="mtop">                    
                    <input type="submit" value="<?php echo __('Edit', 'sp-slider'); ?>">
                </div>
                
            </fieldset>   
            </form>
 
      
 <?php      
 }
 
 /**
  * Create slider POST
  */
 
 function sp_slider_edit_post(){
    
    global $wpdb;   
    
    
    if(!empty($_POST)){
        
        $slider_id = $_POST['slider_id'];

        if(!empty($_POST['name'])){
            $name = $_POST['name'];
        }else{
            $name = '';
        }

        if(!empty($_POST['time'])){
            $time = $_POST['time'];
        }else{
            $time = '';
        }
        
        if(!empty($_POST['post_type'])){
            $post_type = $_POST['post_type'];
        }else{
            $post_type = '';
        }
       
        $posts = $_POST['posts'];
       

        $wpdb->update(
            SP_SLIDER_DB,
            array(
                'name' => sanitize_text_field($name),
                'time' => sanitize_text_field($time),                
                'post_type' => sanitize_text_field($post_type)                
            ),
            array( 'id' => intval($slider_id) )
            
        );
        
        /*
        // Print last SQL query string
        echo $wpdb->last_query . "<br>";
        // Print last SQL query result
        echo $wpdb->last_result . "<br>";
        // Print last SQL query Error
        echo $wpdb->last_error . "<br>";
        */
        

        
        $wpdb->delete( SP_SLIDER_POST_DB, array( 'slider_id' => $slider_id ) );
        
        if(!empty($posts)){
            for($i=0; $i<count($posts);$i++){
                
                $wpdb->insert(
                SP_SLIDER_POST_DB,
                    array(
                        'slider_id' => intval($slider_id),
                        'post_id' => intval($posts[$i]),
                        'post_type' => sanitize_text_field($post_type)                
                    )
                );
            }
        }

    }
    
    redirect_js('admin.php?page=sp-slider');die('Loading...');
    
 }
 
 
 function sp_slider_del($id){
    
    global $wpdb;
    
    $wpdb->delete( SP_SLIDER_DB, array( 'id' => $id ) );
    
    $wpdb->delete( SP_SLIDER_POST_DB, array( 'slider_id' => $id ) );
    
    redirect_js('admin.php?page=sp-slider');die('Loading...');
    
 }
=======
 function sp_slider_edit(){
    
    
 }
>>>>>>> Stashed changes
