**************************************************
	    ROI Member Loot v2.0
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



Change Log:
+++++++++++

v2.0
====
- Added real styling to the template file.
  ROIMemberLoot.css file is now included.
- Added JavaScript filtering abilities inside the
  template file. You can now filter on the tier,
  Rots, or Alt loots.
  ROIMemberLoot.js is now included.
- Added a Global Viewer status to the roster. When
  A user has this privledge, they will be able to
  select any active member from the roster to see
  their loot.


v1.1
====
- Added a default sort on loot date descending
