<?php
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

global $smcFunc;

add_integration_function('integrate_pre_include', '$sourcedir/Subs-ROIMemberLoot.php', TRUE);
add_integration_function('integrate_menu_buttons','ROIMemberLoot_set_menu', TRUE);
add_integration_function('integrate_actions', 'ROIMemberLoot_view_loot_action', TRUE);


?>