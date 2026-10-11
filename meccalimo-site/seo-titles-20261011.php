$r=[];$f=WP_CONTENT_DIR.'/plugins/mecca-home/seo.php';$c=file_get_contents($f);
$old='Charleston Limo Service: Weddings & CHS Airport Transfers';$new='Charleston Limo & Car Service: Weddings & CHS Airport';
$n1=substr_count($c,$old);$n2=substr_count($c,'Charleston Limo Service: Weddings &amp; CHS Airport Transfers');
if($n1||$n2){copy($f,WP_CONTENT_DIR.'/uploads/mecca-backups/seo-php-20261011.txt');$c=str_replace([$old,'Charleston Limo Service: Weddings &amp; CHS Airport Transfers'],[$new,'Charleston Limo &amp; Car Service: Weddings &amp; CHS Airport'],$c);file_put_contents($f,$c);if(function_exists('opcache_invalidate'))opcache_invalidate($f,true);}
$r['home_in_seo_php']=$n1+$n2;
$rm=get_post_meta(get_option('page_on_front'),'rank_math_title',true);$r['home_rm']=$rm;
foreach([52=>'Charleston Airport Transportation & Car Service (CHS) 24/7 | Mecca Limo',111=>'Wedding Transportation & Limo Rental in Charleston, SC | Mecca Limo'] as $id=>$t){$r[$id]=['before'=>get_post_meta($id,'rank_math_title',true)];update_post_meta($id,'rank_math_title',$t);}
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();return $r;
$set=['executive-limousine-service'=>'Executive Black Car Service in Charleston, SC | Mecca Limo','chauffeur-service-charleston-sc'=>'Chauffeur Service in Charleston, SC: Private Drivers 24/7 | Mecca Limo','charleston-airport-to-downtown-transportation'=>'Charleston Airport to Downtown: Private Car Service | Mecca Limo','charleston-sc-airport-shuttle-guide'=>'Charleston SC Airport Shuttle Guide (CHS) | Mecca Limo'];
$r=[];foreach($set as $s=>$t){$p=get_page_by_path($s,OBJECT,['post','page']);if(!$p){$r[$s]='missing';continue;}$r[$s]=get_post_meta($p->ID,'rank_math_title',true);update_post_meta($p->ID,'rank_math_title',$t);}
@wp_cache_clear_cache();return $r;
