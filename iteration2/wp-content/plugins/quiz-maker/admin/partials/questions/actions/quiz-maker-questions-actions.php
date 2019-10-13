<?php
$action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';
$heading = '';
$image_text = __('Add Image', $this->plugin_name);

$id = (isset($_GET['question'])) ? absint(intval($_GET['question'])) : null;
$user_id = get_current_user_id();
$user = get_userdata($user_id);
$author = array(
    'id' => $user->ID,
    'name' => $user->data->display_name
);
$options = array(
    'author' => $author,
);
$question = array(
    'category_id' => '',
    'question' => '',
    'question_image' => '',
    'type' => 'radio',
    'published' => '',
    'wrong_answer_text' => '',
    'right_answer_text' => '',
    'explanation' => '',
    'create_date' => date("Y-m-d H:i:s"),
    'not_influence_to_score' => 'off',
    'options' => json_encode($options),
);

$answers = array();
switch ($action) {
    case 'add':
        $heading = __('Add new question', $this->plugin_name);
        break;
    case 'edit':
        $heading = __('Edit question', $this->plugin_name);
        $question = $this->questions_obj->get_question($id);
        $answers = $this->questions_obj->get_question_answers($id);
        break;
}
$question['type'] = (isset($question['type']) && $question['type'] != '') ? $question['type'] : 'radio';
$question_categories = $this->questions_obj->get_question_categories();
if (isset($_POST['ays_submit']) || isset($_POST['ays_submit_top'])) {
    $_POST["id"] = $id;
    $this->questions_obj->add_edit_questions($_POST);
}
if(isset($_POST['ays_apply_top']) || isset($_POST['ays_apply'])){
    $_POST["id"] = $id;
    $_POST['ays_change_type'] = 'apply';
    $this->questions_obj->add_edit_questions($_POST);
}
$style = null;
if ($question['question_image'] != '') {
    $style = "display: block;";
    $image_text = __('Edit Image', $this->plugin_name);
}
$question_create_date = (isset($question['create_date']) && $question['create_date'] != '') ? $question['create_date'] : "0000-00-00 00:00:00";
$options = json_decode($question['options'], true);

