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
		if ($td.find("div[title='Approved']").length > 0) {
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
	let newArray = [];
	for (i = 0; i < keys.length; i++) {
		newArray[keys[i]] = values[i]
	}

	return newArray;
}

let weekToVacations = {};

let names = getNames();
let weeks = getWeekOverviews();
let schedule = array_combine(names, weeks);
let currentWeek = getCurrentWeek();
weekToVacations[currentWeek] = schedule;

// "return outside function declaration" error is okay here, because it will be used in puphpeteer context
return weekToVacations;
