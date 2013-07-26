// Hides or displays loot rows based on the selections
// of the various filters on the page.
// var invokerName = The name of the object invoking this method
function updateLootFilters(invokerName) {
	var tierSelectionObj = document.getElementById("tierFilter");
	var includeRotObj = document.getElementById("includeRot");
	var includeAltObj = document.getElementById("includeAlt");

	var tierSelection = tierSelectionObj.options.item(tierSelectionObj.selectedIndex).value;
	var includeRot = includeRotObj.checked;
	var includeAlt = includeAltObj.checked;
	var numLoots = document.getElementById("numLoot").value;
	
	// Used to alternate the style for each displayed row
	var altClass = "";
	
	// If rot is unchecked, then alt
	// should be unchecked as well
	if(invokerName == "includeRot" && !includeRot) {
		includeAltObj.checked = false;
		includeAlt = false;
	}
	
	// Only filter if we have loots to filter on
	if(numLoots) {
		for(var i = 0; i < numLoots; i++) {
			// Get the table row for this Id
			var lootRow = document.getElementById("loot_" + i);
			
			// Get the Tier, Rot and Alt status for this row
			var rowTier = document.getElementById("lootTier_" + i).value;
			var rowIsRot = document.getElementById("lootIsRot_" + i).innerHTML.toLowerCase();
			var rowIsAlt = document.getElementById("lootIsAlt_" + i).innerHTML.toLowerCase();
			
			// Filter row based on tier
			if(tierSelection != "All" && tierSelection != rowTier) {
				lootRow.style.display = "none";
			} else if(!includeRot && rowIsRot == "yes") {
				// Filter row based on rot
				
				// If we are filtering out rots, but include alts is checked
				// Then display the row if it is an Alt loot
				// This may or may not be confusing...
				if(includeAlt && rowIsAlt == "yes") {
					lootRow.style.display = "table-row";
				} else {				
					lootRow.style.display = "none";
				}
			}  else if(!includeAlt && rowIsAlt == "yes") {
				// Filter row based on alt
				lootRow.style.display = "none";
			} else {
				// No filters matched, so display the row
				lootRow.style.display = "table-row";
				
				// Alternate the CSS class
				if (altClass == "ROILootTableAlt1")
					altClass = "ROILootTableAlt2";
				else
					altClass = "ROILootTableAlt1";
				
				// Set this rows class
				lootRow.className = altClass;
			}
		}
	}
}

// Submits the member select form
function submitForm() {
	document.getElementById("ROIMemberSelectForm").submit();
}
