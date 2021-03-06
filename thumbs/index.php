<?php
/**
 * Create a thumbnail
 *
 * @author Brett @ Mr PHP
 */
 
// define allowed image sizes
$sizes = array(
	'25x25',
	'50x50',
	'100x100',
	'300x300',
	'600x600',
);

// ensure there was a thumb in the URL
if (!$_GET['thumb']) {
	error('no thumb');
}

// get the thumbnail from the URL
$thumb = strip_tags(htmlspecialchars($_GET['thumb']));

// get the image and size
$thumb_array = explode('/',$thumb);

$size = array_shift($thumb_array);

$image = '../assets/'.implode('/',$thumb_array);
list($width,$height) = explode('x',$size);

// ensure the size is valid
if (!in_array($size,$sizes)) {
	error('invalid size');
}

/*
	echo "<pre>";
	echo $image;
	echo "</pre>";
*/

// ensure the image file exists
if (!file_exists($image)) {
	error('no source image');
}

// generate the thumbnail
require('../phpThumb/phpthumb.class.php');
$phpThumb = new phpThumb();
$phpThumb->setSourceFilename($image);
$phpThumb->setParameter('w',$width);
$phpThumb->setParameter('h',$height);
$phpThumb->setParameter('f',substr($thumb,-3,3)); // set the output format
//$phpThumb->setParameter('far','C'); // scale outside
//$phpThumb->setParameter('bg','FFFFFF'); // scale outside
if (!$phpThumb->GenerateThumbnail()) {
	error('cannot generate thumbnail');
}

// make the directory to put the image
if (!mkpath(dirname($thumb),true)) {
        error('cannot create directory');
}
/*
echo "<pre>";
print_r($phpThumb);
echo "</pre>";
*/


// write the file
if (!$phpThumb->RenderToFile($thumb)) {
	error('cannot save thumbnail');
}




// redirect to the thumb
// note: you need the '?new' or IE wont do a redirect
header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/'.$thumb.'?new');

// basic error handling
function error($error) {
	header("HTTP/1.0 404 Not Found");
	echo '<h1>Not Found</h1>';
	echo '<p>The image you requested could not be found.</p>';
	echo "<p>An error was triggered: <b>$error</b></p>";
	exit();
}
//recursive dir function
function mkpath($path, $mode){
    is_dir(dirname($path)) || mkpath(dirname($path), $mode);
    return is_dir($path) || @mkdir($path,0777,$mode);
}
?>