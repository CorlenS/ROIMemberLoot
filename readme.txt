**************************************************
	    ROI Member Loot v1.0
**************************************************
Installation Notes:
+++++++++++++++++++
A seperate .p12 private certificate should be provided
along with this plugin package. It needs to be installed
on the host server idealy located outside of the public
accessable website. Then inside $sourcedir\Subs-ROIMemberLoot.php
change the cons variable KEY_FILE to the full path to
this file.



This plugin will add a menu button to which takes
the user to a page at index.php?action=view_loot.

If the logged in user has their user name mapped
to the ROI Roster google doc, then they will be
to see their loot.

Tested on Version 2.X
