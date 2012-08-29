<?php
$graph_id = 'getNumberOfDistincts'.rand(0,mktime());
?>
<div class="box">
    <div class="box-title">
        <span><?php echo $widget_title?></span>
        <div class="l"></div>
        <div class="r"></div>
        <div class="configure-tools">
            <a href="#" onclick="dialog_graph_conf(this,true);return false;" class="tools configure_btn"></a>
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
                'method' => $method,
                'idSite' => $domain_id,
                'period' => $period,
                'date' => $date,
                'segment' => $segment,
                'widget_id' => $graph_id,
                'type' => $chart_type,
                'title' => $chart_title,
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