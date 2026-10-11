global $wpdb;$r=[];
// 1. nav items pointing to unpublished pages/posts
$items=get_posts(['post_type'=>'nav_menu_item','numberposts'=>-1,'post_status'=>'any']);$rm=[];
foreach($items as $it){$oid=(int)get_post_meta($it->ID,'_menu_item_object_id',true);$t=get_post_meta($it->ID,'_menu_item_type',true);if($t==='post_type'&&$oid&&get_post_status($oid)!=='publish'){$rm[]=$it->post_title?:get_the_title($oid);wp_delete_post($it->ID,true);}}
$r['menu_removed']=$rm;
// 2. rewrite internal links in published content
$map=['/airport/'=>['airport-transportation-charleston-sc','charleston-airport-transportation','airport-shuttle-charleston-sc','charleston-airport-shuttle-service','charleston-airport-car-service-prices','charleston-airport-shuttle-prices','charleston-international-airport'],'/wedding/'=>['wedding-transportation-charleston-sc','wedding-transportation-charleston-sc-2025','wedding-car-service-in-charleston','day-memorable-wedding-limo-rental','best-wedding-car-service-in-charleston'],'/'=>['guide-to-limousine-service-charleston-sc','best-of-charleston-limo-services','top-7-charleston-car-services','best-car-service-charleston-sc','limo-service-charleston-sc-prices-2025','charleston-limo-service-rates','top-10-charleston-transportation-services','luxury-transportation-services-in-charleston-sc','affordable-limo-rental-charleston-for-event','best-car-service-charleston-sc-2025']];
// also pick up any existing Rank Math redirects (exact sources)
$ids=$wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_status='publish' AND post_type IN ('post','page','et_footer_layout','et_header_layout','et_body_layout') ");
$changed=0;$links=0;
foreach($ids as $id){$c=get_post_field('post_content',$id);$o=$c;
$c=preg_replace('#https?://meccalimo\.com#i','https://www.meccalimo.com',$c);
$c=preg_replace('#http://www\.meccalimo\.com#i','https://www.meccalimo.com',$c);
foreach($map as $to=>$slugs)foreach($slugs as $s)$c=preg_replace('#https://www\.meccalimo\.com/'.preg_quote($s,'#').'/?(?=["\'\s\]])#','https://www.meccalimo.com'.$to,$c);
$c=str_replace(['https://www.meccalimo.com/?p=160','https://www.meccalimo.com/?page_id=80'],['https://www.meccalimo.com/wedding/','https://www.meccalimo.com/'],$c);
$c=preg_replace('#href="wedding transportation services"#i','href="https://www.meccalimo.com/wedding/"',$c);
if($c!==$o){$links+=similar_text('','');update_post_meta($id,'_mecca_link_bak_20261011',$o);$wpdb->update($wpdb->posts,['post_content'=>$c],['ID'=>$id]);clean_post_cache($id);$changed++;}}
$r['posts_updated']=$changed;
// remaining malformed relative link check
$r['malformed_left']=$wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_status='publish' AND post_content LIKE '%wedding transportation services\"%'");
// 3. Divi minify + static css
$o=get_option('et_divi');$o['divi_minify_combine_scripts']='on';$o['divi_minify_combine_styles']='on';$o['et_pb_static_css_file']='on';update_option('et_divi',$o);
if(function_exists('et_core_page_resource_auto_clear'))et_core_page_resource_auto_clear();
if(class_exists('ET_Core_PageResource'))ET_Core_PageResource::remove_static_resources('all','all');
if(class_exists('\\RankMath\\Sitemap\\Cache'))\RankMath\Sitemap\Cache::invalidate_storage();
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();
return $r;
$c=get_post_field('post_content',22);$b='@ET-DC@'.base64_encode('{"dynamic":true,"content":"post_link_url_page","settings":{"post_id":"80"}}').'@';
$i=strpos($c,$b);if($i===false){preg_match('/@ET-DC@[A-Za-z0-9+\/=]+@/',$c,$mm);}
$s=strrpos(substr($c,0,$i),'[et_pb_blurb ');$e=strpos($c,'[/et_pb_blurb]',$i)+strlen('[/et_pb_blurb]');
$blk=substr($c,$s,$e-$s);preg_match('/title="([^"]*)"/',$blk,$t);
update_post_meta(22,'_mecca_footer_bak_20261011',$c);
$c2=substr($c,0,$s).substr($c,$e);
global $wpdb;$wpdb->update($wpdb->posts,['post_content'=>$c2],['ID'=>22]);clean_post_cache(22);
if(class_exists('ET_Core_PageResource'))ET_Core_PageResource::remove_static_resources('all','all');
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();
return ['removed_title'=>$t[1]??'?','len'=>strlen($blk)];
