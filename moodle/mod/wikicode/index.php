<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * This page lists all the instances of wikicode in a particular course
 *
 * @package mod-wikicode-2.0
 * @copyrigth 2009 Marc Alier, Jordi Piguillem marc.alier@upc.edu
 * @copyrigth 2009 Universitat Politecnica de Catalunya http://www.upc.edu
 *
 * @author Jordi Piguillem
 * @author Marc Alier
 * @author David Jimenez
 * @author Josep Arus
 * @author Kenneth Riba
 * @author Antonio J. González
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

	require_once("../../config.php");
    require_once("lib.php");

    $id = required_param('id', PARAM_INT);   // course

    if (! $course = get_record("course", "id", $id)) {
        error("Course ID is incorrect");
    }

    require_course_login($course);

    add_to_log($course->id, "wikicode", "view all", "index.php?id=$course->id", "");


/// Get all required strings

    $strwikicodes = get_string("modulenameplural", "wikicode");
    $strwikicode  = get_string("modulename", "wikicode");


/// Print the header
    $navlinks = array();
    $navlinks[] = array('name' => $strwikicodes, 'link' => "index.php?id=$course->id", 'type' => 'activity');
    $navigation = build_navigation($navlinks);

    print_header_simple("$strwikicodes", "", $navigation, "", "", true, "", navmenu($course));

/// Get all the appropriate data

    if (! $wikicodes = get_all_instances_in_course("wikicode", $course)) {
        notice(get_string('thereareno', 'moodle', $strwikicodes), "../../course/view.php?id=$course->id");
        die;
    }

/// Print the list of instances (your module will probably extend this)

    $timenow = time();
    $strname  = 'Nombre';
    $strsummary = get_string('summary');
    $strtype = 'Tipo';
    $strlastmodified = 'Creación';
    $strweek  = get_string('week');
    $strtopic  = get_string('topic');

    if ($course->format == "weeks") {
        $table->head  = array ($strweek, $strname, $strsummary, $strtype, $strlastmodified);
        $table->align = array ('CENTER', 'LEFT', 'LEFT', 'LEFT', 'LEFT');
    } else if ($course->format == "topics") {
        $table->head  = array ($strtopic, $strname, $strsummary, $strtype, $strlastmodified);
        $table->align = array ('CENTER', 'LEFT', 'LEFT', 'LEFT', 'LEFT');
    } else {
        $table->head  = array ($strname, $strsummary, $strtype, $strlastmodified);
        $table->align = array ('LEFT', 'LEFT', 'LEFT', 'LEFT');
    }

    foreach ($wikicodes as $wikicode) {
        if (!$wikicode->visible) {
            //Show dimmed if the mod is hidden
            $link = '<a class="dimmed" href="view.php?id='.$wikicode->coursemodule.'">'.format_string($wikicode->name,true).'</a>';
        } else {
            //Show normal if the mod is visible
            $link = '<a href="view.php?id='.$wikicode->coursemodule.'">'.format_string($wikicode->name,true).'</a>';
        }

        $timmod = '<span class="smallinfo">'.userdate($wikicode->timemodified).'</span>';
        $summary = '<div class="smallinfo">'.$wikicode->firstpagetitle.'</div>';

        $site = get_site();

        $wtype = '<span class="smallinfo">'.$wikicode->wikimode.'</span>';

        if ($course->format == "weeks" or $course->format == "topics") {
            $table->data[] = array ($wikicode->section, $link, $summary, $wtype, $timmod);
        } else {
            $table->data[] = array ($link, $summary, $wtype, $timmod);
        }
    }

    echo "<br />";

    print_table($table);

/// Finish the page

    print_footer($course);
