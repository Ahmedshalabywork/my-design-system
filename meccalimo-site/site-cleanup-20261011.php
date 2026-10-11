$r=[];
// 1. swap bot-blocked link
$c=get_post(63)->post_content;$c2=str_replace('https://www.charlestoncvb.com/beaches/sullivans-island/','https://en.wikipedia.org/wiki/Sullivan%27s_Island,_South_Carolina',$c,$n);$c2=str_replace("Visit Sullivan's Island beach guide","Visit Sullivan's Island",$c2);if($n){wp_update_post(['ID'=>63,'post_content'=>$c2]);}$r['link_swapped']=$n;
// where is /policy/ linked in footer?
global $wpdb;$r['policy_link_in']=$wpdb->get_results("SELECT ID,post_type,post_title FROM $wpdb->posts WHERE post_content LIKE '%meccalimo.com/policy/%' AND post_status='publish' AND post_type NOT IN ('revision','post','page') LIMIT 10");
$f=get_post(18)->post_content;$i=strpos($f,'policy');$r['footer_snip']=$i!==false?substr($f,max(0,$i-300),400):null;
return $r;
$r=[];
// privacy policy page
$html=<<<'H'
<p><em>Last updated: October 11, 2026</em></p>
<p>Mecca Limo ("we", "us") provides chauffeured transportation in Charleston, SC. This policy explains what information we collect through meccalimo.com and our booking process, how we use it, and the choices you have. Questions: <a href="mailto:info@meccalimo.com">info@meccalimo.com</a> or <a href="tel:+18438041188">(843) 804-1188</a>.</p>
<h2>Information we collect</h2>
<ul>
<li><strong>Quote requests:</strong> your name, phone, email, trip date and time, pickup and drop-off locations, passenger count, flight details and any notes you send us.</li>
<li><strong>Booking confirmation:</strong> when you confirm a booking we collect your typed signature, the date and IP address of the confirmation, and a photo of your driver's license or ID.</li>
<li><strong>Payment cards:</strong> card details are entered directly into a secure form provided by Square. Card numbers go to Square and never touch our servers; we only see the card brand, last four digits and expiration date.</li>
<li><strong>Website usage:</strong> like most websites, we use cookies and similar tools from Google (Google Analytics, Google Ads and Google Tag Manager) to understand how visitors use the site and to measure our advertising, including which ad led to a quote request.</li>
</ul>
<h2>How we use it</h2>
<ul>
<li>To prepare quotes, confirm and manage your reservation, and contact you about your trip.</li>
<li>To charge the card on file for the agreed fare, approved extra time, or fees under our <a href="/policy/">booking policy</a>.</li>
<li>To verify identity for the reservation (photo ID).</li>
<li>To improve our website and measure our advertising.</li>
</ul>
<p>We do not sell your personal information.</p>
<h2>Who we share it with</h2>
<p>Only service providers that help us run the business: Square (payments), our reservation and dispatch software, our email provider, Zapier (to move quote details into our reservation system), and Google (website analytics and advertising). They may use the information only to provide their services to us.</p>
<h2>How long we keep it</h2>
<p>Photo ID images are stored privately and deleted after your trip. Trip and payment records are kept as long as needed for the reservation, customer service and our legal and accounting obligations.</p>
<h2>Security</h2>
<p>The site uses encrypted (https) connections, ID photos are stored in a private location that cannot be accessed from the internet, and card data is handled by Square.</p>
<h2>Your choices</h2>
<ul>
<li>Ask us to access, correct or delete your information by emailing info@meccalimo.com.</li>
<li>Block or delete cookies in your browser settings, and adjust Google ad personalization at <a href="https://adssettings.google.com" rel="noopener">adssettings.google.com</a>.</li>
</ul>
<h2>Changes</h2>
<p>We may update this policy and will change the date above when we do.</p>
<p>Mecca Limo · 4380 Dorchester Rd, North Charleston, SC 29405 · (843) 804-1188</p>
H;
$p=get_page_by_path('privacy-policy');
$id=$p?wp_update_post(['ID'=>$p->ID,'post_content'=>$html,'post_status'=>'publish']):wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Privacy Policy','post_name'=>'privacy-policy','post_content'=>$html]);
update_option('wp_page_for_privacy_policy',$id);$r['privacy']=get_permalink($id);
// footer link
$f=WP_CONTENT_DIR.'/plugins/mecca-home/home-template.php';$c=file_get_contents($f);
if(strpos($c,"/privacy-policy/")===false){copy($f,WP_CONTENT_DIR.'/uploads/mecca-backups/home-template-20261011.php.txt');
$c=str_replace("home_url( '/policy/' ) ); ?>\">Booking policy</a>","home_url( '/policy/' ) ); ?>\">Booking policy</a>\n\t\t\t\t<a href=\"<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>\">Privacy policy</a>",$c,$n);if($n)file_put_contents($f,$c);$r['footer']=$n;}
if(function_exists('opcache_invalidate'))opcache_invalidate($f,true);
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();
return $r;
require_once ABSPATH.'wp-admin/includes/plugin.php';require_once ABSPATH.'wp-admin/includes/file.php';require_once ABSPATH.'wp-admin/includes/misc.php';require_once ABSPATH.'wp-admin/includes/class-wp-upgrader.php';
$r=[];$all=get_plugins();$find=function($n)use($all){foreach($all as $f=>$d)if($d['Name']===$n)return $f;};
$fm=$find('WP File Manager');if($fm&&is_plugin_active($fm)){deactivate_plugins($fm);$r['file_manager']='deactivated';}
$ed=$find('Exclusive Divi');if($ed&&!is_plugin_active($ed)){$r['exclusive_divi']=delete_plugins([$ed])===true?'deleted':'delete failed';}
$dh=$find('Divi Contact Form Helper');if($dh){wp_update_plugins();$sk=new WP_Ajax_Upgrader_Skin();$u=new Plugin_Upgrader($sk);$res=$u->upgrade($dh);$r['dcfh']=[$res,get_plugin_data(WP_PLUGIN_DIR.'/'.$dh)['Version'],is_plugin_active($dh),$sk->get_errors()->get_error_messages()];}
return $r;
