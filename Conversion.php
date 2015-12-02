<?php

// This file converts between the abbreviations stored in the database
// and the human-friendly names
function AbbToName($abb)
{
	switch ($abb)
	{
		case "CMPE":
			return "Computer Engineering";
		case "CMSC":
			return "Computer Science";
		case "MENG":
			return "Mechanical Engineering";
		case "CENG":
			return "Chemical Engineering";
		case "ENGR":
			return "Engineering Undecided";
		default:
			return "Unhandled Major";
	}
}

function NameToAbb($abb)
{
	switch ($abb)
	{
		case "Computer Engineering":
			return "CMPE";
		case "Computer Science":
			return "CMSC";
		case "Mechanical Engineering":
			return "MENG";
		case "Chemical Engineering":
			return "CENG";
		case "Engineering Undecided":
			return "ENGR";
		default:
			return "Unhandled Major";
	}
}

?>