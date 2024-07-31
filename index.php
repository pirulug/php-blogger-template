<?php
set_time_limit(0);
header('Content-Type: text/plain');

$target_file = 'theme.xml';
$template = file_get_contents('index.xml');
$comments_pattern = "/<!--\s*#file=([a-zA-Z0-9_]+\.xml)\s*-->/";

function replace_comments_callback($matches){
	global $comments_pattern;
	$file = $matches[1];
	echo "Tratando de incluir $file...\n";
	if( file_exists($file) ) {
		$contents = file_get_contents($file);
		$contents = preg_replace_callback($comments_pattern, 'replace_comments_callback', $contents);
		echo "$file incluído\n";
		return $contents;
	}
	return '';
}

echo "Empezando a compilar la plantilla\n";
$template = preg_replace_callback($comments_pattern, 'replace_comments_callback', $template);

if( $target_file ) {
	file_put_contents($target_file, $template);
}

echo "Compilado! Revisa $target_file";