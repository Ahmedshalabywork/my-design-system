$f=WP_CONTENT_DIR.'/plugins/mecca-home/mecca-home.php';$c=file_get_contents($f);
if(strpos($c,'mecca_home_bust')===false){copy($f,$f.'.bak-bust-20261012');
$add="\t// Add a version to image URLs so replaced photos show right away despite long browser caching.\n\t\$html = preg_replace_callback( '#https://www\\.meccalimo\\.com/wp-content/(uploads/mecca-webp|plugins/mecca-home/assets)/([A-Za-z0-9._-]+\\.webp)(?![?\\w])#', 'mecca_home_bust', \$html );\n";
$c=str_replace("function mecca_home_strip( \$html ) {\n","function mecca_home_strip( \$html ) {\n".$add,$c);
$c.="\nfunction mecca_home_bust( \$m ) {\n\t\$p = WP_CONTENT_DIR . '/' . \$m[1] . '/' . \$m[2];\n\treturn \$m[0] . ( file_exists( \$p ) ? '?v=' . filemtime( \$p ) : '' );\n}\n";
file_put_contents($f,$c);}
opcache_invalidate($f,true);wp_cache_clear_cache();echo "ok";
