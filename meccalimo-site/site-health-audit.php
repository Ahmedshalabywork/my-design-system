<?php
// Weekly Mecca site health audit. Run via: novamira run novamira/execute-php (code = this file minus this header).
// Checks: duplicate URLs, draft slug clashes, duplicate focus keywords, missing descriptions, prices, forbidden text
// (980 number, home address, stretch limo), other 843 numbers, fleet capacity (SUV max 5), thin pages, orphan pages,
// redirect chains/redirects shadowing live pages, plugin updates, admins, last quote received.
global $wpdb;
$P=$wpdb->get_results("SELECT ID,post_type,post_status,post_name,post_title,post_content,post_parent FROM {$wpdb->posts} WHERE post_type IN ('post','page') AND post_status='publish'");
$out=[];
// 1 duplicate slugs / permalinks
$perm=[];foreach($P as $p){$perm[get_permalink($p->ID)][]=$p->ID;}
foreach($perm as $u=>$ids) if(count($ids)>1) $out['dup_url'][]="$u => ".implode(',',$ids);
// 2 duplicate titles (rank math or post)
$t=[];$k=[];$d=[];
foreach($P as $p){$rt=get_post_meta($p->ID,'rank_math_title',true)?:$p->post_title; $t[strtolower(trim($rt))][]=$p->ID;
 $fk=strtolower(trim(explode(',',get_post_meta($p->ID,'rank_math_focus_keyword',true))[0])); if($fk) $k[$fk][]=$p->ID;
 $ds=get_post_meta($p->ID,'rank_math_description',true); if(!$ds) $out['no_desc'][]=$p->ID; else $d[strtolower($ds)][]=$p->ID;
 if(strpos($rt,'%title%')!==false||strpos($rt,'%sep%')!==false||strpos($rt,'%page%')!==false) $out['template_title'][]=$p->ID.": $rt";
 $rob=get_post_meta($p->ID,'rank_math_robots',true); if(is_array($rob)&&in_array('noindex',$rob)) $out['noindex'][]=$p->ID.' '.get_permalink($p->ID);
 $txt=wp_strip_all_tags($p->post_content);
 if(preg_match('/\$\s?\d{2,5}/',$txt,$m)) $out['price'][]=$p->ID.': '.$m[0];
 foreach(['980','254-9944','Weeping Cypress','stretch limo','stretch limousine','cruise'] as $bad) if(stripos($p->post_content,$bad)!==false) $out['forbidden'][]=$p->ID." contains '$bad'";
 if(preg_match_all('/\(?843\)?[ .-]?\d{3}[ .-]?\d{4}/',$txt,$ph)) foreach($ph[0] as $n){ if(preg_replace('/\D/','',$n)!=='8438041188') $out['other_phone'][]=$p->ID.": $n"; }
 if(preg_match('/(SUV|Suburban)[^.]{0,40}\b(6|six|7|seven)\b(?! ?\/)/i',$txt,$fm)) $out['fleet_capacity'][]=$p->ID.': '.$fm[0];
 if(str_word_count($txt)<300) $out['thin'][]=$p->ID.' ('.str_word_count($txt).'w) '.get_permalink($p->ID);
}
foreach($t as $x=>$ids) if(count($ids)>1) $out['dup_title'][]="$x => ".implode(',',$ids);
foreach($k as $x=>$ids) if(count($ids)>1) $out['dup_kw'][]="$x => ".implode(',',$ids);
foreach($d as $x=>$ids) if(count($ids)>1) $out['dup_desc'][]=implode(',',$ids);
// 3 drafts/trash whose slug collides with published
$dr=$wpdb->get_results("SELECT ID,post_name,post_status FROM {$wpdb->posts} WHERE post_type IN ('post','page') AND post_status IN ('draft','private','pending')");
$pubslugs=array_column(array_map(fn($p)=>(array)$p,$P),'ID','post_name');
foreach($dr as $x) if(isset($pubslugs[$x->post_name])) $out['draft_slug_clash'][]="{$x->ID} ({$x->post_status}) {$x->post_name} vs {$pubslugs[$x->post_name]}";
// 4 redirects pointing to non-200 targets or chains
$R=$wpdb->get_results("SELECT id,sources,url_to,status FROM {$wpdb->prefix}rank_math_redirections WHERE status='active'");
$out['redirect_count']=count($R);
$srcs=[];foreach($R as $r){$s=maybe_unserialize($r->sources);foreach($s as $q)$srcs[trim($q['pattern'],'/')]=$r->url_to;}
foreach($srcs as $s=>$to){$slug=trim(parse_url($to,PHP_URL_PATH),'/'); if(isset($srcs[$slug])) $out['redirect_chain'][]="$s -> $slug -> {$srcs[$slug]}"; if(isset($pubslugs[$s])) $out['redirect_shadows_live_page'][]="$s (post {$pubslugs[$s]}) redirected to $to";}
// 5 plugins/updates/security basics
$up=get_site_transient('update_plugins'); $out['plugin_updates']=$up&&!empty($up->response)?array_keys($up->response):[];
$out['wp_version']=get_bloginfo('version'); $out['php']=PHP_VERSION;
$out['blog_public']=get_option('blog_public');
$out['admins']=array_map(fn($u)=>$u->user_login,get_users(['role'=>'administrator']));
// 6 quote form health: last quote + last brevo
$q=get_posts(['post_type'=>'mecca_quote','numberposts'=>1,'post_status'=>'any']); $out['last_quote']=$q?$q[0]->post_date.' '.$q[0]->post_title:'none';
$out['quote_to']=get_option('mecca_qf_zap_to')?'set':'missing';
// 7 orphan pages (no internal links in)
$all=implode(' ',array_map(fn($p)=>$p->post_content,$P)).file_get_contents(WP_PLUGIN_DIR.'/mecca-home/home-template.php');
foreach($P as $p){ if($p->post_type!=='page') continue; $path=trim(parse_url(get_permalink($p->ID),PHP_URL_PATH),'/'); if($path && strpos($all,'/'.$path)===false) $out['orphan_page'][]=$p->ID." /$path"; }
echo json_encode($out,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
