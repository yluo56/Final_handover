<?php
/*
 * Frontend main rendering class
 * Currently muted 
 */
class PluginContainer{

	public function __construct(){
		add_filter('the_content', array($this, 'addHeyoyaToFooter'));

		$options = get_option('heyoya_options', null);	

		if($options){
			if($options['affiliate_id']){
				add_action('wp_head',array($this,'headCallback'));

				add_filter( "comments_template", array($this,'my_plugin_comment_template') );

				add_filter( 'woocommerce_product_tabs', array($this,'sb_woo_rename_reviews_tab'), 98);	
				add_filter( 'woocommerce_product_tabs', array($this,'sb_woo_new_test_tab'));
			}
		}

		

		
	}


	function sb_woo_rename_reviews_tab($tabs) {
     if (!comments_open()){
		 return tabs;	 
	 }
		
	 unset($tabs['reviews']);

	 return $tabs;
	}

	

	function sb_woo_new_test_tab_content() {
	 	require_once(dirname(__FILE__) . '/../reviews.php');
	}

	function sb_woo_new_test_tab($tabs) {
     if (!comments_open())
     	return $tabs;	 	 				
		
	 $tabs['test_tab'] = array(
	 'title' => __( 'Reviews', 'woocommerce' ),
	 'priority' => 50,
	 'callback' => array($this,'sb_woo_new_test_tab_content')
	 );

	 return $tabs;
	}

	function my_plugin_comment_template( $comment_template ) {
     global $post;

     if (is_feed() || is_home() || !is_singular() || !is_main_query() || 'open' != $post->comment_status)
     	return;

     return dirname(__FILE__) . '/../reviews.php';
	}

	public function headCallback(){
			$options = get_option('heyoya_options', null);	
		?>
			<script type="text/javascript" src="//commerce-static.heyoya.com/b2b/b2b_settings.hey?affId=<?php echo $options['affiliate_id']; ?>"></script>

			<!--<style type="text/css">
				.woocommerce-Tabs-panel--test_tab{
					display: block !important;
					height: 0px !important;
					overflow: hidden;
				}

				.woocommerce-tabs .woocommerce-Tabs-panel--test_tab.removetab{
					display: block;
				}
			</style> -->
		<?php
	}

	function addHeyoyaToFooter($content){				
		if (is_feed() || is_home() || !is_singular() || !is_main_query())
			return $content;

		return $content; 
	}
}

?>
