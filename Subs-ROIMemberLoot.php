<?php
require_once 'google-api/src/Google_Client.php';
require_once 'google-api/src/contrib/Google_DriveService.php';

// Path to the .p12 key file. This should be in a location not accessable to
// the public web.
const KEY_FILE = '/home/starbuck/www/roiguild.org/c397649304793c9ac6d1ba9641d224ca0d1e9cf4-privatekey.p12';

// Set your client id, service account name, and the path to your private key.
// For more information about obtaining these keys, visit:
// https://developers.google.com/console/help/#service_accounts
const CLIENT_ID = '387553138581-k19pmh7k895mjdsjbhik77k4tktsqjdl.apps.googleusercontent.com';
const SERVICE_ACCOUNT_NAME = '387553138581-k19pmh7k895mjdsjbhik77k4tktsqjdl@developer.gserviceaccount.com';

// Keys for the Roster and Loot Spreadsheets
const ROSTER_ID = '0ApTza9jlcMqYdHNDQmN4TVlXM1RHX0tNMWN2Z2k0MVE';
const LOOT_ID = '0ApTza9jlcMqYdHJ0a0JXaHhseVRCLVNvemk5aE5yNWc';

const COL_LOOT_DATE = 0;
const COL_NAME = 1;
const COL_EVENT = 2;
const COL_ITEM = 3;
const COL_SLOT = 4;
const COL_IS_ROT = 5;
const COL_IS_ALT = 6;

// Debug function. Prints arrays and objects cleanly.
function dumpClean($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	echo "<br/><br/>";
}

// Initializes the google client object. Sets all the various
// credentials for logging in with the service account.
function initializeClient() {
	$client = new Google_Client();
	$client->setApplicationName("ROI Member Loot");

	// Load the key in PKCS 12 format
	$key = file_get_contents(KEY_FILE);
	$client->setAssertionCredentials(new Google_AssertionCredentials(
			SERVICE_ACCOUNT_NAME,
			array('https://www.googleapis.com/auth/drive.readonly'),
			$key)
	);

	$client->setClientId(CLIENT_ID);

	return $client;
}

// Queries a spreadsheet for its data.
// $id = The speadsheet ID
// $queryString = Optional string for filtering on the spreadsheet
// $specialFilter = Optional string for additional query parameters to add
function querySheet($id, $queryString = "", $specialFilter = "") {
	global $client;

	$qryStr = "";
	$special = "";

	// If $queryString is set lets append it to our URI request.
	if($queryString) {
		$qryStr = "&tq=" . $queryString;
	}

	if($specialFilter) {
		$special = "&" . $specialFilter;
	}

	$uri = "https://docs.google.com/spreadsheet/tq?key=" . $id . "&tqx=reqId:1;out:csv&headers=1" . $qryStr . $special;

	// Initialize the request
	$request = new Google_HttpRequest($uri);

	// Get our response
	$response = $client->getIo()->authenticatedRequest($request);

	// Set the content from the response
	$content = $response->getResponseBody();

	return $content;
}

function ROIMemberLoot_set_menu($buttons) {
	global $smcFunc;
	global $user_info;
	global $scripturl;

	try {	
		$new = array();
		$new['View Loot'] = array(
				'title' => "View Loot",
				'href' => $scripturl . '?action=view_loot',
				'show' => !$user_info['is_guest'],
				'sub_buttons' => array(
				),
				'is_last' => false,
			);
		
		array_splice($buttons, count($buttons) - 1, 0, $new);
	} catch(Exception $e) {} // Ignore errors for now...
}

// Adds the view_loot custom action to the golbal action array
// To invoke the action browse to index.php?action=view_loot
function ROIMemberLoot_view_loot_action(&$actionArray) {
	$actionArray['view_loot'] = array('Subs-ROIMemberLoot.php', 'ROIMemberLoot_view_loot');
}

// Called when view_loot action is invoked.
// Generates the Loot page for the logged in user
function ROIMemberLoot_view_loot() {
	global $smcFunc;
	global $context;
	global $user_info;

	// Load the custom template
	loadTemplate('ROIMemberLoot');
	
	// Initialize the client
	global $client;
	$client = initializeClient();
	
	// Initialize our display variables
	$loot = array();
	$lastLootDate = "";
	$totalLoots = 0;
	$totalRots = 0;
	$totalAlts = 0;
	$tierCounts = array();
	$name = "";
	
	// Get the contents of the roster.
	$arrContent = explode("\n", querySheet(ROSTER_ID, "select%20*%20where%20E%20%3D%20'" . $user_info['username'] . "'"));
	
	// If there are three elements here, we know we only have 1 match
	// so lets continue
	if(count($arrContent) == 3) {
		// Get the members Name out of the roster
		$rosterInfo = str_getcsv($arrContent[1]);
		$name = $rosterInfo[0];
		
		// Get the content from the Loot Sheet
		$content = querySheet(LOOT_ID, "select%20*%20where%20B%20%3D%20'" . $name . "'");
	
		// If we have loot lets continue
		if($content) {
			// Convert the loot content into an array
			$arrContent = explode("\n", $content);
	
			// If there are loots to display lets continue.
			if(count($arrContent) > 1) {
				
				// First generate a list of events so we can determine what tier
				// each peice of loot came from
				$events = explode("\n", querySheet(LOOT_ID, "", "sheet=RainOfFearRaids"));
				$eventsArr = array();
				for($i = 1; $i < count($events); $i++) {
					if($events[$i]) {
						$tmp = str_getcsv($events[$i]);
						// Set our events array with the key being the event name,
						// and value the tier
						$eventsArr[$tmp[1]] = $tmp[2];
						
						// Also initialize the tier loot counts to 0 for this tier.
						$tierCounts[$tmp[2]] = 0;
					}
				}
				// This array can get out of order so sort by tier here
				ksort($tierCounts);
					
				// Loop over our actual loots and build the various information variables
				for($i = 1; $i < count($arrContent); $i++) {
					if($arrContent[$i]) {
						$tmp = str_getcsv($arrContent[$i]);
						
						// Add this loot into the final loot array
						array_push($loot, $tmp);
							
						// If this is a non rot, non alt loot, then add it
						// to the real loot totals
						if(!$tmp[COL_IS_ROT] && !$tmp[COL_IS_ALT]) {
							// Figure out the last loot date here
							if(strtotime($lastLootDate) < strtotime($tmp[COL_LOOT_DATE]))
								$lastLootDate =  $tmp[COL_LOOT_DATE];
							
							// Get the tier this loot is from an add it to the tier counts
							$tierCounts[$eventsArr[$tmp[COL_EVENT]]]++;
							
							// Increment our total loot count
							$totalLoots++;
						}
							
						// If this is a rot loot increment the rot loot count
						if($tmp[COL_IS_ROT]) {
							$totalRots++;
						}
							
						// If this is an alt loot increment the alt loot count
						if($tmp[COL_IS_ALT]) {
							$totalAlts++;
						}
					}
				}
			}
		}
	} else {
		// Couldn't get the initial mapping from logged in user to real
		// member name.
		$name = "Error";
	}


	// Set the context variables for the template
	$context['name'] = $name;
	$context['last_loot_date'] = $lastLootDate;
	$context['total_loots'] = $totalLoots;
	$context['total_rots'] = $totalRots;
	$context['total_alts'] = $totalAlts;
	$context['tier_counts'] = $tierCounts;
	$context['loot'] = $loot;
	
}
?>