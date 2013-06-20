<?php
const COL_LOOT_DATE = 0;
const COL_NAME = 1;
const COL_EVENT = 2;
const COL_ITEM = 3;
const COL_SLOT = 4;
const COL_IS_ROT = 5;
const COL_IS_ALT = 6;

function template_main()
{
	// Make sure we have all the global variables we need
	global $context;

	$summaryLabelStyle = "text-align: right; padding-left:10px;";
	$lootSummaryTdStart = "<td style=\"border-bottom: 1px solid #61708E; padding-right:10px;\">";

	// Generate the page - this is pretty much a mess...
	// Should look into seeing how to tie a CSS page into the forum plugin
	echo "<h1>ROI Loot for " . $context['name'] . "</h1>\n";
	echo "<table>\n";
	echo "	<tr>\n";
	echo "		<th colspan = 2 style=\"padding-left:10px;\">Loot Hits</th>\n";
	echo "		<th colspan = 2 style=\"padding-left:10px;\">Alt / Rots</th>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td style=\"" . $summaryLabelStyle . "\">Last Loot Date:</td>\n";
	echo "		<td>" . ($context['last_loot_date'] ? $context['last_loot_date'] : "&nbsp;") . "</td>\n";
	echo "		<td style=\"" . $summaryLabelStyle . "\">Total Rot Loots:</td>\n";
	echo "		<td>" . $context['total_rots'] . "</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td style=\"" . $summaryLabelStyle . "\">Total Loots:</td>\n";
	echo "		<td>" . $context['total_loots'] . "</td>\n";
	echo "		<td style=\"" . $summaryLabelStyle . "\">Total Alt Loots:</td>\n";
	echo "		<td>" . $context['total_alts'] . "</td>\n";
	echo "	</tr>\n";
	foreach($context['tier_counts'] as $key => $value) {
		echo "\t<tr><td style=\"" . $summaryLabelStyle . "\">Tier " . $key . " Count:</td><td>" . $value . "</td><td>&nbsp;</td></tr>\n";
	}
	echo "</table>\n";
	echo "<br/>\n";
	
	echo "<h2>Loot Summary</h2>\n";
	echo "<table style=\"text-align: left; border: 1px solid black; border-collapse:collapse; \">\n";
	echo "	<tr>\n";
	echo "		<th style=\"padding-right:10px; border-bottom: 1px solid black;\">Loot Date</th>\n";
	echo "		<th style=\"padding-right:10px; border-bottom: 1px solid black;\">Event</th>\n";
	echo "		<th style=\"padding-right:10px; border-bottom: 1px solid black;\">Item</th>\n";
	echo "		<th style=\"padding-right:10px; border-bottom: 1px solid black;\">Slot</th>\n";
	echo "		<th style=\"padding-right:10px; border-bottom: 1px solid black;\">Is Rot</th>\n";
	echo "		<th style=\"padding-right:10px; border-bottom: 1px solid black;\">Is Alt</th>\n";
	echo "	</tr>\n";
	foreach($context['loot'] as $key => $value) {
		echo "\t<tr>" . $lootSummaryTdStart . $value[COL_LOOT_DATE] . "</td>" . $lootSummaryTdStart;
		echo $value[COL_EVENT] . "</td>" . $lootSummaryTdStart . $value[COL_ITEM] . "</td>" . $lootSummaryTdStart;
		echo $value[COL_SLOT] . "</td>" . $lootSummaryTdStart . $value[COL_IS_ROT] . "</td>" . $lootSummaryTdStart;
		echo $value[COL_IS_ALT] . "</td></tr>\n";
	}
	echo "</table>\n";
}
?>
