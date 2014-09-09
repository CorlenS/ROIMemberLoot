**************************************************
	    ROI Member Loot v3.0
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

v3.0
====
- Addition of Attendance on the page
- Username check will be case insensitive. This will make
  it less cumbersome to add new people
- A better error message will be displayed if you don't
  have access
- Changing the tier selection to multiselect so you can
  see loot across multiple tiers
- The loot summary section with the totals will display
  based on the tiers you have selected (That's how we
  view loot anyways not overall totals)
- Last loot date will function based on your tiers selected
- Alts/rot counts will function based on your tiers selected
- Will add in an Alt last loot date based on tiers selected
- Whatever visual improvements I see fit (I'm not that great
  with styling/UI though)
- May add in visible/non-visible/weapon totals based on tier selection

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
