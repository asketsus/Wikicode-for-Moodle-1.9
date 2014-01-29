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
 * @TODO: Doc this file
 *
 * @package mod-wikicode
 * @copyrigth 2009 Marc Alier, Jordi Piguillem marc.alier@upc.edu
 * @copyrigth 2009 Universitat Politecnica de Catalunya http://www.upc.edu
 *
 * @author Jordi Piguillem
 * @author Marc Alier
 * @author David Jimenez
 * @author Josep Arus
 * @author Daniel Serrano
 * @author Kenneth Riba
 * @author Antonio J. González
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
echo "instancecomments.php";
require_once('../../config.php');
require_once($CFG->dirroot . "/mod/wikicode/pagelib.php");
require_once($CFG->dirroot . "/mod/wikicode/locallib.php");
require_once($CFG->dirroot . '/mod/wikicode/comments_form.php');

$pageid = required_param('pageid', PARAM_TEXT);
$action = optional_param('action', '', PARAM_ACTION);
$id = optional_param('id', 0, PARAM_INT);
$commentid = optional_param('commentid', 0, PARAM_INT);
$newcontent = optional_param('newcontent', '', PARAM_CLEANHTML);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if (!$page = wikicode_get_page($pageid)) {
    print_error('incorrectpageid', 'wikicode');
}

if (!$subwiki = wikicode_get_subwiki($page->subwikiid)) {
    print_error('incorrectsubwikiid', 'wikicode');
}
if (!$cm = get_coursemodule_from_instance("wikicode", $subwiki->wikiid)) {
    print_error('invalidcoursemodule');
}
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
if (!$wiki = wikicode_get_wiki($subwiki->wikiid)) {
    print_error('incorrectwikiid', 'wikicode');
}
require_login($course->id, true, $cm);

if ($action == 'add' || $action == 'edit') {
    //just check sesskey
    if (!confirm_sesskey()) {
        print_error(get_string('invalidsesskey', 'wikicode'));
    }
    $comm = new page_wikicode_handlecomments($wiki, $subwiki, $cm);
    $comm->set_page($page);
} else {
    if(!$confirm) {
        $comm = new page_wikicode_deletecomment($wiki, $subwiki, $cm);
        $comm->set_page($page);
        $comm->set_url();
    } else {
        $comm = new page_wikicode_handlecomments($wiki, $subwiki, $cm);
        $comm->set_page($page);
        if (!confirm_sesskey()) {
            print_error(get_string('invalidsesskey', 'wikicode'));
        }
    }
}

if ($action == 'delete') {
    $comm->set_action($action, $commentid, 0);
} else {
    if (empty($newcontent)) {
        $form = new mod_wikicode_comments_form();
        $newcomment = $form->get_data();
        $content = $newcomment->entrycomment_editor['text'];
    } else {
        $content = $newcontent;
    }

    if ($action == 'edit') {
        $comm->set_action($action, $id, $content);

    } else {
        $action = 'add';
        $comm->set_action($action, 0, $content);
    }
}

$comm->print_header();
$comm->print_content();
$comm->print_footer();
