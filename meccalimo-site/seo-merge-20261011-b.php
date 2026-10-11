$slugs=['charleston-limo-service-rates','top-10-charleston-transportation-services','luxury-transportation-services-in-charleston-sc','affordable-limo-rental-charleston-for-event','best-car-service-charleston-sc-2025'];
$dir=WP_CONTENT_DIR.'/uploads/mecca-backups';$bak=[];$done=[];
foreach($slugs as $s){$p=get_page_by_path($s,OBJECT,['post','page']);if(!$p||$p->post_status!=='publish'){$done[$s]='not found/unpublished';continue;}
$bak[$p->ID]=['slug'=>$s,'title'=>$p->post_title,'status'=>$p->post_status,'content'=>$p->post_content,'to'=>'/'];
\RankMath\Redirections\Redirection::from(['sources'=>[['pattern'=>$s,'comparison'=>'exact']],'url_to'=>home_url('/'),'header_code'=>301])->save();
wp_update_post(['ID'=>$p->ID,'post_status'=>'draft']);$done[$s]='-> / (ID '.$p->ID.')';}
file_put_contents($dir.'/merged-posts-20261011-b.json',wp_json_encode($bak));
if(class_exists('\\RankMath\\Sitemap\\Cache'))\RankMath\Sitemap\Cache::invalidate_storage();
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();
return $done;
