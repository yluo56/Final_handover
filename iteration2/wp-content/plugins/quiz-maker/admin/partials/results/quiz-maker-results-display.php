<?php
?>
<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php
        echo __(esc_html(get_admin_page_title()),$this->plugin_name);
        ?>
    </h1>
    <a href="javascript:void(0);">
        <button class="disabled-button" style="float: right; margin-right: 5px;" title="This property aviable only in pro version" ><?php echo __('Export',$this->plugin_name)?></button>
    </a>
    <div class="nav-tab-wrapper">
        <a href="#tab1" class="nav-tab nav-tab-active"><?php echo __('Results',$this->plugin_name)?></a>
        <a href="#tab2" class="nav-tab"><?php echo __('Statistics',$this->plugin_name)?></a>
    </div>
    <div id="tab1" class="ays-quiz-tab-content ays-quiz-tab-content-active">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <a class="ays-disable-setting" href="javascript:void(0);" title="<?php echo __('This property aviable only in pro version',$this->plugin_name)?>">
                            <div class="alignleft actions bulkactions">
                                <label for="bulk-action-selector-top" class="screen-reader-text"><?php echo __('Select Filter Type',$this->plugin_name)?></label>
                                <select name="orderby" id="bulk-action-selector-top">
                                    <option value="-1"><?php echo __('Result Filters',$this->plugin_name)?></option>
                                    <option value="quiz_complete" disabled><?php echo __('By Quiz Complete',$this->plugin_name)?></option>
                                </select>
                                <input type="button" disabled id="doaction" class="button action" value="<?php echo __('Filter',$this->plugin_name)?>" >
                            </div>
                        </a>
                        <form method="post">
                            <?php
                            $this->results_obj->prepare_items();
                            $this->results_obj->display();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>

    <div id="tab2" class="ays-quiz-tab-content">
        <br>
        <div class="row" style="margin:0;">
            <div class="col-sm-12">
                <div class="pro_features">
                    <div>
                        <p>
                            <?php echo __("This feature available only in ", $this->plugin_name); ?>
                            <a href="https://ays-pro.com/index.php/wordpress/quiz-maker/" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", $this->plugin_name); ?></a>
                        </p>
                    </div>
                </div>
                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/chart_screen.png'?>" alt="Statistics" style="width:100%;">
            </div>
        </div>
    </div>
    <div id="ays-results-modal" class="ays-modal modal">
        <div class="ays-modal-content">
            <div class="ays-modal-header">
                <span class="ays-close" id="ays-close-results">&times;</span>
                <h2><?php echo __("Results for", $this->plugin_name); ?></h2>
            </div>
            <div class="ays-modal-body" id="ays-results-body">
                <iframe style="width: 100%; height: 450px;" src="https://www.youtube.com/embed/2N6HQyd6xS8" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                <p class="ays-notification-for-pro">
                    <?php echo __('Liked what we do? Checkout the ',$this->plugin_name)?><a href="http://ays-pro.com/index.php/wordpress/quiz-maker" target="_blank"><?php echo __('PRO version',$this->plugin_name)?></a> <?php echo __('and track the results of your passers-by for each quiz.',$this->plugin_name)?>
                </p>
            </div>

        </div>
    </div>
</div>
