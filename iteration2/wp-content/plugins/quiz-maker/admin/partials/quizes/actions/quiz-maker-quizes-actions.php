<?php
if(isset($_GET['ays_quiz_tab'])){
    $ays_quiz_tab = $_GET['ays_quiz_tab'];
}else{
    $ays_quiz_tab = 'tab1';
}
$action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';
$heading = '';

$id = (isset($_GET['quiz'])) ? absint(intval($_GET['quiz'])) : null;

$user_id = get_current_user_id();
$user = get_userdata($user_id);
$author = array(
    'id' => $user->ID,
    'name' => $user->data->display_name
);
$quiz = array(
    'title' => '',
    'description' => '',
    'quiz_image' => '',
    'quiz_category_id' => '',
    'question_ids' => '',
    'published' => ''
);
$options = array(
    'quiz_theme' => 'classic_light',
    'color' => '#27AE60',
    'bg_color' => '#fff',
    'text_color' => '#000',
    'height' => 350,
    'width' => 400,
    'timer' => 0,
    'information_form' => 'disable',
    'form_name' => '',
    'form_email' => '',
    'form_phone' => '',
    'enable_logged_users' => 'off',
    'image_width' => '',
    'image_height' => '',
    'enable_correction' => 'off',
    'enable_questions_counter' => 'on',
    'limit_users' => 'off',
    'limitation_message' => '',
    'redirect_url' => '',
    'redirection_delay' => '',
    'enable_progress_bar' => 'off',
    'randomize_questions' => 'off',
    'randomize_answers' => 'off',
    'enable_questions_result' => 'off',
    'custom_css' => '',
    'enable_restriction_pass' => 'off',
    'restriction_pass_message' => '',
    'user_role' => '',
    'result_text' => '',
    'enable_result' => 'off',
    'enable_timer' => 'off',
    'enable_pass_count' => 'on',
    'enable_quiz_rate' => 'off',
    'enable_rate_avg' => 'off',
    'enable_rate_comments' => 'off',
    'hide_score' => 'off',
    'rate_form_title' => '',
    'enable_box_shadow' => 'on',
    'box_shadow_color' => '#000',
    'quiz_border_radius' => '0',
    'quiz_bg_image' => '',
    'enable_border' => 'off',
    'quiz_border_width' => '1',
    'quiz_border_style' => 'solid',
    'quiz_border_color' => '#000',
    'quiz_timer_in_title' => 'off',
    'enable_restart_button' => 'off',
    'quiz_loader' => 'default',
    'create_date' => date("Y-m-d H:i:s"),
    'author' => $author,
    'autofill_user_data' => 'off',
    'quest_animation' => 'shake',
    'form_title' => '',
    'enable_bg_music' => 'off',
    'quiz_bg_music' => '',
    'answers_font_size' => '15',
    'show_create_date' => 'off',
    'show_author' => 'off',
    'enable_early_finish' => 'off',
    'answers_rw_texts' => 'on_passing',
    'disable_store_data' => 'off',
    'enable_background_gradient' => 'off',
    'background_gradient_color_1' => '#000',
    'background_gradient_color_2' => '#fff',
    'quiz_gradient_direction' => 'vertical',
    'redirect_after_submit' => 'off',
    'submit_redirect_url' => '',
    'submit_redirect_delay' => '',
    'progress_bar_style' => 'first',
    'enable_exit_button' => 'off',
    'exit_redirect_url' => '',
    'image_sizing' => 'cover',
    'quiz_bg_image_position' => 'center center',
    'custom_class' => '',
    'enable_social_links' => 'off',
    'social_links' => array(
        'linkedin_link' => '',
        'facebook_link' => '',
        'twitter_link' => ''
    ),
);
$question_ids = '';
$question_id_array = array();
$quiz_intervals = 3;
switch ($action) {
    case 'add':
        $heading = __('Add new quiz', $this->plugin_name);
        break;
    case 'edit':
        $heading = __('Edit quiz', $this->plugin_name);
        $quiz = $this->quizes_obj->get_quiz_by_id($id);
        $options = json_decode($quiz['options'], true);
        $question_ids = $quiz['question_ids'];
        $question_id_array = explode(',', $question_ids);
        $question_id_array = ($question_id_array[0] == '' && count($question_id_array) == 1) ? array() : $question_id_array;
        break;
}

$questions = $this->quizes_obj->get_published_questions();
$total_questions_count = $this->quizes_obj->published_questions_record_count();
$quiz_categories = $this->quizes_obj->get_quiz_categories();
$question_categories = $this->quizes_obj->get_question_categories();

if (isset($_POST['ays_submit']) || isset($_POST['ays_submit_top'])) {
    $_POST['id'] = $id;
    $this->quizes_obj->add_or_edit_quizes($_POST);
}
if (isset($_POST['ays_apply_top']) || isset($_POST['ays_apply'])) {
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'apply';
    $this->quizes_obj->add_or_edit_quizes($_POST);
}
$style = null;
$image_text = __('Add Image', $this->plugin_name);
$bg_image_text = __('Add Image', $this->plugin_name);
if ($quiz['quiz_image'] != '') {
    $style = "display: block;";
    $image_text = __('Edit Image', $this->plugin_name);
}
global $wp_roles;
$ays_users_roles = $wp_roles->roles;

$required_fields = (isset($options['required_fields']) ? $options['required_fields'] : array());
$enable_pass_count = (isset($options['enable_pass_count'])) ? $options['enable_pass_count'] : '';
$enable_timer = (isset($options['enable_timer'])) ? $options['enable_timer'] : 'off';
$enable_quiz_rate = (isset($options['enable_quiz_rate'])) ? $options['enable_quiz_rate'] : '';
$enable_rate_avg = (isset($options['enable_rate_avg'])) ? $options['enable_rate_avg'] : '';
$enable_rate_comments = (isset($options['enable_rate_comments'])) ? $options['enable_rate_comments'] : '';
$enable_box_shadow = (!isset($options['enable_box_shadow'])) ? 'on' : $options['enable_box_shadow'];
$box_shadow_color = (!isset($options['box_shadow_color'])) ? '#000' : $options['box_shadow_color'];
$quiz_border_radius = (isset($options['quiz_border_radius']) && $options['quiz_border_radius'] != '') ? $options['quiz_border_radius'] : '0';
$quiz_bg_image = (isset($options['quiz_bg_image']) && $options['quiz_bg_image'] != '') ? $options['quiz_bg_image'] : '';
$enable_border = (isset($options['enable_border']) && $options['enable_border'] == 'on') ? true : false;
$quiz_border_width = (isset($options['quiz_border_width']) && $options['quiz_border_width'] != '') ? $options['quiz_border_width'] : '1';
$quiz_border_style = (isset($options['quiz_border_style']) && $options['quiz_border_style'] != '') ? $options['quiz_border_style'] : 'solid';
$quiz_border_color = (isset($options['quiz_border_color']) && $options['quiz_border_color'] != '') ? $options['quiz_border_color'] : '#000';
$quiz_timer_in_title = (isset($options['quiz_timer_in_title']) && $options['quiz_timer_in_title'] == 'on') ? true : false;
$enable_restart_button = (isset($options['enable_restart_button']) && $options['enable_restart_button'] == 'on') ? true : false;

$rate_form_title = (isset($options['rate_form_title'])) ? $options['rate_form_title'] : __('Please click the stars to rate the quiz', $this->plugin_name);
$quiz_loader = (isset($options['quiz_loader']) && $options['quiz_loader'] != '') ? $options['quiz_loader'] : 'default';

$quiz_create_date = (isset($options['create_date']) && $options['create_date'] != '') ? $options['create_date'] : "0000-00-00 00:00:00";
if(isset($options['author']) && $options['author'] != 'null'){
    $quiz_author = $options['author'];
} else {
    $quiz_author = array('name' => 'Unknown');
}

$autofill_user_data = (isset($options['autofill_user_data']) && $options['autofill_user_data'] == 'on') ? true : false;

$quest_animation = (isset($options['quest_animation'])) ? $options['quest_animation'] : "shake";
$enable_bg_music = (isset($options['enable_bg_music']) && $options['enable_bg_music'] == "on") ? true : false;
$quiz_bg_music = (isset($options['quiz_bg_music']) && $options['quiz_bg_music'] != "") ? $options['quiz_bg_music'] : "";
$answers_font_size = (isset($options['answers_font_size']) && $options['answers_font_size'] != "") ? $options['answers_font_size'] : '15';
$show_create_date = (isset($options['show_create_date']) && $options['show_create_date'] == "on") ? true : false;
$show_author = (isset($options['show_author']) && $options['show_author'] == "on") ? true : false;
$enable_early_finish = (isset($options['enable_early_finish']) && $options['enable_early_finish'] == "on") ? true : false;
$answers_rw_texts = (isset($options['answers_rw_texts']) && $options['answers_rw_texts'] != '') ? $options['answers_rw_texts'] : 'on_passing';
$disable_store_data = (isset($options['disable_store_data']) && $options['disable_store_data'] == 'on') ? true : false;

$options['enable_background_gradient'] = (!isset($options['enable_background_gradient'])) ? 'off' : $options['enable_background_gradient'];
$enable_background_gradient = (isset($options['enable_background_gradient']) && $options['enable_background_gradient'] == 'on') ? true : false;
$background_gradient_color_1 = (isset($options['background_gradient_color_1']) && $options['background_gradient_color_1'] != '') ? $options['background_gradient_color_1'] : '#000';
$background_gradient_color_2 = (isset($options['background_gradient_color_2']) && $options['background_gradient_color_2'] != '') ? $options['background_gradient_color_2'] : '#fff';
$quiz_gradient_direction = (isset($options['quiz_gradient_direction']) && $options['quiz_gradient_direction'] != '') ? $options['quiz_gradient_direction'] : 'vertical';

// Redirect after submit
$options['redirect_after_submit'] = (!isset($options['redirect_after_submit'])) ? 'off' : $options['redirect_after_submit'];
$redirect_after_submit = isset($options['redirect_after_submit']) && $options['redirect_after_submit'] == 'on' ? true : false;
$submit_redirect_url = isset($options['submit_redirect_url']) ? $options['submit_redirect_url'] : '';
$submit_redirect_delay = isset($options['submit_redirect_delay']) ? $options['submit_redirect_delay'] : '';

// Progress bar style
$progress_bar_style = (isset($options['progress_bar_style']) && $options['progress_bar_style'] != "") ? $options['progress_bar_style'] : 'first';

// Exit button in finish page
$options['enable_exit_button'] = (!isset($options['enable_exit_button'])) ? 'off' : $options['enable_exit_button'];
$enable_exit_button = isset($options['enable_exit_button']) && $options['enable_exit_button'] == 'on' ? true : false;
$exit_redirect_url = isset($options['exit_redirect_url']) ? $options['exit_redirect_url'] : '';

// Question image sizing
$image_sizing = (isset($options['image_sizing']) && $options['image_sizing'] != "") ? $options['image_sizing'] : 'cover';

// Quiz background image position
$quiz_bg_image_position = (isset($options['quiz_bg_image_position']) && $options['quiz_bg_image_position'] != "") ? $options['quiz_bg_image_position'] : 'center center';

