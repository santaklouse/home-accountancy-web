<div class="box">
    <div class="box-title">
        <span><?php echo __('Pages per visit');?></span>
        <div class="l"></div>
        <div class="r"></div>
    </div>
    <div class="box-content simple-track-widget" id="pagespervisit">
        <?php echo __('Loading widget data');
        
              $request = array(
                'method' => 'VisitorInterest.getNumberOfVisitsPerPage',
                'idSite' => $domain_id,
                'period' => $period,
                'date' => $date,
                'widget_id' => 'pagespervisit'
            );
        ?>
        <script>
            $(function() {
                 mytrack_widget_data_simple(<?php echo json_encode($request);?>);
            });
        </script>
    </div>
</div>