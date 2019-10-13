<?php
//    $actions = $this->settings_obj;

    if( isset( $_REQUEST['ays_submit'] ) ){
        $actions->store_data($_REQUEST);
    }
    if(isset($_GET['ays_quiz_tab'])){
        $ays_quiz_tab = $_GET['ays_quiz_tab'];
    }else{
        $ays_quiz_tab = 'tab1';
    }
//    $data = $actions->get_data();
//    $db_data = $actions->get_db_data();

//    $paypal_client_id = isset($data['paypal_client_id']) ? $data['paypal_client_id'] : '' ;
    global $wp_roles;
    $ays_users_roles = $wp_roles->role_names;
//    $user_roles = json_decode($actions->ays_get_setting('user_roles'), true);
//    $mailchimp_res = ($actions->ays_get_setting('mailchimp') === false) ? json_encode(array()) : $actions->ays_get_setting('mailchimp');
//    $mailchimp = json_decode($mailchimp_res, true);
//    $mailchimp_username = isset($mailchimp['username']) ? $mailchimp['username'] : '' ;
//    $mailchimp_api_key = isset($mailchimp['apiKey']) ? $mailchimp['apiKey'] : '' ;
?>
<div class="wrap" style="position:relative;">
    <div class="container-fluid">
        <form method="post">
            <input type="hidden" name="ays_quiz_tab" value="<?php echo $ays_quiz_tab; ?>">
            <h1 class="wp-heading-inline">
            <?php
                echo __('General Settings',$this->plugin_name);
            ?>
            </h1>
            <hr/>
            <div class="ays-settings-wrapper">
                <div>
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_quiz_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("General", $this->plugin_name);?>
                        </a>
                        <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_quiz_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Integrations", $this->plugin_name);?>
                        </a>
                        <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_quiz_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("User page shortcode", $this->plugin_name);?>
                        </a>
                    </div>
                </div>
                <div class="ays-quiz-tabs-wrapper">
                    <div id="tab1" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab1') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('General Settings',$this->plugin_name)?></p>
                        <hr/>
                        <div class="form-group row" style="padding:15px;">
                            <div class="col-sm-12" style="padding:20px;">
                                <div class="pro_features" style="">
                                    <div style="margin-right:20px;">
                                        <p style="font-size:20px;">
                                            <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                            <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_user_roles">
                                            <?php echo __( "Select user role", $this->plugin_name ); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select id="ays_user_roles" multiple>
                                            <?php
                                                foreach($ays_users_roles as $role => $role_name){
                                                    $selected = $role == 'administrator' || $role == 'author' ? 'selected' : '';
                                                    echo "<option ".$selected." value='".$role."'>".$role_name."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <blockquote>
                                    <?php echo __( "Ability to manage Quiz Maker plugin for selected user roles.", $this->plugin_name ); ?>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <div id="tab2" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab2') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Integrations',$this->plugin_name)?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/mailchimp_logo.png" alt="">
                                <h5><?php echo __('MailChimp',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        <div style="margin-right:20px;">
                                            <p style="font-size:20px;">
                                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_mailchimp_username">
                                                <?php echo __('MailChimp Username',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                class="ays-text-input" 
                                                id="ays_mailchimp_username" 
                                                name="ays_mailchimp_username"
                                            />
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_mailchimp_api_key">
                                                <?php echo __('MailChimp API Key',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                class="ays-text-input" 
                                                id="ays_mailchimp_api_key" 
                                                name="ays_mailchimp_api_key"
                                            />
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo sprintf( __( "You can get your API key from your ", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://us20.admin.mailchimp.com/account/api/", "Account Extras menu" ); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>                    
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/paypal_logo.png" alt="">
                                <h5><?php echo __('PayPal',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        <div style="margin-right:20px;">
                                            <p style="font-size:20px;">
                                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="DEVELOPER feature"><?php echo __("DEVELOPER package!!!", $this->plugin_name); ?></a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_paypal_client_id">
                                                <?php echo __('Paypal Client ID',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                class="ays-text-input" 
                                                id="ays_paypal_client_id" 
                                                name="ays_paypal_client_id"
                                            />
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo sprintf( __( "You can get your Client ID from", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://developer.paypal.com/developer/applications", "Developer Paypal" ); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/campaignmonitor_logo.png" alt="">
                                <h5><?php echo __('Campaign Monitor',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        <div style="margin-right:20px;">
                                            <p style="font-size:20px;">
                                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="DEVELOPER feature"><?php echo __("DEVELOPER packadge!!!", $this->plugin_name); ?></a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_monitor_client">
                                                Campaign Monitor <?= __('Client ID', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_monitor_client" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_monitor_api_key">
                                                Campaign Monitor <?= __('API Key', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_monitor_api_key">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?= __("You can get your API key and Client ID from your Account Settings page", $this->plugin_name); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/zapier_logo.png" alt="">
                                <h5><?php echo __('Zapier',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        <div style="margin-right:20px;">
                                            <p style="font-size:20px;">
                                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="DEVELOPER feature"><?php echo __("DEVELOPER packadge!!!", $this->plugin_name); ?></a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_zapier_hook">
                                                <?= __('Zapier Webhook URL', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_zapier_hook">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo sprintf(__("If you do not have any ZAP created, go " . "<a href='%s' target='_blank'>%s</a>" . ". Remember to choose Webhooks by Zapier as Trigger App.", $this->plugin_name), "https://zapier.com/app/editor/", "here"); ?>
                                    </blockquote>
                                    <blockquote>
                                        <?= __("We will send you all data from quiz information form with \"AysQuiz\" key by POST method", $this->plugin_name); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/activecampaign_logo.png" alt="">
                                <h5><?php echo __('ActiveCampaign',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        <div style="margin-right:20px;">
                                            <p style="font-size:20px;">
                                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="DEVELOPER feature"><?php echo __("DEVELOPER packadge!!!", $this->plugin_name); ?></a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_active_camp_url">
                                                <?= __('API Access URL', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_active_camp_url" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <label for="ays_active_camp_api_key">
                                                <?= __('API Access Key', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_active_camp_api_key">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?= __("Your API URL and Key can be found in your account on the My Settings page under the \"Developer\" tab", $this->plugin_name); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/slack_logo.png" alt="">
                                <h5><?php echo __('Slack',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">
                                        <div style="margin-right:20px;">
                                            <p style="font-size:20px;">
                                                <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                                <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="DEVELOPER feature"><?php echo __("DEVELOPER packadge!!!", $this->plugin_name); ?></a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_client">
                                                <?= __('App Client ID', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_slack_client">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_oauth">
                                                <?= __('Slack Authorization', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" id="slackOAuth2" class="btn btn-outline-secondary disabled">
                                                <?= __("Authorize", $this->plugin_name) ?>
                                            </button>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_secret">
                                                <?= __('App Client Secret', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_slack_secret" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_slack_oauth">
                                                <?= __('App Access Token', $this->plugin_name) ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <button type="button" class="btn btn-outline-secondary disabled">
                                                <?= __("Need Authorization", $this->plugin_name) ?>
                                            </button>
                                            <input type="hidden" id="ays_slack_token">
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?= __("You can get your App Client ID and Client Secret from your App's the Basic Information page", $this->plugin_name); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div id="tab3" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab3') ? 'ays-quiz-tab-content-active' : ''; ?>" style="position:relative;padding:15px;">
                        <div class="pro_features" style="">
                            <div style="margin-right:20px;">
                                <p style="font-size:20px;">
                                    <?php echo __("This feature available only in ", $this->plugin_name); ?>
                                    <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                                </p>
                            </div>
                        </div>
                        <p class="ays-subtitle"><?php echo __('Shortcode',$this->plugin_name)?></p>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <input type="text" onclick="this.setSelectionRange(0, this.value.length)" readonly value="[ays_user_page]">
                            </div>
                        </div>
                        <blockquote>
                            <?php echo __( "Ability to manage Quiz Maker plugin for selected user roles.", $this->plugin_name ); ?>
                        </blockquote>
                    </div>
                </div>
            </div>
            <hr/>
            <button type="button" class="button button-primary" disabled>Save changes</button>
        </form>
    </div>
</div>