<?php
/**
 *
 * Extended Online Status. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2023, Anix, https://phpbbhacks.ro
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [
	'EOS_YEAR'		=> array(
		1	=> '%d Year',
		2	=> '%d Years',
	),
	'EOS_MONTH'		=> array(
		1	=> '%d Month',
		2	=> '%d Months',
	),
	'EOS_WEEK'		=> array(
		1	=> '%d Week',
		2	=> '%d Weeks',
	),
	'EOS_DAY'		=> array(
		1	=> '%d Day',
		2	=> '%d Days',
	),
	'EOS_HOUR'		=> array(
		1	=> '%d Hour',
		2	=> '%d Hours',
	),
	'EOS_MINUTE'	=> array(
		1	=> '%d Minute',
		2	=> '%d Minutes',
	),
	'EOS_SPACE'		=> ' ',
	'EOS_ACTIVE'	=> '(Active %s ago)',
	'EOS_ACTIVE_NOW'	=> '(Active now)',
	'EOS_STATUS'	=> 'Status',
	'EOS_ONLINE'	=> 'Online',
	'EOS_OFFLINE'	=> 'Offline',
]);
