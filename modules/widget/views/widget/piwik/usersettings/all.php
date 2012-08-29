<div class="box">
    <div class="box-title">
        <span><?php echo __('Browser and computer configuration');?></span>
        <div class="l"></div>
        <div class="r"></div>
    </div>
    <div class="box-content simple-track-widget" id="usersettingall">
        <?php echo __('Loading widget data');
        
              $request = array(
                'method' => '',
                'idSite' => $domain_id,
                'period' => $period,
                'date' => $date,
                'widget_id' => 'usersettingall'
            );
        ?>
        <script>
            $(function() {
                 mytrack_widget_data_simple(<?php echo json_encode($request);?>);
            });
        </script>
    </div>
</div>