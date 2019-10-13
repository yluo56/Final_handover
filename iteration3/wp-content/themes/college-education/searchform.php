<?php
/**
* Template for displaying search forms in Theme
*/
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'college-education' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'college-education' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="fa search-submit"><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'college-education' ); ?></span></button>
</form>
