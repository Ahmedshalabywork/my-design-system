$U='https://www.meccalimo.com/';$o=[];
// 1) top-10 post: send wedding + limo intent to the right pages
$p=get_page_by_path('top-10-charleston-transportation-services',OBJECT,'post');
if($p && strpos($p->post_content,'mecca-quick-answer')===false){$c=$p->post_content;
 if(!get_post_meta($p->ID,'_mecca_content_bak_20261011',true))update_post_meta($p->ID,'_mecca_content_bak_20261011',wp_slash($c));
 $box='[et_pb_text _builder_version="4.22.2"]<div class="mecca-quick-answer" style="border-left:4px solid #c9a227;background:#faf7ef;padding:16px 20px;margin-bottom:20px"><h2 style="font-size:20px;margin:0 0 8px">Quick answer</h2><p>For weddings, see our <a href="'.$U.'wedding/">wedding transportation in Charleston, SC</a> page. For flights, see <a href="'.$U.'airport/">Charleston airport transportation (CHS)</a>. For everything else, Mecca Limo offers <a href="'.$U.'">Charleston limo and car service</a> in sedans, SUVs and Sprinter vans, 24/7 at (843) 804-1188.</p></div>[/et_pb_text]';
 $c=preg_replace('/(\[et_pb_column[^\]]*\])/',"$1".$box,$c,1);wp_update_post(['ID'=>$p->ID,'post_content'=>wp_slash($c)]);$o[]='top10 ok';}
// 2) airport posts: link to /airport/ with keyword anchor inside their quick answer
foreach(['charleston-executive-airport-shuttle-service','airport-shuttle-and-limo-service-charleston-sc','kiawah-island-transportation-guide','luxury-car-service-vs-regular-taxi'] as $s){$p=get_page_by_path($s,OBJECT,'post');if(!$p)continue;$c=$p->post_content;
 if(strpos($c,'mecca-quick-answer')!==false && strpos($c,'"'.$U.'airport/">Charleston airport transportation')===false){
  $c=preg_replace('/(<div class="mecca-quick-answer".*?)(<\/p><\/div>)/s','$1 See <a href="'.$U.'airport/">Charleston airport transportation</a>.$2',$c,1);
  wp_update_post(['ID'=>$p->ID,'post_content'=>wp_slash($c)]);$o[]="$s ok";}}
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();echo implode("\n",$o);
