<div class="box" >
    <div class="box-title">
        <span><?php echo __('Length of visits');?></span>
        <div class="l"></div>
        <div class="r"></div>
    </div>
    <div class="box-content simple-track-widget" id="visitsperpage">
        <?php echo __('Loading widget data');
             $request = array(
                'method' => 'VisitorInterest.getNumberOfVisitsPerVisitDuration',
                'idSite' => $domain_id,
                'period' => $period,
                'date' => $date,
                'widget_id' => 'visitsperpage'
            );
        ?>
        <script>
            $(function() {
                 mytrack_widget_data_simple(<?php echo json_encode($request);?>);
            });
        </script>
    </div>
</div>