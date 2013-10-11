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

function template_main()
{
	// Make sure we have all the global variables we need
	global $context;

	echo "\n<div class=\"ROILootAllItems\">\n";
	echo "<form id=\"ROIMemberSelectForm\" name=\"ROIMemberSelectForm\" method=\"GET\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"view_loot\" />\n";
	echo "<h1>ROI Loot for ";
	if(isset($context['global_viewer']) && $context['global_viewer'] == true && isset($context['roster_names'])) {
		
		echo "<select name=\"ROIMemberSelect\" id=\"ROIMemberSelect\" onChange=\"submitForm();\">\n";
		foreach($context['roster_names'] as $name) {
			$selected = $context['name'] == $name ? " selected=\"selected\"" : "";
			
			echo "<option", $selected, ">", $name, "</option>\n";
		}
		echo "</select>\n";
	} else {
		echo $context['name'];
	}
	echo "</h1>\n";
	echo "</form>\n";
	
	// Print the Summary table	
	echo "<table class=\"ROILootSummaryTable\">\n";
	echo "	<tr>\n";
	echo "		<th colspan = 2>Loot Hits</th>\n";
	echo "		<th colspan = 2>Alt / Rots</th>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"ROILootSummaryTableLabel\">Last Loot Date:</td>\n";
	echo "		<td class=\"ROILootSummaryTableData\">", ($context['last_loot_date'] ? $context['last_loot_date'] : "&nbsp;"), "</td>\n";
	echo "		<td class=\"ROILootSummaryTableLabel\">Total Rot Loots:</td>\n";
	echo "		<td class=\"ROILootSummaryTableData\">", $context['total_rots'], "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"ROILootSummaryTableLabel\">Total Loots:</td>\n";
	echo "		<td class=\"ROILootSummaryTableData\">", $context['total_loots'], "</td>\n";
	echo "		<td class=\"ROILootSummaryTableLabel\">Total Alt Loots:</td>\n";
	echo "		<td class=\"ROILootSummaryTableData\">", $context['total_alts'], "</td>\n";
	echo "	</tr>\n";
	
	foreach($context['tier_counts'] as $key => $value) {
		echo "	<tr>\n";
		echo "		<td class=\"ROILootSummaryTableLabel\">", $key, " Count:</td>\n";
		echo "		<td class=\"ROILootSummaryTableData\">", $value, "</td>\n";
		echo "		<td>&nbsp;</td>\n";
		echo "	</tr>\n";
	}
	
	echo "</table>\n";
	echo "<br/>\n";
	
	// Print the Loot table
	echo "<h2>Loot Summary</h2>\n";
	echo "<div id=\"filterSpan\">\n";
	echo "Tier: ";
	echo "<select id=\"tierFilter\" name=\"tierFilter\" onChange=\"updateLootFilters('tierFilter');\">\n";
	echo "<option>All</option>\n";

	// Selected variable - the first real tier in the option list will be selected
	$isSelected = " selected='selected' ";
	foreach($context['tier_counts'] as $key => $value) {
		echo "<option", $isSelected, ">", $key, "</option>\n";
		$isSelected = "";
	}

	echo "</select>\n";
	echo "&nbsp;";
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
	echo "		<th>Is Rot</th>\n";
	echo "		<th>Is Alt</th>\n";
	echo "	</tr>\n";
	
	$idCount = 0;
	foreach($context['loot'] as $key => $value) {
		if ($altClass == "ROILootTableAlt1")
			$altClass = "ROILootTableAlt2";
		else
			$altClass = "ROILootTableAlt1";
		
		echo "	<tr class=\"", $altClass, "\" id=\"loot_", $idCount, "\">\n";
		echo "		<td>", $value[COL_LOOT_DATE], "</td>\n";
		echo "		<td>", $value[COL_EVENT], "</td>\n";
		echo "		<td>", $value[COL_ITEM], "</td>\n";
		echo "		<td>", $value[COL_SLOT], "</td>\n";
		echo "		<td id=\"lootIsRot_", $idCount, "\">", ($value[COL_IS_ROT] ? $value[COL_IS_ROT] : "&nbsp;"), "</td>\n";
		echo "		<td id=\"lootIsAlt_", $idCount, "\">", ($value[COL_IS_ALT] ? $value[COL_IS_ALT] : "&nbsp;"), "</td>\n";
		echo "		<input type=\"hidden\" id=\"lootTier_", $idCount, "\" value=\"", $value[COL_LOOT_TIER], "\" />\n";
		echo "	</tr>\n";
		
		$idCount++;
	}
	
	echo "</table>\n";
	echo "<input type=\"hidden\" id=\"numLoot\" name=\"numLoot\" value=\"", $idCount, "\" />";
	echo "<br/>\n";
	echo "</div>\n";

	echo "<script type=\"text/javascript\">updateLootFilters('tierFilter');</script>\n";
}

?>
