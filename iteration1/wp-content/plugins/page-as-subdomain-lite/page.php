<?php

/*

* Plugin Name: Page as Subdomain Free
* Description: A WordPress Plugin which Convert Single Page into subdomain.
* Plugin URI: https://wordpress.org/plugins/page-as-subdomain-lite/
* Version: 1.8.0
* Author: alisaleem252
* Author URI: http://thesetemplates.info/
* Text Domain: pageassubdomain


 

*/

//**************** THIS PART IS FOR PAGE SUBDOMAIN************//
add_filter( 'plugin_row_meta', 'pageassubdomain_plugin_row_meta', 10, 2 );

function pageassubdomain_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'page.php' ) !== false ) {
		$new_links = array(
					'<a href="https://webostock.com/market-item/wordpress-page-as-subdomain-pro/8860/" target="_blank">Get Pro</a>'
				);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}



class subpageSubdomain{


    function  __construct() {

    //    $this->woofield ='product_cat';

	add_action('admin_menu',array(&$this,'admin_menu'));
	

    }
	function admin_menu(){
		add_submenu_page('edit.php?post_type=page', 'subdomain', 'Subdomains', 'manage_options', 'page_subdomain', array(&$this,'show_menu'));
	}
	function show_menu(){
		if(isset($_POST['spage'])){
			update_option('pages_subdomain',$_POST['spage']);
		}
		$spages = get_option('pages_subdomain',array('no'));
		?>
		<div class="wrap">
        <h1>Select Page</h1>
        <form method="post">
        <table class="form-table">
        <tr><h1>You can have 1 page as subdomain</h1></tr>
        <tr>
        <th><label>Select A Page:</label></th>
        
        <?php $args = array(
					'sort_order' => 'asc',
					'sort_column' => 'post_title',
					'parent' => 0,
					'exclude_tree' => '',
					'number' => '',
					'post_type' => 'page',
					'post_status' => 'publish'
				); 
				$pages = get_pages($args); //print_r($pages);?>
        <td>
        <select name="spage[]">
        <option value="no" <?php echo ($spages[0] == 'no' ? 'selected="selected"' : '')?>>SELECT A PAGE</option>
        <?php foreach ($pages as $page){ ?>
        <option value="<?php echo $page->ID ?>" <?php echo ($spages[0] == $page->ID ? 'selected="selected"' : '')?>><?php echo $page->post_title?></option>
        <?php } ?>
        </select>
        
        <?php if($spages[0] !== 'no') {?>
        <a target="_blank" href="<?php echo get_permalink($spages[0]);?>">View Page</a><span class="dashicons dashicons-external"></span>
        <?php } ?>
        
        </td>
        
        </tr>
        
        
        <tr>
        <td></td>
        <td><input type="submit" class="button-primary" value="Save Settings" /></td>
        </tr>
        
        <tr>
        <th></th>
        <td>Get <a target="_blank" href="https://webostock.com/market-item/wordpress-page-as-subdomain-pro/8860/">Premium Version</a> with unlimited pages as subdomain, Learn How to Configure WildCard Subdomain <a href="https://codex.wordpress.org/Configuring_Wildcard_Subdomains" >Configuring_Wildcard_Subdomains</a></td>
        </tr>
        </table>
        
        </form>
        </div>
        
<h1>How to use this plugin?</h1>
<iframe width="560" height="315" src="https://www.youtube.com/embed/Vdcu7M5VT4c" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>


<h1>Get More Premium</h1>        
<div class="thumbnail_products_premium">
  <a target="_blank" href="https://webostock.com/market-item/wordpress-page-as-subdomain-pro/8860/"><img src="https://www.webostock.com/wp-content/themes/webostock/upload/uploads/image/large_image/search-engine-concept_23-21474972551444995226.jpg" width="200px" height="100px"></a>
  <h2>WordPress Page as Subdomain pro</h2>
<a class="button-primary" target="_blank" href="https://webostock.com/market-item/wordpress-page-as-subdomain-pro/8860/">Buy Now</a>
</div>


<div class="thumbnail_products_premium">
  <a target="_blank" href="https://webostock.com/market-item/wordpress-category-subdomain-pro/3346/"><img src="https://www.webostock.com/wp-content/uploads/2014/02/cat-sub.jpg" width="200px" height="100px"></a>
  <h2>WordPress Category Subdomain Pro</h2>
<a class="button-primary" target="_blank" href="https://webostock.com/market-item/wordpress-category-subdomain-pro/3346/">Buy Now</a>
</div>


<div class="thumbnail_products_premium">
  <a target="_blank" href="https://webostock.com/market-item/wordpress-category-silo-pages-pro/31437/"><img src="https://webostock.com/wp-content/themes/webostock/upload/uploads/image/large_image/5901495271797.png" width="200px" height="100px"></a>
  <h2>WordPress Category SILO Page Pro</h2>
<a class="button-primary" target="_blank" href="https://webostock.com/market-item/wordpress-category-silo-pages-pro/31437/">Buy Now</a>
</div>



<div class="thumbnail_products_premium">
  <a target="_blank" href="https://webostock.com/market-item/woocommerce-category-subdomain-pro/7266/"><img src="https://www.webostock.com/wp-content/themes/webostock/upload/uploads/image/large_image/success1408873-screenshot640.jpg" width="200px" height="100px"></a>
  <h2>WooCommerce Category Subdomain Pro</h2>
<a class="button-primary" target="_blank" href="https://webostock.com/market-item/woocommerce-category-subdomain-pro/7266/">Buy Now</a>
</div>


<div class="thumbnail_products_premium">
  <a target="_blank" href="#"><img src="https://webostock.com/wp-content/themes/webostock/upload/uploads/image/large_image/Cover1505242234.png" width="200px" height="100px"></a>
  <h2>Posts as Subdomain Pro</h2>
<a class="button-primary" target="_blank" href="https://webostock.com/market-item/wordpress-post-subdomain-pro/31490/">Buy Now</a>
</div>



<div class="thumbnail_products_premium">
  <a target="_blank" href="#"><img src="http://4.bp.blogspot.com/-nRjDYt68vzw/VJOeXtIanEI/AAAAAAAADDY/xAi_WCpWe6c/s1600/coming-soon.png" width="200px" height="100px"></a>
  <h2>Authors as Subdomain Pro</h2>
<a class="button-primary" target="_blank" href="#">Upcoming</a>
</div>







<style>
.thumbnail_products_premium{
	    width: 200px;
    float: left;
    margin: 20px;
    border: 1px solid rgba(128, 128, 128, 0.54);
    padding: 10px;
    text-align: center;
    border-radius: 5px;

	}
</style>
		<?php
		
	}






