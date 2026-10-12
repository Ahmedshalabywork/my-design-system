$U='https://www.meccalimo.com/wp-content/uploads/2026/10/mecca-fleet-';
$map=['corporate'=>'navigator','daniel-island-car-service'=>'suburban','out-of-town-car-service'=>'navigator','night-out'=>'sprinter'];$out=[];
foreach($map as $slug=>$car){$p=get_page_by_path($slug,OBJECT,['page','post']);if(!$p){$out[$slug]='missing';continue;}$c=$p->post_content;
if(!get_post_meta($p->ID,'_mecca_hero_bak_20261012',true))update_post_meta($p->ID,'_mecca_hero_bak_20261012',wp_slash($c));
if(strpos($c,'[et_pb_')!==false){$c=preg_replace('/(et_pb_image[^\]]*src=")[^"]+/','${1}'.$U.$car.'.webp',$c,1,$n);}
else{$c=preg_replace('/(<img[^>]+src=")[^"]+/','${1}'.$U.$car.'.webp',$c,1,$n);}
wp_update_post(['ID'=>$p->ID,'post_content'=>wp_slash($c)]);$out[$slug]=$n;}
wp_cache_clear_cache();echo json_encode($out);
