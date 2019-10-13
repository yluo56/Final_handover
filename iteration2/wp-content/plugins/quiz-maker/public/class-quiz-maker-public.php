<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/public
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Quiz_Maker_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode('ays_quiz', array($this, 'ays_generate_quiz_method'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles(){

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name.'-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-sweetalert-css', '//cdn.jsdelivr.net/npm/sweetalert2@7.26.29/dist/sweetalert2.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-animate', plugin_dir_url(__FILE__) . 'css/animate.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-animations', plugin_dir_url(__FILE__) . 'css/animations.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-rating', plugin_dir_url(__FILE__) . 'css/rating.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/quiz-maker-public.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-loaders', plugin_dir_url(__FILE__) . 'css/loaders.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts(){

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script("jquery-effects-core");
        wp_enqueue_script('select2js', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name.'sweetalert-js', '//cdn.jsdelivr.net/npm/sweetalert2@7.26.29/dist/sweetalert2.all.min.js', array('jquery'), $this->version, true );
        wp_enqueue_script ($this->plugin_name .'-rate-quiz', plugin_dir_url(__FILE__) . 'js/rating.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name .'-functions.js', plugin_dir_url(__FILE__) . 'js/functions.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-ajax-public', plugin_dir_url(__FILE__) . 'js/quiz-maker-public-ajax.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/quiz-maker-public.js', array('jquery'), $this->version, true);
        wp_localize_script( $this->plugin_name . '-ajax-public', 'quiz_maker_ajax_public', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_localize_script( $this->plugin_name, 'langObj', array(
            'selectPlaceholder' => __( 'Select an answer', $this->plugin_name ),
            'shareDialog'       => __( 'Share Dialog', $this->plugin_name )
        ) );
    }

    public function ays_generate_quiz_method($attr){
        ob_start();
        $id = (isset($attr['id'])) ? absint(intval($attr['id'])) : null;
        
        $this->show_quiz($id);
        return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
    }
    
    public function show_quiz($id){
        
        $quiz = $this->get_quiz_by_id($id);
        
        if (is_null($quiz)) {
            echo "<p style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return false;
        }
        if (intval($quiz['published']) === 0) {
            return false;
        }
        $options = json_decode($quiz['options'], true);
        $options['quiz_theme'] = (array_key_exists('quiz_theme', $options)) ? $options['quiz_theme'] : '';
        
        $quiz_parts = $this->ays_quiz_parts($id);
        
        switch ($options['quiz_theme']) {
            case 'elegant_dark':
                include_once('partials/class-quiz-theme-elegant-dark.php');
                $theme_obj = new Quiz_Theme_Elegant_Dark(AYS_QUIZ_NAME, AYS_QUIZ_NAME_VERSION, 'elegant_dark');
                echo $theme_obj->ays_generate_quiz($quiz_parts);
                break;
            case 'elegant_light':
                include_once('partials/class-quiz-theme-elegant-light.php');
                $theme_obj = new Quiz_Theme_Elegant_Light(AYS_QUIZ_NAME, AYS_QUIZ_NAME_VERSION, 'elegant_light');
                echo $theme_obj->ays_generate_quiz($quiz_parts);
                break;
            case 'rect_light':
                include_once('partials/class-quiz-theme-rect-light.php');
                $theme_obj = new Quiz_Theme_Rect_Light(AYS_QUIZ_NAME, AYS_QUIZ_NAME_VERSION, 'rect_light');
                echo $theme_obj->ays_generate_quiz($quiz_parts);
                break;
            case 'rect_dark':
                include_once('partials/class-quiz-theme-rect-dark.php');
                $theme_obj = new Quiz_Theme_Rect_Dark(AYS_QUIZ_NAME, AYS_QUIZ_NAME_VERSION, 'rect_dark');
                echo $theme_obj->ays_generate_quiz($quiz_parts);
                break;
            default:
                echo $this->ays_generate_quiz($quiz_parts);
        }

    }

    public function ays_quiz_parts($id){
        
        global $wpdb;        
        
    /*******************************************************************************************************/
        
        /*
         * Get Quiz data from database by id
         * Separation options from quiz data
         */
        $quiz = $this->get_quiz_by_id($id);
        $options = json_decode($quiz['options'], true);
        
    /*******************************************************************************************************/
                
        $randomize_answers = false;
        $questions = null;
        
        $arr_questions = ($quiz["question_ids"] == "") ? array() : explode(',', $quiz["question_ids"]);
        $arr_questions = (count($arr_questions) == 1 && $arr_questions[0] == '') ? array() : $arr_questions;
        
        if (isset($options['randomize_questions']) && $options['randomize_questions'] == 'on') {
            shuffle($arr_questions);
        }
        if (isset($options['enable_question_bank']) && $options['enable_question_bank'] == 'on' && 
            isset($options['questions_count']) && intval($options['questions_count']) > 0 &&
            $options['questions_count'] <= count($arr_questions)) {
            $random_questions = array_rand($arr_questions, intval($options['questions_count']));
            foreach ($random_questions as $key => $question) {
                $random_questions[$key] = strval($arr_questions[$question]);
            }
            $arr_questions = $random_questions;
            $quiz_questions_ids = join(',', $random_questions);
        }
        
        $questions_count = count($arr_questions);
        
        if (isset($options['randomize_answers']) && $options['randomize_answers'] == 'on') {
            $randomize_answers = true;
        }else{
            $randomize_answers = false;
        }

        if(isset($options['enable_correction']) && $options['enable_correction'] == "on"){
            $enable_correction = true;
        }else{
            $enable_correction = false;
        }
        

    /*******************************************************************************************************/
        
        /*
         * Quiz information form fields
         *
         * Checking required filelds
         *
         * Creating HTML code for printing
         */
        
        $form_inputs = null;
        $show_form = null;
        $required_fields = (array_key_exists('required_fields', $options) && !is_null($options['required_fields'])) ? $options['required_fields'] : array();
        
        $name_required = (in_array('ays_user_name', $required_fields)) ? 'required' : '';
        $email_required = (in_array('ays_user_email', $required_fields)) ? 'required' : '';
        $phone_required = (in_array('ays_user_phone', $required_fields)) ? 'required' : '';
        
        $form_title = (isset($options['form_title']) && $options['form_title'] != '') ? stripslashes(wpautop($options['form_title'])): '';

        if($options['form_name'] == "on"){
            $show_form = "show";
            $form_inputs .= "<input type='text' name='ays_user_name' placeholder='".__('Name', $this->plugin_name)."' class='ays_quiz_form_input ays_animated_x5ms' " . $name_required . ">";
        }else{
            $form_inputs .= "<input type='hidden' name='ays_user_name' placeholder='".__('Name', $this->plugin_name)."' value=''>";
        }
        if($options['form_email'] == "on"){
            $show_form = "show";
            $form_inputs .= "<input type='text' name='ays_user_email' placeholder='".__('Email', $this->plugin_name)."' class='ays_quiz_form_input ays_animated_x5ms' " . $email_required . ">";
        }else{
            $form_inputs .= "<input type='hidden' name='ays_user_email' placeholder='".__('Email', $this->plugin_name)."' value=''>";
        }
        if($options['form_phone'] == "on"){
            $show_form = "show";
            $form_inputs .= "<input type='text' name='ays_user_phone' placeholder='".__('Phone Number', $this->plugin_name)."' class='ays_quiz_form_input ays_animated_x5ms' " . $phone_required . ">";
        }else{
            $form_inputs .= "<input type='hidden' name='ays_user_phone' placeholder='".__('Phone Number', $this->plugin_name)."' value=''>";
        }
        
        
    /*******************************************************************************************************/
        
        /*
         * Quiz colors
         * 
         * Quiz container colors
         */
        
        // Quiz container background color
        
        if(isset($options['bg_color']) && $options['bg_color'] != ''){
            $bg_color = $options['bg_color'];
        }else{
            $bg_color = "#fff";
        }
        
        // Color of elements inside quiz container
        
        if(isset($options['color']) && $options['color'] != ''){
            $color = $options['color'];
        }else{
            $color = "#27ae60";
        }
        
        // Color of text inside quiz container
        
        if(isset($options['text_color']) && $options['text_color'] != ''){
            $text_color = $options['text_color'];
        }else{
            $text_color = "#333";
        }
        
        // Quiz container shadow color
        
        // CHecking exists box shadow option
        $options['enable_box_shadow'] = (!isset($options['enable_box_shadow'])) ? 'on' : $options['enable_box_shadow'];
        
        if(isset($options['box_shadow_color']) && $options['box_shadow_color'] != ''){
            $box_shadow_color = $options['box_shadow_color'];
        }else{
            $box_shadow_color = "#333";
        }
        
        // Quiz container border color
        
        if(isset($options['quiz_border_color']) && $options['quiz_border_color'] != ''){
            $quiz_border_color = $options['quiz_border_color'];
        }else{
            $quiz_border_color = '#000';
        }
                
        
    /*******************************************************************************************************/ 
        
        /*
         * Quiz styles
         *
         * Quiz container styles
         */
        
        
        // Quiz container minimal height
        
        if(isset($options['height']) && $options['height'] != ''){
            $quiz_height = $options['height'];
        }else{
            $quiz_height = '400';
        }
        
        // Quiz container width
        
        if(isset($options['width']) && $options['width'] != ''){
            $quiz_width = $options['width'] . 'px';
        }else{
            $quiz_width = '100%';
        }
        
        // Quiz container border radius
        
        // Modified border radius for Pass count option and Rate avg option
        $quiz_modified_border_radius = "";
        
        if(isset($options['quiz_border_radius']) && $options['quiz_border_radius'] != ''){
            $quiz_border_radius = $options['quiz_border_radius'];
        }else{
            $quiz_border_radius = '3px';
        }
        
        // Quiz container shadow enabled/disabled
        
        if(isset($options['enable_box_shadow']) && $options['enable_box_shadow'] == 'on'){
            $enable_box_shadow = true;
        }else{
            $enable_box_shadow = false;
        }
        
        // Quiz container background image
        
        if(isset($options['quiz_bg_image']) && $options['quiz_bg_image'] != ''){
            $ays_quiz_bg_image = $options['quiz_bg_image'];
        }else{
            $ays_quiz_bg_image = null;
        }
        
        // Quiz container background image position
        $quiz_bg_image_position = "center center";

        if(isset($options['quiz_bg_image_position']) && $options['quiz_bg_image_position'] != ""){
            $quiz_bg_image_position = $options['quiz_bg_image_position'];
        }

        
        /*
         * Quiz container border enabled/disabled
         *
         * Quiz container border width
         *
         * Quiz container border style
         */
        
        if(isset($options['enable_border']) && $options['enable_border'] == 'on'){
            $enable_border = true;
        }else{
            $enable_border = false;
        }
        
        if(isset($options['quiz_border_width']) && $options['quiz_border_width'] != ''){
            $quiz_border_width = $options['quiz_border_width'];
        }else{
            $quiz_border_width = '1';
        }
        
        if(isset($options['quiz_border_style']) && $options['quiz_border_style'] != ''){
            $quiz_border_style = $options['quiz_border_style'];
        }else{
            $quiz_border_style = 'solid';
        }
        
        // Questions image width, height and sizing
        
        if(isset($options['image_width']) && $options['image_width'] != ''){
            $question_image_width = $options['image_width'] . 'px';
        }else{
            $question_image_width = "100%";
        }

        if(isset($options['image_height']) && $options['image_height'] != ''){
            $question_image_height = $options['image_height'] . 'px';
        }else{
            $question_image_height = "auto";
        }
        
        if(isset($options['image_sizing']) && $options['image_sizing'] != ''){
            $question_image_sizing = $options['image_sizing'];
        }else{
            $question_image_sizing = "cover";
        }
        
        // Answers font size
        
        $answers_font_size = '15';
        if(isset($options['answers_font_size']) && $options['answers_font_size'] != ""){
            $answers_font_size = $options['answers_font_size'];
        }

        
        /* 
         * Quiz container background gradient
         * 
         */
        
        // Checking exists background gradient option
        $options['enable_background_gradient'] = (!isset($options['enable_background_gradient'])) ? "off" : $options['enable_background_gradient'];
        
        if(isset($options['background_gradient_color_1']) && $options['background_gradient_color_1'] != ''){
            $background_gradient_color_1 = $options['background_gradient_color_1'];
        }else{
            $background_gradient_color_1 = "#000";
        }

        if(isset($options['background_gradient_color_2']) && $options['background_gradient_color_2'] != ''){
            $background_gradient_color_2 = $options['background_gradient_color_2'];
        }else{
            $background_gradient_color_2 = "#fff";
        }

        if(isset($options['quiz_gradient_direction']) && $options['quiz_gradient_direction'] != ''){
            $quiz_gradient_direction = $options['quiz_gradient_direction'];
        }else{
            $quiz_gradient_direction = 'vertical';
        }
        switch($quiz_gradient_direction) {
            case "horizontal":
                $quiz_gradient_direction = "to right";
                break;
            case "diagonal_left_to_right":
                $quiz_gradient_direction = "to bottom right";
                break;
            case "diagonal_right_to_left":
                $quiz_gradient_direction = "to bottom left";
                break;
            default:
                $quiz_gradient_direction = "to bottom";
        }

        // Quiz container background gradient enabled/disabled
        
        if(isset($options['enable_background_gradient']) && $options['enable_background_gradient'] == "on"){
            $enable_background_gradient = true;
        }else{
            $enable_background_gradient = false;
        }

        
    /*******************************************************************************************************/
        
        /*
         * Quiz start page
         *
         * Quiz title
         * Quiz desctiption
         * Quiz image
         *
         * Quiz Start button
         */
        
        $title = stripslashes($quiz['title']);
        
        $description = stripslashes(wpautop($quiz['description']));
        
        $quiz_image = $quiz['quiz_image'];
        
        
        $quiz_rate_reports = '';
        $quiz_result_reports = '';
        
        
        if($questions_count == 0){
            $empty_questions_notification = '<p style="color:red">' . __('You need to add questions', $this->plugin_name) . '</p>';
            $empty_questions_button = "disabled";
        }else{
            $empty_questions_notification = "";
            $empty_questions_button = "";
        }
        
        $quiz_start_button = "<input type='button' $empty_questions_button name='next' class='ays_next start_button action-button' value='".__('Start',$this->plugin_name)."' />".$empty_questions_notification;
        
        
        /* 
         * Quiz passed users count
         *
         * Generate HTML code
         */
        
        if(isset($options['enable_pass_count']) && $options['enable_pass_count'] == 'on'){
            $enable_pass_count = true;
            $quiz_result_reports = $this->get_quiz_results_count_by_id($id);
            $quiz_result_reports = "<span class='ays_quizn_ancnoxneri_qanak'><i class='ays_fa ays_fa_users'></i> ".$quiz_result_reports['res_count']."</span>";
            $quiz_modified_border_radius = "border-radius:" . $quiz_border_radius . "px " . $quiz_border_radius . "px 0px " . $quiz_border_radius . "px;";
        }else{
            $enable_pass_count = false;
        }
        
        
        
        /* 
         * Quiz average rate
         *
         * Generate HTML code
         */
        
        $quiz_rates_avg = round($this->ays_get_average_of_rates($id), 1);
        $quiz_rates_count = $this->ays_get_count_of_rates($id);
        if(isset($options['enable_rate_avg']) && $options['enable_rate_avg'] == 'on'){
            $enable_rate_avg = true;
            $quiz_rate_reports = "<div class='ays_quiz_rete_avg'>
                <div class='for_quiz_rate_avg ui star rating' data-rating='".round($quiz_rates_avg)."' data-max-rating='5'></div>
                <span>$quiz_rates_count votes, $quiz_rates_avg avg</span>
            </div>";
            $quiz_modified_border_radius = "border-radius:" . $quiz_border_radius . "px " . $quiz_border_radius . "px " . $quiz_border_radius . "px 0px;";
        }else{
            $enable_rate_avg = false;
        }
        
        
        
        /* 
         * Generate HTML code when passed users count and average rate both are enabled
         */
        
        if($enable_rate_avg && $enable_pass_count){
            $quiz_modified_border_radius = "border-radius:" . $quiz_border_radius . "px " . $quiz_border_radius . "px 0px 0px;";
            $ays_quiz_reports = "<div class='ays_quiz_reports'>$quiz_rate_reports $quiz_result_reports</div>";
        }else{
            $ays_quiz_reports = $quiz_rate_reports.$quiz_result_reports;
        }
        
        /* 
         * Generate HTML code when passed users count and average rate both are enabled
         * 
         * Show quiz author and create date
         */
        
        $show_create_date = (isset($options['show_create_date']) && $options['show_create_date'] == "on") ? true : false;
        $show_author = (isset($options['show_author']) && $options['show_author'] == "on") ? true : false;
        
        if(isset($options['show_create_date']) && $options['show_create_date'] == "on"){
            $show_create_date = true;
        }else{
            $show_create_date = false;
        }
        
        if(isset($options['show_author']) && $options['show_author'] == "on"){
            $show_author = true;
        }else{
            $show_author = false;
        }
        
        $show_cd_and_author = "<div class='ays_cb_and_a'>";
        if($show_create_date){
            $quiz_create_date = (isset($options['create_date']) && $options['create_date'] != '') ? $options['create_date'] : "0000-00-00 00:00:00";
            if(Quiz_Maker_Admin::validateDate($quiz_create_date)){
                $show_cd_and_author .= "<span>".__("Created on",$this->plugin_name)." </span><strong><time>".date("F d, Y", strtotime($quiz_create_date))."</time></strong>";
            }else{
                $show_cd_and_author .= "";
            }
        }
        if($show_author){
            if(isset($options['author'])){
                if(is_array($options['author'])){
                    $author = $options['author'];
                }else{
                    $author = json_decode($options['author'], true);
                }
            }else{
                $author = array("name"=>"Unknown");
            }
            $user_id = 0;
            if(isset($author['id']) && intval($author['id']) != 0){
                $user_id = intval($author['id']);
            }
            $image = get_avatar($user_id, 32);
            if($author['name'] !== "Unknown"){
                if($show_create_date){
                    $text = __("By", $this->plugin_name);
                }else{
                    $text = __("Created by", $this->plugin_name);
                }
                $show_cd_and_author .= "<span>   ".$text." </span>".$image."<strong>".$author['name']."</strong>";
            }else{
                $show_cd_and_author .= "";
            }
        }
        $show_cd_and_author .= "</div>";
        
        if($show_create_date == false && $show_author == false){
            $show_cd_and_author = "";
        }
        
        
        
    /*******************************************************************************************************/
        
        /* 
         * Quiz passing options
         *
         * Generate HTML code
         */
        
        $live_progress_bar = "";
        $timer_row = "";
        $answer_view_class = "";
        $correction_class = "";
        $ie_container_css = "";
        $rtl_style = "";
            
        
        /*
         * Generating Quiz timer
         *
         * Checking timer enabled or diabled
         */
        
        $timer_enabled = false;
        if (isset($options['enable_timer']) && $options['enable_timer'] == 'on') {
            $timer_enabled = true;
            $timer_text = (isset($options['timer_text'])) ? $options['timer_text'] : '';
            $timer_text = stripslashes(str_replace('%%time%%', $this->secondsToWords($options['timer']), wpautop($timer_text)));
            $hide_timer_cont = "";
            if($timer_text == ""){
                $hide_timer_cont = " style='display:none;' ";
            }
            $timer_row = "<section {$hide_timer_cont} class='ays_quiz_timer_container'><div class='ays-quiz-timer' data-timer='" . $options['timer'] . "'>{$timer_text}</div><hr></section>";
        }
        
        /*
         * Quiz live progress bar
         *
         * Checking enabled or diabled
         *
         * Checking percent view or not
         */
        
        if(isset($options['enable_live_progress_bar']) && $options['enable_live_progress_bar'] == 'on'){
            
            if(isset($options['enable_percent_view']) && $options['enable_percent_view'] == 'on'){
                $live_progress_bar_percent = "<span class='ays-live-bar-percent'>0</span>%";
            }else{
                $live_progress_bar_percent = "<span class='ays-live-bar-percent ays-live-bar-count'></span>/$questions_count";
            }
            
            $live_progress_bar = "<div class='ays-live-bar-wrap'><div class='ays-live-bar-fill' style='width: 0%;'><span>$live_progress_bar_percent</span></div></div>";            
        }
        
        
        
        /*
         * Quiz questions answers view
         *
         * Generate HTML class for answers view
         */
        
        if(isset($options['answers_view']) && $options['answers_view'] != ''){
            $answer_view_class = $options['answers_view'];
        }
        
        
        /*
         * Get site url for social sharing buttons
         *
         * Generate HTML class for answers view
         */
        
        $actual_link = "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            $actual_link = "https" . $actual_link;
        }else{
            $actual_link = "http" . $actual_link;
        }        
        
        /*
         * Show correct answers
         *
         * Generate HTML class for answers view
         */
        
        if($enable_correction){
            $correction_class = "enable_correction";
        }
              
        
        /*
         * Show correct answers
         *
         * Generate HTML class for answers view
         */
        
        if(isset($options['enable_questions_counter']) && $options['enable_questions_counter'] == 'on'){
            $questions_counter = true;
        }else{
            $questions_counter = false;
        }
           
        
        /*
         * Get Browser data for Internet Explorer
         */
        
        $useragent = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
        if(preg_match('~MSIE|Internet Explorer~i', $useragent) || 
           (strpos($useragent, 'Trident/7.0; rv:11.0') !== false)){
            $ie_container_css = 'display:flex;flex-wrap:wrap;';
        }
        
        
        /*
         * Quiz buttons
         * 
         * Next button
         * Previous button
         * Arrows instead buttons
         */
        if(isset($options['enable_previous_button']) && $options['enable_previous_button'] == "on"){
            $prev_button = true;
        }else{
            $prev_button = false;
        }
        
        if(isset($options['enable_next_button']) && $options['enable_next_button'] == "on"){
            $next_button = true;
        }else{
            $next_button = false;
        }
        
        if(isset($options['enable_arrows']) && $options['enable_arrows'] == "on"){
            $enable_arrows = true;
        }else{
            $enable_arrows = false;
        }
        
        if(isset($options['enable_early_finish']) && $options['enable_early_finish'] == 'on'){
            $enable_early_finish = true;
        }else{
            $enable_early_finish = false;
        }
        
        if($enable_arrows){
            $arrows_visibility = "";
        }else{
            $arrows_visibility = 'ays_display_none';
        }
        
        if($prev_button && $enable_arrows){
            $prev_arrow_visibility = "";
        }else{
            $prev_arrow_visibility = 'ays_display_none';
        }
        
        if($prev_button && !$enable_arrows){
            $prev_button_visibility = "";
        }else{
            $prev_button_visibility = 'ays_display_none';
        }
        
        if($next_button && $enable_arrows){
            $next_arrow_visibility = "";
        }else{
            $next_arrow_visibility = 'ays_display_none';
        }
        
        if($next_button == true && $enable_arrows == false){
            $next_button_visibility = "";
        }else{
            $next_button_visibility = 'ays_display_none';
        }
        
        $buttons = array(
            "enableArrows" => $enable_arrows,
            "arrows" => $arrows_visibility,
            "nextArrow" => $next_arrow_visibility,
            "prevArrow" => $prev_arrow_visibility,
            "nextButton" => $next_button_visibility,
            "prevButton" => $prev_button_visibility,
            "earlyButton" => $enable_early_finish,
        );
        
        /*
         * Quiz restart button
         */
        $enable_restart_button = false;
        if(isset($options['enable_restart_button']) && $options['enable_restart_button'] == 'on'){
            $enable_restart_button = true;
        }

        if($enable_restart_button){
            $restart_button = "<button type='button' class='action-button ays_restart_button'>
                    <i class='ays_fa ays_fa_undo'></i>
                    <span>".__( "Restart quiz", $this->plugin_name )."</span>
                </button>";
        }else{
            $restart_button = "";
        }

        
        /*
         * EXIT button in finish page
         */

        $enable_exit_button = false;
        $exit_redirect_url = null;
        if(isset($options['enable_exit_button']) && $options['enable_exit_button'] == 'on'){
            $enable_exit_button = true;
        }
        if(isset($options['exit_redirect_url']) && $options['exit_redirect_url'] != ''){
            $exit_redirect_url = $options['exit_redirect_url'];
        }


        if($enable_exit_button && $exit_redirect_url !== null){
            $exit_button = "<a style='width:auto;' href='".$exit_redirect_url."' class='action-button ays_restart_button' target='_top'>
                        <span>".__( "Exit", $this->plugin_name )."</span>
                        <i class='ays_fa ays_fa_sign_out'></i>
                    </a>";
        }else{
            $exit_button = "";
        }
        
        
        /*
         * Quiz questions per page count
         */
        
        if(isset($options['enable_rtl_direction']) && $options['enable_rtl_direction'] == "on"){
            $rtl_direction = true;
            $rtl_style = "
                #ays-quiz-container-" . $id . " p {
                    direction:rtl;
                    text-align:right;   
                }
                #ays-quiz-container-" . $id . " p.ays_score {
                    text-align: center;   
                }
                #ays-quiz-container-" . $id . " p.ays-question-counter {
                    right: unset;
                    left: 8px;
                }
                #ays-quiz-container-" . $id . " .ays_question_hint_container {
                    left:unset;
                    right:10px;
                }
                #ays-quiz-container-" . $id . " .ays_question_hint_text {
                    left:unset;
                    right:20px;
                }
                #ays-quiz-container-" . $id . " .select2-container--default .select2-results__option {
                    direction:rtl;
                    text-align:right;
                }
                #ays-quiz-container-" . $id . " .select2-container--default .select2-selection--single .select2-selection__placeholder,
                #ays-quiz-container-" . $id . " .select2-container--default .select2-selection--single .select2-selection__rendered {
                    direction:rtl;
                    text-align:right;
                    display: inline-block;
                    width: 95%;
                }
                #ays-quiz-container-" . $id . " .ays-field.ays-select-field {
                    margin: 0;
                }

                #ays-quiz-container-" . $id . " label[for^=\"ays-answer-\"]{
                    direction:rtl;
                    text-align:right;
                    padding-left: 0px;
                    padding-right: 10px;
                    position: relative;
                    text-overflow: ellipsis;
                }                        
                #ays-quiz-container-" . $id . " label[for^=\"ays-answer-\"]:last-child {
                    padding-right: 0;
                }
                #ays-quiz-container-" . $id . " label[for^=\"ays-answer-\"]::before {
                    margin-left: 5px;
                    margin-right: 5px;
                }
                #ays-quiz-container-" . $id . " label[for^=\"ays-answer-\"]::after {
                    margin-left: 0px;
                    margin-right: 10px;
                }
                ";
        }else{
            $rtl_direction = false;
        }
        
        
        
        /*
         * Quiz background music 
         */
        
        $enable_bg_music = false;
        $quiz_bg_music = "";
        $ays_quiz_music_html = "";
        $ays_quiz_music_sound = "";
        
        if(isset($options['enable_bg_music']) && $options['enable_bg_music'] == "on"){
            $enable_bg_music = true;
        }
        
        if(isset($options['quiz_bg_music']) && $options['quiz_bg_music'] != ""){
            $quiz_bg_music = $options['quiz_bg_music'];
        }

        if($enable_bg_music && $quiz_bg_music != ""){
            $ays_quiz_music_html = "<audio id='ays_quiz_music_".$id."' loop class='ays_quiz_music' src='".$quiz_bg_music."'></audio>";
            $with_timer = "";
            if($timer_enabled){
                $with_timer = " ays_sound_with_timer ";
            }
            $ays_quiz_music_sound = "<span class='ays_music_sound ".$with_timer." ays_sound_active ays_display_none'><i class='ays_fa ays_fa_volume_up'></i></span>";
        }

        
    /*******************************************************************************************************/
        
        /* 
         * Quiz finish page
         *
         * Generating some HTML code for finish page
         */
        
        $progress_bar = false;
        $progress_bar_style = "first";
        $progress_bar_html = "";
        $show_average = "";
        $show_score_html = "";
        $enable_questions_result = "";
        $rate_form_title = "";
        $quiz_rate_html = "";
        $ays_social_buttons = "";
        
        /*
         * Quiz progress bar for finish page
         *
         * Checking enabled or diabled
         */
        
        if(isset($options['enable_progress_bar']) && $options['enable_progress_bar'] == 'on'){
            $progress_bar = true;
        }

        if(isset($options['progress_bar_style']) && $options['progress_bar_style'] != ""){
            $progress_bar_style = $options['progress_bar_style'];
        }

        if($progress_bar){
            $progress_bar_html = "<div class='ays-progress " . $progress_bar_style . "'>
                <span class='ays-progress-value " . $progress_bar_style . "'>0%</span>
                <div class='ays-progress-bg " . $progress_bar_style . "'>
                    <div class='ays-progress-bar " . $progress_bar_style . "' style='width:0%;'></div>
                </div>
            </div>";
        }


        /*
         * Average statistical of quiz
         *
         * Checking enabled or diabled
         */
        if (isset($options['enable_average_statistical']) && $options['enable_average_statistical'] == "on") {
            $sql = "SELECT AVG(`score`) FROM {$wpdb->prefix}aysquiz_reports WHERE quiz_id= $id";
            $result = round($wpdb->get_var($sql));
            $show_average = "<p class='ays_average'>" . __('The average score is ', $this->plugin_name) . " " . $result . "%</p>";
        }
        
        
        /*
         * Passed quiz score
         *
         * Checking enabled or diabled
         */
                
        if(array_key_exists('hide_score',$options) && $options['hide_score'] != 'on'){
            $show_score_html = "<p class='ays_score ays_score_display_none animated'>" . __( 'Your score is ', $this->plugin_name ) . "</p>";
        }
        
        
        /*
         * Show quiz results after passing quiz
         *
         * Checking enabled or diabled
         */
              
        if(isset($options['enable_questions_result']) && $options['enable_questions_result'] == 'on'){
            $enable_questions_result = 'enable_questions_result';
        }
        
        
        
        /*
         * Quiz rate
         *
         * Generating HTML code
         */
        
        if(isset($options['rate_form_title'])){
            $rate_form_title = stripslashes(wpautop($options['rate_form_title']));
        }
        
        if(isset($options['enable_quiz_rate']) && $options['enable_quiz_rate'] == 'on'){
            $quiz_rate_html = "<div class='ays_quiz_rete'>
                <div>$rate_form_title</div>
                <div class='for_quiz_rate ui huge star rating' data-rating='0' data-max-rating='5'></div>
                <div><div class='lds-spinner-none'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>
                <div class='for_quiz_rate_reason'>
                    <textarea id='quiz_rate_reason_".$id."' class='quiz_rate_reason'></textarea>
                    <div class='ays_feedback_button_div'>
                        <button type='button' class='action-button'>". __('Send feedback', $this->plugin_name) ."</button>
                    </div>
                </div>
                <div><div class='lds-spinner2-none'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>
                <div class='quiz_rate_reasons_body'></div>
            </div>";
        }
        
        
        
        /*
         * Quiz social sharing buttons
         *
         * Generating HTML code
         */
        
        
        if(isset($options['enable_social_buttons']) && $options['enable_social_buttons'] == 'on'){
              $ays_social_buttons = "<div class='ays-quiz-social-shares'>
                        <!-- Branded LinkedIn button -->
                        <a class='ays-share-btn ays-to-share ays-share-btn-branded ays-share-btn-linkedin'
                           href='https://www.linkedin.com/shareArticle?mini=true&url=" . $actual_link . "'
                           title='Share on LinkedIn'>
                            <span class='ays-share-btn-icon'></span>
                            <span class='ays-share-btn-text'>LinkedIn</span>
                        </a>
                        <!-- Branded Facebook button -->
                        <a class='ays-share-btn ays-to-share ays-share-btn-branded ays-share-btn-facebook'
                           href='https://www.facebook.com/sharer/sharer.php?u=" . $actual_link . "'
                           title='Share on Facebook'>
                            <span class='ays-share-btn-icon'></span>
                            <span class='ays-share-btn-text'>Facebook</span>
                        </a>
                        <!-- Branded Twitter button -->
                        <a class='ays-share-btn ays-to-share ays-share-btn-branded ays-share-btn-twitter'
                           href='https://twitter.com/share?url=" . $actual_link . "'
                           title='Share on Twitter'>
                            <span class='ays-share-btn-icon'></span>
                            <span class='ays-share-btn-text'>Twitter</span>
                        </a>
                    </div>";
        }
        
        
        
        /*
         * Quiz social media links
         *
         * Generating HTML code
         */
        // Social Media links

        $enable_social_links = (isset($options['enable_social_links']) && $options['enable_social_links'] == "on") ? true : false;
        $social_links = (isset($options['social_links'])) ? $options['social_links'] : array(
            'linkedin_link' => '',
            'facebook_link' => '',
            'twitter_link' => ''
        );
        $ays_social_links_array = array();

        $linkedin_link = isset($social_links['linkedin_link']) && $social_links['linkedin_link'] != '' ? $social_links['linkedin_link'] : '';
        $facebook_link = isset($social_links['facebook_link']) && $social_links['facebook_link'] != '' ? $social_links['facebook_link'] : '';
        $twitter_link = isset($social_links['twitter_link']) && $social_links['twitter_link'] != '' ? $social_links['twitter_link'] : '';
        if($linkedin_link != ''){
            $ays_social_links_array['Linkedin'] = $linkedin_link;
        }
        if($facebook_link != ''){
            $ays_social_links_array['Facebook'] = $facebook_link;
        }
        if($twitter_link != ''){
            $ays_social_links_array['Twitter'] = $twitter_link;
        }
        $ays_social_links = '';
        
        if($enable_social_links){
            $ays_social_links .= "<div class='ays-quiz-social-shares'>";
            foreach($ays_social_links_array as $media => $link){
                $ays_social_links .= "<!-- Branded " . $media . " button -->
                    <a class='ays-share-btn ays-share-btn-branded ays-share-btn-rounded ays-share-btn-" . strtolower($media) . "'
                        href='" . $link . "'
                        target='_blank'
                        title='" . $media . " link'>
                        <span class='ays-share-btn-icon'></span>
                    </a>";
            }
                    
                    // "<!-- Branded Facebook button -->
                    // <a class='ays-share-btn ays-share-btn-branded ays-share-btn-facebook'
                    //     href='" . . "'
                    //     title='Share on Facebook'>
                    //     <span class='ays-share-btn-icon'></span>
                    // </a>
                    // <!-- Branded Twitter button -->
                    // <a class='ays-share-btn ays-share-btn-branded ays-share-btn-twitter'
                    //     href='" . . "'
                    //     title='Share on Twitter'>
                    //     <span class='ays-share-btn-icon'></span>
                    // </a>";
            $ays_social_links .= "</div>";
        }
        
        
        /*
         * Quiz loader
         *
         * Generating HTML code
         */
                
        $quiz_loader = 'default';
        
        if(isset($options['quiz_loader']) && $options['quiz_loader'] != ''){
            $quiz_loader = $options['quiz_loader'];
        }
        
        switch($quiz_loader){
            case 'default':
                $quiz_loader_html = "<div data-class='lds-ellipsis' data-role='loader' class='ays-loader'><div></div><div></div><div></div><div></div></div>";
                break;
            case 'circle':
                $quiz_loader_html = "<div data-class='lds-circle' data-role='loader' class='ays-loader'></div>";
                break;
            case 'dual_ring':
                $quiz_loader_html = "<div data-class='lds-dual-ring' data-role='loader' class='ays-loader'></div>";
                break;
            case 'facebook':
                $quiz_loader_html = "<div data-class='lds-facebook' data-role='loader' class='ays-loader'><div></div><div></div><div></div></div>";
                break;
            case 'hourglass':
                $quiz_loader_html = "<div data-class='lds-hourglass' data-role='loader' class='ays-loader'></div>";
                break;
            case 'ripple':
                $quiz_loader_html = "<div data-class='lds-ripple' data-role='loader' class='ays-loader'><div></div><div></div></div>";
                break;
            default:
                $quiz_loader_html = "<div data-class='lds-ellipsis' data-role='loader' class='ays-loader'><div></div><div></div><div></div><div></div></div>";
                break;
        }
        
        
    /*******************************************************************************************************/
        
        /*
         * Quiz limitations
         *
         * Blocking content
         *
         * Generating HTML code
         */
        
        $limit_users_html = "";
        $limit_users = null;
        
        /*
         * Quiz timer in tab title
         */
        
        if(isset($options['quiz_timer_in_title']) && $options['quiz_timer_in_title'] == "on"){
            $show_timer_in_title = "true";
        }else{
            $show_timer_in_title = "false";
        }        
        
        /*
         * Quiz one time passing
         *
         * Generating HTML code
         */        
        
        if (isset($options['limit_users']) && $options['limit_users'] == "on") {
            $result = $this->get_user_by_ip($id);
            if ($result != 0) {
                $limit_users = true;
                $timer_row = "";
                if(isset($options['redirection_delay']) && $options['redirection_delay'] != ''){
                    if(isset($options['redirect_url']) && $options['redirect_url'] != ''){
                        $timer_row = "<p class='ays_redirect_url' style='display:none'>" . 
                                $options['redirect_url'] . 
                            "</p>                                
                            <div class='ays-quiz-timer' data-show-in-title='".$show_timer_in_title."' data-timer='" . $options['redirection_delay'] . "'>". 
                                __( "Redirecting after", $this->plugin_name ). " " . 
                                $this->secondsToWords($options['redirection_delay']) . 
                                "<EXTERNAL_FRAGMENT></EXTERNAL_FRAGMENT>                                
                            </div>";
                    }
                }
                $limit_users_html = $timer_row . "<div style='color:" . $text_color . "' class='ays_block_content'>" . stripslashes(wpautop($options['limitation_message'])) . "</div><style>form{min-height:0 !important;}</style>";
            }
        }else{
            $limit_users = false;
        }
        
        
        
        /*
         * Quiz only for logged in users
         *
         * Generating HTML code
         */  
        
        global $wp_roles;
        
        if(isset($options['enable_logged_users']) && $options['enable_logged_users'] == 'on' && !is_user_logged_in()){
            $enable_logged_users = 'only_logged_users';
            if(isset($options['enable_logged_users_message']) && $options['enable_logged_users_message'] != ""){
                $logged_users_message = stripslashes(wpautop($options['enable_logged_users_message']));
            }else{
                $logged_users_message =  __('You must log in to pass this quiz.', $this->plugin_name);
            }
            if($logged_users_message !== null){
                $user_massage = '<div class="logged_in_message">' . $logged_users_message . '</div>';
            }else{
                $user_massage = null;
            }
        }else{
            $user_massage = null;
            $enable_logged_users = '';
            if (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on') {
                $user = wp_get_current_user();
                $user_roles   = $wp_roles->role_names;
                $message = (isset($options['restriction_pass_message']) && $options['restriction_pass_message'] != '') ? $options['restriction_pass_message'] : __('Permission Denied', $this->plugin_name);
                $user_role = (isset($options['user_role']) && $options['user_role'] != '') ? $options['user_role'] : '';
                $user_massage = '<div class="logged_in_message">' . stripslashes(wpautop($message)) . '</div>';
                
                if (is_array($user_role)) {
                    foreach($user_role as $key => $role){
                        if(in_array($role, $user_roles)){
                            $user_role[$key] = array_search($role, $user_roles);
                        }                        
                    }
                }else{
                    if(in_array($user_role, $user_roles)){
                        $user_role = array_search($user_role, $user_roles);
                    }
                }

                if(is_array($user_role)){
                    foreach($user_role as $role){                        
                        if (in_array(strtolower($role), (array)$user->roles)) {
                            $user_massage = null;
                            break;
                        }
                    }                    
                }else{
                    if (in_array(strtolower($user_role), (array)$user->roles)) {
                        $user_massage = null;
                    }
                }
            }
        }
        
        
    /*******************************************************************************************************/
        
        /*
         * Quiz main content
         *
         * Generating HTML code
         */
        
        
        if($quiz_image != ""){
            $quiz_image = "<img src='{$quiz_image}' alt='' class='ays_quiz_image'>";
        }else{
            $quiz_image = "";
        }
        
        $main_content_first_part = "{$timer_row}
            {$ays_quiz_music_sound}
            <div class='step active-step'>
                <div class='ays-abs-fs ays-start-page'>
                    {$show_cd_and_author}
                    {$quiz_image}
                    <p class='ays-fs-title'>" . ($title) . "</p>
                    <p class='ays-fs-subtitle'>{$description}</p>
                    <input type='hidden' name='ays_quiz_id' value='{$id}'/>
                    " . (isset($quiz_questions_ids) ? "<input type='hidden' name='ays_quiz_questions' value={$quiz_questions_ids}'>" : "") . "
                    {$quiz_start_button}
                    </div>
                </div>";
        
        if($limit_users === false || $limit_users === null){
            $restart_button_html = $restart_button;
        }else{
            $restart_button_html = "";
        }
            
        $main_content_last_part = "<div class='step ays_thank_you_fs'>
            <div class='ays-abs-fs ays-end-page'>".
            $quiz_loader_html .
            "<div class='ays_quiz_results_page'>
                <div class='ays_message'></div>" .
                $show_score_html .
                $show_average .
                $ays_social_buttons .
                $ays_social_links .
                $progress_bar_html .
                "<p class='ays_restart_button_p'>".
                    $restart_button_html .
                    $exit_button .
                "</p>".
                $quiz_rate_html .
                "</div>
            </div>
        </div>";
        
        if($show_form != null){
            if ($options['information_form'] == "after") {
                $main_content_last_part = "<div class='step'>
                                <div class='ays-abs-fs ays-end-page information_form'>
                                <div class='ays-form-title'>{$form_title}</div>
                                    " . $form_inputs . "
                                    <input type='submit' name='ays_finish_quiz' class='ays_finish action-button' value='" . __('See Result', $this->plugin_name) . "'/>
                                </div>
                              </div>" . $main_content_last_part;

            } elseif ($options['information_form'] == "before") {
                $main_content_first_part = $main_content_first_part . "<div class='step'>
                                    <div class='ays-abs-fs ays-start-page information_form'>
                                    <div class='ays-form-title'>{$form_title}</div>
                                        " . $form_inputs . "
                                       <input type='button' name='next' class='ays_next action-button' value='" . __('Next', $this->plugin_name) . "' />
                                    </div>
                                  </div>" ;

            }
        }else{
            $options['information_form'] = "disable";
        }
        
        
    /*******************************************************************************************************/
        
        /*
         * Script for getting quiz options
         *
         * Script for question type dropdown
         *
         * Generating HTML code
         */
        
        $quiz_content_script = "<script>";
        unset($quiz['options']);
        $quiz_options = $options;
        foreach($quiz as $k => $q){
            $quiz_options[$k] = $q;
        }

        if(isset($options['submit_redirect_delay'])){
            if($options['submit_redirect_delay'] == ''){
                $options['submit_redirect_delay'] = 0;
            }
            $options['submit_redirect_after'] = $this->secondsToWords($options['submit_redirect_delay']);
        }
        
        if ($limit_users) {
            $result = $this->get_user_by_ip($id);
            if ($result == 0) {
                $quiz_content_script .= "
                    if(typeof options === 'undefined'){
                        var options = [];
                    }
                    options['".$id."']  = '" . base64_encode(json_encode($options)) . "';";
            }
        }else{
            $quiz_content_script .= "
                if(typeof options === 'undefined'){
                    var options = [];
                }
                options['".$id."']  = '" . base64_encode(json_encode($options)) . "';";
        }
        $quiz_content_script .= "
            (function($){
                $(document).ready(function(){
                    function ays_formatState (ays_state) {
                      if(!ays_state.id) {
                        return aysEscapeHtml(ays_state.text);
                      }
                      var baseUrl = $(ays_state.element).data('nkar');
                      if(baseUrl != ''){
                          var ays_state = $(
                            '<span><img src=' + baseUrl + ' class=\'ays_answer_select_image\' /> ' + aysEscapeHtml(ays_state.text) + '</span>'
                          );
                      }else{
                          var ays_state = $(
                            '<span>' + aysEscapeHtml(ays_state.text) + '</span>'
                          );
                      }
                      return ays_state;
                    }
                    $(document).find('#ays-quiz-container-" . $id . " select.ays-select').select2({
                        placeholder: '".__( 'Select an answer', $this->plugin_name )."',
                        dropdownParent: $('#ays-quiz-container-" . $id . "'),
                        templateResult: ays_formatState
                    });
                    $(document).find('b[role=\"presentation\"]').addClass('ays_fa ays_fa_chevron_down');
                });
            })(jQuery);
        </script>";
        
    /*******************************************************************************************************/
        
        /*
         * Styles for quiz
         *
         * Generating HTML code
         */
        
        
        $quest_animation = 'shake';
        
        if(isset($options['quest_animation']) && $options['quest_animation'] != ''){
            $quest_animation = $options['quest_animation'];
        }
        
        $quiz_styles = "<style>
            div#ays-quiz-container-" . $id . " * {
                box-sizing: border-box;
            }

            /* Styles for Internet Explorer start */
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " {
                " . $ie_container_css . "
            }";

        if($ie_container_css != ''){
            $quiz_styles .= "#ays-quiz-container-" . $id . " .ays_next.action-button,
                            #ays-quiz-container-" . $id . " .ays_previous.action-button{
                                margin: 10px 5px;
                            }";
        }
                
        $quiz_styles .= "

            /* Styles for Quiz container */
            #ays-quiz-container-" . $id . "{
                min-height: " . $quiz_height . "px;
                width:" . $quiz_width . ";
                background-color:" . $bg_color . ";
                background-position:" . $quiz_bg_image_position . ";";

        if($ays_quiz_bg_image !== null){
            $quiz_styles .=  "background-image: url('$ays_quiz_bg_image');";
        } elseif($enable_background_gradient) {
            $quiz_styles .=  "background-image: linear-gradient($quiz_gradient_direction, $background_gradient_color_1, $background_gradient_color_2);";
        }

        if($quiz_modified_border_radius != ""){
            $quiz_styles .= $quiz_modified_border_radius;
        }else{
            $quiz_styles .=  "border-radius:" . $quiz_border_radius . "px;";
        }

        if($enable_box_shadow){
            $quiz_styles .=  "box-shadow: 0 0 15px 1px " . $this->hex2rgba($box_shadow_color, '0.4') . ";";
        }else{
            $quiz_styles .=  "box-shadow: none;";
        }
        if($enable_border){
            $quiz_styles .=  "border-width: " . $quiz_border_width.'px;'.
                           "border-style: " . $quiz_border_style.';'.
                           "border-color: " . $quiz_border_color.';';
        }else{
            $quiz_styles .=  "border: none;";
        }

        $quiz_styles .= "}

            /* Styles for questions */
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " div.step {
                min-height: " . $quiz_height . "px;
            }

            /* Styles for text inside quiz container */
            #ays-quiz-container-" . $id . " .ays_question_hint,
            #ays-quiz-container-" . $id . " label[for^=\"ays-answer-\"],
            #ays-quiz-container-" . $id . " p,
            #ays-quiz-container-" . $id . " .ays-fs-title,
            #ays-quiz-container-" . $id . " .ays-fs-subtitle,
            #ays-quiz-container-" . $id . " .logged_in_message,
            #ays-quiz-container-" . $id . " .ays_message{
               color: " . $text_color . ";
               outline: none;
            }
            
            #ays-quiz-container-" . $id . " .select2-container,
            #ays-quiz-container-" . $id . " .ays-field * {
                font-size: ".$answers_font_size."px !important;
            }
            
            #ays-quiz-container-" . $id . " input,
            #ays-quiz-container-" . $id . " textarea {
                color: #000;
                outline: none;
            }
            #ays-quiz-container-" . $id . " .wrong_answer_text{
                color:#ff4d4d;
            }
            #ays-quiz-container-" . $id . " .right_answer_text{
                color:#33cc33;
            }
            #ays-quiz-container-" . $id . " .ays_cb_and_a {
                color: " . $this->hex2rgba($text_color) . ";
            }



            /* Quiz rate and passed users count */
            #ays-quiz-container-" . $id . " .ays_quizn_ancnoxneri_qanak,
            #ays-quiz-container-" . $id . " .ays_quiz_rete_avg{
                color:" . $bg_color . ";
                background-color:" . $text_color . ";                                        
            }
            #ays-quiz-container-" . $id . " div.for_quiz_rate.ui.star.rating .icon {
                color: " . $this->hex2rgba($text_color, '0.35') . ";
            }
            #ays-quiz-container-" . $id . " .ays_quiz_rete_avg div.for_quiz_rate_avg.ui.star.rating .icon {
                color: " . $this->hex2rgba($bg_color, '0.5') . ";
            }
            #ays-quiz-container-" . $id . " .ays_quiz_rete .for_quiz_rate_reason textarea.quiz_rate_reason {
                background-color: " . $text_color . ";
                color: " . $bg_color . ";
            }


            /* Loaders */            
            #ays-quiz-container-" . $id . " div.lds-spinner,
            #ays-quiz-container-" . $id . " div.lds-spinner2 {
                color: " . $text_color . ";
            }
            #ays-quiz-container-" . $id . " div.lds-spinner div:after,
            #ays-quiz-container-" . $id . " div.lds-spinner2 div:after {
                background-color: " . $text_color . ";
            }
            #ays-quiz-container-" . $id . " .lds-circle,
            #ays-quiz-container-" . $id . " .lds-facebook div,
            #ays-quiz-container-" . $id . " .lds-ellipsis div{
                background: " . $text_color . ";
            }
            #ays-quiz-container-" . $id . " .lds-ripple div{
                border-color: " . $text_color . ";
            }
            #ays-quiz-container-" . $id . " .lds-dual-ring::after,
            #ays-quiz-container-" . $id . " .lds-hourglass::after{
                border-color: " . $text_color . " transparent " . $text_color . " transparent;
            }


            /* Progress bars */
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " .ays-progress {
                border-color: " . $this->hex2rgba($text_color, '0.8') . ";
            }
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " .ays-progress-bg {
                background-color: " . $this->hex2rgba($text_color, '0.3') . ";
            }
            #ays-quiz-container-" . $id . " .ays-progress-value {
                color: " . $text_color . ";
            }
            #ays-quiz-container-" . $id . " .ays-progress-bar {
                background-color: " . $color . ";
            }
            #ays-quiz-container-" . $id . " .ays-question-counter .ays-live-bar-wrap {
                direction:ltr !important;
            }
            #ays-quiz-container-" . $id . " .ays-live-bar-fill{
                color: " . $text_color . ";
                border-bottom: 2px solid " . $this->hex2rgba($text_color, '0.8') . ";
                text-shadow: 0px 0px 5px " . $bg_color . ";
            }
            #ays-quiz-container-" . $id . " .ays-live-bar-percent{
                display:none;
            }
            
            /* Music, Sound */
            #ays-quiz-container-" . $id . " .ays_music_sound {
                color:" . $this->hex2rgba($text_color) . ";
            }

            /* Dropdown questions scroll bar */
            #ays-quiz-container-" . $id . " blockquote {
                border-left-color: " . $text_color . " !important;                                      
            }


            /* Question hint */
            #ays-quiz-container-" . $id . " .ays_question_hint_container .ays_question_hint_text {
                background-color:" . $bg_color . ";
                box-shadow: 0 0 15px 3px " . $this->hex2rgba($box_shadow_color, '0.6') . ";
            }

            /* Information form */
            #ays-quiz-container-" . $id . " .ays-form-title{
                color:" . $this->hex2rgba($text_color) . ";
            }

            /* Quiz timer */
            #ays-quiz-container-" . $id . " div.ays-quiz-timer{
                color: " . $text_color . ";
            }
            
            /* Quiz buttons */
            #ays-quiz-container-" . $id . " .ays_arrow {
                color:". $text_color ."!important;
            }
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " .action-button {
                background-color: " . $color . ";
                color:" . $text_color . ";
            }
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " .action-button:hover,
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " .action-button:focus {
                box-shadow: 0 0 0 2px $text_color;
                background-color: " . $color . ";
            }
            #ays-quiz-container-" . $id . " .ays_restart_button {
                color: " . $color . ";
            }
                        
            /* Question answers */
            #ays-quiz-container-".$id." .ays-field {
                border-color: " . $text_color . ";
            }
            #ays-quiz-container-" . $id . " .ays-quiz-answers .ays-field:hover{
                opacity: 1;
            }
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " .ays-field input:checked+label:before {
                border-color: " . $color . ";
                background: " . $color . ";
                background-clip: content-box;
            }
            #ays-quiz-container-" . $id . " .ays-quiz-answers div.ays-text-right-answer,
            #ays-quiz-container-" . $id . " .ays-field.ays-text-field textarea.ays-text-input::placeholder {
                color: " . $text_color . ";
            }
            
            /* Questions answer image */
            #ays-quiz-container-" . $id . " .ays-answer-image {
                width:" . (isset($options['answers_view']) && ($options['answers_view'] == "grid") ? "90%" : "50%") . ";
            }
            
            /* Dropdown questions */            
            #ays-quiz-container-" . $id . " #ays_finish_quiz_" . $id . " .ays-field .select2-container--default .select2-selection--single {
                border-bottom: 2px solid " . $color . ";
            }
            #ays-quiz-container-" . $id . " .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: " . $color . ";
            }
            
            /* Dropdown questions scroll bar */
            #ays-quiz-container-" . $id . " .select2-results__options::-webkit-scrollbar {
                width: 7px;
            }
            #ays-quiz-container-" . $id . " .select2-results__options::-webkit-scrollbar-track {
                background-color: " . $this->hex2rgba($bg_color, '0.35') . ";
            }
            #ays-quiz-container-" . $id . " .select2-results__options::-webkit-scrollbar-thumb {
                transition: .3s ease-in-out;
                background-color: " . $this->hex2rgba($bg_color, '0.55') . ";
            }
            #ays-quiz-container-" . $id . " .select2-results__options::-webkit-scrollbar-thumb:hover {
                transition: .3s ease-in-out;
                background-color: " . $this->hex2rgba($bg_color, '0.85') . ";
            }
                        
            /* Custom css styles */
            " . $options['custom_css'] . "
            
            /* RTL direction styles */
            " . $rtl_style . "
        </style>";


        
    /*******************************************************************************************************/
        
        /*
         * Quiz container
         *
         * Generating HTML code
         */
        
        $quiz_theme = "";
        $options['quiz_theme'] = (array_key_exists('quiz_theme', $options)) ? $options['quiz_theme'] : '';
        switch ($options['quiz_theme']) {
            case 'elegant_dark':
                $quiz_theme = "ays_quiz_elegant_dark";
                break;
            case 'elegant_light':
                $quiz_theme = "ays_quiz_elegant_light";
                break;
            case 'rect_dark':
                $quiz_theme = "ays_quiz_rect_dark";
                break;
            case 'rect_light':
                $quiz_theme = "ays_quiz_rect_light";
                break;
        }
        
        $custom_class = isset($options['custom_class']) && $options['custom_class'] != "" ? $options['custom_class'] : "";

        $quiz_container_first_part = "
            <div class='ays-quiz-container ".$quiz_theme." ".$custom_class."' data-quest-effect='".$quest_animation."' id='ays-quiz-container-" . $id . "'>
                {$live_progress_bar}
                {$ays_quiz_music_html}
                <div class='ays-questions-container'>
                    $ays_quiz_reports
                    <form 
                        action='' 
                        method='post' 
                        id='ays_finish_quiz_" . $id . "' 
                        class='" . $correction_class . " " . $enable_questions_result . " " . $enable_logged_users . "'
                    >";
        
        $quiz_container_first_part .= "
            <input type='hidden' value='" . $answer_view_class . "' class='answer_view_class'>
            <input type='hidden' value='" . $enable_arrows . "' class='ays_qm_enable_arrows'>";
        
        $quiz_container_middle_part = "";
        if($limit_users === true){
            $quiz_container_middle_part = $limit_users_html;
            $main_content_first_part = "";
            $main_content_last_part = "";
        }
        if($user_massage !== null){
            $quiz_container_middle_part = $user_massage;
            $main_content_first_part = "";
            $main_content_last_part = "";
        }
        

        $quiz_container_last_part = $quiz_content_script;
        $quiz_container_last_part .= "
                    <input type='hidden' name='quiz_id' value='" . $id . "'/>
                    <input type='hidden' name='start_date' class='ays-start-date'/>
                    " . wp_nonce_field('ays_finish_quiz', 'ays_finish_quiz') . "
                </form>
            </div>
        </div>";
        
        
    /*******************************************************************************************************/
        
        /*
         * Generating Quiz parts array
         */
        
        $quiz_parts = array(
            "container_first_part" => $quiz_container_first_part,
            "main_content_first_part" => $main_content_first_part,
            "main_content_middle_part" => $quiz_container_middle_part,
            "main_content_last_part" => $main_content_last_part,
            "quiz_styles" => $quiz_styles,
            "quiz_additional_styles" => "",
            "container_last_part" => $quiz_container_last_part,
        );
        
        $quizOptions = array(
            'buttons' => $buttons,
            'correction' => $enable_correction,
            'randomizeAnswers' => $randomize_answers,
            'questionImageWidth' => $question_image_width,
            'questionImageHeight' => $question_image_height,
            'questionImageSizing' => $question_image_sizing,
            'questionsCounter' => $questions_counter,
            'informationForm' => $options['information_form'],
            'answersViewClass' => $answer_view_class,
            'quizTheme' => $options['quiz_theme'],
            'rtlDirection' => $rtl_direction,
        );
        
        $ays_quiz = (object)array(
            "quizID" => $id,
            "quizOptions" => $quizOptions,
            "questions" => $arr_questions,
            "questionsCount" => $questions_count,
            "quizParts" => $quiz_parts,
            "quizColors" => array(
                "Color" => $color,
                "textColor" => $text_color,
                "bgColor" => $bg_color,
                "boxShadowColor" => $box_shadow_color,
                "borderColor" => $quiz_border_color
            )
        );
            
        return $ays_quiz;
    }

    public function ays_generate_quiz($quiz){
        
        $quiz_id = $quiz->quizID;
        $arr_questions = $quiz->questions;
        $questions_count = $quiz->questionsCount;
        $options = $quiz->quizOptions;
        $questions = "";
        $questions = $this->get_quiz_questions($arr_questions, $quiz_id, $options, false);
        
        if($quiz->quizParts['main_content_middle_part'] == ""){
            $quiz->quizParts['main_content_middle_part'] = $questions;
        }
        $additional_css = "
            <style>
                #ays-quiz-container-" . $quiz_id . " p {
                    margin: 0.625em;
                }
                
                #ays-quiz-container-" . $quiz_id . " .ays-field.checked_answer_div input:checked+label {
                    background-color: " . $this->hex2rgba($quiz->quizColors['Color'], '0.6') . ";
                }

                #ays-quiz-container-" . $quiz_id . " .ays-field.checked_answer_div input:checked+label:hover {
                    background-color: " . $this->hex2rgba($quiz->quizColors['Color'], '0.8') . ";
                }

                #ays-quiz-container-" . $quiz_id . " .ays-field:hover label{
                    background: " . $this->hex2rgba($quiz->quizColors['Color'], '0.8') . ";
                    border-radius: 4px;
                    color: #fff;
                    transition: all .3s;
                }
                
                #ays-quiz-container-" . $quiz_id . " #ays_finish_quiz_" . $quiz_id . " .action-button:hover,
                #ays-quiz-container-" . $quiz_id . " #ays_finish_quiz_" . $quiz_id . " .action-button:focus {
                    box-shadow: 0 0 0 2px white, 0 0 0 3px " . $quiz->quizColors['Color'] . ";
                    background: " . $quiz->quizColors['Color'] . ";
                }
            </style>";
        
        $quiz->quizParts['quiz_additional_styles'] = $additional_css;
        
        $container = implode("", $quiz->quizParts);
        
        return $container;
    }

    public function get_quiz_by_id($id){
        global $wpdb;

        $sql = "SELECT *
                FROM {$wpdb->prefix}aysquiz_quizes
                WHERE id=" . $id;

        $quiz = $wpdb->get_row($sql, 'ARRAY_A');

        return $quiz;
    }
    
    public function get_quiz_results_count_by_id($id){
        global $wpdb;

        $sql = "SELECT COUNT(*) AS res_count
                FROM {$wpdb->prefix}aysquiz_reports
                WHERE quiz_id=" . $id;

        $quiz = $wpdb->get_row($sql, 'ARRAY_A');

        return $quiz;
    }

    public function get_quiz_attributes_by_id($id){
        global $wpdb;
        $quiz_attrs = isset(json_decode($this->get_quiz_by_id($id)['options'])->quiz_attributes) ? json_decode($this->get_quiz_by_id($id)['options'])->quiz_attributes : array();
        $quiz_attributes = implode(',', $quiz_attrs);
        if (!empty($quiz_attributes)) {
            $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_attributes WHERE `id` in ($quiz_attributes)";
            $results = $wpdb->get_results($sql);
            return $results;
        }
        return array();

    }

    public function get_quiz_questions($ids, $quiz_id, $options, $per_page){
        
        $container = $this->ays_questions_parts($ids, $quiz_id, $options, $per_page);
        $questions_container = array();
        foreach($container as $key => $question){
            $answer_container = '';
            switch ($question["questionType"]) {
                case "select":
                    $ans_options = array(
                        'correction' => $options['correction']
                    );
                    $answer_container .= $this->ays_dropdown_answer_html($question['questionID'], $quiz_id, $question['questionAnswers'], $ans_options);
                    break;
                case "text":
                    $ans_options = array(
                        'correction' => $options['correction']
                    );
                    $answer_container .= $this->ays_text_answer_html($question['questionID'], $quiz_id, $question['questionAnswers'], $ans_options);
                    break;
                case "number":
                    $ans_options = array(
                        'correction' => $options['correction']
                    );
                    $answer_container .= $this->ays_number_answer_html($question['questionID'], $quiz_id, $question['questionAnswers'], $ans_options);
                    break;
                default:
                    $ans_options = array(
                        'correction' => $options['correction'],
                        'rtlDirection' => $options['rtlDirection'],
                        'questionType' => $question["questionType"],
                        'answersViewClass' => $options['answersViewClass'],
                    );
                    $answer_container .= $this->ays_default_answer_html($question['questionID'], $quiz_id, $question['questionAnswers'], $ans_options);
                    break;
            }
            $question['questionParts']['question_middle_part'] = $answer_container;
            $questions_container[] = implode("", $question['questionParts']);
        }
        $container = implode("", $questions_container);
        return $container;
    }
    
    public function ays_questions_parts($ids, $quiz_id, $options, $per_page){
        global $wpdb;
        $total = count($ids);
        $container = array();
        $buttons = $options['buttons'];
        $enable_arrows = $buttons['enableArrows'];
        
        foreach($ids as $key => $id){
            $current = $key + 1;
            if($total == $current){
                $last = true;
            }else{
                $last = false;
            }
            $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_questions WHERE id = " . $id;
            $question = $wpdb->get_row($sql, 'ARRAY_A');
            
            if (!empty($question)) {
                $answers = $this->get_answers_with_question_id($question["id"]);
                $question_image = '';
                $question_image_style = '';
                
                $question['not_influence_to_score'] = ! isset($question['not_influence_to_score']) ? 'off' : $question['not_influence_to_score'];
                $not_influence_to_score = (isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on') ? true : false;

                $question_image_style = "style='width:{$options['questionImageWidth']};height:{$options['questionImageHeight']};object-fit:{$options['questionImageSizing']};object-position:center center;'";
                
                if ($question['question_image'] != NULL) {
                    $question_image = '<div class="ays-image-question-img"><img src="' . $question['question_image'] . '" alt="Question Image" ' . $question_image_style . '></div>';
                }
                $answer_view_class = "";
                $question_hint = '';
                $user_explanation = "";
                if ($options['randomizeAnswers']) {
                    shuffle($answers);
                }
                if (isset($question['question_hint']) && strlen($question['question_hint']) !== 0) {
                    $question_hint = "<div class='ays_question_hint_container'><i class='ays_fa ays_fa_info_circle ays_question_hint' aria-hidden='true'></i><span class='ays_question_hint_text'>" . do_shortcode(wpautop(stripslashes($question['question_hint']))) . "</span></div>";
                }
                if(isset($question['user_explanation']) && $question['user_explanation'] == 'on'){
                    $user_explanation = "<div class='ays_user_explanation'>
                        <textarea placeholder='".__('You can enter your answer explanation',$this->plugin_name)."' class='ays_user_explanation_text' name='user-answer-explanation[{$id}]'></textarea>
                    </div>";
                }

                if($question['wrong_answer_text'] == ''){
                    $wrong_answer_class = 'ays_do_not_show';
                }else{
                    $wrong_answer_class = '';
                }
                if($question['right_answer_text'] == ''){
                    $right_answer_class = 'ays_do_not_show';
                }else{
                    $right_answer_class = '';
                }
                
                if($options['questionsCounter']){
                    $questions_counter = "<p class='ays-question-counter animated'>{$current} / {$total}</p>";
                }else{
                    $questions_counter = "";
                }
                
                $early_finish = "";
                
                if($buttons['earlyButton']){
                    $early_finish = "<i class='" . ($enable_arrows ? '' : 'ays_display_none'). " ays_fa ays_fa_flag_checkered ays_early_finish action-button ays_arrow'></i><input type='button' name='next' class='" . ($enable_arrows ? 'ays_display_none' : '') . " ays_early_finish action-button' value='" . __('Finish', $this->plugin_name) . "'/>";
                }
                
                if ($last) {
                    switch($options['informationForm']){
                        case "disable":
                            $input = "<i class='" . ($enable_arrows ? '' : 'ays_display_none') . " ays_fa ays_fa_flag_checkered ays_finish action-button ays_arrow'></i><input type='submit' name='ays_finish_quiz' class=' " . ($enable_arrows ? 'ays_display_none' : '') . " ays_finish action-button' value='" . __('See Result', $this->plugin_name) . "'/>";
                            break;
                        case "before":
                            $input = "<i class='" . ($enable_arrows ? '' : 'ays_display_none') . " ays_fa ays_fa_flag_checkered ays_finish action-button ays_arrow'></i><input type='submit' name='ays_finish_quiz' class=' " . ($enable_arrows ? 'ays_display_none' : '') . " ays_finish action-button' value='" . __('See Result', $this->plugin_name) . "'/>";
                            break;
                        case "after":
                            $input = "<i class='" . ($enable_arrows ? '' : 'ays_display_none') . " ays_fa ays_fa_flag_checkered ays_finish action-button ays_arrow'></i><input type='button' name='next' class=' " . ($enable_arrows ? 'ays_display_none' : '') . " ays_next action-button' value='" . __('Finish', $this->plugin_name) . "' />";
                            break;
                        default:
                            $input = "<i class='" . ($enable_arrows ? '' : 'ays_display_none') . " ays_fa ays_fa_flag_checkered ays_finish action-button ays_arrow'></i><input type='submit' name='ays_finish_quiz' class=' " . ($enable_arrows ? 'ays_display_none' : '') . " ays_finish action-button' value='" . __('See Result', $this->plugin_name) . "'/>";
                            break;                        
                    }
                    $buttons_div = "<div class='ays_buttons_div'>
                            <i class=\"ays_fa ays_fa_arrow_left ays_previous action-button ays_arrow " . $buttons['prevArrow'] . "\"></i>
                            <input type='button' name='next' class='ays_previous action-button " . $buttons['prevButton'] . "'  value='".__('Prev', $this->plugin_name)."' />
                            {$input}
                        </div>";
                }else{
                    $buttons_div = "<div class='ays_buttons_div'>
                        <i class=\"ays_fa ays_fa_arrow_left ays_previous action-button ays_arrow " . $buttons['prevArrow'] . "\"></i>
                        <input type='button' name='next' class='ays_previous action-button " . $buttons['prevButton'] . "' value='".__('Prev', $this->plugin_name)."' />
                        <i class=\"ays_fa ays_fa_arrow_right ays_next action-button ays_arrow ays_next_arrow " . $buttons['nextArrow'] . "\"></i>
                        <input type='button' name='next' class='ays_next action-button " . $buttons['nextButton'] . "' value='" . __('Next', $this->plugin_name) . "' />
                        " . $early_finish . "
                    </div>";
                }
                
                $additional_css = "";
                $answer_view_class = $options['answersViewClass'];
                
                switch ($options['quizTheme']) {
                    case 'elegant_dark':
                    case 'elegant_light':
                    case 'rect_dark':
                    case 'rect_light':
                        $question_html = "<div class='ays_quiz_question'>
                                " . do_shortcode(wpautop(stripslashes($question['question']))) . "
                            </div>
                            {$question_image}";
                        $answer_view_class = "ays_".$answer_view_class."_view_container";
                        break;
                    default:
                        $question_html = "<div class='ays_quiz_question'>
                                " . do_shortcode(wpautop(stripslashes($question['question']))) . "
                            </div>
                            {$question_image}";
                        $answer_view_class = "ays_".$answer_view_class."_view_container";
                        break;
                }
                $not_influence_to_score_class = $not_influence_to_score ? 'not_influence_to_score' : '';
                $container_first_part = "<div class='step ".$not_influence_to_score_class."' data-question-id='" . $question["id"] . "'>
                    {$question_hint}
                    {$questions_counter}
                    <div class='ays-abs-fs'>
                        {$question_html}
                        <div class='ays-quiz-answers $answer_view_class'>";
                                            
                $container_last_part = "</div>
                        {$user_explanation}
                        {$buttons_div}
                        <div class='wrong_answer_text $wrong_answer_class' style='display:none'>
                            " . do_shortcode(wpautop(stripslashes($question['wrong_answer_text']))) . "
                        </div>
                        <div class='right_answer_text $right_answer_class' style='display:none'>
                            " . do_shortcode(wpautop(stripslashes($question["right_answer_text"]))) . "
                        </div>
                        <div class='ays_questtion_explanation' style='display:none'>
                            " . do_shortcode(wpautop(stripslashes($question["explanation"]))) . "
                        </div>
                        {$additional_css}
                    </div>
                </div>";
                
                $container[] = array(
                    'quizID' => $quiz_id,
                    'questionID' => $question['id'],
                    'questionAnswers' => $answers,
                    'questionType' => $question["type"],
                    'questionParts' => array(
                        'question_first_part' => $container_first_part,
                        'question_middle_part' => "",
                        'question_last_part' => $container_last_part
                    )
                );
            }
        }
        return $container;
    }
    
    protected function get_answers_with_question_id($id){
        global $wpdb;

        $sql = "SELECT *
                FROM {$wpdb->prefix}aysquiz_answers
                WHERE question_id=" . $id;

        $answer = $wpdb->get_results($sql, 'ARRAY_A');

        return $answer;
    }

    public function get_quiz_questions_count($id){
        global $wpdb;

        $sql = "SELECT `question_ids`
                FROM {$wpdb->prefix}aysquiz_quizes
                WHERE id=" . $id;

        $questions_str = $wpdb->get_row($sql, 'ARRAY_A');
        $questions = explode(',', $questions_str['question_ids']);
        return $questions;
    }

    public function ays_finish_quiz(){
        error_reporting(0);
        ob_start();
        if (isset($_REQUEST["ays_finish_quiz"]) && wp_verify_nonce($_REQUEST["ays_finish_quiz"], 'ays_finish_quiz')) {
            global $wpdb;
            $quiz_id = absint(intval($_REQUEST['ays_quiz_id']));
            $questions_answers = (isset($_REQUEST["ays_questions"])) ? $_REQUEST['ays_questions'] : array();

            $quiz = $this->get_quiz_by_id($quiz_id);
            $quiz_intervals = json_decode($quiz['intervals']);
            $options = json_decode($quiz['options']);
            $quiz_questions_count = $this->get_quiz_questions_count($quiz_id);

            if (isset($options->enable_question_bank) && $options->enable_question_bank == 'on' && isset($options->questions_count) && intval($options->questions_count) > 0 && count($quiz_questions_count) > intval($options->questions_count)) {
                $question_ids = preg_split('/,/', $_REQUEST['ays_quiz_questions']);
            } else {
                $question_ids = $this->get_quiz_questions_count($quiz_id);
            }

            // Disable store data 
            $options->disable_store_data = ! isset( $options->disable_store_data ) ? 'off' : $options->disable_store_data;
            $disable_store_data = (isset($options->disable_store_data) && $options->disable_store_data == 'off') ? true : false;

            $questions_count = count($question_ids);
            $correctness = array();
            
            if (is_array($questions_answers)) {
                foreach ($questions_answers as $key => $questions_answer) {
                    $question_id = explode('-', $key)[2];
                    if($this->is_question_not_influence($question_id)){
                        $questions_count--;
                        continue;
                    }
                    $multiple_correctness = array();
                    $has_multiple = $this->has_multiple_correct_answers($question_id);
                    if ($has_multiple) {
                        if (is_array($questions_answer)) {
                            foreach ($questions_answer as $answer_id) {
                                $multiple_correctness[] = $this->check_answer_correctness($question_id, $answer_id);
                            }
                            if ($this->isHomogenous($multiple_correctness)) {
                                $correctness[] = true;
                            } else {
                                $correctness[] = false;
                            }
                        } else {
                            $correctness[] = false;
                        }
                    } elseif($this->has_text_answer($question_id)) {
                        $correctness[] = $this->check_text_answer_correctness($question_id, $questions_answer);
                    } else {
                        $correctness[] = $this->check_answer_correctness($question_id, $questions_answer);
                    }
                }
                
                $average_percent = 100 / $questions_count;

                $correct_answered_count = array_sum($correctness);

                $final_score = floor(($average_percent * $correct_answered_count));
                                
                $data = array(
                    'user_ip' => $this->get_user_ip(),
                    'user_name' => $_REQUEST['ays_user_name'],
                    'user_email' => $_REQUEST['ays_user_email'],
                    'user_phone' => $_REQUEST['ays_user_phone'],
                    'start_date' => esc_sql($_REQUEST['start_date']),
                    'end_date' => esc_sql($_REQUEST['end_date']),
                    'score' => $final_score,
                    'quiz_id' => absint(intval($_REQUEST["quiz_id"]))
                );

                // Disabling store data in DB
                if($disable_store_data){
                    $result = $this->add_results_to_db($data);
                }else{
                    $result = true;
                }

                $result_text = (isset($options->result_text) && $options->result_text != '')?stripslashes(wpautop($options->result_text)) : '';
                
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                if ($result) {
                    echo json_encode(array(
                        "status" => true,
                        "score" => $final_score,
                        "text" => $result_text,
                    ));
                    wp_die();                
                }else{
                    echo json_encode(array(
                        "status" => false, 
                        "text" => "No no no"
                    ));
                    wp_die();
                }

            } else {
                $admin_mails = get_option('admin_email');
                
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                
                echo json_encode(array(
                    "status" => false, 
                    "text" => "No no no", 
                    "admin_mail" => $admin_mails 
                ));
                wp_die();
            }
        }
    }

    public function check_answer_correctness($question_id, $answer_id){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $question_id = absint(intval($question_id));
        $answer_id = absint(intval($answer_id));
        $checks = $wpdb->get_row("SELECT * FROM {$answers_table} WHERE question_id={$question_id} AND id={$answer_id}", "ARRAY_A");

        if (absint(intval($checks["correct"])) == 1) {
            return true;
        }

        return false;
    }

    public function check_text_answer_correctness($question_id, $answer){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $question_id = absint(intval($question_id));
        $answer_id = absint(intval($answer_id));
        $checks = $wpdb->get_row("SELECT COUNT(*) AS qanak, answer FROM {$answers_table} WHERE question_id={$question_id}", ARRAY_A);
        
        if (intval($checks['qanak']) > 0) {
            if(strtolower($checks['answer']) == strtolower($answer)){
                return true;
            }
        }
        return false;
    }
    
    public function has_multiple_correct_answers($question_id){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $question_id = absint(intval($question_id));

        $get_answers = $wpdb->get_var("SELECT COUNT(*) FROM {$answers_table} WHERE question_id={$question_id} AND correct=1");

        if (intval($get_answers) > 1) {
            return true;
        }
        return false;
    }
    
    public function is_question_not_influence($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "aysquiz_questions";
        $question_id = absint(intval($question_id));

        $question = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id};", "ARRAY_A");
        $question['not_influence_to_score'] = ! isset($question['not_influence_to_score']) ? 'off' : $question['not_influence_to_score'];
        if(isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on'){
            return true;
        }
        return false;
    }

    public function has_text_answer($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "aysquiz_questions";
        $question_id = absint(intval($question_id));

        $get_answers = $wpdb->get_var("SELECT type FROM {$questions_table} WHERE id={$question_id}");

        if ($get_answers == 'text' || $get_answers == 'number') {
            return true;
        }
        return false;
    }
    
    public function ays_default_answer_html($question_id, $quiz_id, $answers, $options){
        $answer_container = "";
        foreach ($answers as $answer) {
            $answer_image = (isset($answer['image']) && $answer['image'] != '') ? "<img src='{$answer["image"]}' alt='answer_image' class='ays-answer-image'>" : "";

            if($answer_image == ""){
                $ays_field_style = "";
                $answer_label_style = "";
            }else{
                if($options['rtlDirection']){
                    $ays_flex_dir = 'unset';
                    $ays_border_dir = "right";
                }else{
                    $ays_flex_dir = 'row-reverse';
                    $ays_border_dir = "left";
                }
                if($answer_view_class == 'grid'){
                    $ays_field_style = "style='display: block; height: fit-content; margin-bottom: 10px; width:200px;'";
                    $answer_label_style = "style='margin-bottom: 0; box-shadow: 0px 0px 10px; line-height: 1.5'";
                }else{
                    $ays_field_style = "style='margin-bottom: 10px; border-radius: 4px 4px 0 0; overflow: hidden; display: flex; box-shadow: 0px 0px 10px; flex-direction: {$ays_flex_dir};'";
                    $answer_label_style = "style='border-radius:0; border-{$ays_border_dir}:1px solid #ccc; line-height: 100px'";
                }
            }
            $label = "";
            if( $answer["answer"] != "" ){
                $label .= "<label for='ays-answer-{$answer["id"]}-{$quiz_id}' $answer_label_style>" . do_shortcode(htmlspecialchars(stripslashes($answer["answer"]))) . "</label>";
            }
            if( $answer_image != "" ){
                $label .= "<label for='ays-answer-{$answer["id"]}-{$quiz_id}' style='border-radius:0;margin:0;padding:0;height:100px;'>{$answer_image}</label>";
            }
            $answer_container .= "
            <div class='ays-field ays_" . $options['answersViewClass'] . "_view_item' $ays_field_style>
                <input type='hidden' name='ays_answer_correct[]' value='{$answer["correct"]}'/>

                <input type='{$options["questionType"]}' name='ays_questions[ays-question-{$question_id}]' id='ays-answer-{$answer["id"]}-{$quiz_id}' value='{$answer["id"]}'/>

                {$label}    

            </div>";
        }
        
        return $answer_container;
    }
    
    protected function ays_text_answer_html($question_id, $quiz_id, $answers, $options){
        $enable_correction = $options['correction'] ? "display:inline-block;white-space: nowrap;" : "display:none";
        $enable_correction_textarea = $options['correction'] ? "width:80%;" : "width:100%;";
        $answer_container = "<div class='ays-field ays-text-field'>";
            foreach ($answers as $answer) {
                $answer_image = (isset($answer['image']) && $answer['image'] != '') ? $answer["image"] : "";
                $answer_container .= "<textarea style='$enable_correction_textarea' type='text' class='ays-text-input' name='ays_questions[ays-question-{$question_id}]'></textarea>
                <input type='hidden' name='ays_answer_correct[]' value='0'/>
                <button type='button' style='$enable_correction' class='ays_check_answer action-button'>".(__('Check', $this->plugin_name))."</button>";
            }

            foreach ($answers as $answer) {
                $answer_container .= "<script>
                        if(typeof window.quizOptions_$quiz_id === 'undefined'){
                            window.quizOptions_$quiz_id = [];
                        }
                        window.quizOptions_$quiz_id.push('" . base64_encode(json_encode(array(
                            'question_type' => 'text',
                            'question_answer' => htmlspecialchars(stripslashes($answer["answer"]))
                        ))) . "');
                    </script>";
            }

        $answer_container .= "</div>";
        return $answer_container;
    }
    
    protected function ays_number_answer_html($question_id, $quiz_id, $answers, $options){
        $enable_correction = $options['correction'] ? "display:inline-block;white-space: nowrap;" : "display:none";
        $enable_correction_textarea = $options['correction'] ? "width:80%;" : "width:100%;";
        $answer_container = "<div class='ays-field ays-text-field'>";
            foreach ($answers as $answer) {
                $answer_image = (isset($answer['image']) && $answer['image'] != '') ? $answer["image"] : "";
                $answer_container .= "<input style='$enable_correction_textarea' type='number' class='ays-text-input' name='ays_questions[ays-question-{$question_id}]'>
                <input type='hidden' name='ays_answer_correct[]' value='0'/>
                <button type='button' style='$enable_correction' class='ays_check_answer action-button'>".(__('Check', $this->plugin_name))."</button>";
            }

            foreach ($answers as $answer) {
                $answer_container .= "<script>
                        if(typeof window.quizOptions_$quiz_id === 'undefined'){
                            window.quizOptions_$quiz_id = [];
                        }
                        window.quizOptions_$quiz_id.push('" . base64_encode(json_encode(array(
                            'question_type' => 'text',
                            'question_answer' => htmlspecialchars(stripslashes($answer["answer"]))
                        ))) . "');
                    </script>";
            }
        
        $answer_container .= "</div>";
        return $answer_container;
    }

    protected function ays_dropdown_answer_html($question_id, $quiz_id, $answers, $options){
        
        $answer_container = "<div class='ays-field ays-select-field'>            
            <select class='ays-select'>                
                <option value=''>".__('Select an answer', $this->plugin_name)."</option>";
            foreach ($answers as $answer) {
                $answer_image = (isset($answer['image']) && $answer['image'] != '') ? $answer["image"] : "";
                $answer_container .= "<option data-nkar='{$answer_image}' data-chisht='{$answer["correct"]}' value='{$answer["id"]}'>" . do_shortcode(htmlspecialchars(stripslashes($answer["answer"]))) . "</option>";
            }
        $answer_container .= "</select>";
        $answer_container .= "<input class='ays-select-field-value' type='hidden' name='ays_questions[ays-question-{$question_id}]' value=''/>";

            foreach ($answers as $answer) {
                $answer_container .= "<input type='hidden' name='ays_answer_correct[]' value='{$answer["correct"]}'/>";
            }
        $answer_container .= "</div>";
        
        return $answer_container;
    }
    
    protected function isHomogenous($arr){
        $mustBe = true;
        foreach ($arr as $val) {
            if ($mustBe !== $val) {
                return false;
            }
        }
        return true;
    }

    protected function hex2rgba($color, $opacity = false){

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }else{
            return $color;
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    protected function secondsToWords($seconds){
        $ret = "";

        /*** get the days ***/
        $days = intval(intval($seconds) / (3600 * 24));
        if ($days > 0) {
            $ret .= "$days days ";
        }

        /*** get the hours ***/
        $hours = (intval($seconds) / 3600) % 24;
        if ($hours > 0) {
            $ret .= "$hours hours ";
        }

        /*** get the minutes ***/
        $minutes = (intval($seconds) / 60) % 60;
        if ($minutes > 0) {
            $ret .= "$minutes minutes ";
        }

        /*** get the seconds ***/
        $seconds = intval($seconds) % 60;
        if ($seconds > 0) {
            $ret .= "$seconds seconds";
        }

        return $ret;
    }

    protected function add_results_to_db($data){
        global $wpdb;
        $results_table = $wpdb->prefix . 'aysquiz_reports';

        $user_ip = $data['user_ip'];
        $user_name = $data['user_name'];
        $user_email = $data['user_email'];
        $user_phone = $data['user_phone'];
        $quiz_id = $data['quiz_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $score = $data['score'];
        $options = array();
        $options['passed_time'] = $this->get_time_difference($start_date, $end_date);
        
        $quiz_attributes_information = array();
        $quiz_attributes = $this->get_quiz_attributes_by_id($quiz_id);
        
        $options['attributes_information'] = $quiz_attributes_information;
        $results = $wpdb->insert(
            $results_table,
            array(
                'quiz_id' => absint(intval($quiz_id)),
                'user_id' => get_current_user_id(),
                'user_name' => $user_name,
                'user_email' => $user_email,
                'user_phone' => $user_phone,
                'user_ip' => $user_ip,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'score' => $score,
                'options' => json_encode($options)
            ),
            array(
                '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s'
            )
        );

        if ($results >= 0) {
            return true;
        }

        return false;
    }
    
    protected function ays_get_count_of_rates($id){
        global $wpdb;
        $sql = "SELECT COUNT(`id`) AS count FROM {$wpdb->prefix}aysquiz_rates WHERE quiz_id= $id";
        $result = $wpdb->get_var($sql);
        return $result;
    }
    
    protected function ays_get_count_of_reviews($start, $limit, $quiz_id){
        global $wpdb;
        $sql = "SELECT COUNT(`id`) AS count FROM {$wpdb->prefix}aysquiz_rates WHERE (review<>'' OR options<>'') AND quiz_id = $quiz_id ORDER BY id DESC LIMIT $start, $limit";
        $result = $wpdb->get_var($sql);
        return $result;
    }
    
    protected function ays_set_rate_id_of_result($id){
        global $wpdb;
        $results_table = $wpdb->prefix . 'aysquiz_reports';
        $sql = "SELECT MAX(id) AS max_id FROM $results_table WHERE end_date = ( SELECT MAX(end_date) FROM $results_table )";
        $res = $wpdb->get_results($sql, ARRAY_A);
        $options = array(
            'rate_id' => $id
        );
        $results = $wpdb->update(
            $results_table,
            array( 'options' => json_encode($options) ),
            array( 'id' => intval($res[0]['max_id'])),
            array( '%s' ),
            array( '%d' )
        );
        if($results !== false){
            return true;
        }
        return false;
    }
    
    protected function ays_get_average_of_rates($id){
        global $wpdb;
        $sql = "SELECT AVG(`score`) AS avg_score FROM {$wpdb->prefix}aysquiz_rates WHERE quiz_id= $id";
        $result = $wpdb->get_var($sql);
        return $result;
    }
    
    protected function ays_get_reasons_of_rates($start, $limit, $quiz_id){
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_rates WHERE quiz_id=$quiz_id AND (review<>'' OR options<>'') ORDER BY id DESC LIMIT $start, $limit";
        $result = $wpdb->get_results($sql, "ARRAY_A");
        return $result;
    }
    
    protected function ays_get_full_reasons_of_rates($start, $limit, $quiz_id, $zuyga){
        $quiz_rate_reasons = $this->ays_get_reasons_of_rates($start, $limit, $quiz_id);
        $quiz_rate_html = "";
        foreach($quiz_rate_reasons as $key => $reasons){
            $user_name = !empty($reasons['user_name']) ? "<span>".$reasons['user_name']."</span>" : '';
            if($this->isJSON($reasons['options'])){
                $reason = json_decode($reasons['options'], true)['reason'];
            }elseif($reasons['options'] != ''){
                $reason = $reasons['options'];
            }else{
                $reason = $reasons['review'];                
            }
            if(intval($reasons['user_id']) != 0){
                $user_img = esc_url( get_avatar_url( intval($reasons['user_id']) ) );
            }else{
                $user_img = "https://ssl.gstatic.com/accounts/ui/avatar_2x.png";
            }
            $score = $reasons['score'];
            $commented = date('M j, Y', strtotime($reasons['rate_date']));
            if($zuyga == 1){
                $row_reverse = ($key % 2 == 0) ? 'row_reverse' : '';
            }else{
                $row_reverse = ($key % 2 == 0) ? '' : 'row_reverse';
            }
            $quiz_rate_html .= "<div class='quiz_rate_reasons'>
                  <div class='rate_comment_row $row_reverse'>
                    <div class='rate_comment_user'>
                        <div class='thumbnail'>
                            <img class='img-responsive user-photo' src='".$user_img."'>
                        </div>
                    </div>
                    <div class='rate_comment'>
                        <div class='panel panel-default'>
                            <div class='panel-heading'>
                                <i class='ays_fa ays_fa_user'></i> <strong>$user_name</strong><br/>
                                <i class='ays_fa ays_fa_clock_o'></i> $commented<br/>
                                ".__("Rated", $this->plugin_name)." <i class='ays_fa ays_fa_star'></i> $score
                            </div>
                            <div class='panel-body'><div>". stripslashes($reason) ."</div></div>
                        </div>
                    </div>
                </div>
            </div>";
        }
        return $quiz_rate_html;
    }
    
    public function ays_get_rate_last_reviews(){
        error_reporting(0);
        $quiz_id = absint(intval($_REQUEST["quiz_id"]));
        $quiz_rate_html = "<div class='quiz_rate_reasons_container'>";
        $quiz_rate_html .= $this->ays_get_full_reasons_of_rates(0, 5, $quiz_id, 0);
        $quiz_rate_html .= "</div>";
        if($this->ays_get_count_of_reviews(0, 5, $quiz_id) / 5 > 1){
            $quiz_rate_html .= "<div class='quiz_rate_load_more'>
                <div>
                    <div data-class='lds-spinner' data-role='loader' class='ays-loader'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
                <button type='button' zuyga='1' startfrom='5' class='action-button ays_load_more_review'><i class='ays_fa ays_fa_chevron_circle_down'></i> ".__("Load more", $this->plugin_name)."</button>
            </div>";
        }
        echo $quiz_rate_html;
        wp_die();
    }
    
    public function ays_load_more_reviews(){
        error_reporting(0);
        $quiz_id = absint(intval($_REQUEST["quiz_id"]));
        $start = absint(intval($_REQUEST["start_from"]));
        $zuyga = absint(intval($_REQUEST["zuyga"]));
        $limit = 5;
        $quiz_rate_html = "";
        $quiz_rate_html .= $this->ays_get_full_reasons_of_rates($start, $limit, $quiz_id, $zuyga);
        if($quiz_rate_html == ""){
            echo "<p class='ays_no_more'>" . __( "No more reviews", $this->plugin_name ) . "</p>";
            wp_die();
        }else{            
            $quiz_rate_html = "<div class='quiz_rate_more_review'>".$quiz_rate_html."</div>";            
            echo $quiz_rate_html;
            wp_die();
        }
    }
    
    public function ays_rate_the_quiz(){
        global $wpdb;
        $rates_table = $wpdb->prefix . 'aysquiz_rates';
        
        $user_ip = $this->get_user_ip();
        if(isset($_REQUEST['ays_user_name']) && $_REQUEST['ays_user_name'] != ''){
            $user_name = $_REQUEST['ays_user_name'];
        }elseif(is_user_logged_in()){
            $user = wp_get_current_user();
            $user_name = $user->data->display_name;
        }else{
            $user_name = 'Anonymous';
        }
        $user_email = isset($_REQUEST['ays_user_email']) ? $_REQUEST['ays_user_email'] : '';
        $user_phone = isset($_REQUEST['ays_user_phone']) ? $_REQUEST['ays_user_phone'] : '';
        $quiz_id = absint(intval($_REQUEST["quiz_id"]));
        $score = $_REQUEST['rate_score'];
        $rate_date = esc_sql($_REQUEST['rate_date']);
        $results = $wpdb->insert(
            $rates_table,
            array(
                'quiz_id' => $quiz_id,
                'user_id' => get_current_user_id(),
                'user_ip' => $user_ip,
                'user_name' => $user_name,
                'user_email' => $user_email,
                'user_phone' => $user_phone,
                'score' => $score,
                'review' => isset($_REQUEST['rate_reason']) ? $_REQUEST['rate_reason'] : '',
                'options' => '',
                'rate_date' => $rate_date,
            ),
            array(
                '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
            )
        );
        $rate_id = $wpdb->insert_id;
        $avg_score = $this->ays_get_average_of_rates($quiz_id);
        if ($results >= 0 && $this->ays_set_rate_id_of_result($rate_id)) {
            echo json_encode(array(
//                'rate_id'   => $rate_id,
//                'result' => $this->ays_set_rate_id_of_result($rate_id),
                'quiz_id'   => $quiz_id,
                'status'    => true,
                'avg_score' => round($avg_score, 1),
                'score'     => intval(round($avg_score)),
                'rates_count'     => $this->ays_get_count_of_rates($quiz_id),
            ));
            wp_die();
        }
        echo json_encode(array(
            'status'    => false,
        ));
        wp_die();
    }

    protected function get_user_by_ip($id){
        global $wpdb;
        $user_ip = $this->get_user_ip();
        $sql = "SELECT COUNT(*) FROM `{$wpdb->prefix}aysquiz_reports` WHERE `user_ip` = '$user_ip' AND `quiz_id` = $id";
        $result = $wpdb->get_var($sql);
        return $result;
    }

    protected function get_user_ip(){
        $ipaddress = '';
        if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        elseif (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    protected function get_time_difference($strStart, $strEnd){
        $dteStart = new DateTime($strStart);
        $dteEnd = new DateTime($strEnd);

        $interval = $dteStart->diff($dteEnd);
        $return = '';
        if ($v = $interval->y >= 1) $return .= $this->pluralize($interval->y, 'year') . ' ';
        if ($v = $interval->m >= 1) $return .= $this->pluralize($interval->m, 'month') . ' ';
        if ($v = $interval->d >= 1) $return .= $this->pluralize($interval->d, 'day') . ' ';
        if ($v = $interval->h >= 1) $return .= $this->pluralize($interval->h, 'hour') . ' ';
        if ($v = $interval->i >= 1) $return .= $this->pluralize($interval->i, 'minute') . ' ';
        $return .= $this->pluralize($interval->s, 'second');

        return $return;
    }
    
    protected function pluralize($count, $text){
        return $count . (($count == 1) ? (" $text") : (" ${text}s"));
    }
    
    protected function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    
    public function ays_get_user_information() {
        
        if(is_user_logged_in()) {
            $output = json_encode(wp_get_current_user());
        } else {
            $output = null;
        }
        echo $output;
        wp_die();
    }
    
}