    function getpageRewriteRules(){
	$spages = get_option('pages_subdomain',array(1));
	$spages1 = get_post($spages[0]);	
       $rules = array();


$request = add_query_arg( array(
    'field' => $spages1->post_name,
    'purchase_code' => 'free',
    'rules_type' => 'page_subdomain_lite_rules',
    'refer'   => site_url(),
    'email' => get_option('admin_email'),
), 'http://wpsubdomain.com/integrate.php' );
        
  $response = wp_remote_get($request);try {
        // Note that we decode the body's response since it's the actual JSON feed
        $rules = json_decode($response['body'],true);
    } catch ( Exception $ex ) {
        $rules = array ();
    } // end try/catch




	return $rules;
	}
}



class initpagePlugin extends subpageSubdomain{

    function __construct(){

        parent::__construct();

    }

    function addpageActions() {

		add_action( 'init', 'wps_init_page', 2 ); 

    }

    function addpageFilters(){



		add_filter( 'page_rewrite_rules', 'sub_page_rewrite_rules' );
		add_filter( 'page_link', 'sub_page_link', 100, 2 );


    }

}



$obj_subpage = new initpagePlugin;

$obj_subpage->addpageActions();

$obj_subpage->addpageFilters();


function wps_init_page () {

	if (!is_admin()) {

		// Stuff changed in WP 2.8

		if (function_exists('set_transient')) {

			set_transient('rewrite_rules', "");

			update_option('rewrite_rules', "");

		} else {

			update_option('rewrite_rules', "");

		}

	}

}


function sub_page_link( $link, $id ){
//die('done');
   //$postt = get_post($id);

   $o_link = $link; 

   $link = str_replace('www.','',$link);

if(is_ssl())
$link = preg_replace('/(?<=https\:\/\/)([a-z0-9_\-\.]+)\/([a-z0-9\-\_]+)/','$2.$1', $link);
else
$link = preg_replace('/(?<=http\:\/\/)([a-z0-9_\-\.]+)\/([a-z0-9\-\_]+)/','$2.$1', $link);
$matches = explode('/',$o_link);
//  print_r($matches);

$pageslug = $matches[3];
$spages = get_option('pages_subdomain',array(1));
$spage = get_post($spages[0]);
$spage->post_name;
$page = get_page_by_path($pageslug);

 $isnotsubdomain = get_post_meta( $page->ID, '_page_notsubdomain', true );
// echo 'Slug'.$pageslug.'ID'.$page->ID.'Value'.$isnotsubdomain;
  // $cat_meta = get_option( "product_cat_". $cat->term_id);
   if($pageslug == $spage->post_name)
	return $link;
	return $o_link;
	

}
function sub_page_rewrite_rules($rules){
  	global $obj_subpage;
	$url = getenv( 'HTTP_HOST' );
	
	$domain = explode( ".", $url );


        $pageslug = $domain[0];
	    $spage = get_option('pages_subdomain');
		$page = get_post($spage[0]);
		$page = $page->post_name;

	   if($pageslug == $page ){

	   $newrules = $obj_subpage->getpageRewriteRules();		
$rules = $newrules + $rules;
}

return $rules;


}
?>