<?php
$graph_id = 'getVisitInformationPerLocalTime'.rand(0,mktime());
?>
<div class="box">
    <div class="box-title">
        <span><?php echo __('Visits by Local time');?></span>
        <div class="l"></div>
        <div class="r"></div>
        <div class="configure-tools">
            <a href="#" onclick="dialog_graph_conf(this,true);return false;" class="tools configure_btn "></a>
       </div>
    </div>
    <div class="box-content box-content_style2">
           <div id="<?php echo $graph_id;?>" class="chart-container" >
                        <?php 
                         echo Tpl::image('img/directory/loading17.gif',array(
                                            'width'=> 66,
                                            'height' => 66));
                         echo '<br/>'.__('Loading Data');
                        ?>
           </div>
        <?php 
              $request = array(
                'method' => 'VisitTime.getVisitInformationPerLocalTime',
                'idSite' => $domain_id,
                'period' => $period,
                'date' => $date,
                'widget_id' => $graph_id,
                'type' => 'column',
                'title' => __('Visits  by Local  time'),
                'y_titles' => __('Visits')
            );
        ?>
        <script>
            $(function() {
                 mytrack_graph_widget_data(<?php echo json_encode($request);?>);
            });
        </script>
    </div>
</div>