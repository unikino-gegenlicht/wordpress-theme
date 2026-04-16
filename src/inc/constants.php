<?php
const GGL_TITLE_SEPARATOR = " | ";
const GGL_LIST_DATETIME   = "d.m.Y | H:i";
const GGL_TIME_ONLY       = "H:i";

const GGL_THEME__GERMAN_DATETIME_FORMAT  = "d.m.Y | H:i \U\h\\r";
const GGL_THEME__GERMAN_DATE_FORMAT      = "d.m.Y";
const GGL_THEME__GERMAN_TIME_FORMAT      = "H:i \U\h\\r";
const GGL_THEME__ENGLISH_DATETIME_FORMAT = "m/d/Y | g:i a";
const GGL_THEME__ENGLISH_DATE_FORMAT     = "m/d/Y";
const GGL_THEME__ENGLISH_TIME_FORMAT     = "g:i a";
const GGL_THEME__FALLBACK_DATETIME_FORMAT = "Y-m-d H:i";
const GGL_THEME__FALLBACK_DATE_FORMAT     = "Y-m-d";

const GGL_ICAL_SUBSCRIBE_PATHS = [
	"ical",
	"ical.php",
	"ics.php",
	"ics",
	"calendar.php",
	"calendar.ics",
	"gegenlicht.ics",
	"main.ics",
	"screenings.ics"
];