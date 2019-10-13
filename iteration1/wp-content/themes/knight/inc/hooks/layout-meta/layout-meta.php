<?php
/**
 * Implement theme metabox.
 *
 * @package Knight
 */

if (!function_exists('knight_add_theme_meta_box')) :

    /**
     * Add the Meta Box
     *
     * @since 1.0.0
     */
    function knight_add_theme_meta_box()
    {

        $apply_metabox_post_types = array('post', 'page');

        foreach ($apply_metabox_post_types as $key => $type) {
            add_meta_box(
                'knight-theme-settings',
                esc_html__('Single Page/Post Settings', 'knight'),
                'knight_render_theme_settings_metabox',
                $type
            );
        }

    }

endif;

add_action('add_meta_boxes', 'knight_add_theme_meta_box');

add_action( 'admin_enqueue_scripts', 'knight_backend_scripts');
if ( ! function_exists( 'knight_backend_scripts' ) ){
    function knight_backend_scripts( $hook ) {
        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
    }
}

if (!function_exists('knight_render_theme_settings_metabox')) :

    /**
     * Render theme settings meta box.
     *
     * @since 1.0.0
     */
    function knight_render_theme_settings_metabox($post, $metabox)
    {

        $post_id = $post->ID;
        $knight_post_meta_value = get_post_meta($post_id);

        // Meta box nonce for verification.
        wp_nonce_field(basename(__FILE__), 'knight_meta_box_nonce');
        // Fetch Options list.
        $page_layout = get_post_meta($post_id, 'knight-meta-select-layout', true);
        $page_image_layout = get_post_meta($post_id, 'knight-meta-image-layout', true);
        $bg_color = ( isset( $knight_post_meta_value['knight_background_color'][0] ) ) ? $knight_post_meta_value['knight_background_color'][0] : '#fafafa';
        $text_color = ( isset( $knight_post_meta_value['knight_text_color'][0] ) ) ? $knight_post_meta_value['knight_text_color'][0] : '#666';
        ?>
        <script>
            jQuery(document).ready(function($){
                $('.color_field').each(function(){
                    $(this).wpColorPicker();
                });
            });
        </script>
        <div id="knight-settings-metabox-container" class="knight-settings-metabox-container">
            <div id="knight-settings-metabox-tab-layout">
                <h4><?php echo esc_html__('Layout Settings', 'knight'); ?></h4>
                <div class="knight-row-content">
                    <p>
                    <div class="pagebox">
                        <p><?php esc_attr_e('Choose a color for your Post Backgorund.', 'knight' ); ?></p>
                        <input class="color_field" type="visible" name="knight_background_color" value="<?php echo esc_html( $bg_color ); ?>"/>
                    </div>
                </p>
                <p>
                    <div class="pagebox">
                        <p><?php esc_attr_e('Choose a color for your Post Text.', 'knight' ); ?></p>
                        <input class="color_field" type="visible" name="knight_text_color" value="<?php echo esc_html( $text_color ); ?>"/>
                    </div>
                </p>
                    <p>
                    <div class="knight-row-content">
                        <label for="knight-meta-checkbox">
                            <input type="checkbox" name="knight-meta-checkbox" id="knight-meta-checkbox"
                                   value="yes" <?php if (isset ($knight_post_meta_value['knight-meta-checkbox'])) checked($knight_post_meta_value['knight-meta-checkbox'][0], 'yes'); ?> />
                            <?php _e('Disable Featured Image As Banner Image', 'knight') ?>
                        </label>
                    </div>
                    </p>
                    <!-- Select Field-->
                    <p>
                        <label for="knight-meta-select-layout" class="knight-row-title">
                            <?php _e('Single Page/Post Layout', 'knight') ?>
                        </label>
                        <select name="knight-meta-select-layout" id="knight-meta-select-layout">
                            <option value="right-sidebar" <?php selected('right-sidebar', $page_layout); ?>>
                                <?php _e('Content - Primary Sidebar', 'knight') ?>
                            </option>
                            <option value="left-sidebar" <?php selected('left-sidebar', $page_layout); ?>>
                                <?php _e('Primary Sidebar - Content', 'knight') ?>
                            </option>
                            <option value="no-sidebar" <?php selected('no-sidebar', $page_layout); ?>>
                                <?php _e('No Sidebar', 'knight') ?>
                            </option>
                        </select>
                    </p>

                    <!-- Select Field-->
                    <p>
                        <label for="knight-meta-image-layout" class="knight-row-title">
                            <?php _e('Single Page/Post Image Layout', 'knight') ?>
                        </label>
                        <select name="knight-meta-image-layout" id="knight-meta-image-layout">
                            <option value="full" <?php selected('full', $page_image_layout); ?>>
                                <?php _e('Full', 'knight') ?>
                            </option>
                            <option value="left" <?php selected('left', $page_image_layout); ?>>
                                <?php _e('Left', 'knight') ?>
                            </option>
                            <option value="right" <?php selected('right', $page_image_layout); ?>>
                                <?php _e('Right', 'knight') ?>
                            </option>
                            <option value="no-image" <?php selected('no-image', $page_image_layout); ?>>
                                <?php _e('No Image', 'knight') ?>
                            </option>
                        </select>
                    </p>
                </div><!-- .knight-row-content -->
            </div><!-- #knight-settings-metabox-tab-layout -->
        </div><!-- #knight-settings-metabox-container -->

        <?php
    }

endif;


if (!function_exists('knight_save_theme_settings_meta')) :

    /**
     * Save theme settings meta box value.
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID.
     * @param WP_Post $post Post object.
     */
    function knight_save_theme_settings_meta($post_id, $post)
    {

        // Verify nonce.
        if (!isset($_POST['knight_meta_box_nonce']) || !wp_verify_nonce($_POST['knight_meta_box_nonce'], basename(__FILE__))) {
            return;
        }

        // Bail if auto save or revision.
        if (defined('DOING_AUTOSAVE') || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
        if (empty($_POST['post_ID']) || $_POST['post_ID'] != $post_id) {
            return;
        }

        // Check permission.
        if ('page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $knight_meta_checkbox = isset($_POST['knight-meta-checkbox']) ? esc_attr($_POST['knight-meta-checkbox']) : '';
        update_post_meta($post_id, 'knight-meta-checkbox', sanitize_text_field($knight_meta_checkbox));

        $knight_meta_select_layout = isset($_POST['knight-meta-select-layout']) ? esc_attr($_POST['knight-meta-select-layout']) : '';
        if (!empty($knight_meta_select_layout)) {
            update_post_meta($post_id, 'knight-meta-select-layout', sanitize_text_field($knight_meta_select_layout));
        }
        $knight_meta_image_layout = isset($_POST['knight-meta-image-layout']) ? esc_attr($_POST['knight-meta-image-layout']) : '';
        if (!empty($knight_meta_image_layout)) {
            update_post_meta($post_id, 'knight-meta-image-layout', sanitize_text_field($knight_meta_image_layout));
        }
        $knight_background_color = (isset($_POST['knight_background_color']) && $_POST['knight_background_color']!='') ? $_POST['knight_background_color'] : '';
        update_post_meta($post_id, 'knight_background_color', $knight_background_color);

        $knight_text_color = (isset($_POST['knight_text_color']) && $_POST['knight_text_color']!='') ? $_POST['knight_text_color'] : '';
        update_post_meta($post_id, 'knight_text_color', $knight_text_color);
    }

endif;

add_action('save_post', 'knight_save_theme_settings_meta', 10, 3);