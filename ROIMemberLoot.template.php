<?php
const COL_LOOT_DATE = 0;
const COL_NAME = 1;
const COL_EVENT = 2;
const COL_ITEM = 3;
const COL_SLOT = 4;
const COL_IS_ROT = 5;
const COL_IS_ALT = 6;
const COL_LOOT_TIME = 7;
const COL_LOOT_TIER = 8;
const COL_TIER_TOTAL = 0;
const COL_TIER_VISIBLE = 1;
const COL_TIER_NON_VIS = 2;
const COL_TIER_WEAPON = 3;
const COL_TIER_ROT = 4;
const COL_TIER_ALT = 5;
const COL_TIER_ALT_LL = 6;
const COL_TIER_LL = 7;
function template_main() {
	// Make sure we have all the global variables we need
	global $context;
	
	echo "\n<div class=\"ROILootAllItems\">\n";
	echo "<form id=\"ROIMemberSelectForm\" name=\"ROIMemberSelectForm\" method=\"GET\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"view_loot\" />\n";
	echo "<input type=\"hidden\" id=\"playerName\" value=\"", $context ['name'], "\" />\n";
	
	// Build the tier totals hidden inputs
	foreach ( $context ['tier_counts'] as $key => $value ) {
		echo "<input type=\"hidden\" id=\"tier", $key, "total\" value=\"", $value [COL_TIER_TOTAL], "\" /> \n";
		echo "<input type=\"hidden\" id=\"tier", $key, "visible\" value=\"", $value [COL_TIER_VISIBLE], "\" /> \n";
		echo "<input type=\"hidden\" id=\"tier", $key, "nonVis\" value=\"", $value [COL_TIER_NON_VIS], "\" /> \n";
		echo "<input type=\"hidden\" id=\"tier", $key, "weapon\" value=\"", $value [COL_TIER_WEAPON], "\" /> \n";
		echo "<input type=\"hidden\" id=\"tier", $key, "rot\" value=\"", $value [COL_TIER_ROT], "\" /> \n";
		echo "<input type=\"hidden\" id=\"tier", $key, "alt\" value=\"", $value [COL_TIER_ALT], "\" /> \n";
		echo "<input type=\"hidden\" id=\"tier", $key, "altLL\" value=\"", $value [COL_TIER_ALT_LL], "\" /> \n";
		echo "<input type=\"hidden\" id=\"tier", $key, "LL\" value=\"", $value [COL_TIER_LL], "\" /> \n";
	}
	
	// Print the selected name
	if ($context ['name'] == "ERROR") {
		echo "<div class=\"ROIError\">";
		echo "Could not find your username associated to the roster, or something bad happened. Contact Corlen or Qulas.";
		echo "</div>\n";
	} else {
		
		echo "<h1>ROI Loot for ";
		if (isset ( $context ['global_viewer'] ) && $context ['global_viewer'] == true && isset ( $context ['roster_names'] )) {
			
			echo "<select name=\"ROIMemberSelect\" id=\"ROIMemberSelect\" onChange=\"submitForm();\">\n";
			foreach ( $context ['roster_names'] as $name ) {
				$selected = $context ['name'] == $name ? " selected=\"selected\"" : "";
				
				echo "<option", $selected, ">", $name, "</option>\n";
			}
			echo "</select>\n";
		} else {
			echo $context ['name'];
		}
		echo "</h1>\n";
	}
	echo "</form>\n";
	
	// Attendance
	echo "<span class=\"ROIAttendance\">";
	echo "Attendance: &nbsp;";
	echo "30 Day = <div id=\"at30\"></div>";
	echo "60 Day = <div id=\"at60\"></div>";
	echo "90 Day = <div id=\"at90\"></div>";
	echo "Lifetime = <div id=\"atLife\"></div>";
	echo "</span>\n";
	
	echo "<h2>Loot Summary</h2>\n";
	
	// Tier Selection
	echo "<div>Tier: </div>";
	echo "<select multiple size=\"5\" id=\"tierFilter\" name=\"tierFilter\" onChange=\"updateLootFilters('tierFilter');\">\n";
	echo "<option>All</option>\n";
	
	// Selected variable - the first real tier in the option list will be selected
	$isSelected = " selected='selected' ";
	foreach ( $context ['tier_counts'] as $key => $value ) {
		echo "<option", $isSelected, ">", $key, "</option>\n";
		$isSelected = "";
	}
	
	echo "</select>\n";
	echo "<br/><br/>\n";
	
	// Print the Summary table
	echo "<table class=\"ROILootTable\">\n";
	echo "	<tr>\n";
	echo "		<th>Total Loot</th>\n";
	echo "		<th>Visible</th>\n";
	echo "		<th>Non-Visible</th>\n";
	echo "		<th>Weapon</th>\n";
	echo "		<th>Rot</th>\n";
	echo "		<th>Alt</th>\n";
	echo "		<th>Alt Last Loot</th>\n";
	echo "		<th>Last Loot Date</th>\n";
	echo "	</tr>\n";
	echo "	<tr class=\"ROILootTableAlt1\">\n";
	echo "		<td id=\"tblTotal\">&nbsp;</td>\n";
	echo "		<td id=\"tblVisible\">&nbsp;</td>\n";
	echo "		<td id=\"tblNonVis\">&nbsp;</td>\n";
	echo "		<td id=\"tblWeapon\">&nbsp;</td>\n";
	echo "		<td id=\"tblRot\">&nbsp;</td>\n";
	echo "		<td id=\"tblAlt\">&nbsp;</td>\n";
	echo "		<td id=\"tblAltLL\">&nbsp;</td>\n";
	echo "		<td id=\"tblLL\">&nbsp;</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<br/>\n";
	
	// Print the Loot table
	echo "<div id=\"filterSpan\">\n";
	echo "Include Rots: ";
	echo "<input id=\"includeRot\" name=\"includeRot\" type=\"checkbox\" checked=\"checked\" onChange=\"updateLootFilters('includeRot');\" />\n";
	echo "&nbsp;";
	echo "Include Alt: ";
	echo "<input id=\"includeAlt\" name=\"includeAlt\" type=\"checkbox\" checked=\"checked\" onChange=\"updateLootFilters('includeAlt');\" />\n";
	echo "</div>\n";
	echo "<table class=\"ROILootTable\">\n";
	echo "	<tr>\n";
	echo "		<th>Loot Date</th>\n";
	echo "		<th>Event</th>\n";
	echo "		<th>Item</th>\n";
	echo "		<th>Slot</th>\n";
	echo "		<th>Rot</th>\n";
	echo "		<th>Alt</th>\n";
	echo "	</tr>\n";
	
	$idCount = 0;
	foreach ( $context ['loot'] as $key => $value ) {
		if ($altClass == "ROILootTableAlt1")
			$altClass = "ROILootTableAlt2";
		else
			$altClass = "ROILootTableAlt1";
		
		echo "	<tr class=\"", $altClass, "\" id=\"loot_", $idCount, "\">\n";
		echo "		<td>", $value [COL_LOOT_DATE], "</td>\n";
		echo "		<td>", $value [COL_EVENT], "</td>\n";
		echo "		<td>", $value [COL_ITEM], "</td>\n";
		echo "		<td>", $value [COL_SLOT], "</td>\n";
		echo "		<td id=\"lootIsRot_", $idCount, "\">", ($value [COL_IS_ROT] ? $value [COL_IS_ROT] : "&nbsp;"), "</td>\n";
		echo "		<td id=\"lootIsAlt_", $idCount, "\">", ($value [COL_IS_ALT] ? $value [COL_IS_ALT] : "&nbsp;"), "</td>\n";
		echo "		<input type=\"hidden\" id=\"lootTier_", $idCount, "\" value=\"", $value [COL_LOOT_TIER], "\" />\n";
		echo "	</tr>\n";
		
		$idCount ++;
	}
	
	echo "</table>\n";
	echo "<input type=\"hidden\" id=\"numLoot\" name=\"numLoot\" value=\"", $idCount, "\" />";
	echo "<br/>\n";
	echo "</div>\n";
	
	echo "<script type=\"text/javascript\">updateLootFilters('tierFilter');</script>\n";
}

?>