if(isset($options['author']) && $options['author'] != 'null'){
    if($action == 'edit'){
        if(is_array($options['author'])){
            $question_author = $options['author'];
        }else{
            $question_author = json_decode($options['author'], true);
        }        
    }else{
        $question_author = $options['author'];
    }
} else {
    $question_author = array('name' => 'Unknown');
}
$question_types = array(
    "radio" => __("Radio", $this->plugin_name),
    "checkbox" => __("Checkbox( Multiple )", $this->plugin_name),
    "select" => __("Dropdown", $this->plugin_name),
    "text" => __("Text", $this->plugin_name),
    "number" => __("Number", $this->plugin_name),
);
$question['not_influence_to_score'] = ! isset($question['not_influence_to_score']) ? 'off' : $question['not_influence_to_score'];
$not_influence_to_score = (isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on') ? true : false;
?>

<div class="wrap">
    <div class="container-fluid">
        <hr/>
        <form method="post" id="ays-question-form">
            <input type="hidden" name="ays_question_ctrate_date" value="<?php echo $question_create_date; ?>">
            <input type="hidden" name="ays_question_author" value="<?php echo htmlentities(json_encode($question_author)); ?>">
            <h1 class="wp-heading-inline">
                <?php
                    echo $heading;
                    $other_attributes = array('id' => 'ays-button-top');
                    submit_button(__('Save Question', $this->plugin_name), 'primary', 'ays_submit_top', false, $other_attributes);
                    submit_button(__('Apply Question', $this->plugin_name), '', 'ays_apply_top', false, $other_attributes);
                ?>
            </h1>
            <hr/>
            <div class="ays-quiz-category-form" id="ays-quiz-category-form">
                <div class="ays-field">
                    <label for='ays-question'><?php echo __('Question', $this->plugin_name); ?>
                        <a href="javascript:void(0)" class="add-question-image"><?php echo $image_text; ?></a>
                    </label>
                    <div class="ays-question-image-container" style="<?php echo $style; ?>">
                        <span class="ays-remove-question-img"></span>
                        <img src="<?php echo $question['question_image']; ?>" id="ays-question-img"/>
                        <input type="hidden" name="ays_question_image" id="ays-question-image" value="<?php echo $question['question_image']; ?>"/>
                    </div>
                    <?php
                        $content = stripslashes(wpautop($question["question"]));
                        $editor_id = 'ays-question';
                        $settings = array('editor_height' => '8', 'textarea_name' => 'ays_question', 'editor_class' => 'ays-textarea', 'media_buttons' => true);
                        wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays-type"><?php echo __('Question type', $this->plugin_name); ?></label>
                    </div>
                    <div class="col-sm-8">
                        <select id="ays-type" name="ays_question_type">
                            <option></option>
                            <?php
                                foreach($question_types as $type => $label):
                                $selected = $question["type"] == $type ? "selected" : "";
                            ?>
                            <option value="<?php echo $type; ?>" <?php echo $selected; ?> ><?php echo $label; ?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <hr/>
                <div>
                    <label class='ays-label' for="ays-answers-table">
                       <?php
                            if($question["type"] == 'text'):
                        ?>
                       <?php echo __('Answer', $this->plugin_name); ?>
                        <?php
                            else:
                        ?>
                       <?php echo __('Answers', $this->plugin_name); ?>      
                        <a href="javascript:void(0)" class="ays-add-answer">
                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                        </a>
                       <?php
                            endif;
                        ?>
                    </label>
                </div>
                <table class="ays-answers-table" id="ays-answers-table">
                    <thead>
                        <tr class="ui-state-default">
                           <?php
                                if($question["type"] != 'text' &&
                                   $question["type"] != 'number'):
                            ?>
                            <th class="th-150 removable"><?php echo __('Ordering', $this->plugin_name); ?></th>
                            <th class="th-150 removable"><?php echo __('Correct', $this->plugin_name); ?></th>
                           <?php
                                endif;
                            ?>
                            <th <?php echo ($question["type"] == 'text' || $question["type"] == 'number') ? 'class="th-650"' : ''; ?>><?php echo __('Answer', $this->plugin_name); ?></th>
                            <th class="th-150"><?php echo __('Image', $this->plugin_name); ?></th>
                            <th class="th-150"><?php echo __('Delete', $this->plugin_name); ?></th>
                        </tr>
                    </thead>
                    <tbody class="<?php echo ($question["type"] == 'text') ? 'text_answer' : '';?>">
                    <?php if (empty($answers)) : ?>
                        <tr class="ays-answer-row ui-state-default">
                            <td><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                            <td>
                                <span>
                                    <input type="radio" id="ays-correct-answer-1" class="ays-correct-answer" name="ays-correct-answer[]" value="1"/>
                                    <label for="ays-correct-answer-1"></label>
                                </span>
                            </td>
                            <td>
                                <input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"/>
                            </td>
                            <td title="This property aviable only in pro version">
                                <label class='ays-label' for='ays-answer'>
                                    <a style="opacity: 0.4" href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" class="add-answer-image"><?php echo __('Add',$this->plugin_name)?></a>
                                </label>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="ays-delete-answer">
                                    <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <tr class="ays-answer-row ui-state-default even">
                            <td><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                            <td>
                                <span>
                                    <input type="radio" id="ays-correct-answer-2" class="ays-correct-answer" name="ays-correct-answer[]" value="2"/>
                                    <label for="ays-correct-answer-2"></label>
                                </span>
                            </td>
                            <td><input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"/></td>
                            <td title="This property aviable only in pro version">
                                <label class='ays-label' for='ays-answer'>
                                    <a style="opacity: 0.4" href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" class="add-answer-image"><?php echo __('Add',$this->plugin_name)?></a>
                                </label>
                            </td>

                            <td>
                                <a href="javascript:void(0)" class="ays-delete-answer">
                                    <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <tr class="ays-answer-row ui-state-default">
                            <td><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                            <td>
                                <span>
                                    <input type="radio" id="ays-correct-answer-3" class="ays-correct-answer" name="ays-correct-answer[]" value="3"/>
                                    <label for="ays-correct-answer-3"></label>
                                </span>
                            </td>
                            <td><input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"/></td>
                            <td title="This property aviable only in pro version">
                                <label class='ays-label' for='ays-answer'>
                                    <a style="opacity: 0.4" href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" class="add-answer-image"><?php echo __('Add',$this->plugin_name)?></a>
                                </label>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="ays-delete-answer">
                                    <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    else:
                        foreach ($answers as $index => $answer) {
                            $class = (($index + 1) % 2 == 0) ? "even" : "";
                            
                            switch ($question["type"]) {
                                case "number":
                                    $question_type = 'number';
                                    break;
                                case "text":
                                    $question_type = 'radio';
                                    break;
                                case "checkbox":
                                    $question_type = 'checkbox';
                                    break;
                                default:
                                    $question_type = 'radio';
                                    break;
                            }
                            ?>
                            <tr class="ays-answer-row ui-state-default <?php echo $class; ?>">
                                <?php
                                    if($question["type"] == 'number'):
                                ?>
                                <td>
                                    <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                    <input type="<?php echo $question_type; ?>" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value="<?php echo stripslashes(htmlentities($answer["answer"])); ?>"/>
                                </td>
                                <td title="This property aviable only in pro version">
                                    <label class='ays-label' for='ays-answer'>
                                        <a style="opacity: 0.4" href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" class="add-answer-image"><?php echo __('Add',$this->plugin_name)?></a>
                                    </label>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="ays-delete-answer">
                                        <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <?php
                                    elseif($question["type"] == 'text'):
                                ?>
                                <td>
                                    <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                    <textarea type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"><?php echo stripslashes(htmlentities($answer["answer"])); ?></textarea>
                                </td>
                                <td title="This property aviable only in pro version">
                                    <label class='ays-label' for='ays-answer'>
                                        <a style="opacity: 0.4" href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" class="add-answer-image"><?php echo __('Add',$this->plugin_name)?></a>
                                    </label>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="ays-delete-answer">
                                        <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <?php
                                    else:
                                ?>
                                <td><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                                <td>
                                    <span>
                                        <input type="<?php echo $question_type; ?>" id="ays-correct-answer-<?php echo($index + 1); ?>" class="ays-correct-answer" name="ays-correct-answer[]" value="<?php echo($index + 1); ?>" <?php echo ($answer["correct"] == 1) ? "checked" : ""; ?>/>
                                        <label for="ays-correct-answer-<?php echo($index + 1); ?>"></label>
                                    </span>
                                </td>
                                <td><input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value="<?php echo stripslashes(htmlentities($answer["answer"])); ?>"/></td>
                                <td title="This property aviable only in pro version">
                                    <label class='ays-label' for='ays-answer'>
                                        <a style="opacity: 0.4" href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" class="add-answer-image"><?php echo __('Add',$this->plugin_name)?></a>
                                    </label>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="ays-delete-answer">
                                        <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <?php
                                    endif;
                                ?>
                            </tr>
                            <?php
                        }
                    endif;
                    ?>
                    </tbody>
                </table>
                <hr/>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays-category"><?php echo __('Question category', $this->plugin_name); ?></label>
                    </div>
                    <div class="col-sm-8">
                        <select id="ays-category" name="ays_question_category">
                            <option></option>
                            <?php
                            $cat = 0;
                            foreach ($question_categories as $question_category) {
                                $checked = (intval($question_category['id']) == intval($question['category_id'])) ? "selected" : "";
                                if ($cat == 0 && intval($question['category_id']) == 0) {
                                    $checked = 'selected';
                                }
                                echo "<option value='" . $question_category['id'] . "' " . $checked . ">" . stripslashes($question_category['title']) . "</option>";
                                $cat++;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label><?php echo __('Question status', $this->plugin_name); ?></label>
                    </div>

                    <div class="col-sm-8">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-publish" name="ays_publish" value="1" <?php echo ($question["published"] == '') ? "checked" : ""; ?>  <?php echo ($question["published"] == '1') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays-publish"> <?php echo __('Published', $this->plugin_name); ?> </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="ays-unpublish" name="ays_publish" value="0" <?php echo ($question["published"] == '0') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays-unpublish"> <?php echo __('Unpublished', $this->plugin_name); ?> </label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label><?php echo __('Not influence to score', $this->plugin_name); ?></label>
                    </div>

                    <div class="col-sm-8">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" id="ays_not_influence_to_score" name="ays_not_influence_to_score" value="on" <?php echo ($not_influence_to_score) ? "checked" : ""; ?> />
                        </div>
                        <div style="font-style:italic;padding-top:5px;color:#555;">
                            <p style="margin:0;"><?php echo __( "If you enable this option, this question will not be counted in the final score.", $this->plugin_name ); ?></p>
                            <p style="margin:0;"><?php echo __( "So this question will be just a Survey question.", $this->plugin_name ); ?></p>
                            <p style="margin:0;"><?php echo __( "There will not be correct/incorrect answers.", $this->plugin_name ); ?></p>
                            <p style="margin:0;"><?php echo __( "This is for just collecting data from users.", $this->plugin_name ); ?></p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="right_answer_text"><?php echo __('Question hint',$this->plugin_name)?></label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $content = wpautop(stripslashes((isset($question['question_hint']))?$question['question_hint']:''));
                        $editor_id = 'ays_question_hint';
                        $settings = array('editor_height' => '4', 'textarea_name' => 'ays_question_hint', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="wrong_answer_text">
                            <?php echo __('Question explanation',$this->plugin_name)?>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $content = wpautop(stripslashes((isset($question['explanation']))?$question['explanation']:''));
                        $editor_id = 'explanation';
                        $settings = array('editor_height' => '4', 'textarea_name' => 'explanation', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="wrong_answer_text"><?php echo __('Text In case of wrong answer',$this->plugin_name)?> <sup>(<?php echo __('only for radio type',$this->plugin_name)?>)</sup></label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $content = wpautop(stripslashes((isset($question['wrong_answer_text']))?$question['wrong_answer_text']:''));
                        $editor_id = 'wrong_answer_text';
                        $settings = array('editor_height' => '4', 'textarea_name' => 'wrong_answer_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="right_answer_text"><?php echo __('Text In case of right answer',$this->plugin_name)?> <sup>(<?php echo __('only for radio type',$this->plugin_name)?>)</sup></label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $content = wpautop(stripslashes((isset($question['right_answer_text']))?$question['right_answer_text']:''));
                        $editor_id = 'right_answer_text';
                        $settings = array('editor_height' => '4', 'textarea_name' => 'right_answer_text', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr>
                <?php
                wp_nonce_field('question_action', 'question_action');
                $other_attributes = array('id' => 'ays-button');
                submit_button(__('Save Question', $this->plugin_name), 'primary', 'ays_submit', true, $other_attributes);
                submit_button(__('Apply Question', $this->plugin_name), '', 'ays_apply', true, $other_attributes);

                ?>
            </div>
        </form>
    </div>
</div>