// Custom class for quiz container
$custom_class = (isset($options['custom_class']) && $options['custom_class'] != "") ? $options['custom_class'] : '';

// Social Media links
$enable_social_links = (isset($options['enable_social_links']) && $options['enable_social_links'] == "on") ? true : false;
$social_links = (isset($options['social_links'])) ? $options['social_links'] : array(
    'linkedin_link' => '',
    'facebook_link' => '',
    'twitter_link' => ''
);
$linkedin_link = isset($social_links['linkedin_link']) && $social_links['linkedin_link'] != '' ? $social_links['linkedin_link'] : '';
$facebook_link = isset($social_links['facebook_link']) && $social_links['facebook_link'] != '' ? $social_links['facebook_link'] : '';
$twitter_link = isset($social_links['twitter_link']) && $social_links['twitter_link'] != '' ? $social_links['twitter_link'] : '';

// var_dump($options);

?>
<style id="ays_live_custom_css"></style>
<div class="wrap">
    <div class="container-fluid">
        <form class="ays-quiz-category-form" id="ays-quiz-category-form" method="post">
            <input type="hidden" name="ays_quiz_tab" value="<?php echo $ays_quiz_tab; ?>">
            <input type="hidden" name="ays_quiz_ctrate_date" value="<?php echo $quiz_create_date; ?>">
            <input type="hidden" name="ays_quiz_author" value="<?php echo htmlentities(json_encode($quiz_author)); ?>">
            <h1 class="wp-heading-inline">
                <?php
                echo $heading;
                $other_attributes = array();
                submit_button(__('Save Quiz', $this->plugin_name), 'primary', 'ays_submit_top', false, $other_attributes);
                submit_button(__('Apply Quiz', $this->plugin_name), '', 'ays_apply_top', false, $other_attributes);

                ?>
            </h1>
            <div>
                <p class="ays-subtitle">
                    <strong class="ays_quiz_title_in_top"><?php echo stripslashes(htmlentities($quiz['title'])); ?></strong>
                </p>
                <?php if($id !== null): ?>
                <div class="row">
                    <div class="col-sm-3">
                        <label> <?php echo __( "Shortcode text for editor", $this->plugin_name ); ?> </label>
                    </div>
                    <div class="col-sm-9">
                        <p style="font-size:14px; font-style:italic;">
                            <?php echo __("To insert the Quiz into a page, post or text widget, copy shortcode", $this->plugin_name); ?>
                            <strong style="font-size:16px; font-style:normal;"><?php echo "[ays_quiz id='".$id."']"; ?></strong>
                            <?php echo " " . __( "and paste it at the desired place in the editor.", $this->plugin_name); ?>
                        </p>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <hr/>
            <div class="nav-tab-wrapper">
                <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_quiz_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("General", $this->plugin_name);?>
                </a>
                <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_quiz_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("Styles", $this->plugin_name);?>
                </a>
                <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_quiz_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("Settings", $this->plugin_name);?>
                </a>
                <a href="#tab4" data-tab="tab4" class="nav-tab <?php echo ($ays_quiz_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("Results Settings", $this->plugin_name);?>
                </a>
                <a href="#tab5" data-tab="tab5" class="nav-tab <?php echo ($ays_quiz_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("Limitation Users", $this->plugin_name);?>
                </a>
                <a href="#tab6" data-tab="tab6" class="nav-tab <?php echo ($ays_quiz_tab == 'tab6') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("User Data", $this->plugin_name);?>
                </a>
                <a href="#tab7" data-tab="tab7" class="nav-tab <?php echo ($ays_quiz_tab == 'tab7') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("E-Mail, Certificate", $this->plugin_name);?>
                </a>
                <a href="#tab8" data-tab="tab8" class="nav-tab <?php echo ($ays_quiz_tab == 'tab8') ? 'nav-tab-active' : ''; ?>">
                    <?php echo __("Integrations", $this->plugin_name);?>
                </a>
            </div>

            <div id="tab1" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab1') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('General Settings',$this->plugin_name)?></p>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for='ays-quiz-title'>
                            <?php echo __('Title', $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Title of the quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="ays-text-input" id='ays-quiz-title' name='ays_quiz_title' required
                               value="<?php echo stripslashes(htmlentities($quiz['title'])); ?>"/>
                    </div>
                </div>
                <hr/>
                <div class='ays-field'>
                    <label>
                        <?php echo __('Quiz', $this->plugin_name); ?>
                        <a href="javascript:void(0)" class="add-quiz-image"><?php echo $image_text; ?></a>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Add image to the starting page of the quiz',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                    <div class="ays-quiz-image-container" style="<?php echo $style; ?>">
                        <span class="ays-remove-quiz-img"></span>
                        <img src="<?php echo $quiz['quiz_image']; ?>" id="ays-quiz-img"/>
                    </div>
                </div>
                <hr/>
                <input type="hidden" name="ays_quiz_image" id="ays-quiz-image"
                       value="<?php echo $quiz['quiz_image']; ?>"/>
                <div class='ays-field'>
                    <label for='ays-quiz-description'>
                        <?php echo __('Description', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Details about the quiz',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                    <?php
                    $content = stripslashes(wpautop($quiz['description']));
                    $editor_id = 'ays-quiz-description';
                    $settings = array('editor_height' => '4', 'textarea_name' => 'ays_quiz_description', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="ays-category">
                            <?php echo __('Quiz category', $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Category of the quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <select id="ays-category" name="ays_quiz_category">
                            <option></option>
                            <?php
                            $cat = 0;
                            foreach ($quiz_categories as $key => $quiz_category) {
                                $selected = (intval($quiz_category['id']) == intval($quiz['quiz_category_id'])) ? "selected" : "";
                                if ($cat == 0 && intval($quiz['quiz_category_id']) == 0) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $quiz_category["id"] . '" ' . $selected . '>' . stripslashes($quiz_category['title']) . '</option>';
                                $cat++;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label>
                            <?php echo __('Quiz status', $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Status of the quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-publish" name="ays_publish"
                                   value="1" <?php echo ($quiz["published"] == '') ? "checked" : ""; ?>  <?php echo ($quiz["published"] == '1') ? 'checked' : ''; ?>/>
                            <label class="form-check-label"
                                   for="ays-publish"> <?php echo __('Published', $this->plugin_name); ?> </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-unpublish" name="ays_publish"
                                   value="0" <?php echo ($quiz["published"] == '0') ? 'checked' : ''; ?>/>
                            <label class="form-check-label"
                                   for="ays-unpublish"> <?php echo __('Unpublished', $this->plugin_name); ?> </label>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class='ays-field ays_items_count_div'>
                    <label for="ays-answers-table"><?php echo __('Questions', $this->plugin_name); ?>
                        <a href="javascript:void(0)" class="ays-add-question">
                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                        </a>
                        <a class="ays_help" style="font-size:15px;" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Add questions to the quiz',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                    <p class="ays_questions_action" style="line-height: 53px;margin:0; width:80%;">
                        <button class="ays_bulk_del_questions button" type="button" disabled>
                            <?php echo __( 'Delete', $this->plugin_name); ?>                            
                        </button>
                        <button class="ays_select_all button" type="button">
                            <?php echo __( 'Select All', $this->plugin_name); ?>                            
                        </button>
                    </p>
                    <p class="ays_questions_count" style="margin:0;line-height: 53px; width:auto;white-space:nowrap;">
                        <?php
                        echo '<span id="questions_count_number">' . count($question_id_array) . '</span> items';
                        ?>
                    </p>
                </div>
                <div class="ays-field" style="padding-top: 15px;">
                    <table class="ays-questions-table" id="ays-questions-table">
                        <thead>
                        <tr class="ui-state-default">
                            <th><?php echo __('Ordering', $this->plugin_name); ?></th>
                            <th style="min-width:500px;"><?php echo __('Question', $this->plugin_name); ?></th>
                            <th><?php echo __('Type', $this->plugin_name); ?></th>
                            <th style="max-width:100px;"><?php echo __('ID', $this->plugin_name); ?></th>
                            <th style="min-width:100px;max-width:110px;"><?php echo __('Delete', $this->plugin_name); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!(count($question_id_array) === 1 && $question_id_array[0] == '')) {
                            foreach ($question_id_array as $key => $question_id) {
                                $data = $this->quizes_obj->get_published_questions_by('id', absint(intval($question_id)));
                                $className = "";
                                if (($key + 1) % 2 == 0) {
                                    $className = "even";
                                }
                                $table_question = (strip_tags(stripslashes($data['question'])));
                                $table_question = $this->ays_restriction_string("word",$table_question, 10);
                                ?>
                                <tr class="ays-question-row ui-state-default <?php echo $className; ?>"
                                    data-id="<?php echo $data['id']; ?>">
                                    <td class="ays-sort"><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                                    <td><?php echo $table_question ?></td>
                                    <td><?php echo $data['type']; ?></td>
                                    <td><?php echo $data['id']; ?></td>
                                    <td>
                                        <input type="checkbox" class="ays_del_tr" style="margin-right:15px;">
                                        <a href="javascript:void(0)" class="ays-delete-question"
                                           data-id="<?php echo $data['id']; ?>">
                                            <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        if(empty($question_id_array)){                            
                            ?>
                            <tr class="ays-question-row ui-state-default">
                                <td colspan="5" class="empty_quiz_td">
                                    <div>
                                        <i class="ays_fa ays_fa_info" aria-hidden="true" style="margin-right:10px"></i>
                                        <span style="font-size: 13px; font-style: italic;">
                                        <?php
                                            echo __( 'There are no questions yet.', $this->plugin_name );
                                        ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                </table>
                    <p class="ays_questions_action" style="line-height: 53px;margin:0; width:100%; text-align:left;">
                        <button class="ays_bulk_del_questions button" type="button" disabled>
                            <?php echo __( 'Delete', $this->plugin_name); ?>                            
                        </button>
                        <button class="ays_select_all button" type="button">
                            <?php echo __( 'Select All', $this->plugin_name); ?>                            
                        </button>
                    </p>
                </div>
                <input type="hidden" id="ays_already_added_questions" name="ays_added_questions" value="<?php echo $question_ids; ?>"/>
            </div>

            <div id="tab2" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab2') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('Quiz Styles',$this->plugin_name)?></p>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label>
                            <?php echo __('Quiz Theme', $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Design of the quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-group row ays_themes_images_main_div">
                            <div class="ays_theme_image_div col-sm-2"><label
                                        for="theme_elegant_dark" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'elegant_dark') ? 'class="ays_active_theme_image"' : '' ?>><p><?php echo __('Elegant Dark',$this->plugin_name)?></p><img
                                            src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/elegant_dark.JPG' ?>"
                                            alt="Elegant Dark"></label>
                            </div>
                            <div class="ays_theme_image_div col-sm-2"><label
                                        for="theme_elegant_light" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'elegant_light') ? 'class="ays_active_theme_image"' : '' ?>><p><?php echo __('Elegant Light',$this->plugin_name)?></p><img
                                            src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/elegant_light.JPG' ?>"
                                            alt="Elegant Light"></label>
                            </div>
                            <div class="ays_theme_image_div col-sm-2"><label
                                        for="theme_classic_dark" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'classic_dark') ? 'class="ays_active_theme_image"' : '' ?>><p><?php echo __('Classic Dark',$this->plugin_name)?></p><img
                                            src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/classic_dark.PNG' ?>"
                                            alt="Classic Dark"></label>
                            </div>
                            <div class="ays_theme_image_div col-sm-2"><label
                                        for="theme_classic_light" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'classic_light') ? 'class="ays_active_theme_image"' : '' ?>><p><?php echo __('Classic Light',$this->plugin_name)?></p><img
                                            src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/classic_light.PNG' ?>"
                                            alt="Classic Light"></label>
                            </div>
                            <div class="ays_theme_image_div col-sm-2"><label for="theme_rect_dark" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'rect_dark') ? 'class="ays_active_theme_image"' : '' ?>><p><?php echo __('Rect Dark',$this->plugin_name)?></p><img
                                            src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/rect_dark.JPG' ?>"
                                            alt="Rect Dark" ></label>
                            </div>
                            <div class="ays_theme_image_div col-sm-2"><label for="theme_rect_light" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'rect_light') ? 'class="ays_active_theme_image"' : '' ?>><p><?php echo __('Rect Light',$this->plugin_name)?></p><img
                                            src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/rect_light.JPG' ?>"
                                            alt="Rect Light" ></label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12 only_pro">
                                <div class="pro_features">
                                    <div>
                                        <p style="font-size:25px;">
                                            <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                            <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2 ays_theme_image_div">
                                        <label class="ays-disable-setting">
                                            <p><?php echo __('Modern Light',$this->plugin_name)?></p>
                                            <img style="width: 60px;" src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/modern_light.PNG' ?>" alt="Modern Light"/>
                                        </label>
                                    </div>
                                    <div class="col-sm-2 ays_theme_image_divs">
                                        <label class="ays-disable-setting">
                                            <p><?php echo __('Modern Dark',$this->plugin_name)?></p>
                                            <img style="width: 60px;" src="<?php echo AYS_QUIZ_ADMIN_URL . '/images/themes/modern_dark.PNG' ?>" alt="Modern Dark"/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="checkbox" id="theme_elegant_dark" name="ays_quiz_theme"
                               value="elegant_dark" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'elegant_dark') ? 'checked' : '' ?>>
                        <input type="checkbox" id="theme_elegant_light" name="ays_quiz_theme"
                               value="elegant_light" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'elegant_light') ? 'checked' : '' ?>>
                        <input type="checkbox" id="theme_classic_dark" name="ays_quiz_theme"
                               value="classic_dark" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'classic_dark') ? 'checked' : '' ?>>
                        <input type="checkbox" id="theme_classic_light" name="ays_quiz_theme"
                               value="classic_light" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'classic_light') ? 'checked' : '' ?>>
                        <input type="checkbox" id="theme_rect_dark" name="ays_quiz_theme"
                               value="rect_dark" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'rect_dark') ? 'checked' : '' ?>>
                        <input type="checkbox" id="theme_rect_light" name="ays_quiz_theme"
                               value="rect_light" <?php echo (isset($options['quiz_theme']) && $options['quiz_theme'] == 'rect_light') ? 'checked' : '' ?>>
                    </div>
                </div>
                <hr/>
                <div class="cow">
                    <div class="row">
                        <div class="col-lg-7 col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for='ays_quest_animation'>
                                        <?php echo __('Animation effect', $this->plugin_name); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Animation effect of transition between questions',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <select class="ays-text-input ays-text-input-short" name="ays_quest_animation" id="ays_quest_animation">
                                        <option <?php echo $quest_animation == "none" ? "selected" : ""; ?> value="none">None</option>
                                        <option <?php echo $quest_animation == "fade" ? "selected" : ""; ?> value="fade">Fade</option>
                                        <option <?php echo $quest_animation == "shake" ? "selected" : ""; ?> value="shake">Shake</option>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for='ays-quiz-color'>
                                        <?php echo __('Quiz Color', $this->plugin_name); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Main color of the quiz',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="text" class="ays-text-input" id='ays-quiz-color' data-alpha="true" name='ays_quiz_color'
                                           value="<?php echo (isset($options['color'])) ? $options['color'] : ''; ?>"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for='ays-quiz-bg-color'>
                                        <?php echo __('Quiz Background Color', $this->plugin_name); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Background color of the quiz',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="text" class="ays-text-input" id='ays-quiz-bg-color' data-alpha="true"
                                           name='ays_quiz_bg_color'
                                           value="<?php echo (isset($options['bg_color'])) ? $options['bg_color'] : ''; ?>"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for='ays-quiz-bg-color'>
                                        <?php echo __('Text Color', $this->plugin_name); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Color of the quiz text',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="text" class="ays-text-input" id='ays-quiz-text-color' data-alpha="true"
                                           name='ays_quiz_text_color'
                                           value="<?php echo (isset($options['text_color'])) ? $options['text_color'] : ''; ?>"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for='ays_answers_font_size'>
                                        <?php echo __('Answers font size', $this->plugin_name); ?> (px)
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The font size of the answers in pixels in the quiz.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_answers_font_size'name='ays_answers_font_size' value="<?php echo $answers_font_size; ?>"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for='ays-quiz-width'>
                                        <?php echo __('Quiz width', $this->plugin_name); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Quiz width in pixels',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="number" class="ays-text-input ays-text-input-short" id='ays-quiz-width'
                                           name='ays_quiz_width'
                                           value="<?php echo (isset($options['width'])) ? $options['width'] : ''; ?>"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for='ays-quiz-height'>
                                        <?php echo __('Quiz min height', $this->plugin_name); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Quiz minimal height in pixels',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="number" class="ays-text-input ays-text-input-short"
                                           id='ays-quiz-height'
                                           name='ays_quiz_height'
                                           value="<?php echo (isset($options['height'])) ? $options['height'] : ''; ?>"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label>
                                        <?php echo __('Questions Image Styles',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The styles of an images inside the quiz',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="ays_image_width">
                                                <?php echo __('Image Width',$this->plugin_name)?>(px)
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The width of an images inside the quiz',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <input type="number" class="ays-text-input ays-text-input-short" id="ays_image_width" name="ays_image_width" value="<?php echo (isset($options['image_width']) && $options['image_width'] != '') ? $options['image_width'] : ''; ?>"/>
                                            <span class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name);?></span>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="ays_image_height">
                                                <?php echo __('Image Height',$this->plugin_name)?>(px)
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The height of an images inside the quiz',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <input type="number" class="ays-text-input ays-text-input-short" id="ays_image_height" name="ays_image_height" value="<?php echo (isset($options['image_height']) && $options['image_height'] != '') ? $options['image_height'] : ''; ?>"/>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="ays_image_sizing">
                                                <?php echo __('Image sizing', $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The sizing of an images inside the quiz',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <select name="ays_image_sizing" id="ays_image_sizing" class="ays-text-input ays-text-input-short" style="display:block;">
                                                <option value="cover" <?php echo $image_sizing == 'cover' ? 'selected' : ''; ?>><?php echo __( "Cover", $this->plugin_name ); ?></option>
                                                <option value="contain" <?php echo $image_sizing == 'contain' ? 'selected' : ''; ?>><?php echo __( "Contain", $this->plugin_name ); ?></option>
                                                <option value="none" <?php echo $image_sizing == 'none' ? 'selected' : ''; ?>><?php echo __( "None", $this->plugin_name ); ?></option>
                                                <option value="unset" <?php echo $image_sizing == 'unset' ? 'selected' : ''; ?>><?php echo __( "Unset", $this->plugin_name ); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="ays_enable_border">
                                        <?php echo __('Quiz container border',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow quiz container border',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                           id="ays_enable_border"
                                           name="ays_enable_border"
                                           value="on"
                                           <?php echo ($enable_border) ? 'checked' : ''; ?>/>
                                    <label for="ays_enable_border" class="ays_switch_toggle">Toggle</label>
                                    <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_border) ? '' : 'display:none;' ?>">
                                        <label for="ays_quiz_border_width">
                                            <?php echo __('Border width',$this->plugin_name)?> (px)
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The width of quiz container border',$this->plugin_name)?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                         </label>
                                        <input type="number" class="ays-text-input" id='ays_quiz_border_width'
                                               name='ays_quiz_border_width'
                                               value="<?php echo $quiz_border_width; ?>"/>
                                    </div>
                                    <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_border) ? '' : 'display:none;' ?>">
                                        <label for="ays_quiz_border_style">
                                            <?php echo __('Border style',$this->plugin_name)?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The style of quiz container border',$this->plugin_name)?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                        <select id="ays_quiz_border_style" 
                                                name="ays_quiz_border_style" 
                                                class="ays-text-input">
                                            <option <?php echo ($quiz_border_style == 'solid') ? 'selected' : ''; ?> value="solid">Solid</option>
                                            <option <?php echo ($quiz_border_style == 'dashed') ? 'selected' : ''; ?> value="dashed">Dashed</option>
                                            <option <?php echo ($quiz_border_style == 'dotted') ? 'selected' : ''; ?> value="dotted">Dotted</option>
                                            <option <?php echo ($quiz_border_style == 'double') ? 'selected' : ''; ?> value="double">Double</option>
                                            <option <?php echo ($quiz_border_style == 'groove') ? 'selected' : ''; ?> value="groove">Groove</option>
                                            <option <?php echo ($quiz_border_style == 'ridge') ? 'selected' : ''; ?> value="ridge">Ridge</option>
                                            <option <?php echo ($quiz_border_style == 'inset') ? 'selected' : ''; ?> value="inset">Inset</option>
                                            <option <?php echo ($quiz_border_style == 'outset') ? 'selected' : ''; ?> value="outset">Outset</option>
                                            <option <?php echo ($quiz_border_style == 'none') ? 'selected' : ''; ?> value="none">None</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_border) ? '' : 'display:none;' ?>">
                                        <label for="ays_quiz_border_color">
                                            <?php echo __('Border color',$this->plugin_name)?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The color of the quiz container border',$this->plugin_name)?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                        <input id="ays_quiz_border_color" 
                                               class="ays-text-input" 
                                               type="text" 
                                               data-alpha="true"
                                               name='ays_quiz_border_color'
                                               value="<?php echo $quiz_border_color; ?>" 
                                               data-default-color="#000000">
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="ays_quiz_border_radius">
                                        <?php echo __('Quiz border radius',$this->plugin_name)?>(px)
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Quiz container border radius in px',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="number" class="ays-text-input ays-text-input-short"
                                           id="ays_quiz_border_radius"
                                           name="ays_quiz_border_radius"
                                           value="<?php echo $quiz_border_radius; ?>"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="ays_enable_box_shadow">
                                        <?php echo __('Quiz box shadow',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow quiz container box shadow',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                           id="ays_enable_box_shadow"
                                           name="ays_enable_box_shadow"
                                           <?php echo ($enable_box_shadow == 'on') ? 'checked' : ''; ?>/>
                                    <label for="ays_enable_box_shadow" class="ays_switch_toggle">Toggle</label>
                                    <div class="col-sm-12 ays_toggle_target ays_divider_top" style="margin-top: 10px; padding-top: 10px; <?php echo ($enable_box_shadow == 'on') ? '' : 'display:none;' ?>">
                                        <label for="ays-quiz-box-shadow-color">
                                            <?php echo __('Box shadow color',$this->plugin_name)?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The shadow color of quiz container',$this->plugin_name)?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                         </label>
                                        <input type="text" class="ays-text-input" id='ays-quiz-box-shadow-color'
                                               name='ays_quiz_box_shadow_color'
                                               data-alpha="true"
                                               data-default-color="#000000"
                                               value="<?php echo $box_shadow_color; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label>
                                        <?php echo __('Quiz background image',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Background image of the quiz',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="ays_quiz_bg_image_position">
                                                <?php echo __( "Background image position", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The position of background image of the quiz',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <select id="ays_quiz_bg_image_position" name="ays_quiz_bg_image_position" class="ays-text-input ays-text-input-short" style="display:inline-block;">
                                                <option value="left top" <?php echo $quiz_bg_image_position == "left top" ? "selected" : ""; ?>><?php echo __( "Left Top", $this->plugin_name ); ?></option>
                                                <option value="left center" <?php echo $quiz_bg_image_position == "left center" ? "selected" : ""; ?>><?php echo __( "Left Center", $this->plugin_name ); ?></option>
                                                <option value="left bottom" <?php echo $quiz_bg_image_position == "left bottom" ? "selected" : ""; ?>><?php echo __( "Left Bottom", $this->plugin_name ); ?></option>
                                                <option value="center top" <?php echo $quiz_bg_image_position == "center top" ? "selected" : ""; ?>><?php echo __( "Center Top", $this->plugin_name ); ?></option>
                                                <option value="center center" <?php echo $quiz_bg_image_position == "center center" ? "selected" : ""; ?>><?php echo __( "Center Center", $this->plugin_name ); ?></option>
                                                <option value="center bottom" <?php echo $quiz_bg_image_position == "center bottom" ? "selected" : ""; ?>><?php echo __( "Center Bottom", $this->plugin_name ); ?></option>
                                                <option value="right top" <?php echo $quiz_bg_image_position == "right top" ? "selected" : ""; ?>><?php echo __( "Right Top", $this->plugin_name ); ?></option>
                                                <option value="right center" <?php echo $quiz_bg_image_position == "right center" ? "selected" : ""; ?>><?php echo __( "Right Center", $this->plugin_name ); ?></option>
                                                <option value="right bottom" <?php echo $quiz_bg_image_position == "right bottom" ? "selected" : ""; ?>><?php echo __( "Right Bottom", $this->plugin_name ); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr/>
                                    <a href="javascript:void(0)" style="<?php echo $quiz_bg_image == '' ? 'display:inline-block' : 'display:none'; ?>" class="add-quiz-bg-image"><?php echo $bg_image_text; ?></a>
                                    <input type="hidden" id="ays_quiz_bg_image" name="ays_quiz_bg_image"
                                           value="<?php echo $quiz_bg_image; ?>"/>
                                    <div class="ays-quiz-bg-image-container" style="<?php echo $quiz_bg_image == '' ? 'display:none' : 'display:block'; ?>">
                                        <span class="ays-edit-quiz-bg-img">
                                            <i class="ays_fa ays_fa_pencil_square_o"></i>
                                        </span>
                                        <span class="ays-remove-quiz-bg-img"></span>
                                        <img src="<?php echo $quiz_bg_image; ?>" id="ays-quiz-bg-img"/>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="ays-enable-background-gradient">
                                        <?php echo __('Quiz background gradient',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Background gradient of the quiz',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="checkbox" class="ays_toggle ays_toggle_slide"
                                           id="ays-enable-background-gradient"
                                           name="ays_enable_background_gradient"
                                            <?php echo ($enable_background_gradient) ? 'checked' : ''; ?>/>
                                    <label for="ays-enable-background-gradient" class="ays_switch_toggle">Toggle</label>
                                    <div class="row ays_toggle_target" style="margin: 10px 0 0 0; padding-top: 10px; <?php echo ($enable_background_gradient) ? '' : 'display:none;' ?>">
                                        <div class="col-sm-12 ays_divider_top" style="margin-top: 10px; padding-top: 10px;">
                                            <label for='ays-background-gradient-color-1'>
                                                <?php echo __('Color 1', $this->plugin_name); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Color 1 of the quiz background gradient',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <input type="text" class="ays-text-input" id='ays-background-gradient-color-1' data-alpha="true" name='ays_background_gradient_color_1' value="<?php echo $background_gradient_color_1; ?>"/>
                                        </div>
                                        <div class="col-sm-12 ays_divider_top" style="margin-top: 10px; padding-top: 10px;">
                                            <label for='ays-background-gradient-color-2'>
                                                <?php echo __('Color 2', $this->plugin_name); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Color 2 of the quiz background gradient',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <input type="text" class="ays-text-input" id='ays-background-gradient-color-2' data-alpha="true" name='ays_background_gradient_color_2' value="<?php echo $background_gradient_color_2; ?>"/>
                                        </div>
                                        <div class="col-sm-12 ays_divider_top" style="margin-top: 10px; padding-top: 10px;">
                                            <label for="ays_quiz_gradient_direction">
                                                <?php echo __('Gradient direction',$this->plugin_name)?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The direction of quiz background gradient',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <select id="ays_quiz_gradient_direction" name="ays_quiz_gradient_direction" class="ays-text-input">
                                                <option <?php echo ($quiz_gradient_direction == 'vertical') ? 'selected' : ''; ?> value="vertical"><?php echo __( 'Vertical', $this->plugin_name); ?></option>
                                                <option <?php echo ($quiz_gradient_direction == 'horizontal') ? 'selected' : ''; ?> value="horizontal"><?php echo __( 'Horizontal', $this->plugin_name); ?></option>
                                                <option <?php echo ($quiz_gradient_direction == 'diagonal_left_to_right') ? 'selected' : ''; ?> value="diagonal_left_to_right"><?php echo __( 'Diagonal left to right', $this->plugin_name); ?></option>
                                                <option <?php echo ($quiz_gradient_direction == 'diagonal_right_to_left') ? 'selected' : ''; ?> value="diagonal_right_to_left"><?php echo __( 'Diagonal right to left', $this->plugin_name); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="ays_progress_bar_style">
                                        <?php echo __('Progress bar style',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Progress bar styles in finish page.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <select id="ays_progress_bar_style" name="ays_progress_bar_style" class="ays-text-input ays-text-input-short">
                                        <option <?php echo ($progress_bar_style == 'first') ? 'selected' : ''; ?> value="first"><?php echo __( 'Rounded', $this->plugin_name); ?></option>
                                        <option <?php echo ($progress_bar_style == 'second') ? 'selected' : ''; ?> value="second"><?php echo __( 'Rectangle', $this->plugin_name); ?></option>
                                        <option <?php echo ($progress_bar_style == 'third') ? 'selected' : ''; ?> value="third"><?php echo __( 'With stripes', $this->plugin_name); ?></option>
                                        <option <?php echo ($progress_bar_style == 'fourth') ? 'selected' : ''; ?> value="fourth"><?php echo __( 'With stripes and animation', $this->plugin_name); ?></option>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label for="ays_custom_class">
                                        <?php echo __('Custom class for quiz container',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Custom HTML class for quiz container. You can use your class for adding your custom styles for quiz container.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-7 ays_divider_left">
                                    <input type="text" class="ays-text-input" name="ays_custom_class" id="ays_custom_class" placeholder="myClass myAnotherClass..." value="<?php echo $custom_class; ?>">
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_custom_css">
                                        <?php echo __('Custom CSS',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                <textarea class="ays-textarea" id="ays_custom_css" name="ays_custom_css" cols="30"
                                          rows="10"><?php echo (isset($options['custom_css']) && $options['custom_css'] != '') ? $options['custom_css'] : '' ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;">
                            <div id="ays_styles_tab" style="position:sticky;top:50px; margin:auto;">
                                <div class="ays-quiz-live-container ays-quiz-live-container-1">
                                    <div class="step active-step">
                                        <div class="ays-abs-fs">
                                            <img src="" alt="Ays Question Image" class="ays-quiz-live-image">
                                            <p class="ays-fs-title ays-quiz-live-title"></p>
                                            <p class="ays-fs-subtitle ays-quiz-live-subtitle"></p>
                                            <input type="hidden" name="ays_quiz_id" value="2">
                                            <input type="button" name="next" class="ays_next start_button action-button ays-quiz-live-button"
                                                   value="<?php echo __( "Start", $this->plugin_name ); ?>">
                                            <br>
                                            <br>

                                            <div class='ays-progress first <?php echo ($progress_bar_style == 'first') ? "display_block" : ""; ?>'>
                                                <span class='ays-progress-value first' style='width:67%;'>67%</span>
                                                <div class="ays-progress-bg first">
                                                    <div class="ays-progress-bar first" style='width:67%;'></div>
                                                </div>
                                            </div>

                                            <div class='ays-progress second <?php echo ($progress_bar_style == 'second') ? "display_block" : ""; ?>'>
                                                <span class='ays-progress-value second' style='width:88%;'>88%</span>
                                                <div class="ays-progress-bg second">
                                                    <div class="ays-progress-bar second" style='width:88%;'></div>
                                                </div>
                                            </div>
                                            
                                            <div class="ays-progress third <?php echo ($progress_bar_style == 'third') ? "display_block" : ""; ?>">
                                                <span class="ays-progress-value third">55%</span>
                                                <div class="ays-progress-bg third">
                                                    <div class="ays-progress-bar third" style='width:55%;'></div>
                                                </div>
                                            </div>
                                            
                                            <div class="ays-progress fourth <?php echo ($progress_bar_style == 'fourth') ? "display_block" : ""; ?>">
                                                <span class="ays-progress-value fourth">34%</span>
                                                <div class="ays-progress-bg fourth">
                                                    <div class="ays-progress-bar fourth" style="width:34%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-quiz-live-container ays-quiz-live-container-2" style="display:none;">
                                    <div class="step active-step">
                                        <div class="ays-abs-fs">
                                            <img src="" alt="Ays Question Image" class="ays-quiz-live-image">
                                            <p class="ays-fs-title ays-quiz-live-title"></p>
                                            <p class="ays-fs-subtitle ays-quiz-live-subtitle"></p>
                                            <input type="hidden" name="ays_quiz_id" value="2">
                                            <input type="button" name="next" class="ays_next start_button action-button ays-quiz-live-button"
                                                   value="<?php echo __( "Start", $this->plugin_name ); ?>">
                                            <br>
                                            <br>

                                            <div class='ays-progress first <?php echo ($progress_bar_style == 'first') ? "display_block" : ""; ?>'>
                                                <span class='ays-progress-value first' style='width:67%;'>67%</span>
                                                <div class="ays-progress-bg first">
                                                    <div class="ays-progress-bar first" style='width:67%;'></div>
                                                </div>
                                            </div>

                                            <div class='ays-progress second <?php echo ($progress_bar_style == 'second') ? "display_block" : ""; ?>'>
                                                <span class='ays-progress-value second' style='width:88%;'>88%</span>
                                                <div class="ays-progress-bg second">
                                                    <div class="ays-progress-bar second" style='width:88%;'></div>
                                                </div>
                                            </div>
                                            
                                            <div class="ays-progress third <?php echo ($progress_bar_style == 'third') ? "display_block" : ""; ?>">
                                                <span class="ays-progress-value third">55%</span>
                                                <div class="ays-progress-bg third">
                                                    <div class="ays-progress-bar third" style='width:55%;'></div>
                                                </div>
                                            </div>
                                            
                                            <div class="ays-progress fourth <?php echo ($progress_bar_style == 'fourth') ? "display_block" : ""; ?>">
                                                <span class="ays-progress-value fourth">34%</span>
                                                <div class="ays-progress-bg fourth">
                                                    <div class="ays-progress-bar fourth" style="width:34%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab3" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab3') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('Quiz Settings',$this->plugin_name)?></p>
                <hr/>
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                        <div class="pro_features" style="justify-content:flex-end;padding-right:20px;">
                            <div>
                                <p style="font-size:20px;margin:0;">
                                    <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                    <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="active_date_check">
                                    <?php echo __('Schedule the Quiz', $this->plugin_name); ?>
                                    <a class="ays_help" data-toggle="tooltip"
                                    title="<?php echo __('The period of time when quiz will be active', $this->plugin_name) ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8 ays_divider_left">
                                <div class="form-group row">
                                    <div class="col-sm-1">
                                        <input id="active_date_check" type="checkbox" class="active_date_check">
                                    </div>
                                    <div class="col-sm-11 active_date">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="ays-active"> <?php echo __('Start date:', $this->plugin_name); ?> </label>
                                                    <input type="date" id="ays-active" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="ays-deactive"> <?php echo __('End date:', $this->plugin_name); ?> </label>
                                                    <input type="date" id="ays-deactive" value="">
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label class="form-check-label"><?php echo __("Expiration message:", $this->plugin_name) ?></label>
                                                    <div class="editor" style="padding-right:15px;">
                                                        <textarea class="ays-textarea"><?php echo __("This quiz has expired!", $this->plugin_name); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_randomize_answers">
                            <?php echo __('Enable randomize answers',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The possibility of showing the answers of the questions in an accidental sequence.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_randomize_answers"
                               name="ays_enable_randomize_answers"
                               value="on" <?php echo (isset($options['randomize_answers']) && $options['randomize_answers'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_randomize_questions">
                           <?php echo __('Enable randomize questions',$this->plugin_name)?>
                           <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The possibility of showing questions in an accidental sequence.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_randomize_questions"
                               name="ays_enable_randomize_questions"
                               value="on" <?php echo (isset($options['randomize_questions']) && $options['randomize_questions'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_question_bank">
                            <?php echo __('Enable question bank',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Selecting random questions from quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_question_bank"
                               name="ays_enable_question_bank" value="on"
                            <?php echo (isset($options['enable_question_bank']) && $options['enable_question_bank'] == 'on') ? 'checked' : ''; ?>>
                    </div>
                    <div class="col-sm-7" style="border-left: 1px solid #ccc; <?php echo (isset($options['enable_question_bank']) && $options['enable_question_bank'] == 'on') ? '' : 'display:none;'; ?>"
                         id="ays_question_bank_div" >
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_questions_count">
                                    <?php echo __('Questions count',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Number of randomly selected questions',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" name="ays_questions_count" id="ays_questions_count"
                                       class="ays-enable-timerl"
                                       value="<?php echo (isset($options['questions_count'])) ? $options['questions_count'] : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-12 only_pro" style="padding:10px 0 0 10px;">
                        <div class="pro_features" style="justify-content:flex-end;padding-right:20px;">
                            <div>
                                <p style="font-size:20px;margin:0;">
                                    <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                    <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="ays-disable-setting">
                                    <?php echo __('Question count per page',$this->plugin_name)?>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" disabled>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting">
                                            <?php echo __('Questions count',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="number" class="ays-text-input" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_answers_view">
                            <?php echo __('Answers view',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('How the answers of questions are shown',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select class="ays-enable-timerl" id="ays_answers_view" name="ays_answers_view">
                            <option value="list" <?php echo (isset($options['answers_view']) && $options['answers_view'] == 'list') ? 'selected' : ''; ?>>
                                List
                            </option>
                            <option value="grid" <?php echo (isset($options['answers_view']) && $options['answers_view'] == 'grid') ? 'selected' : ''; ?>>
                                Grid
                            </option>
                        </select>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_questions_counter">
                            <?php echo __('Show questions counter',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show total number of quiz questions and the number of one that is active on display',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_questions_counter"
                               name="ays_enable_questions_counter"
                               value="on" <?php echo (isset($options['enable_questions_counter']) && $options['enable_questions_counter'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_rtl_direction">
                            <?php echo __('Use RTL Direction',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Use direction from Right to Left',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_rtl_direction"
                               name="ays_enable_rtl_direction"
                               value="on" <?php echo (isset($options['enable_rtl_direction']) && $options['enable_rtl_direction'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="ays_enable_correction">
                            <?php echo __('Show correct answers',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the noted answers are right or false.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_correction"
                               name="ays_enable_correction"
                               value="on" <?php echo (isset($options['enable_correction']) && $options['enable_correction'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="ays_enable_pass_count">
                            <?php echo __('Show passed users count',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the number of users who passed the quiz.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" id="ays_enable_pass_count"
                               name="ays_enable_pass_count"
                               value="on" <?php echo ($enable_pass_count == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="ays_enable_rate_avg">
                            <?php echo __('Show Quiz average rate',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the average rate of the quiz.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" id="ays_enable_rate_avg"
                               name="ays_enable_rate_avg"
                               value="on" <?php echo ($enable_rate_avg == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="ays_show_create_date">
                            <?php echo __('Show quiz creation date',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show quiz creation date in quiz start page',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" id="ays_show_create_date"
                               name="ays_show_create_date"
                               value="on" <?php echo ($show_create_date == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="ays_show_author">
                            <?php echo __('Show quiz author',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show quiz author in quiz start page',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" id="ays_show_author"
                               name="ays_show_author"
                               value="on" <?php echo ($show_author == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="ays_enable_quiz_rate">
                            <?php echo __('Enable Quiz assessment',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Comment and rate the quiz with up to 5 stars',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" id="ays_enable_quiz_rate"
                               name="ays_enable_quiz_rate"
                               value="on" <?php echo ($enable_quiz_rate == 'on') ? 'checked' : ''; ?>/>
                    </div>
                    <div class="col-sm-7 ays_hidden" style="border-left: 1px solid #ccc; <?php echo ($enable_quiz_rate == 'on') ? '' : 'display:none;' ?>">
                        <div class="form-group row">
                            <div class="col-sm-4" style="padding-right: 0px;">
                                <label for="ays_enable_rate_comments">
                                    <?php echo __('Show last 5 reviews',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show last 5 reviews of quiz rate',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" id="ays_enable_rate_comments"
                                       name="ays_enable_rate_comments"
                                       value="on" <?php echo ($enable_rate_comments == 'on') ? 'checked' : ''; ?>/>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-3" style="padding-right: 0px;">
                                <label for="ays_rate_form_title">
                                    <?php echo __('Rating form title',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Text which will notify user that he can submit a feedback',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <?php
                                $content = stripslashes(wpautop($rate_form_title));
                                $editor_id = 'ays_rate_form_title';
                                $settings = array('editor_height' => '4', 'textarea_name' => 'ays_rate_form_title', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_live_bar_option">
                            <?php echo __('Enable live progressbar',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Bar fills parallel to increase of questions number',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_live_bar_option"
                               name="ays_enable_live_progress_bar"
                               value="on" <?php echo (isset($options['enable_live_progress_bar']) && $options['enable_live_progress_bar'] == 'on') ? 'checked' : '' ?>/>
                    </div>
                    <div class="col-sm-7" style="border-left: 1px solid #ccc; <?php echo (isset($options['enable_live_progress_bar']) && $options['enable_live_progress_bar'] == 'on') ? '' : 'display:none;' ?>"
                         id="ays_enable_percent_view_option_div" >
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_percent_view_option">
                                    <?php echo __('Enable percent view',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Shows the progress of questions by percentage',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_percent_view_option"
                                       name="ays_enable_percent_view"
                                       value="on" <?php echo (isset($options['enable_percent_view']) && $options['enable_percent_view'] == 'on') ? 'checked' : '' ?>/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_early_finish">
                            <?php echo __('Enable to finish the quiz early',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable the option to finish the quiz early',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_early_finish"
                               name="ays_enable_early_finish"
                               value="on" <?php echo ($enable_early_finish) ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_enable_next_button">
                            <?php echo __('Enable next button',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('User can change the question forward manually',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" id="ays_enable_next_button" value="on"
                               name="ays_enable_next_button" <?php echo (isset($options['enable_next_button']) && $options['enable_next_button'] == 'on') ? 'checked' : '' ?>>
                    </div>
                    <hr/>
                    <div class="col-sm-3" style="border-left: 1px solid #ccc">
                        <label for="ays_enable_previous_button">
                            <?php echo __('Enable previous button',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('User can change the question backward manually',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" id="ays_enable_previous_button" value="on"
                               name="ays_enable_previous_button" <?php echo (isset($options['enable_previous_button']) && $options['enable_previous_button'] == 'on') ? 'checked' : '' ?>>
                    </div>
                    <hr>
                    <div class="col-sm-3" style="border-left: 1px solid #ccc">
                        <label for="ays_enable_arrows">
                            <?php echo __('Use arrows instead of buttons',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Change questions with arrows',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_arrows" name="ays_enable_arrows"
                               value="on" <?php echo (isset($options['enable_arrows']) && $options['enable_arrows'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_enable_timer">
                            <?php echo __('Enable Timer',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show countdown timer on display',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timerl" id="ays_enable_timer"
                               name="ays_enable_timer"
                               value="on" <?php echo ($enable_timer == 'on') ? 'checked' : ''; ?>/>
                    </div>
                    <div class="col-sm-8" style="border-left: 1px solid #ccc">
                        <div class="ays-quiz-timer-container" id="ays-quiz-timer-container"
                             style="display: <?php echo ($enable_timer == 'on') ? 'block' : 'none'; ?>">
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_quiz_timer"><?php echo __('Timer seconds',$this->plugin_name)?></label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="number" name="ays_quiz_timer" id="ays_quiz_timer"
                                           class="ays-text-input"
                                           value="<?php echo (isset($options['timer'])) ? $options['timer'] : ''; ?>"/>
                                    <p class="ays-important-note"><span><?php echo __('Note',$this->plugin_name)?>!!</span> <?php echo __('After timer finished
                                        countdowning, quiz will be submitted automatically.',$this->plugin_name)?></p>
                                    <hr>
                                    <label for="timer_text">
                                        <?php echo __("Timer Text", $this->plugin_name); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Use',$this->plugin_name)?> %%time%% <?php echo __('for showing time',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                    <?php
                                    $content = wpautop(stripslashes((isset($options['timer_text'])) ? $options['timer_text'] : ''));
                                    $editor_id = 'timer_text';
                                    $settings = array('editor_height' => '4', 'textarea_name' => 'ays_timer_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                    wp_editor($content, $editor_id, $settings);
                                    ?>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_timer_in_title">
                                        <?php echo __('Show timer in page tab',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('For showing timer in page tab before page title.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_timer_in_title" id="ays_quiz_timer_in_title"
                                           <?php echo ($quiz_timer_in_title) ? 'checked' : ''; ?>/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row ays_toggle_parent">
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <label for="ays_enable_bg_music">
                            <?php echo __('Enable Background music',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Background music will play during the passing quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" id="ays_enable_bg_music"
                               name="ays_enable_bg_music" class="ays_toggle_checkbox"
                               value="on" <?php echo $enable_bg_music ? 'checked' : ''; ?>/>
                    </div>
                    <div class="col-sm-7 ays_toggle_target ays_divider_left" style="<?php echo $enable_bg_music ? '' : 'display:none;' ?>">
                        <div class="ays-bg-music-container">
                            <a class="add-quiz-bg-music" href="javascript:void(0);"><?php echo __("Select music", $this->plugin_name); ?></a>
                            <audio controls src="<?php echo $quiz_bg_music; ?>"></audio>
                            <input type="hidden" name="ays_quiz_bg_music" class="ays_quiz_bg_music" value="<?php echo $quiz_bg_music; ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="tab4" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab4') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('Quiz results settings',$this->plugin_name)?></p>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_disable_store_data">
                            <?php echo __('Disable data storing in database',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Disable data storing in the database, and results will not be displayed on the \'Results\' page.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_disable_store_data"
                               name="ays_disable_store_data"
                               value="on" <?php echo $disable_store_data ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row ays_toggle_parent">
                    <div class="col-sm-4">
                        <label for="ays_redirect_after_submit">
                            <?php echo __('Redirect after submit',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Redirect to custom URL after user submit the form.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_redirect_after_submit"
                               name="ays_redirect_after_submit"
                               value="on" <?php echo $redirect_after_submit ? 'checked' : '' ?>/>
                    </div>
                    <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $redirect_after_submit ? '' : 'display_none'; ?>">                                
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_submit_redirect_url">
                                    <?php echo __('Redirect URL',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The URL for redirecting after the user submits the form.',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="url" class="ays-text-input" id="ays_submit_redirect_url"
                                    name="ays_submit_redirect_url"
                                    value="<?php echo $submit_redirect_url; ?>"/>
                            </div>
                        </div>
                        <hr/>                                
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_submit_redirect_delay">
                                    <?php echo __('Redirect delay (sec)', $this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The redirection delay in seconds after the user submits the form.',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" class="ays-text-input" id="ays_submit_redirect_delay"
                                    name="ays_submit_redirect_delay"
                                    value="<?php echo $submit_redirect_delay; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row ays_toggle_parent">
                    <div class="col-sm-4">
                        <label for="ays_enable_exit_button">
                            <?php echo __('Enable EXIT button',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Exit button will be displayed in the finish page and must redirect the user to a custom URL.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_exit_button"
                               name="ays_enable_exit_button"
                               value="on" <?php echo $enable_exit_button ? 'checked' : '' ?>/>
                    </div>
                    <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_exit_button ? '' : 'display_none'; ?>">                                
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_exit_redirect_url">
                                    <?php echo __('Redirect URL',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The custom URL address for EXIT button in finish page.',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="url" class="ays-text-input" id="ays_exit_redirect_url"
                                    name="ays_exit_redirect_url"
                                    value="<?php echo $exit_redirect_url; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-12 only_pro" style="padding:15px;">
                        <div class="pro_features">                            
                            <div>
                                <p style="font-size:20px;margin:0;">
                                    <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                    <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class="ays-disable-setting">
                                    <?php echo __('Hide result',$this->plugin_name)?>
                                </label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="ays_quiz_result_text" id="ays_quiz_result" class="ays-text-input"
                                       placeholder="Text for showing after quiz" disabled/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_hide_score">
                            <?php echo __('Hide score',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('For not to show score on final page',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_hide_score"
                               name="ays_hide_score"
                               value="on" <?php echo (isset($options['hide_score']) && $options['hide_score'] == 'on') ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_bar_option">
                            <?php echo __('Enable progressbar',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show score via progressbar',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_bar_option"
                               name="ays_enable_progress_bar"
                               value="on" <?php echo (isset($options['enable_progress_bar']) && $options['enable_progress_bar'] == 'on') ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_restart_button">
                            <?php echo __('Enable restart button',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('For restarting quiz and pass it again.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_restart_button"
                               name="ays_enable_restart_button"
                               value="on" <?php echo ($enable_restart_button) ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>
                            <?php echo __('Text for right/wrong answers show',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Text for right/wrong answers is displayed when passing the quiz, only on the results page or in both cases.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <label class="ays_quiz_loader">
                            <input type="radio" class="ays-enable-timer1" name="ays_answers_rw_texts" value="on_passing" <?php echo ($answers_rw_texts == 'on_passing') ? 'checked' : '' ?>/>
                            <span><?php echo __( "On passing the quiz", $this->plugin_name ); ?></span>
                        </label>
                        <label class="ays_quiz_loader">
                            <input type="radio" class="ays-enable-timer1" name="ays_answers_rw_texts" value="on_results_page" <?php echo ($answers_rw_texts == 'on_results_page') ? 'checked' : '' ?>/>
                            <span><?php echo __( "On results page", $this->plugin_name ); ?></span>
                        </label>
                        <label class="ays_quiz_loader">
                            <input type="radio" class="ays-enable-timer1" name="ays_answers_rw_texts" value="on_both" <?php echo ($answers_rw_texts == 'on_both') ? 'checked' : '' ?>/>
                            <span><?php echo __( "Both", $this->plugin_name ); ?></span>
                        </label>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_questions_result_option">
                            <?php echo __('Show all questions result in finish page',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show all questions with right and wrong answers after quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_questions_result_option"
                               name="ays_enable_questions_result"
                               value="on" <?php echo (isset($options['enable_questions_result']) && $options['enable_questions_result'] == 'on') ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_average_statistical_option">
                            <?php echo __('Show the Average statistical',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show average score according to all results of the quiz',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_average_statistical_option"
                               name="ays_enable_average_statistical"
                               value="on" <?php echo (isset($options['enable_average_statistical']) && $options['enable_average_statistical'] == 'on') ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_social_buttons">
                            <?php echo __('Show the Social buttons',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show social buttons for share in social websites',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_social_buttons"
                               name="ays_social_buttons"
                               value="on" <?php echo (isset($options['enable_social_buttons']) && $options['enable_social_buttons'] == 'on') ? 'checked' : '' ?>/>
                    </div>
                </div>
                <hr/>
                <div class="form-group row ays_toggle_parent">
                    <div class="col-sm-4">
                        <label for="ays_enable_social_links">
                            <?php echo __('Enable Social Media links',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable Social media links after quiz finish.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_enable_social_links"
                               name="ays_enable_social_links"
                               value="on" <?php echo $enable_social_links ? 'checked' : '' ?>/>
                    </div>
                    <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_social_links ? '' : 'display_none' ?>">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_linkedin_link">
                                    <i class="ays_fa ays_fa_linkedin_square"></i>
                                    <?php echo __('Linkedin link',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Linkedin profile or page link for showing after quiz finish.',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="url" class="ays-text-input" id="ays_linkedin_link" name="ays_social_links[ays_linkedin_link]"
                                    value="<?php echo $linkedin_link; ?>" />
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_facebook_link">
                                    <i class="ays_fa ays_fa_facebook_square"></i>
                                    <?php echo __('Facebook link',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Facebook profile or page link for showing after quiz finish.',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="url" class="ays-text-input" id="ays_facebook_link" name="ays_social_links[ays_facebook_link]"
                                    value="<?php echo $facebook_link; ?>" />
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_twitter_link">
                                    <i class="ays_fa ays_fa_twitter_square"></i>
                                    <?php echo __('Twitter link',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Twitter profile or page link for showing after quiz finish.',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="url" class="ays-text-input" id="ays_twitter_link" name="ays_social_links[ays_twitter_link]"
                                    value="<?php echo $twitter_link; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>
                            <?php echo __('Select quiz loader',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show social buttons for share in social websites',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <label class="ays_quiz_loader">
                            <input name="ays_quiz_loader" type="radio" value="default" <?php echo ($quiz_loader == 'default') ? 'checked' : ''; ?>>
                            <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                        </label>
                        <label class="ays_quiz_loader">
                            <input name="ays_quiz_loader" type="radio" value="circle" <?php echo ($quiz_loader == 'circle') ? 'checked' : ''; ?>>
                            <div class="lds-circle"></div>
                        </label>
                        <label class="ays_quiz_loader">
                            <input name="ays_quiz_loader" type="radio" value="dual_ring" <?php echo ($quiz_loader == 'dual_ring') ? 'checked' : ''; ?>>
                            <div class="lds-dual-ring"></div>
                        </label>
                        <label class="ays_quiz_loader">
                            <input name="ays_quiz_loader" type="radio" value="facebook" <?php echo ($quiz_loader == 'facebook') ? 'checked' : ''; ?>>
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                        </label>
                        <label class="ays_quiz_loader">
                            <input name="ays_quiz_loader" type="radio" value="hourglass" <?php echo ($quiz_loader == 'hourglass') ? 'checked' : ''; ?>>
                            <div class="lds-hourglass"></div>
                        </label>
                        <label class="ays_quiz_loader">
                            <input name="ays_quiz_loader" type="radio" value="ripple" <?php echo ($quiz_loader == 'ripple') ? 'checked' : ''; ?>>
                            <div class="lds-ripple"><div></div><div></div></div>
                        </label>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_result_text">
                            <?php echo __('Text after quiz is completed',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Text on final page for additional information',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $content = wpautop(stripslashes((isset($options['result_text'])) ? $options['result_text'] : ''));
                        $editor_id = 'ays_result_text';
                        $settings = array('editor_height' => '10', 'textarea_name' => 'ays_result_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr/>
                <div class="only_pro" style="margin-top: 30px;">
                    <div class="pro_features">
                        <div>
                            <p>
                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                            </p>
                        </div>
                    </div>
                    <div>
                        <img style="width:100%;max-width:100%;" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/QuizIntervals.png">
                    </div>
                </div>
            </div>
            
            <div id="tab5" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab5') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('Limitation of Users',$this->plugin_name)?></p>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_limit_users">
                            <?php echo __('Limit Users',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('This option allows to block the users who have already passed the quiz.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_limit_users" name="ays_limit_users"
                               value="on" <?php echo (isset($options['limit_users']) && $options['limit_users'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                    <div class="col-sm-8" id="limit-user-options" style="border-left: 1px solid #ccc">
                        <div class="ays-limitation-options">
                            <!-- Limitation message -->
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_limitation_message">
                                        <?php echo __('Message',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for those who have passed the quiz',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <?php
                                    $content = wpautop(stripslashes((isset($options['limitation_message'])) ? $options['limitation_message'] : ''));
                                    $editor_id = 'ays_limitation_message';
                                    $settings = array('editor_height' => '4', 'textarea_name' => 'ays_limitation_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                    wp_editor($content, $editor_id, $settings);
                                    ?>
                                </div>
                            </div>
                            <!-- Limitation redirect url -->
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_redirect_url">
                                        <?php echo __('Redirect url',$this->plugin_name)?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('For leave current page and go to the link provided',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="ays_redirect_url" id="ays_redirect_url"
                                           class="ays-text-input"
                                           value="<?php echo (isset($options['redirect_url'])) ? $options['redirect_url'] : ''; ?>"/>
                                </div>
                            </div>
                            <!-- Limitation redirect delay -->
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_redirection_delay">
                                        <?php echo __('Redirect delay',$this->plugin_name)?>(s)
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Leave current page and go to the link provided after X second',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="number" name="ays_redirection_delay" id="ays_redirection_delay"
                                           class="ays-text-input"
                                           value="<?php echo (isset($options['redirection_delay'])) ? $options['redirection_delay'] : 0; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_enable_logged_users">
                            <?php echo __('Only for logged in users',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('In case of swithching this option the quiz can be passed only by logged in users.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_logged_users"
                               name="ays_enable_logged_users"
                               value="on" <?php echo (((isset($options['enable_logged_users']) && $options['enable_logged_users'] == 'on')) || (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on')) ? 'checked' : ''; ?>/>
                    </div>
                    <div class="col-sm-8" style="border-left: 1px solid #ccc; <?php echo ((isset($options['enable_logged_users']) && $options['enable_logged_users'] == 'on') || (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on')) ? '' : 'display:none;' ?>"
                         id="ays_logged_in_users_div" >
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="ays_logged_in_message">
                                    <?php echo __('Message',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for those who haven’t logged in',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <?php
                                $content = wpautop(stripslashes((isset($options['enable_logged_users_message'])) ? $options['enable_logged_users_message'] : ''));
                                $editor_id = 'ays_logged_in_message';
                                $settings = array('editor_height' => '4', 'textarea_name' => 'ays_enable_logged_users_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_enable_restriction_pass">
                            <?php echo __('Only for selected user role',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Quiz available only for positions mentioned in the list',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_restriction_pass"
                               name="ays_enable_restriction_pass"
                               value="on" <?php echo (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on') ? 'checked' : ''; ?>/>
                    </div>
                    <div class="col-sm-8" id="ays_users_roles_td"
                         style="border-left: 1px solid #ccc; display: <?php echo (isset($options['enable_restriction_pass']) && $options['enable_restriction_pass'] == 'on') ? '' : 'none'; ?>">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_users_roles">
                                    <?php echo __('User role',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Position of the user in the website',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <select name="ays_users_roles[]" id="ays_users_roles" multiple>
                                    <?php
                                    foreach ($ays_users_roles as $key => $user_role) {
                                        $selected_role = "";
                                        if(isset($options['user_role'])){
                                            if(is_array($options['user_role'])){
                                                if(in_array($user_role['name'], $options['user_role'])){
                                                    $selected_role = 'selected';
                                                }else{
                                                    $selected_role = '';
                                                }
                                            }else{
                                                if($options['user_role'] == $user_role['name']){
                                                    $selected_role = 'selected';
                                                }else{
                                                    $selected_role = '';
                                                }
                                            }
                                        }
                                        echo "<option value='" . $user_role['name'] . "' " . $selected_role . ">" . $user_role['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="restriction_pass_message">
                                    <?php echo __('Message',$this->plugin_name)?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for those whose position is not selected',$this->plugin_name)?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <?php
                                $content = wpautop(stripslashes((isset($options['restriction_pass_message'])) ? $options['restriction_pass_message'] : ''));
                                $editor_id = 'restriction_pass_message';
                                $settings = array('editor_height' => '4', 'textarea_name' => 'restriction_pass_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                                wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="tab6" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab6') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('User Information',$this->plugin_name)?></p>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_form_title">
                            <?php echo __('Information Form title',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Title of data form for the user personal information',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8" style="border-left: 1px solid #ccc">
                        <?php
                        $content = wpautop(stripslashes((isset($options['form_title'])) ? $options['form_title'] : ''));
                        $editor_id = 'ays_form_title';
                        $settings = array('editor_height' => '8', 'textarea_name' => 'ays_form_title', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label>
                            <?php echo __('Information form',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Data form for the user personal information',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <div class="information_form_settings">
                            <select name="ays_information_form" id="ays_information_form">
                                <option value="after" <?php echo (isset($options['information_form']) && $options['information_form'] == 'after') ? 'selected' : ''; ?>>
                                    <?php echo __('After Quiz',$this->plugin_name)?>
                                </option>
                                <option value="before" <?php echo (isset($options['information_form']) && $options['information_form'] == 'before') ? 'selected' : ''; ?>>
                                    <?php echo __('Before Quiz',$this->plugin_name)?>
                                </option>
                                <option value="disable" <?php echo (isset($options['information_form']) && $options['information_form'] == 'disable') ? 'selected' : ''; ?>>
                                    <?php echo __('Disable',$this->plugin_name)?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-8" style="border-left: 1px solid #ccc">
                        <div class="information_form_options" <?php echo (!isset($options['information_form']) || $options['information_form'] == "disable") ? 'style="display:none"' : ''; ?>>
                            <p class="ays_required_field_title"><?php echo __('Form Fields',$this->plugin_name)?></p>
                            <hr>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="ays_form_name" name="ays_form_name"
                                       value="on" <?php echo (isset($options['form_name']) && $options['form_name'] !== '') ? 'checked' : ''; ?>/>
                                <label class="form-check-label" for="ays_form_name"><?php echo __('Name',$this->plugin_name)?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="ays_form_email"
                                       name="ays_form_email"
                                       value="on" <?php echo (isset($options['form_email']) && $options['form_email'] !== '') ? 'checked' : ''; ?>/>
                                <label class="form-check-label" for="ays_form_email"><?php echo __('Email',$this->plugin_name)?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="ays_form_phone"
                                       name="ays_form_phone"
                                       value="on" <?php echo (isset($options['form_phone']) && $options['form_phone'] !== '') ? 'checked' : ''; ?>/>
                                <label class="form-check-label" for="ays_form_phone"><?php echo __('Phone',$this->plugin_name)?></label>
                            </div>
                            <hr>
                            <p class="ays_required_field_title"><?php echo __('Required Fields',$this->plugin_name)?></p>
                            <hr>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="ays_form_name_required"
                                       name="ays_required_field[]"
                                       value="ays_user_name" <?php echo (in_array('ays_user_name', $required_fields)) ? 'checked' : ''; ?>/>
                                <label class="form-check-label" for="ays_form_name_required"><?php echo __('Name',$this->plugin_name)?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="ays_form_email_required"
                                       name="ays_required_field[]"
                                       value="ays_user_email" <?php echo (in_array('ays_user_email', $required_fields)) ? 'checked' : ''; ?>/>
                                <label class="form-check-label" for="ays_form_email_required"><?php echo __('Email',$this->plugin_name)?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="ays_form_phone_required"
                                       name="ays_required_field[]"
                                       value="ays_user_phone" <?php echo (in_array('ays_user_phone', $required_fields)) ? 'checked' : ''; ?>/>
                                <label class="form-check-label" for="ays_form_phone_required"><?php echo __('Phone',$this->plugin_name)?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row" style="position:relative;padding:15px;">
                    <div class="pro_features" style="align-items: flex-end;justify-content: flex-end;">
                        <div>
                            <p style="margin: 0;margin-bottom: 5px;margin-right: 5px;padding: 4px 8px;font-size: 20px;">
                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label>
                            <?php echo __('Add custom fields',$this->plugin_name); ?>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_divider_left">
                        <blockquote>
                            <?php echo __("For creating custom fields click ", $this->plugin_name); ?>
                            <a href="?page=<?php echo $this->plugin_name; ?>-quiz-attributes" target="_blank" ><?php echo __("here", $this->plugin_name); ?></a>
                        </blockquote>
                    </div>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_autofill_user_data">
                            <?php echo __('Autofill logged in user data',$this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Autofill logged in user name and email',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <div class="information_form_settings">
                            <input type="checkbox" id="ays_autofill_user_data" name="ays_autofill_user_data" value="on" <?php echo $autofill_user_data ? "checked" : ""; ?>>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="tab7" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab7') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('E-mail and Certificate settings',$this->plugin_name)?></p>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="pro_features">
                            <div>
                                <p>
                                    <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                    <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class="ays-disable-setting"><?php echo __('Send Mail To User',$this->plugin_name)?></label>

                            </div>
                            <div class="col-sm-2">
                                <input type="checkbox" class="ays-enable-timerl ays-disable-setting"
                                       id="ays_enable_mail_user"
                                       disabled/>
                            </div>

                            <div class="col-sm-8" id="ays_mail_message_div">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('Mail message',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                    <textarea type="text" id="ays_mail_message" class="ays-textarea ays-disable-setting"
                                              disabled></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class="ays-disable-setting"><?php echo __('Send Certificate To User',$this->plugin_name)?></label>

                            </div>
                            <div class="col-sm-2">
                                <input type="checkbox" class="ays-enable-timerl ays-disable-setting"
                                       id="ays_enable_certificate"
                                       value="on" disabled/>
                            </div>
                            <div class="col-sm-8" id="ays_certificate_pass_div">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('Certificate pass score',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="number" id="ays_certificate_pass" class="ays-disable-setting" disabled>
                                    </div>    
                                </div>                            
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('Certificate title',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea disabled class="ays-textarea ays-disable-setting">Certificate of Completion</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('Certificate body',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea disabled class="ays-textarea ays-disable-setting" style="height:320px;">This is to certify that

%%user_name%%

has completed the quiz

"%%quiz_name%%"

with score of %%score%%

dated
%%current_date%%
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class="ays-disable-setting"><?php echo __('Use SMTP',$this->plugin_name)?>
                                    <sup class="ays_recommended"><?php echo __('Recommended',$this->plugin_name)?></sup>
                                </label>
                            </div>
                            <div class="col-sm-2">
                                <input type="checkbox" class="ays-enable-timerl ays-disable-setting" id="ays_enable_smtp" value="on" disabled/>
                            </div>
                            <div class="col-sm-8" id="ays_smtp_inputs">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('SMTP Username',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="ays-smtp-name" name="ays_smtp_username"
                                               class="ays-text-input ays-text-input-short ays-disable-setting" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('SMTP Password',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="password" id="ays-smtp-password" name="ays_smtp_password"
                                               class="ays-text-input ays-text-input-short ays-disable-setting" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('Host',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" id="ays-smtp-host" name="ays_smtp_host"
                                               class="ays-text-input ays-text-input-short ays-disable-setting" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('SMTP Secure',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="ays_smtp_secure" id="ays_smtp_secures"
                                                class="ays-disable-setting ays-text-input ays-text-input-short" disabled>
                                            <option value="ssl"><?php echo __('SSL',$this->plugin_name)?></option>
                                            <option value="tls"><?php echo __('TLS',$this->plugin_name)?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="ays-disable-setting"><?php echo __('Port',$this->plugin_name)?></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="number" id="ays-smtp-port" name="ays_smtp_port"
                                               class="ays-text-input ays-text-input-short ays-disable-setting" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label class="ays-disable-setting"><?php echo __('Send Mail To Admin',$this->plugin_name)?></label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timerl" id="ays_enable_mail_admin" value="on"/>
                            </div>
                            <div class="col-sm-8 ays_divider_left">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_additional_emails">
                                            <?php echo __('Additional Emails',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input" id="ays_additional_emails" placeholder="example1@gmail.com, example2@gmail.com, ..."/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <?php echo __('Email Configuration',$this->plugin_name)?>
                                </label>
                            </div>
                            <div class="col-sm-8 ays_divider_left">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_email_configuration_from_email">
                                            <?php echo __('From Email',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input" id="ays_email_configuration_from_email"/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_email_configuration_from_name">
                                            <?php echo __('From Name',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input" id="ays_email_configuration_from_name"/>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_email_configuration_from_subject">
                                            <?php echo __('From Subject',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input" id="ays_email_configuration_from_subject"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="tab8" class="ays-integrations-tab ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab8') ? 'ays-quiz-tab-content-active' : ''; ?>">
                <p class="ays-subtitle"><?php echo __('Integrations settings',$this->plugin_name)?></p>
                <hr/>
                <fieldset>
                    <legend>
                        <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/mailchimp_logo.png" alt="">
                        <h5><?php echo __('MailChimp Settings',$this->plugin_name)?></h5>
                    </legend>
                    <div class="form-group row">
                    <div class="col-sm-12" style="padding:20px;">
                        <div class="pro_features" style="justify-content:flex-end;">
                            <div style="margin-right:20px;">
                                <p style="font-size:20px;">
                                    <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                    <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_mailchimp">
                                    <?php echo __('Enable MailChimp',$this->plugin_name)?>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" checked class="ays-enable-timer1" id="ays_enable_mailchimp"/>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_mailchimp_list">
                                    <?php echo __('MailChimp list',$this->plugin_name)?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <select id="ays_mailchimp_list">
                                    <option value="" disabled selected>Select list</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </fieldset>
                <hr/>
                <fieldset>
                    <legend>
                        <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/paypal_logo.png" alt="">
                        <h5><?php echo __('PayPal Settings',$this->plugin_name)?></h5>
                    </legend>                    
                    <div class="form-group row">
                        <div class="col-sm-12" style="padding:20px;">
                            <div class="pro_features" style="justify-content:flex-end;">
                                <div style="margin-right:20px;">
                                    <p style="font-size:20px;">
                                        <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                        <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="Developer feature"><?php echo __("Developer package!!!", $this->plugin_name); ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_enable_paypal">
                                        <?php echo __('Enable PayPal',$this->plugin_name)?>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_paypal" value="on" checked/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_paypal_amount">
                                        <?php echo __('Amount',$this->plugin_name)?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="ays-text-input ays-text-input-short" id="ays_paypal_amount" value="20"/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_paypal_currency">
                                        <?php echo __('Currency',$this->plugin_name)?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_paypal_currency">
                                        <option value="USD">USD - United States Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound Sterling</option>
                                        <option value="CHF">CHF - Swiss Franc</option>
                                        <option value="JPY">JPY - Japanese Yen</option>
                                        <option value="INR">INR - Indian Rupee</option>
                                        <option value="CNY">CNY - Chinese Yuan</option>
                                        <option value="CAD">CAD - Canadian Dollar</option>
                                        <option value="AED">AED - United Arab Emirates Dirham</option>
                                        <option value="RUB">RUB - Russian Ruble</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <hr/>
                <fieldset>
                    <legend>                        
                        <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/campaignmonitor_logo.png" alt="">
                        <h5><?php echo __('Campaign Monitor Settings', $this->plugin_name) ?></h5>
                    </legend>
                    <div class="form-group row">
                        <div class="col-sm-12" style="padding:20px;">
                            <div class="pro_features" style="justify-content:flex-end;">
                                <div style="margin-right:20px;">
                                    <p style="font-size:20px;">
                                        <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                        <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="Developer feature"><?php echo __("Developer package!!!", $this->plugin_name); ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_enable_monitor">
                                        <?php echo __('Enable Campaign Monitor', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_monitor"/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_monitor_list">
                                        <?php echo __('Campaign Monitor list', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_monitor_list">
                                        <option value="" disabled selected><?= __("Select List", $this->plugin_name) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <hr/>                
                <fieldset>
                    <legend>
                        <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/zapier_logo.png" alt="">
                        <h5><?php echo __('Zapier Integration Settings', $this->plugin_name) ?></h5>
                    </legend>
                    <div class="form-group row">
                        <div class="col-sm-12" style="padding:20px;">
                            <div class="pro_features" style="justify-content:flex-end;">
                                <div style="margin-right:20px;">
                                    <p style="font-size:20px;">
                                        <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                        <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="Developer feature"><?php echo __("Developer package!!!", $this->plugin_name); ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_enable_zapier">
                                        <?php echo __('Enable Zapier Integration', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_zapier"/>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="testZapier" class="btn btn-outline-secondary">
                                        <?= __("Send test data", $this->plugin_name) ?>
                                    </button>
                                    <a class="ays_help" data-toggle="tooltip" style="font-size: 16px;"
                                       title="<?= __('We will send you a test data, and you can catch it in your ZAP for configure it.', $this->plugin_name) ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </div>
                            </div>
                            <div id="testZapierFields" class="d-none">
                                <input type="checkbox"/>
                                <input type="checkbox"/>
                                <input type="checkbox"/>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <hr/>                
                <fieldset>
                    <legend>
                        <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/activecampaign_logo.png" alt="">
                        <h5><?php echo __('ActiveCampaign Settings', $this->plugin_name) ?></h5>
                    </legend>
                    <div class="form-group row">
                        <div class="col-sm-12" style="padding:20px;">
                            <div class="pro_features" style="justify-content:flex-end;">
                                <div style="margin-right:20px;">
                                    <p style="font-size:20px;">
                                        <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                        <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="Developer feature"><?php echo __("Developer package!!!", $this->plugin_name); ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_enable_active_camp">
                                        <?php echo __('Enable ActiveCampaign', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_active_camp"/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_active_camp_list">
                                        <?php echo __('ActiveCampaign list', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_active_camp_list">
                                        <option value="" disabled selected><?= __("Select List", $this->plugin_name) ?></option>
                                        <option value=""><?= __("Just create contact", $this->plugin_name) ?></option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_active_camp_automation">
                                        <?php echo __('ActiveCampaign automation', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_active_camp_automation">
                                        <option value="" disabled selected><?= __("Select List", $this->plugin_name) ?></option>
                                        <option value=""><?= __("Just create contact", $this->plugin_name) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <hr/>
                <fieldset>
                    <legend>
                        <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/slack_logo.png" alt="">
                        <h5><?php echo __('Slack Settings', $this->plugin_name) ?></h5>
                    </legend>
                    <div class="form-group row">
                        <div class="col-sm-12" style="padding:20px;">
                            <div class="pro_features" style="justify-content:flex-end;">
                                <div style="margin-right:20px;">
                                    <p style="font-size:20px;">
                                        <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                        <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="Developer feature"><?php echo __("Developer package!!!", $this->plugin_name); ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_enable_slack">
                                        <?php echo __('Enable Slack integration', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_slack"/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_slack_conversation">
                                        <?php echo __('Slack conversation', $this->plugin_name) ?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_slack_conversation">
                                        <option value="" disabled selected><?= __("Select Channel", $this->plugin_name) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            
            <hr/>
            <!-- <div class="ays_divider_top ays_sticky_submit"> -->
            <?php
                wp_nonce_field('quiz_action', 'quiz_action');
                $other_attributes = array();
                submit_button(__('Save Quiz', $this->plugin_name), 'primary', 'ays_submit', true, $other_attributes);
                submit_button(__('Apply Quiz', $this->plugin_name), '', 'ays_apply', true, $other_attributes);
            ?>
            <!-- </div> -->
        </form>
        <div id="ays-questions-modal" class="ays-modal modal">
            <!-- Modal content -->
            <div class="ays-modal-content">
                <form method="post" id="ays_add_question_rows">
                    <div class="ays-quiz-preloader">
                        <img src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/loaders/cogs.svg">
                    </div>
                    <div class="ays-modal-header">
                        <span class="ays-close">&times;</span>
                        <h2><?php echo __('Select questions', $this->plugin_name); ?></h2>
                    </div>
                    <div class="ays-modal-body">
                        <?php
                        wp_nonce_field('add_question_rows_top', 'add_question_rows_top_second');
                        $other_attributes = array();
                        submit_button(__('Select questions', $this->plugin_name), 'primary', 'add_question_rows_top', true, $other_attributes);
                        ?>
                        <span style="font-size: 13px; font-style: italic;">
                            <?php echo __('For select questions click on question row and then click "Select questions" button', $this->plugin_name); ?>
                        </span>
                        <p style="font-size: 16px; padding-right:20px; margin:0; text-align:right;">
                            <a class="" href="admin.php?page=<?php echo $this->plugin_name; ?>-questions&action=add" target="_blank"><?php echo __('Create question', $this->plugin_name); ?></a>
                        </p>
                        <table class="ays-add-questions-table hover order-column" id="ays-question-table-add" data-page-length='5'>
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo __('Question', $this->plugin_name); ?></th>
                                <th><?php echo __('Type', $this->plugin_name); ?></th>
                                <th><?php echo __('Created', $this->plugin_name); ?></th>
                                <th><?php echo __('Category', $this->plugin_name); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($questions as $index => $question) {
                                $question_options = json_decode($question['options'], true);
                                $date = isset($question['create_date']) && $question['create_date'] != '' ? $question['create_date'] : "0000-00-00 00:00:00";
                                if(isset($question_options['author'])){
                                    if(is_array($question_options['author'])){
                                        $author = $question_options['author'];
                                    }else{
                                        $author = json_decode($question_options['author'], true);
                                    }
                                }else{
                                    $author = array("name"=>"Unknown");
                                }
                                $text = "";
                                if(Quiz_Maker_Admin::validateDate($date)){
                                    $text .= "<p style='margin:0;text-align:left;'><b>Date:</b> ".$date."</p>";
                                }
                                if($author['name'] !== "Unknown"){
                                    $text .= "<p style='margin:0;text-align:left;'><b>Author:</b> ".$author['name']."</p>";
                                }
                                $selected_question = (in_array($question["id"], $question_id_array)) ? "selected" : "";
                                $table_question = (strip_tags(stripslashes($question['question'])));
                                $table_question = $this->ays_restriction_string("word", $table_question, 8);
                                ?>
                                <tr class="ays_quest_row <?php echo $selected_question; ?>" data-id='<?php echo $question["id"]; ?>'>
                                    <td>
                                        <span>
                                        <?php if (in_array($question["id"], $question_id_array)) : ?>
                                           <i class="ays-select-single ays_fa ays_fa_check_square_o"></i>
                                        <?php else: ?>
                                           <i class="ays-select-single ays_fa ays_fa_square_o"></i>
                                        <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="ays-modal-td-question"><?php echo $table_question; ?></td>
                                    <td><?php echo $question["type"]; ?></td>
                                    <td><?php echo $text; ?></td>
                                    <td class="ays-modal-td-category"><?php echo stripslashes($question["title"]); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="ays-modal-footer">
                        <?php
                        wp_nonce_field('add_question_rows', 'add_question_rows');
                        $other_attributes = array('id' => 'ays-button');
                        submit_button(__('Select questions', $this->plugin_name), 'primary', 'add_question_rows', true, $other_attributes);
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>