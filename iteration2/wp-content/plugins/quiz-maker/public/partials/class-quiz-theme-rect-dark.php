<?php


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/includes
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Quiz_Theme_Rect_Dark extends Quiz_Maker_Public{

    private $plugin_name;

    private $version;

    private $theme_name;



    public function __construct($plugin_name,$plugin_version,$theme_name) {
        $this->version = $plugin_version;
        $this->plugin_name = $plugin_name;
        $this->theme_name = $theme_name;
        $this->define_theme_styles();
        $this->define_theme_scripts();
    }

    protected function define_theme_styles(){
        wp_enqueue_style($this->plugin_name.'rect_dark_css',dirname(plugin_dir_url(__FILE__)) . '/css/theme_rect_dark.css', array(), false, 'all');
    }
    protected function define_theme_scripts(){
        wp_enqueue_script(
            $this->plugin_name.'-rect_dark_js',
            dirname(plugin_dir_url(__FILE__)) . '/js/theme_rect_dark.js',
            array('jquery'),
            $this->version,
            false
        );
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
                #ays-quiz-container-".$quiz_id.".ays_quiz_rect_dark .ays-quiz-answers .ays-field:hover {
                    background: " . $this->hex2rgba($quiz->quizColors['Color'], '0.3') . ";
                }

                #ays-quiz-container-".$quiz_id.".ays_quiz_rect_dark .checked_answer_div{
                    background: ".$quiz->quizColors['Color'].";
                }
            </style>";
        
        $quiz->quizParts['quiz_additional_styles'] = $additional_css;
        
        $container = implode("", $quiz->quizParts);
        
        return $container;
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

    public function ays_default_answer_html($question_id, $quiz_id, $answers, $options){
        $answer_container = "";
        foreach ($answers as $answer) {
            $answer_image = (isset($answer['image']) && $answer['image'] != '') ? "<img src='{$answer["image"]}' alt='answer_image' class='ays-answer-image'>" : "";
            $answer_container .= "
            <div class='ays-field ays_".$options['answersViewClass']."_view_item'>
                <input type='hidden' name='ays_answer_correct[]' value='{$answer["correct"]}'/>

                <input type='{$options["questionType"]}' name='ays_questions[ays-question-{$question_id}]' id='ays-answer-{$answer["id"]}-{$quiz_id}' value='{$answer["id"]}'/>
                    <label for='ays-answer-{$answer["id"]}-{$quiz_id}'>
                        " . do_shortcode(htmlspecialchars(stripslashes($answer["answer"]))) . "
                    </label>
                    <label for='ays-answer-{$answer["id"]}-{$quiz_id}'>{$answer_image}</label>
            </div>";

        }  
        return $answer_container;
    }
}

?>