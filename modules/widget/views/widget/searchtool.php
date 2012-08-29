<?php
if (!empty($tabs))
{
    if (!isset($role))
    {
        $role = 'guest';
    }
    if (!isset($search_str))
    {
        $search_str = '';
    }
    if (!isset($search_keyword))
    {
        $search_keyword = '';
    }
    if (!isset($tab))
    {
        $tab = 'domain';
    }
?>
<div class="box">
    <?php
         echo Helper_Box::header_html('widget_searchtool', array(
            'title_text' => __('QUICK OVERVIEW CHECK'),
            'title_image' => Tpl::image('img/icons/icon_overview.png', array(
                'width' => 18,
                'height' => 18,
                'align' => 'absmiddle',
            )),
         ));
    ?>
    <div class="box-content quick-overview-bg">
        <?php
        if(!empty($latest_search_visible))
        {
        ?>
        <div class="lastsearches">
            <div class="lastsearches-title">
                <?php echo __('Last Searches');?>
            </div>
            <div class="lastsearches-text">
                <?php echo __('last 5 searches')?>
            </div>
            <ul>
            <?php
            if($tab == 'bulkdomain')
                echo '';
            else
            if($search_type == 0 )
            {
                echo '<li><b>' . __('Coming soon') . '</b></li>';
            }
            else if(count($latest_search) > 0 )
            {
                foreach($latest_search as $latest)
                {
                    $url = '';
                    switch($latest['search_type'])
                    {
                        case '1':
                            $url = Route::url('directory_site', array(
                                'arg1' => $latest['search_value'],
                            ), TRUE);
                            break;
                        case '2':
                            $url = Route::url('directory_keyword', array(
                                'arg1' => $latest['search_value'],
                            ), TRUE);
                            break;
                        default:
                            break;
                    }
                    $value = $latest['search_value'];
                    if(strlen($value) > 37)
                        $value =  substr($value,0,33)."...";
                        echo '<li><a href="'. $url . '" title="'.$latest['search_value'].'">'.
                                                    $value .'</a></li>';
                }
            }
            else
            {
                echo '<li><b>'.__('This list is empty at this moment.').'</b></li>';
            }
            ?>
            </ul>
            <div class="lastsearches-pager pagination-container" parent="<?php echo $search_type?>" id="latest_search" rel="directory">
                <?php echo $pagination;?>
            </div>
            <div class="tl"></div><div class="tr"></div><div class="bl"></div><div class="br"></div>
        </div>
        <?php
        }
        ?>
        <div class="newtabs">
            <div class="newtabs-nav">
                <?php
                if (isset($tabs['searchtool/domain']))
                {
                ?>
                    <a class="tab<?php if ($tab == 'domain') echo ' active'; ?>" id="domain"
                       href="<?php echo Route::url('directory',array('controller' => 'directory'));?>">
                    <span><?php echo $tab_items['searchtool/domain']['title']?><br />
                        <small>(<?php echo $tab_items['searchtool/domain']['description'];?>)</small><br />
                    </span>
                </a>
                <?php
                }
                if (isset($tabs['searchtool/backlinks']))
                {
                ?>
                <a class="tab<?php if ($tab == 'backlinks') echo ' active'; ?>" id="backlink"
                   href="<?php echo Route::url('directory',array('controller' => 'backlinks'));?>">
                    <span><?php echo $tab_items['searchtool/backlinks']['title'];?><br />
                        <small>(<?php echo $tab_items['searchtool/backlinks']['description'];?>)</small><br />
                    </span>
                </a>
                <?php
                }
                if (isset($tabs['searchtool/whois']))
                {
                ?>
                <a class="tab<?php if ($tab == 'whois') echo ' active'; ?>" id="whois"
                   href="<?php echo Route::url('directory',array('controller' => 'whois'));?>">
                    <span><?php echo $tab_items['searchtool/whois']['title'];?><br />
                        <small>(<?php echo $tab_items['searchtool/whois']['description'];?>)</small><br />
                    </span>
                </a>
                <?php
                }
                if (isset($tabs['searchtool/keyword']))
                {
                ?>
                <a class="tab<?php if ($tab == 'keyword') echo ' active'; ?>" id="keyword"
                   href="<?php echo Route::url('directory',array('controller' => 'keywords')); ?>">
                    <span><?php echo $tab_items['searchtool/keyword']['title']?><br />
                        <small>(<?php echo $tab_items['searchtool/keyword']['description'];?>)</small><br />
                    </span>
                </a>
                <?php
                }
                if (isset($tabs['searchtool/bulkdomain']))
                {
                ?>
                <a class="tab<?php if ($tab == 'bulkdomain') echo ' active'; ?>" id="bulkdomain"
                   href="<?php echo Route::url('bulk',array(),TRUE); ?>">
                    <span><?php echo $tab_items['searchtool/bulkdomain']['title']?><br />
                        <small>(<?php echo $tab_items['searchtool/bulkdomain']['description'];?>)</small><br />
                    </span>
                </a>
                <?php
                }
                ?>
            </div>
            <div class="newtabs-content">
                <div class="newtabs-content-inner">
                    <?php
                    if (isset($tabs['searchtool/domain']))
                    {
                    ?>
                    <div class="topsearch" id="domain"<?php if ($tab != 'domain') echo 'style="display:none;"'; ?>>
                        <div class="topsearch-domain">
                            <div class="title">
                                <?php echo __('Get detailed domain & site report whois, Keywordrankings, pagerank, competitors and a wide range of important SEO Values');?>
                            </div>
                            <form accept-charset="utf-8" id="domainsearch" method="get" action="<?php echo URL::get('directory', 'directory/search');?>">
                                <input type="text" id="inputString" name="domainurl" placeholder="<?php echo __('Enter domain or URL here');?>" value="<?php echo $search_str; ?>" class="inp" />
                                <input type="submit" id="overview-submit" value="<?php echo __('Search');?>" class="btn overview-submit" />
                            </form>
                            <span class="suggestion-loading">&nbsp;</span>
                        </div>
                        <div class="example">
                            <b><?php echo __('Example:')?></b> <?php echo __('www.mysite.com, mysite.com or http://www.mysite.com')?></b>
                        </div>
                    </div>
                    <?php
                    }
                    if (isset($tabs['searchtool/backlinks']))
                    {
                    ?>
                    <div class="topsearch" id="backlink"<?php if ($tab != 'backlinks') echo ' style="display:none;"'; ?>>
                        <div class="topsearch" id="domain">
                            <div class="topsearch-domain">
                                <div class="title">
                                    <?php echo __('Validate a URL  linking to you if backlink is valid, we do Cloacking/Iframe/nofollow and several other checks to verify a backlink');?>
                                </div>
                                <form accept-charset="utf-8" id="domainsearch" method="get" action="<?php echo Route::url('directory',array('controller' => 'backlinks','action' => 'search'));?>">
                                    <input type="text" id="inputString" name="domainurl" placeholder="<?php echo __('enter destination URL');?>" value="<?php echo $search_str; ?>" class="inp domainbacklink" />
                                    <input type="submit" id="overview-submit" value="<?php echo __('Search');?>" class="btn overview-submit" />
                                </form>
                                <span class="suggestion-loading">&nbsp;</span>
                            </div>
                            <div class="example">
                              <b><?php echo __('Example:')?></b> <?php echo __('Enter destination URL (where the backlinks link to) here example:  (www.mysite.com/index.html , www.mysite.com/page1.html)')?></b>
                            </div>
                            <div class="topsearch-backlinks">
                            <?php
                            if ($role == 'guest')
                            {
                            ?>
                                <div class="title">
                                    <?php echo __('Enter up to 4 URLs of pages linking to your above mentioned URL');?>
                                </div>
                                <form name="backlinks" id="backlinks-checker" method="post" action="<?php echo Route::url('directory',array('controller' => 'backlinks','action' => 'site'));?>">
                                <input name="backlink1" type="text" value="" placeholder="backlink 1" class="inp" />
                                <input name="backlink2" type="text" value="" placeholder="backlink 2" class="inp" />
                                <input name="backlink3" type="text" value="" placeholder="backlink 3" class="inp" />
                                <input name="backlink4" type="text" value="" placeholder="backlink 4" class="inp" />
                                <input name="check-backlinks" type="button" class="btn checkbacklinks" value="verify" />
                                </form>
                                <br/>
                            <?php
                            }
                            else
                            {
                            ?>
                                <form name="backlinks" id="backlinks-checker" method="post" action="<?php echo Route::url('directory',array('controller' => 'backlinks','action' => 'site'));?>">
                                <textarea name="backlinks" placeholder="backlinks" class="inp-text" COLS=40 ROWS=6></textarea>
                                <input name="check-backlinks" type="button" class="btn checkbacklinks" value="verify" />
                                </form>
                            <?php
                            }
                            ?>
                            </div>
                                <div class="example">
                                  <b><?php echo __('Example:')?></b> <?php echo __('Enter up to 4 source URL (the URLs of the sites link to your above set URL) here example:  (www.backlinksite.com/index.html , www.backlinksite.com/page1.html)')?></b>
                                </div>
                        </div>
                    </div>
                    <?php
                    }
                    if (isset($tabs['searchtool/whois']))
                    {
                    ?>
                    <!-- domain search whois-->
                    <div class="topsearch" id="whois"<?php if ($tab != 'whois') echo ' style="display:none;"'; ?>>
                        <div class="topsearch-domain">
                            <div class="title">
                                <?php echo __('enter Address, Name or Company name and we tell you all domains we know');?>
                            </div>
                            <form accept-charset="utf-8" id="domainsearch" method="get" action="<?php echo Route::url('directory',array('controller' => 'whois','action' => 'search'));?>">
                                <input type="text" id="inputString" name="domainurl" placeholder="<?php echo __('enter Company, Email address, name or physical address');?>" value="<?php echo $search_str; ?>" class="inp" />
                                <input type="submit" id="overview-submit" value="<?php echo __('Search');?>" class="btn overview-submit" />
                            </form>
                            <span class="suggestion-loading">&nbsp;</span>
                        </div>
                        <div class="example">
                            <b><?php echo __('Example:')?></b> <?php echo __('“marcus@domain.com” or “Internet services inc” or “9583 3th high street”. ')?></b>
                        </div>
                    </div>
                    <?php
                    }
                    if (isset($tabs['searchtool/keyword']))
                    {
                    ?>
                    <div class="topsearch" id="keyword"<?php if ($tab != 'keyword') echo ' style="display:none;"'; ?>>
                        <div class="topsearch-domain">
                            <div class="title">
                                <?php echo __('Research keywords, rankings and competitor keywords, find new keywords based on our unique site category keyword database in all languages and countries!');?>
                            </div>
                            <form accept-charset="utf-8" id="domainsearch" method="get" action="<?php echo  Route::url('directory',array('controller' => 'keywords','action'=> 'search'));?>">
                                <input type="text" id="inputString" name="keyword" placeholder="<?php echo __('enter keyword here');?>" value="<?php echo $search_keyword; ?>" class="inp" />
                                <input type="submit" id="overview-submit" value="<?php echo __('Search');?>" class="btn overview-submit" />
                            </form>
                            <span class="suggestion-loading">&nbsp;</span>
                        </div>
                        <div class="example">
                            <b><?php echo __('Example:')?></b> <?php echo __('web hosting, shoes, real estate, business, etc. ')?></b>
                        </div>
                    </div>
                    <?php
                    }
                    if (isset($tabs['searchtool/bulkdomain']))
                    {
                     $domains = Input::post('bulkdomains',FALSE);
                    ?>
                    <div class="topsearch" id="bulkdomain"<?php if ($tab != 'bulkdomain') echo ' style="display:none;"'; ?>>
                        <div class="topsearch-backlinks">
                            <div class="title">
                                <?php echo __('');?>
                            </div>
                                <div class="title">
                                    <?php echo __('Enter up to 4 URLs of pages linking to your above mentioned URL');?>
                                </div>
                                <form name="bulkdomain" id="bulk-domains" method="post" action="<?php echo Route::url('bulk',array(),TRUE);?>">
                                <textarea name="bulkdomains" placeholder="bulk domains" class="inp-text" COLS=40 ROWS=6><?php if($domains) echo $domains;?></textarea>
                                <input name="bulk-domains" type="submit" class="btn " value="Check" style="margin-top:80px;"/>
                                </form>
                                <br/>
                        </div>
                        <div class="example">
                            <b> </b>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
</div>
<?php
}
?>