$f=WP_CONTENT_DIR.'/plugins/mecca-home/mecca-home.php';$c=file_get_contents($f);
if(strpos($c,"case 'et_pb_code':")===false){copy($f,$f.'.bak-code-20261012');
$c=str_replace("\t\t\tcase 'et_pb_contact_form':","\t\t\tcase 'et_pb_code':\n\t\t\t\t\$flush();\n\t\t\t\tif ( false !== strpos( \$in, 'mecca-fleet-row' ) ) {\n\t\t\t\t\t\$html .= \$in;\n\t\t\t\t}\n\t\t\t\tbreak;\n\t\t\tcase 'et_pb_contact_form':",$c);
file_put_contents($f,$c);}
if(function_exists('opcache_invalidate'))opcache_invalidate($f,true);
if(function_exists('wp_cache_clear_cache'))wp_cache_clear_cache();echo php_sapi_name()?'ok':'';
