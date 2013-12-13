<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
	echo <<< EOT
	To install Honey Bee, put the following line in LocalSettings.php:
	require_once( "\$IP/extensions/HoneyBee/HoneyBee.php" );
EOT;
	exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Honey Bee',
	'author' => '[http://thingelstad.com/ Jamie Thingelstad]',
	'url' => 'http://www.mediawiki.org/wiki/Extension:HoneyBee',
	'description' => 'Honey Bee connects your wiki with [http://wikiapiary.com/ WikiApiary].',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.1',
	);

$dir = dirname(__FILE__) . '/';

$wgAutoloadClasses['SpecialWikiApiary'] = $dir . 'WikiApiarySpecial.php';
$wgExtensionMessagesFiles['HoneyBee'] = $dir . 'HoneyBee.i18n.php';
$wgSpecialPages['WikiApiary'] = 'SpecialWikiApiary';