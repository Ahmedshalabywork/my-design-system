$U='https://www.meccalimo.com/';
$weak=['why-choose-isle-of-palms-car-service'=>'Isle of Palms car service','what-is-the-best-way-to-get-around-charleston'=>'The best way to get around Charleston','the-expert-limo-rental-charleston-sc'=>'Limo rental in Charleston, SC','shuttle-bus-rental-charleston-sc'=>'Sprinter and shuttle rental in Charleston','luxury-rides-in-charleston-best-limo-company'=>'Luxury rides in Charleston','discover-mecca-limo-charlestons-transportation'=>'About Mecca Limo transportation','charleston-bachelor-party'=>'Charleston bachelor party transportation'];
$qa=[
'luxury-rides-in-charleston-best-limo-company'=>'Mecca Limo is a Charleston, SC chauffeur company offering sedans, SUVs and Sprinter vans for weddings, CHS airport transfers, corporate travel and nights out. Every ride is private, booked in advance, and available 24/7 by calling (843) 804-1188.',
'luxury-car-service-vs-regular-taxi'=>'A luxury car service is pre-booked, private and driven by a professional chauffeur, so pickup times are guaranteed and the vehicle is clean and spacious. A taxi or rideshare is on-demand with no guarantee of vehicle or driver. For airport trips, weddings and business travel in Charleston, a car service is the more reliable choice.',
'kiawah-island-transportation-guide'=>'Kiawah Island is about 45 minutes from Charleston International Airport (CHS) and has no public transit, so most visitors use a pre-booked private car service. Mecca Limo provides sedans, SUVs and Sprinter vans between CHS, downtown Charleston and Kiawah Island 24/7.',
'discover-mecca-limo-charlestons-transportation'=>'Mecca Limo is a licensed, insured chauffeured transportation company based at 4380 Dorchester Rd, North Charleston, SC. It serves Charleston, Kiawah, Isle of Palms and Mount Pleasant with sedans, SUVs and Sprinter vans for airport, wedding, corporate and event travel.',
'charleston-executive-airport-shuttle-service'=>'For executive travel to or from Charleston International Airport (CHS), a private chauffeured sedan or SUV is the fastest, most reliable option. Mecca Limo tracks your flight, meets you on arrival and runs 24/7. Call (843) 804-1188 to book.',
'airport-shuttle-and-limo-service-charleston-sc'=>'Mecca Limo provides private airport transportation to and from Charleston International Airport (CHS) in sedans, SUVs and Sprinter vans for groups up to 14. Rides are pre-booked, flight-tracked and available 24/7.'];
$out=[];
$all=array_unique(array_merge(array_keys($weak),array_keys($qa),['getting-from-kiawah-island-to-charleston']));
$wk=array_keys($weak);$i=0;
foreach($all as $s){$p=get_page_by_path($s,OBJECT,'post');if(!$p){$out[$s]='missing';continue;}
 $c=$p->post_content; if(strpos($c,'mecca-related')!==false){$out[$s]='already';continue;}
 if(!get_post_meta($p->ID,'_mecca_content_bak_20261011',true)) update_post_meta($p->ID,'_mecca_content_bak_20261011',wp_slash($c));
 // empty anchor fix
 $c=str_replace('<a href="https://www.meccalimo.com/how-far-is-isle-of-palms-from-charleston/"></a>','<a href="https://www.meccalimo.com/how-far-is-isle-of-palms-from-charleston/">how far Isle of Palms is from Charleston</a>',$c);
 // quick answer
 if(isset($qa[$s])){$box='[et_pb_text _builder_version="4.22.2"]<div class="mecca-quick-answer" style="border-left:4px solid #c9a227;background:#faf7ef;padding:16px 20px;margin-bottom:20px"><h2 style="font-size:20px;margin:0 0 8px">Quick answer</h2><p>'.$qa[$s].'</p></div>[/et_pb_text]';
  $c=preg_replace('/(\[et_pb_column[^\]]*\])/',"$1".$box,$c,1);}
 // related links: 3 weak pages other than self
 $pick=[];for($k=0;count($pick)<3;$k++){$t=$wk[($i+$k)%count($wk)];if($t!==$s&&!in_array($t,$pick))$pick[]=$t;}$i+=2;
 $li='';foreach($pick as $t)$li.='<li><a href="'.$U.$t.'/">'.$weak[$t].'</a></li>';
 $li.='<li><a href="'.$U.'charleston-guides/">All Charleston travel guides</a></li>';
 $rel='[et_pb_text _builder_version="4.22.2"]<div class="mecca-related"><h2 style="font-size:20px">Related Charleston guides</h2><ul>'.$li.'</ul></div>[/et_pb_text]';
 $pos=strrpos($c,'[/et_pb_column]'); if($pos!==false)$c=substr_replace($c,$rel,$pos,0); else $c.=$rel;
 wp_update_post(['ID'=>$p->ID,'post_content'=>wp_slash($c)]);
 $out[$s]=['ok',$pick];}
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();
echo json_encode($out,JSON_PRETTY_PRINT);
