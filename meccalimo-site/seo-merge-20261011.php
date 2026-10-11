$map=[
'/airport/'=>[2091,168,562,412,294,469,527],
'/wedding/'=>[917,2762,160,212],
'/'=>[1526,441,2352,139,1580],
];
$bak=[];foreach($map as $to=>$ids){foreach($ids as $id){$p=get_post($id);$bak[$id]=['slug'=>$p->post_name,'title'=>$p->post_title,'status'=>$p->post_status,'content'=>$p->post_content,'to'=>$to];}}
$dir=WP_CONTENT_DIR.'/uploads/mecca-backups';wp_mkdir_p($dir);@file_put_contents($dir.'/.htaccess',"Require all denied\nDeny from all\n");
file_put_contents($dir.'/merged-posts-20261011.json',wp_json_encode($bak));
$faq=[
52=>'<h2>Airport transfer FAQ</h2>
<h3>Is this a shared airport shuttle?</h3><p>No. Every Mecca Limo airport ride is private: your own chauffeur and vehicle, no other passengers and no extra stops.</p>
<h3>Which Charleston airports do you serve?</h3><p>Charleston International Airport (CHS), Charleston Executive Airport (JZI) and Mount Pleasant Regional Airport (LRO), plus private jet and FBO pickups.</p>
<h3>Can you take us from the airport to downtown Charleston, Kiawah, Folly Beach or Isle of Palms?</h3><p>Yes. We drive between the airport and downtown hotels, the beaches, the islands and every surrounding town, 24/7.</p>
<h3>Which vehicle fits our group and luggage?</h3><p>Executive sedan for up to 2 passengers, luxury SUV for up to 5 and the Mercedes-Benz Sprinter for up to 14, each with room for luggage.</p>
<h3>How is airport transportation priced?</h3><p>Every quote is based on your route, vehicle and time. Request a free quote online or call (843) 804-1188 and we will confirm the total before you book.</p>
',
111=>'<h2>Wedding transportation FAQ</h2>
<h3>How early should we book our wedding transportation?</h3><p>As soon as your date and venues are set. Spring and fall Saturdays in Charleston book up first.</p>
<h3>Can you move both the wedding party and guests?</h3><p>Yes. Many couples book an executive sedan or SUV for the couple and a Mercedes-Benz Sprinter (up to 14 passengers) for the bridal party or guest shuttles between hotel, ceremony and reception.</p>
<h3>Do you drive to venues outside downtown?</h3><p>Yes, including Kiawah, Wadmalaw, Johns Island, Mount Pleasant, Summerville and plantation venues across the Lowcountry.</p>
<h3>How is wedding transportation priced?</h3><p>Your quote depends on the vehicles, hours and stops in your timeline. Send us your plan and we will build a free, clear quote.</p>
',
];
$done=[];
foreach($faq as $id=>$html){$c=get_post($id)->post_content;if(strpos($c,'FAQ</h2>')!==false){$done[$id]='faq exists';continue;}
$needle=$id==52?'<h2>Book your Charleston airport ride':'<h2>Reserve your wedding transportation';$pos=strpos($c,$needle);
$c=$pos===false?$c."\n".$html:substr($c,0,$pos).$html."\n".substr($c,$pos);
wp_update_post(['ID'=>$id,'post_content'=>$c]);$done[$id]='faq added';}
foreach($map as $to=>$ids){foreach($ids as $id){$slug=get_post($id)->post_name;
\RankMath\Redirections\Redirection::from(['sources'=>[['pattern'=>$slug,'comparison'=>'exact']],'url_to'=>home_url($to),'header_code'=>301])->save();
wp_update_post(['ID'=>$id,'post_status'=>'draft']);$done[$slug]='->'.$to;}}
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();
return $done;
