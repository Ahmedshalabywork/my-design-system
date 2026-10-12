global $wpdb;$U='https://www.meccalimo.com/wp-content/uploads/';
$map=[$U.'2023/07/gallery_1.jpeg'=>$U.'2026/10/mecca-fleet-sprinter.webp',$U.'2023/07/MINIBUS-.jpg'=>$U.'2026/10/mecca-fleet-sprinter.webp',$U.'2023/07/gallery_7.jpeg'=>$U.'2026/10/mecca-fleet-navigator.webp',$U.'2023/07/chevy_suburban.jpg'=>$U.'2026/10/mecca-fleet-suburban.webp',$U.'2023/07/hero_2.jpeg'=>$U.'2026/10/mecca-fleet-suburban.webp'];
$out=[];
foreach($wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status='publish' AND post_type IN ('post','page','et_pb_layout')") as $r){$p=get_post($r->ID);$c=$p->post_content;$n=0;
foreach($map as $a=>$b){$c=str_replace($a,$b,$c,$k);$n+=$k;}
if($n){if(!get_post_meta($p->ID,'_mecca_img_bak_20261012',true))update_post_meta($p->ID,'_mecca_img_bak_20261012',wp_slash($p->post_content));wp_update_post(['ID'=>$p->ID,'post_content'=>wp_slash($c)]);$out[]=$p->post_name.":".$n;}}
// featured images using old files
foreach($map as $a=>$b){$o=attachment_url_to_postid($a);$nw=attachment_url_to_postid($b);if($o&&$nw){foreach($wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_thumbnail_id' AND meta_value=%d",$o)) as $pid){update_post_meta($pid,'_thumbnail_id',$nw);$out[]="thumb:$pid";}}}
// other old car files still referenced
$left=[];foreach(['danail2017','gallery_5','gallery_2','gallery_3','gallery_4','gallery_6','gallery_8'] as $f){$left[$f]=(int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status='publish' AND post_content LIKE '%".$f."%'");}
wp_cache_clear_cache();echo json_encode(['changed'=>$out,'still_old'=>$left]);
