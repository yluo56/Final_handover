<?php    
    $action = ( isset($_GET['action']) ) ? $_GET['action'] : '';
    $id     = ( isset($_GET['quiz']) ) ? $_GET['quiz'] : null;

    if($action == 'duplicate'){
        $this->quizes_obj->duplicate_quizzes($id);
    }
    $max_id = $this->get_max_id('questions');
    
?>

<div class="wrap ays_quizzes_list_table">
    <h1 class="wp-heading-inline">
        <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action">' . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add');
        ?>
    </h1>
    <?php if($max_id <= 6): ?>
    <div class="notice notice-success is-dismissible">
        <p style="font-size:14px;">
            <strong>
                <?php echo __( "If you haven't created questions yet, you need to create the questions at first.", $this->plugin_name ); ?> 
            </strong>
            <br>
            <strong>
                <em>
                    <?php echo __( "For creating a question go", $this->plugin_name ); ?> 
                    <a href="<?php echo admin_url('admin.php') . "?page=".$this->plugin_name . "-questions"; ?>" target="_blank">
                        <?php echo __( "here", $this->plugin_name ); ?>.
                    </a>
                </em>
            </strong>
        </p>
    </div>
    <?php endif; ?>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->quizes_obj->views();
                    ?>
                    <form method="post">
                        <?php
                        $this->quizes_obj->prepare_items();
                        $this->quizes_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
