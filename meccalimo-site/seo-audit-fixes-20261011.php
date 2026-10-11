$names=['belmond.com'=>'Belmond Charleston Place','hotelbennett.com'=>'Hotel Bennett','thedewberrycharleston.com'=>'The Dewberry Charleston','charlestoncvb.com/beaches/sullivans-island'=>"Sullivan's Island beach guide",'wikipedia.org/wiki/Isle_of_Palms'=>'Isle of Palms','follybeach.com'=>'Folly Beach','oldslavemartmuseum.com'=>'Old Slave Mart Museum','nps.gov/fosu'=>'Fort Sumter National Monument','patriotspoint.org'=>"Patriots Point"];
$r=[];
foreach([93,63,56] as $id){$c=get_post($id)->post_content;$n=0;
$c=preg_replace_callback('/\[et_pb_button ([^\]]*)\]/',function($m)use($names,&$n,&$r,$id){$a=$m[1];if(!preg_match('/button_url="([^"]*)"/',$a,$u))return $m[0];foreach($names as $k=>$nm){if(stripos($u[1],$k)!==false){preg_match('/button_text="([^"]*)"/',$a,$t);$old=$t[1]??'';$new='Visit '.$nm;$a=isset($t[1])?str_replace('button_text="'.$old.'"','button_text="'.$new.'"',$a):$a.' button_text="'.$new.'"';$n++;$r[$id][]=$old.' -> '.$new;break;}}return '[et_pb_button '.$a.']';},$c);
if($n){update_post_meta($id,'_mecca_anchor_bak_20261011',get_post($id)->post_content);wp_update_post(['ID'=>$id,'post_content'=>$c]);}}
// guides hub
$posts=get_posts(['post_type'=>'post','post_status'=>'publish','numberposts'=>200,'orderby'=>'title','order'=>'ASC']);
$h="<p>Planning a trip around Charleston? These guides from the Mecca Limo team cover beaches, islands, golf, hotels, weddings and getting around the Lowcountry. When you are ready to ride, <a href=\"/get-a-quote/\">request a free quote</a> or call <a href=\"tel:+18438041188\">(843) 804-1188</a>.</p>\n<ul>\n";
foreach($posts as $p)$h.='<li><a href="'.esc_url(get_permalink($p)).'">'.esc_html($p->post_title)."</a></li>\n";$h.="</ul>";
$g=get_page_by_path('charleston-guides');
$gid=$g?wp_update_post(['ID'=>$g->ID,'post_content'=>$h]):wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Charleston Travel Guides','post_name'=>'charleston-guides','post_content'=>$h]);
// links into hub + pickup guide
foreach([52,111] as $id){$c=get_post($id)->post_content;$add='';
if(strpos($c,'/charleston-guides/')===false)$add.="\n<p>Exploring the area? Browse our <a href=\"/charleston-guides/\">Charleston travel guides</a>.</p>";
if($id==111&&strpos($c,'charleston-airport-pickup-guide')===false)$add.="\n<p>Guests flying in for the wedding? Share our <a href=\"/charleston-airport-pickup-guide/\">Charleston airport pickup guide</a> so they know exactly how CHS pickups work.</p>";
if($add)wp_update_post(['ID'=>$id,'post_content'=>$c.$add]);}
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();
return ['anchors'=>$r,'hub'=>get_permalink($gid),'hub_links'=>count($posts)];
