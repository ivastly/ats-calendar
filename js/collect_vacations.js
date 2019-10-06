//(function () {
function getCurrentWeek() {
	return jQuery('#applicationTeamCalendar_currentWeek').text();
}

function getNames() {
	let names = [];
	jQuery("td.headerColorTeamCalendar").each(function () {
		let $td = jQuery(this);
		names.push($td.text());
	});

	return names;
}

function getWeekOverviews() {

	let weekOverviewOfEachColleague = [];
	let weekBuffer = [];
	jQuery('td.applicationTeamCalendar_calRow').each(function (index) {
		let $td = jQuery(this);
		let today = 'regular';
		let $absDiv = $td.find("div[title='Approved']");
		if ($absDiv.length > 0 && $absDiv.text() === 'ABS') {
			today = 'vacation';
		}
		weekBuffer.push(today);

		if ((index + 1) % 9 === 0) {
			weekOverviewOfEachColleague.push(weekBuffer.slice(0, 5));
			weekBuffer = [];
		}
	});

	return weekOverviewOfEachColleague;
};

function array_combine(keys, values) {
	let result = {};
	for (i = 0; i < keys.length; i++) {
		result[keys[i]] = values[i];
	}

	return result;
}

let weekToVacations = {};

let names = getNames();
let weeks = getWeekOverviews();
let schedule = array_combine(names, weeks);
let currentWeek = getCurrentWeek();
weekToVacations[currentWeek] = schedule;

/* 'return outside function definition' is okay here, because it will be run in puphpeteer context */
return weekToVacations;
//})();
