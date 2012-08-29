<?php

$module_home = Route::url('keyword');
?>
<div id="switch-domains">
	<div id="switch-domains-btn">
		<div class="switch-btn-inner" style="background-position:0 0;" >
			<?php
			if (!empty($current_url)) {
				echo $current_url;
			} else {
				echo "Switch to...";
			}
			?>
		</div>
		<div id="switch-domains-popup">
			<div class="switch-popup-t">
				<b class="c"></b><b class="l"></b><b class="r"></b>
			</div>
			<div class="switch-popup-l">
				<div class="switch-popup-r">
					<ul class="switch-popup-content">
						<?php for($i = 0; $i < count($domains_list); $i++){
						?>
						<li>
							<a href="<?php echo $link[$i];?>"><?php echo Arr::path($domains_list,$i.'.url.url');?></a>
						</li>
						<?php }?>
					</ul>
				</div>
			</div>
			<div class="switch-popup-b">
				<b class="c"></b><b class="l"></b><b class="r"></b>
			</div>
		</div>
	</div>
</div>
<?php 	if(!empty($current_url)){
?>
<a class="link" href="<?php echo $module_home?>"> <span> Module home</span> </a>
<?php }
?>
