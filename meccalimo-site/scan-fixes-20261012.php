global $wpdb;$out=[];
$rep=['/\bstretch limousines\b/i'=>'Sprinter vans','/\bstretch limousine\b/i'=>'Sprinter van','/\bstretch limos\b/i'=>'Sprinter vans','/\bstretch limo\b/i'=>'Sprinter van',
'/\bplus \$15\.00 plus the waiting time\b/'=>'plus a stop fee and the waiting time','/an additional fee of \$20\.00 per seat provided/'=>'an additional fee per seat provided'];
foreach($wpdb->get_results("SELECT ID,post_name FROM $wpdb->posts WHERE post_status='publish' AND post_type IN ('post','page') AND (post_content LIKE '%stretch limo%' OR post_content LIKE '%$15.00%' OR post_content LIKE '%$20.00%')") as $r){
$p=get_post($r->ID);$c=$p->post_content;$n=0;foreach($rep as $a=>$b){$c=preg_replace_callback($a,function($m)use($b){return ctype_upper($m[0][0])?ucfirst($b):$b;},$c,-1,$k);$n+=$k;}
if($n){update_post_meta($p->ID,'_mecca_scan_bak_20261012',wp_slash($p->post_content));wp_update_post(['ID'=>$p->ID,'post_content'=>wp_slash($c)]);$out[]=$r->post_name.":$n";}}
$left=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status='publish' AND post_type IN ('post','page') AND post_content LIKE '%stretch limo%'");
wp_cache_clear_cache();echo json_encode($out)," left:$left";
