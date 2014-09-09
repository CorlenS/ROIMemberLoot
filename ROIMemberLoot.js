// Get attendance after the page loads
window.onload = registerAttendance;

// Hides or displays loot rows based on the selections
// of the various filters on the page.
// var invokerName = The name of the object invoking this method
function updateLootFilters(invokerName) {
	var tierSelectionObj = document.getElementById("tierFilter");
	var includeRotObj = document.getElementById("includeRot");
	var includeAltObj = document.getElementById("includeAlt");

	var tierOpts = tierSelectionObj.options;
	var tierOptsSelected = tierSelectionObj.selectedOptions;
	var tiersSelected = [];
	var allTiersSelected = false;

	var includeRot = includeRotObj.checked;
	var includeAlt = includeAltObj.checked;
	var numLoots = document.getElementById("numLoot").value;

	// Used to alternate the style for each displayed row
	var altClass = "";

	// Set the array of tiers selected
	var idx = 0;
	for (var i = 0; i < tierOpts.length; i++) {
		if(tierOpts[i].selected) {
			tiersSelected[idx++] = tierOpts[i].value;
		}
	}

	allTiersSelected = (tiersSelected.indexOf("All") !== -1);

	// If rot is unchecked, then alt
	// should be unchecked as well
	if (invokerName == "includeRot" && !includeRot) {
		includeAltObj.checked = false;
		includeAlt = false;
	}

	// Only filter if we have loots to filter on
	if (numLoots) {
		for (var i = 0; i < numLoots; i++) {
			// Get the table row for this Id
			var lootRow = document.getElementById("loot_" + i);

			// Get the Tier, Rot and Alt status for this row
			var rowTier = document.getElementById("lootTier_" + i).value;
			var rowIsRot = document.getElementById("lootIsRot_" + i).innerHTML
					.toLowerCase();
			var rowIsAlt = document.getElementById("lootIsAlt_" + i).innerHTML
					.toLowerCase();

			// Filter row based on tier
			if (!allTiersSelected && tiersSelected.indexOf(rowTier) === -1) {
				lootRow.style.display = "none";
			} else if (!includeRot && rowIsRot == "yes") {
				// Filter row based on rot

				// If we are filtering out rots, but include alts is checked
				// Then display the row if it is an Alt loot
				// This may or may not be confusing...
				if (includeAlt && rowIsAlt == "yes") {
					lootRow.style.display = "table-row";
				} else {
					lootRow.style.display = "none";
				}
			} else if (!includeAlt && rowIsAlt == "yes") {
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

		// Fill in the Summary table below
		try {
			// If all tiers are selected, rebuild the tiersSelected array
			if (allTiersSelected) {
				tiersSelected = [];
				var idx = 0;
				for (var i = 0; i < tierOpts.length; i++) {
					if (tierOpts[i].value != "All") {
						tiersSelected[idx++] = tierOpts[i].value;
					}
				}
			}

			var allTotal = 0;
			var visibleTotal = 0;
			var nonVisTotal = 0;
			var weaponTotal = 0;
			var rotTotal = 0;
			var altTotal = 0;
			var altLL = "1/1/1900";
			var altLLValue = "";
			var lastLoot = "1/1/1900";
			var lastLootValue = "";
			for (var i = 0; i < tiersSelected.length; i++) {
				allTotal += parseInt(document.getElementById("tier"
						+ tiersSelected[i] + "total").value);
				visibleTotal += parseInt(document.getElementById("tier"
						+ tiersSelected[i] + "visible").value);
				nonVisTotal += parseInt(document.getElementById("tier"
						+ tiersSelected[i] + "nonVis").value);
				weaponTotal += parseInt(document.getElementById("tier"
						+ tiersSelected[i] + "weapon").value);
				rotTotal += parseInt(document.getElementById("tier"
						+ tiersSelected[i] + "rot").value);
				altTotal += parseInt(document.getElementById("tier"
						+ tiersSelected[i] + "alt").value);

				altLLValue = document.getElementById("tier" + tiersSelected[i]
						+ "altLL").value;
				lastLootValue = document.getElementById("tier"
						+ tiersSelected[i] + "LL").value;

				if (!altLLValue) {
					altLLValue = "1/1/1900";
				}

				if (!lastLootValue) {
					lastLootValue = "1/1/1900";
				}

				if (Date.parse(altLL) < Date.parse(altLLValue)) {
					altLL = altLLValue;
				}

				if (Date.parse(lastLoot) < Date.parse(lastLootValue)) {
					lastLoot = lastLootValue;
				}
			}

			if (altLL == "1/1/1900") {
				altLL = "&nbsp;";
			}

			if (lastLoot == "1/1/1900") {
				lastLoot = "&nbsp;";
			}

			document.getElementById("tblTotal").innerHTML = allTotal;
			document.getElementById("tblVisible").innerHTML = visibleTotal;
			document.getElementById("tblNonVis").innerHTML = nonVisTotal;
			document.getElementById("tblWeapon").innerHTML = weaponTotal;
			document.getElementById("tblRot").innerHTML = rotTotal;
			document.getElementById("tblAlt").innerHTML = altTotal;
			document.getElementById("tblAltLL").innerHTML = altLL;
			document.getElementById("tblLL").innerHTML = lastLoot;
		} catch (e) {
			alert(e);
		}

	}
}

// Submits the member select form
function submitForm() {
	document.getElementById("ROIMemberSelectForm").submit();
}

function registerAttendance() {
	var url = "http://guildundertow.com/json.txt";
	// var url = "http://roiguild.org/dkp/viewmembers.php";

	try {
		var playerName = document.getElementById("playerName").value
				.toUpperCase();

		$
				.getJSON(
						url,
						function(data) {
							for (var i = 0; i < data.length; i++) {
								if (data[i].member_name.toUpperCase() == playerName) {
									document.getElementById("at30").innerHTML = data[i]["30"]
											+ "%";
									document.getElementById("at60").innerHTML = data[i]["60"]
											+ "%";
									document.getElementById("at90").innerHTML = data[i]["90"]
											+ "%";
									document.getElementById("atLife").innerHTML = data[i]["lifetime"]
											+ "%";
									break;
								}
							}
						});
	} catch (e) {
		// Just ignore for now
	}
}
