<div class="box" style="width:400px;">
    <div class="box-title">
        <span><?php echo __('Visitors summary');?></span>
        <div class="l"></div>
        <div class="r"></div>
    </div>
    <div class="box-content simple-track-widget" id="visitorssummary">
        <?php echo __('Loading widget data');
             $request = array(
                'method' => 'VisitsSummary.get',
                'idSite' => $domain_id,
                'period' => $period,
                'date' => $date,
                'widget_id' => 'visitorssummary'
            );
        ?>
        <script>
            $(function() {
                 mytrack_widget_data_simple(<?php echo json_encode($request);?>);
            });
        </script>
    </div>
</div>