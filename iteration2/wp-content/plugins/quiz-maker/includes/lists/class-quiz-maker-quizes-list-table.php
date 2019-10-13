<?php
ob_start();
class Quizes_List_Table extends WP_List_Table{
    private $plugin_name;
    /** Class constructor */
    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
        parent::__construct( array(
            'singular' => __( 'Quiz', $this->plugin_name ), //singular name of the listed records
            'plural'   => __( 'Quizzes', $this->plugin_name ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );
        add_action( 'admin_notices', array( $this, 'quiz_notices' ) );
    }


    /**
     * Override of table nav to avoid breaking with bulk actions & according nonce field
     */
    public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            
            <div class="alignleft actions">
                <?php  $this->bulk_actions( $which ); ?>
            </div>
            <?php
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    /**
     * Disables the views for 'side' context as there's not enough free space in the UI
     * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
     *
     * @see WP_List_Table::extra_tablenav()
     */
    public function extra_tablenav($which) {
        ?>
        
        <?php
    }
    
    protected function get_views() {
        $published_count = $this->published_quizzes_count();
        $unpublished_count = $this->unpublished_quizzes_count();
        $all_count = $this->all_record_count();
        $selected_all = "";
        $selected_0 = "";
        $selected_1 = "";
        if(isset($_GET['fstatus'])){
            switch($_GET['fstatus']){
                case "0":
                    $selected_0 = " style='font-weight:bold;' ";
                    break;
                case "1":
                    $selected_1 = " style='font-weight:bold;' ";
                    break;
                default:
                    $selected_all = " style='font-weight:bold;' ";
                    break;
            }
        }else{
            $selected_all = " style='font-weight:bold;' ";
        }
        $status_links = array(
            "all" => "<a ".$selected_all." href='?page=".esc_attr( $_REQUEST['page'] )."'>All (".$all_count.")</a>",
            "published" => "<a ".$selected_1." href='?page=".esc_attr( $_REQUEST['page'] )."&fstatus=1'>Published (".$published_count.")</a>",
            "unpublished"   => "<a ".$selected_0." href='?page=".esc_attr( $_REQUEST['page'] )."&fstatus=0'>Unpublished (".$unpublished_count.")</a>"
        );
        return $status_links;
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_quizes( $per_page = 20, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_quizes";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
        }else{
            $sql .= ' ORDER BY ordering DESC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }


    public function get_published_questions(){
        global $wpdb;
        $sql = "SELECT 
                    {$wpdb->prefix}aysquiz_questions.id, 
                    {$wpdb->prefix}aysquiz_questions.question, 
                    {$wpdb->prefix}aysquiz_questions.type, 
                    {$wpdb->prefix}aysquiz_questions.create_date,
                    {$wpdb->prefix}aysquiz_questions.options,
                    {$wpdb->prefix}aysquiz_categories.title
                FROM {$wpdb->prefix}aysquiz_questions
                INNER JOIN {$wpdb->prefix}aysquiz_categories
                ON {$wpdb->prefix}aysquiz_questions.category_id={$wpdb->prefix}aysquiz_categories.id
                WHERE {$wpdb->prefix}aysquiz_questions.published = 1 ORDER BY id DESC;";

        $results = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $results;

    }

    public function get_quiz_categories(){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_quizcategories";

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public function get_question_categories(){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_categories";

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public function get_published_questions_by($key, $value) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_questions WHERE {$key} = {$value};";

        $results = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $results;

    }

    public function get_quiz_by_id( $id ){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_quizes WHERE id=" . absint( intval( $id ) );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }

    public function add_or_edit_quizes($data){
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_quizes';
        $ays_change_type = (isset($data['ays_change_type']))?$data['ays_change_type']:'';
        if( isset($data["quiz_action"]) && wp_verify_nonce( $data["quiz_action"],'quiz_action' ) ){
            $id                         = ( $data["id"] != NULL ) ? absint( intval( $data["id"] ) ) : null;
            $max_id                     = $this->get_max_id();
            $title                      = stripslashes( $data['ays_quiz_title'] );
            $description                = stripslashes($data['ays_quiz_description']);
            $quiz_category_id           = absint( intval( $data['ays_quiz_category'] ) );
            $question_ids               = sanitize_text_field( $data['ays_added_questions'] );
            $published                  = absint( intval( $data['ays_publish'] ) );
            $ordering                   = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
            $image                      = $data['ays_quiz_image'];
            if(isset($data['ays_enable_restriction_pass']) && $data['ays_enable_restriction_pass'] == "on"){
                $ays_enable_logged_users = "on";
            }elseif(isset($data['ays_enable_logged_users']) && $data['ays_enable_logged_users'] == "on"){
                $ays_enable_logged_users = "on";
            }else{
                $ays_enable_logged_users = "off";
            }
            $ays_form_name              = !isset($data['ays_form_name']) ? null : $data['ays_form_name'];
            $ays_form_email             = !isset($data['ays_form_email']) ? null : $data['ays_form_email'];
            $ays_form_phone             = !isset($data['ays_form_phone']) ? null : $data['ays_form_phone'];
            $enable_correction          = !isset($data['ays_enable_correction']) ? "off" : $data['ays_enable_correction'];
            $enable_progressbar         = !isset($data['ays_enable_progress_bar']) ? "off" : $data['ays_enable_progress_bar'];
            $enable_questions_result    = !isset($data['ays_enable_questions_result']) ? "off" : $data['ays_enable_questions_result'];
            $enable_random_questions    = !isset($data['ays_enable_randomize_questions']) ? "off" : $data['ays_enable_randomize_questions'];
            $enable_random_answers      = !isset($data['ays_enable_randomize_answers']) ? "off" : $data['ays_enable_randomize_answers'];
            $enable_questions_counter   = !isset($data['ays_enable_questions_counter']) ? "off" : $data['ays_enable_questions_counter'];
            $enable_restriction_pass    = !isset($data['ays_enable_restriction_pass']) ? "off" : $data['ays_enable_restriction_pass'];
            $limit_users                = !isset($data['ays_limit_users']) ? "off" : $data['ays_limit_users'];
            $enable_rtl                 = !isset($data['ays_enable_rtl_direction']) ? "off" : $data['ays_enable_rtl_direction'];
            $question_bank              = !isset($data['ays_enable_question_bank']) ? "off" : $data['ays_enable_question_bank'];
            $live_progressbar           = !isset($data['ays_enable_live_progress_bar']) ? "off" : $data['ays_enable_live_progress_bar'];
            $percent_view               = !isset($data['ays_enable_percent_view']) ? "off" : $data['ays_enable_percent_view'];
            $avarage_statistical        = !isset($data['ays_enable_average_statistical']) ? "off" : $data['ays_enable_average_statistical'];
            $next_button                = !isset($data['ays_enable_next_button']) ? "off" : $data['ays_enable_next_button'];
            $prev_button                = !isset($data['ays_enable_previous_button']) ? "off" : $data['ays_enable_previous_button'];
            $enable_arrows              = !isset($data['ays_enable_arrows']) ? "off" : $data['ays_enable_arrows'];
            $quiz_theme                 = !isset($data['ays_quiz_theme'])?null:$data['ays_quiz_theme'];
            $social_buttons             = !isset($data['ays_social_buttons']) ? "off" : $data['ays_social_buttons'];
            $enable_logged_users_mas    = !isset($data['ays_enable_logged_users_message'])?null:$data['ays_enable_logged_users_message'];
            $enable_pass_count          = !isset($data['ays_enable_pass_count']) ? "off" : $data['ays_enable_pass_count'];
            $hide_score                 = !isset($data['ays_hide_score']) ? "off" : $data['ays_hide_score'];
            $rate_form_title            = !isset($data['ays_rate_form_title'])?'':$data['ays_rate_form_title'];
            $quiz_box_shadow_color      = !isset($data['ays_quiz_box_shadow_color'])?'':$data['ays_quiz_box_shadow_color'];
            $quiz_border_radius         = !isset($data['ays_quiz_border_radius'])?'':$data['ays_quiz_border_radius'];
            $quiz_bg_image              = !isset($data['ays_quiz_bg_image'])?'':$data['ays_quiz_bg_image'];
            $quiz_border_width          = !isset($data['ays_quiz_border_width'])?'':$data['ays_quiz_border_width'];
            $quiz_border_style          = !isset($data['ays_quiz_border_style'])?'':$data['ays_quiz_border_style'];
            $quiz_border_color          = !isset($data['ays_quiz_border_color'])?'':$data['ays_quiz_border_color'];            
            $quiz_loader                = !isset($data['ays_quiz_loader'])?'':$data['ays_quiz_loader'];
            
            $quiz_create_date           = !isset($data['ays_quiz_ctrate_date']) ? '0000-00-00 00:00:00' : $data['ays_quiz_ctrate_date'];
            $quest_animation            = !isset($data['ays_quest_animation']) ? 'shake' : $data['ays_quest_animation'];
            $author = isset($data['ays_quiz_author'])?stripslashes($data['ays_quiz_author']):'';
            $author = json_decode($author, true);
            
            $form_title                 = !isset($data['ays_form_title'])?'':$data['ays_form_title'];
            $limit_user_roles           = !isset($data['ays_users_roles'])?array():$data['ays_users_roles'];
            
            $enable_bg_music            = (isset($data['ays_enable_bg_music']) && $data['ays_enable_bg_music'] == "on") ? "on" : "off";
            $quiz_bg_music              = (isset($data['ays_quiz_bg_music']) && $data['ays_quiz_bg_music'] != "") ? $data['ays_quiz_bg_music'] : "";
            $answers_font_size          = (isset($data['ays_answers_font_size']) && $data['ays_answers_font_size'] != "") ? $data['ays_answers_font_size'] : "";
            
            $show_create_date = (isset($data['ays_show_create_date']) && $data['ays_show_create_date'] == "on") ? "on" : "off";
            $show_author = (isset($data['ays_show_author']) && $data['ays_show_author'] == "on") ? "on" : "off";
            $enable_early_finish = (isset($data['ays_enable_early_finish']) && $data['ays_enable_early_finish'] == "on") ? "on" : "off";
            $answers_rw_texts = isset($data['ays_answers_rw_texts']) ? $data['ays_answers_rw_texts'] : 'on_passing';
            $disable_store_data = (isset($data['ays_disable_store_data']) && $data['ays_disable_store_data'] == "on") ? "on" : "off";
            
            // Background gradient
            $enable_background_gradient = ( isset( $data['ays_enable_background_gradient'] ) && $data['ays_enable_background_gradient'] == 'on' ) ? 'on' : 'off';
            $quiz_background_gradient_color_1 = !isset($data['ays_background_gradient_color_1']) ? '' : $data['ays_background_gradient_color_1'];
            $quiz_background_gradient_color_2 = !isset($data['ays_background_gradient_color_2']) ? '' : $data['ays_background_gradient_color_2'];
            $quiz_gradient_direction = !isset($data['ays_quiz_gradient_direction']) ? '' : $data['ays_quiz_gradient_direction'];
            
            // Redirect after submit
            $redirect_after_submit = ( isset( $data['ays_redirect_after_submit'] ) && $data['ays_redirect_after_submit'] == 'on' ) ? 'on' : 'off';
            $submit_redirect_url = !isset($data['ays_submit_redirect_url']) ? '' : $data['ays_submit_redirect_url'];
            $submit_redirect_delay = !isset($data['ays_submit_redirect_delay']) ? '' : $data['ays_submit_redirect_delay'];

            // Progress bar
            $progress_bar_style = (isset($data['ays_progress_bar_style']) && $data['ays_progress_bar_style'] != "") ? $data['ays_progress_bar_style'] : 'first';

            // EXIT button in finish page            
            $enable_exit_button = (isset($data['ays_enable_exit_button']) && $data['ays_enable_exit_button'] == 'on') ? "on" : "off";
            $exit_redirect_url = isset($data['ays_exit_redirect_url']) ? $data['ays_exit_redirect_url'] : '';

            // Question image sizing
            $image_sizing = (isset($data['ays_image_sizing']) && $data['ays_image_sizing'] != "") ? $data['ays_image_sizing'] : 'cover';

            // Quiz background image position
            $quiz_bg_image_position = (isset($data['ays_quiz_bg_image_position']) && $data['ays_quiz_bg_image_position'] != "") ? $data['ays_quiz_bg_image_position'] : 'center center';

            // Custom class for quiz container
            $custom_class = (isset($data['ays_custom_class']) && $data['ays_custom_class'] != "") ? $data['ays_custom_class'] : '';

            // Social Media links
            $enable_social_links = (isset($data['ays_enable_social_links']) && $data['ays_enable_social_links'] == "on") ? 'on' : 'off';
            $ays_social_links = (isset($data['ays_social_links'])) ? $data['ays_social_links'] : array(
                'linkedin_link' => '',
                'facebook_link' => '',
                'twitter_link' => ''
            );
            
            $linkedin_link = isset($ays_social_links['ays_linkedin_link']) && $ays_social_links['ays_linkedin_link'] != '' ? $ays_social_links['ays_linkedin_link'] : '';
            $facebook_link = isset($ays_social_links['ays_facebook_link']) && $ays_social_links['ays_facebook_link'] != '' ? $ays_social_links['ays_facebook_link'] : '';
            $twitter_link = isset($ays_social_links['ays_twitter_link']) && $ays_social_links['ays_twitter_link'] != '' ? $ays_social_links['ays_twitter_link'] : '';
            $social_links = array(
                'linkedin_link' => $linkedin_link,
                'facebook_link' => $facebook_link,
                'twitter_link' => $twitter_link
            );

            $options = array(
                'color'                         =>  sanitize_text_field( $data['ays_quiz_color'] ),
                'bg_color'                      =>  sanitize_text_field( $data['ays_quiz_bg_color'] ),
                'text_color'                    =>  sanitize_text_field( $data['ays_quiz_text_color'] ),
                'height'                        =>  absint( intval( $data['ays_quiz_height'] ) ),
                'width'                         =>  absint( intval( $data['ays_quiz_width'] ) ),
                'enable_logged_users'           =>  $ays_enable_logged_users,
                'information_form'              =>  $data['ays_information_form'],
                'form_name'                     =>  $ays_form_name,
                'form_email'                    =>  $ays_form_email,
                'form_phone'                    =>  $ays_form_phone,
                'image_width'                   =>  $data['ays_image_width'],
                'image_height'                  =>  $data['ays_image_height'],
                'enable_correction'             =>  $enable_correction,
                'enable_progress_bar'           =>  $enable_progressbar,
                'enable_questions_result'       =>  $enable_questions_result,
                'randomize_questions'           =>  $enable_random_questions,
                'randomize_answers'             =>  $enable_random_answers,
                'enable_questions_counter'      =>  $enable_questions_counter,
                'enable_restriction_pass'       =>  $enable_restriction_pass,
                'restriction_pass_message'      =>  $data['restriction_pass_message'],
                'user_role'                     =>  $limit_user_roles,                
                'custom_css'                    =>  $data['ays_custom_css'],
                'limit_users'                   =>  $limit_users,
                'limitation_message'            =>  $data['ays_limitation_message'],
                'redirect_url'                  =>  $data['ays_redirect_url'],
                'redirection_delay'             =>  intval($data['ays_redirection_delay']),
                'answers_view'                  =>  $data['ays_answers_view'],
                'enable_rtl_direction'          =>  $enable_rtl,
                'enable_logged_users_message'   =>  $enable_logged_users_mas,
                'questions_count'               =>  $data['ays_questions_count'],
                'enable_question_bank'          =>  $question_bank,
                'enable_live_progress_bar'      =>  $live_progressbar,
                'enable_percent_view'           =>  $percent_view,
                'enable_average_statistical'    =>  $avarage_statistical,
                'enable_next_button'            =>  $next_button,
                'enable_previous_button'        =>  $prev_button,
                'enable_arrows'                 =>  $enable_arrows,
                'timer_text'                    =>  $data['ays_timer_text'],
                'quiz_theme'                    =>  $quiz_theme,
                'enable_social_buttons'         =>  $social_buttons,
                'result_text'                   =>  stripslashes($data['ays_result_text']),
                'enable_pass_count'             =>  $enable_pass_count,
                'hide_score'                    =>  $hide_score,
                'rate_form_title'               =>  $rate_form_title,
                'box_shadow_color'              =>  $quiz_box_shadow_color,
                'quiz_border_radius'            =>  $quiz_border_radius,
                'quiz_bg_image'                 =>  $quiz_bg_image,
                'quiz_border_width'             =>  $quiz_border_width,
                'quiz_border_style'             =>  $quiz_border_style,
                'quiz_border_color'             =>  $quiz_border_color,
                'quiz_loader'                   =>  $quiz_loader,
                'create_date'                   =>  $quiz_create_date,
                'author'                        =>  $author,
                'quest_animation'               =>  $quest_animation,
                'form_title'                    =>  $form_title,
                'enable_bg_music'               =>  $enable_bg_music,
                'quiz_bg_music'                 =>  $quiz_bg_music,
                'answers_font_size'             =>  $answers_font_size,
                'show_create_date'              =>  $show_create_date,
                'show_author'                   =>  $show_author,
                'enable_early_finish'           =>  $enable_early_finish,
                'answers_rw_texts'              =>  $answers_rw_texts,
                'disable_store_data'            =>  $disable_store_data,
                'enable_background_gradient'    =>  $enable_background_gradient,
                'background_gradient_color_1'   =>  $quiz_background_gradient_color_1,
                'background_gradient_color_2'   =>  $quiz_background_gradient_color_2,
                'quiz_gradient_direction'       =>  $quiz_gradient_direction,
                'redirect_after_submit'         =>  $redirect_after_submit,
                'submit_redirect_url'           =>  $submit_redirect_url,
                'submit_redirect_delay'         =>  $submit_redirect_delay,
                'progress_bar_style'            =>  $progress_bar_style,
                'enable_exit_button'            =>  $enable_exit_button,
                'exit_redirect_url'             =>  $exit_redirect_url,
                'image_sizing'                  =>  $image_sizing,
                'quiz_bg_image_position'        =>  $quiz_bg_image_position,
                'custom_class'                  =>  $custom_class,
                'enable_social_links'           =>  $enable_social_links,
                'social_links'                  =>  $social_links,
            );
            $options['required_fields'] = !isset($data['ays_required_field']) ? null : $data['ays_required_field'];
            if( isset( $data['ays_enable_timer'] ) && $data['ays_enable_timer'] == 'on' ){
                $options['enable_timer'] = 'on';
            }else{                
                $options['enable_timer'] = 'off';
            }
            $options['enable_quiz_rate'] = ( isset( $data['ays_enable_quiz_rate'] ) && $data['ays_enable_quiz_rate'] == 'on' ) ? 'on' : 'off';
            $options['enable_rate_avg'] = ( isset( $data['ays_enable_rate_avg'] ) && $data['ays_enable_rate_avg'] == 'on' ) ? 'on' : 'off';
            $options['enable_box_shadow'] = ( isset( $data['ays_enable_box_shadow'] ) && $data['ays_enable_box_shadow'] == 'on' ) ? 'on' : 'off';
            $options['enable_border'] = ( isset( $data['ays_enable_border'] ) && $data['ays_enable_border'] == 'on' ) ? 'on' : 'off';
            $options['quiz_timer_in_title'] = ( isset( $data['ays_quiz_timer_in_title'] ) && $data['ays_quiz_timer_in_title'] == 'on' ) ? 'on' : 'off';
            
            $options['enable_rate_comments'] = ( isset( $data['ays_enable_rate_comments'] ) && $data['ays_enable_rate_comments'] == 'on' ) ? 'on' : 'off';
            
            $options['enable_restart_button'] = ( isset( $data['ays_enable_restart_button'] ) && $data['ays_enable_restart_button'] == 'on' ) ? 'on' : 'off';
            
            $options['autofill_user_data'] = ( isset( $data['ays_autofill_user_data'] ) && $data['ays_autofill_user_data'] == 'on' ) ? 'on' : 'off';
            
            if( isset( $data['ays_quiz_timer'] ) && $data['ays_quiz_timer'] != 0 ){
                $options['timer'] = absint( intval( $data['ays_quiz_timer'] ) );
            }else{
                $options['timer'] = 0;
            }
            if($id == null) {
                $quiz_result = $wpdb->insert(
                    $quiz_table,
                    array(
                        'title'             => $title,
                        'description'       => $description,
                        'quiz_image'        => $image,
                        'quiz_category_id'  => $quiz_category_id,
                        'question_ids'      => $question_ids,
                        'published'         => $published,
                        'ordering'          => $ordering,
                        'options'           => json_encode($options)
                    ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%d',
                        '%s',
                        '%d',
                        '%d',
                        '%s'
                    )
                );
                $message = 'created';
            }else{
                $quiz_result = $wpdb->update(
                    $quiz_table,
                    array(
                        'title'             => $title,
                        'description'       => $description,
                        'quiz_image'        => $image,
                        'quiz_category_id'  => $quiz_category_id,
                        'question_ids'      => $question_ids,
                        'published'         => $published,
                        'options'           => json_encode($options)
                    ),
                    array( 'id' => $id ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%d',
                        '%s',
                        '%d',
                        '%s'
                    ),
                    array( '%d' )
                );
                $message = 'updated';
            }
            
            $ays_quiz_tab = isset($data['ays_quiz_tab']) ? $data['ays_quiz_tab'] : 'tab1';
            if( $quiz_result >= 0 ){
                if($ays_change_type != ''){
                    if($id == null){
                        $url = esc_url_raw( add_query_arg( array(
                            "action"    => "edit",
                            "quiz"      => $wpdb->insert_id,
                            "ays_quiz_tab"  => $ays_quiz_tab,
                            "status"    => $message
                        ) ) );
                    }else{
                        $url = esc_url_raw( add_query_arg( array(
                            "ays_quiz_tab"  => $ays_quiz_tab,
                            "status"    => $message
                        ) ) );
                    }
                    wp_redirect( $url );
                }else{
                    $url = esc_url_raw( remove_query_arg(array('action', 'quiz')  ) ) . '&status=' . $message;
                    wp_redirect( $url );
                }
            }
        }
    }

    private function get_max_id() {
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_quizes';

        $sql = "SELECT max(id) FROM {$quiz_table}";

        $result = $wpdb->get_var($sql);

        return $result;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_quizes( $id ) {
        global $wpdb;
        $wpdb->delete(
            "{$wpdb->prefix}aysquiz_quizes",
            array( 'id' => $id ),
            array( '%d' )
        );
    }


    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_quizes";

        return $wpdb->get_var( $sql );
    }

    public static function published_questions_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_questions WHERE published=1";

        return $wpdb->get_var( $sql );
    }
    
    public static function all_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_quizes";

        return $wpdb->get_var( $sql );
    }
    
    public static function published_quizzes_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_quizes WHERE published=1";

        return $wpdb->get_var( $sql );
    }
    
    public static function unpublished_quizzes_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_quizes WHERE published=0";

        return $wpdb->get_var( $sql );
    }

    public static function get_quiz_pass_count($id) {
        global $wpdb;
        $quiz_id = intval($id);
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE quiz_id=".$quiz_id;

        return $wpdb->get_var( $sql );
    }

    public function duplicate_quizzes( $id ){
        global $wpdb;
        $quizzes_table = $wpdb->prefix."aysquiz_quizes";
        $quiz = $this->get_quiz_by_id($id);
        
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $author = array(
            'id' => $user->ID,
            'name' => $user->data->display_name
        );
        
        $max_id = $this->get_max_id();
        $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
        
        $options = json_decode($quiz['options'], true);
        
        $options['create_date'] = date("Y-m-d H:i:s");
        $options['author'] = $author;
        
        $result = $wpdb->insert(
            $quizzes_table,            
            array(
                'title'             => "Copy - ".$quiz['title'],
                'description'       => $quiz['description'],
                'quiz_image'        => $quiz['quiz_image'],
                'quiz_category_id'  => intval($quiz['quiz_category_id']),
                'question_ids'      => $quiz['question_ids'],
                'published'         => intval($quiz['published']),
                'ordering'          => $ordering,
                'options'           => json_encode($options)
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
                '%d',
                '%d',
                '%s'
            )
        );
        if( $result >= 0 ){
            $message = "duplicated";
            $url = esc_url_raw( remove_query_arg(array('action', 'question')  ) ) . '&status=' . $message;
            wp_redirect( $url );
        }
        
    }

    /** Text displayed when no customer data is available */
    public function no_items() {
        echo __( 'There are no quizzes yet.', $this->plugin_name );
    }


    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'title':
            case 'quiz_category_id':
            case 'shortcode':
            case 'code_include':
            case 'items_count':
            case 'create_date':
            case 'completed_count':
            case 'id':
                return $item[ $column_name ];
                break;
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_title( $item ) {
        $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-quiz' );
        $restitle = Quiz_Maker_Admin::ays_restriction_string("word",stripcslashes($item['title']), 5);
        $title = sprintf( '<a href="?page=%s&action=%s&quiz=%d">%s</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ), $restitle);

        $actions = array(
            'edit' => sprintf( '<a href="?page=%s&action=%s&quiz=%d">'. __('Edit', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ) ),
            'duplicate' => sprintf( '<a href="?page=%s&action=%s&quiz=%d">'. __('Duplicate', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'duplicate', absint( $item['id'] ) ),
            'delete' => sprintf( '<a href="?page=%s&action=%s&quiz=%s&_wpnonce=%s">'. __('Delete', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }

    function column_quiz_category_id( $item ) {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_quizcategories WHERE id=" . absint( intval( $item['quiz_category_id'] ) );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        if($result !== null){
            return $result['title'];
        }else{
            return '';
        }
    }

    function column_code_include( $item ) {
        return sprintf('<input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="<?php echo do_shortcode([ays_quiz id=\'%s\']); ?>" style="max-width:100%%;" />', $item["id"]);
    }

    function column_published( $item ) {
        switch( $item['published'] ) {
            case "1":
                return '<span class="ays-publish-status"><i class="ays_fa ays_fa_check_square_o" aria-hidden="true"></i> '. __('Published',$this->plugin_name) . '</span>';
                break;
            case "0":
                return '<span class="ays-publish-status"><i class="ays_fa ays_fa_square_o" aria-hidden="true"></i> '. __('Unpublished',$this->plugin_name) . '</span>';
                break;
        }
    }

    function column_shortcode( $item ) {
        return sprintf('<input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="[ays_quiz id=\'%s\']" style="max-width:100%%;" />', $item["id"]);
    }

    function column_create_date( $item ) {
        
        $options = json_decode($item['options'], true);
        $date = isset($options['create_date']) && $options['create_date'] != '' ? $options['create_date'] : "0000-00-00 00:00:00";
        if(isset($options['author'])){
            if(is_array($options['author'])){
                $author = $options['author'];
            }else{
                $author = json_decode($options['author'], true);
            }
        }else{
            $author = array("name"=>"Unknown");
        }
        $text = "";
        if(Quiz_Maker_Admin::validateDate($date)){
            $text .= "<p><b>Date:</b> ".$date."</p>";
        }
        if($author['name'] !== "Unknown"){
            $text .= "<p><b>Author:</b> ".$author['name']."</p>";
        }
        return $text;
    }

    function column_completed_count( $item ) {
        $id = $item['id'];
        $passed_count = $this->get_quiz_pass_count($id);
        $text = "<p style='text-align:center;font-size:14px;'>".$passed_count."</p>";
        return $text;
    }

    function column_items_count( $item ) {
        global $wpdb;
        $count = explode(',', $item['question_ids']);
        return "<p style='text-align:center;font-size:14px;'>" . count($count) . "</p>";
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'                => '<input type="checkbox" />',
            'title'             => __( 'Title', $this->plugin_name ),
            'quiz_category_id'  => __( 'Category', $this->plugin_name ),
            'shortcode'         => __( 'Shortcode', $this->plugin_name ),
            'code_include'      => __( 'Code include', $this->plugin_name ),
            'items_count'       => __( 'Count', $this->plugin_name ),
            'create_date'       => __( 'Created', $this->plugin_name ),
            'completed_count'   => __( 'Completed count', $this->plugin_name ),
            'id'                => __( 'ID', $this->plugin_name ),
        );

        return $columns;
    }


    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'title'         => array( 'title', true ),
            'quiz_category_id'      => array( 'quiz_category_id', true ),
            'id'            => array( 'id', true ),
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            'bulk-delete' => __('Delete', $this->plugin_name)
        );

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'quizes_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );

        $this->items = self::get_quizes( $per_page, $current_page );
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        $message = 'deleted';
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-quiz' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_quizes( absint( $_GET['quiz'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $url = esc_url_raw( remove_query_arg( array('action', 'quiz', '_wpnonce') ) ) . '&status=' . $message;
                wp_redirect( $url );
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_quizes( $id );

            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url

            $url = esc_url_raw( remove_query_arg( array('action', 'quiz', '_wpnonce') ) ) . '&status=' . $message;
            wp_redirect( $url );
        }
    }

    public function quiz_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;

        if ( 'created' == $status )
            $updated_message = esc_html( __( 'Quiz created.', $this->plugin_name ) );
        elseif ( 'updated' == $status )
            $updated_message = esc_html( __( 'Quiz saved.', $this->plugin_name ) );
        elseif ( 'duplicated' == $status )
            $updated_message = esc_html( __( 'Quiz duplicated.', $this->plugin_name ) );
        elseif ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Quiz deleted.', $this->plugin_name ) );

        if ( empty( $updated_message ) )
            return;

        ?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
        <?php
    }
}
