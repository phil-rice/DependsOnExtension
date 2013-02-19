<?php

global $wgDependsOnRootDirectory;

// Take credit for your work.
$wgExtensionCredits['parserhook'][] = array(
		'path' => __FILE__,
		// The name of the extension, which will appear on Special:Version.
		'name' => 'DependsOn ParserFunction',
		'description' => 'Shows the projects that this project depends on',
		'version' => 1,

		// Your name, which will appear on Special:Version.
		'author' => 'Phil Rice',

		// The URL to a wiki page/web page with information about the extension,
		// which will appear on Special:Version.
		'url' => 'https://www.softwarefm.com/Wiki/Manual:DependsOnExtension',
);

$dir = dirname( __FILE__ );

$wgHooks['ParserFirstCallInit'][] = 'DependsOnSetupParserFunction';
$wgExtensionMessagesFiles['DependsOn'] = $dir . '/DependsOn.i18n.php';

function DependsOnSetupParserFunction( &$parser ) {
	$parser->setFunctionHook( 'dependsOn', 'DependsOnExtensionRenderParserFunction' );
	return true;
}

function DependsOnExtensionRenderParserFunction( $parser, $groupId, $artifactId) {
	$dependsOn = DependsOnGetFile( $groupId, $artifactId,"dependsOn");
	$dependsOnThis = DependsOnGetFile($groupId, $artifactId,"dependsOnThis");
	if ($dependsOn != '' || $dependsOnThis != ''){
		$output=  "\n{|\n|" . $dependsOn ."\n|". $dependsOnThis ."\n|}\n";
		return array($output, 'noparse' => false);
	}
	return "";
}

function DependsOnGetFile($groupId, $artifactId, $extension){
	global $wgDependsOnRootDirectory;
	$fileRoot = $wgDependsOnRootDirectory .  str_replace(".", "/", $groupId) . "/" . $artifactId ;
	$fileName = $fileRoot . "/" . $extension;
	if (file_exists($fileName)){
		$dependsOnText = file_get_contents($fileName);
		#	$output = "id : [" . $groupId . ":" . $artifactId . "] root = [" . $wgDependsOnRootDirectory . " filename = " . $fileName ."<br />" . $dependsOnText;
		return $dependsOnText;
	}
	return "";

}