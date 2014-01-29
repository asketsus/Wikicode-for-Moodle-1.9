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
 * This file defines de main wiki configuration form
 *
 * @package mod-wikicode
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

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot . '/mod/wikicode/locallib.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/datalib.php');
require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class mod_wikicode_mod_form extends moodleform_mod {

    function definition() {
        global $COURSE;
        $mform =& $this->_form;

        //-------------------------------------------------------------------------------
        /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));
		
        /// Adding the standard "name" field
        $mform->addElement('text', 'name', "Wiki name", array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
		
        /// Adding the optional "intro" and "introformat" pair of fields
        //    	$mform->addElement('htmleditor', 'intro', get_string('wikiintro', 'wiki'));
        //		$mform->setType('intro', PARAM_RAW);
        //		$mform->addRule('intro', get_string('required'), 'required', null, 'client');
        //
        //        $mform->addElement('format', 'introformat', get_string('format'));
        //$this->add_intro_editor(true, get_string('wikiintro', 'wikicode'));
		
        //-------------------------------------------------------------------------------
        /// Adding the rest of wiki settings, spreeading all them into this fieldset
        /// or adding more fieldsets ('header' elements) if needed for better logic

        $mform->addElement('header', 'wikifieldset', "Wiki Settings");

        $attr = array('size' => '20');
        if (!empty($this->_instance)) {
            $attr['disabled'] = 'disabled';
        } else {
            $attr['value'] = "code";
        }
        
	
        $mform->addElement('text', 'firstpagetitle', "File name", $attr);
        //$mform->setHelpButton('firstpagetitle', 'firstpagetitle', 'wikicode');

        if (empty($this->_instance)) {
            $mform->addRule('firstpagetitle', null, 'required', null, 'client');
        }

		$attr = array('size' => '80');
		
		$gccvalue = wikicode_get_gccpath();
		if ($gccvalue->gccpath != "") {
		    $attr['value'] = $gccvalue->gccpath;
		} else {
			$attr['value'] = 'gcc';
		}
		
		$mform->addElement('text', 'gccpath', 'Unix Compiler Path', $attr);
		
		$mingwvalue = wikicode_get_mingwpath();
		if ($mingwvalue->mingwpath != "") {
		    $attr['value'] = $mingwvalue->mingwpath;
		} else {
			$attr['value'] = 'mingw32-gcc';
		}
	
		$mform->addElement('text', 'mingwpath', 'Windows Compiler Path', $attr);
		//$mform->setHelpButton('mingwpath', 'mingwpath', 'wikicode');

        $wikimodeoptions = array ('collaborative' => "Collaborative wiki", 'individual' => "Individual wiki");
        // don't allow to change wiki type once is set
        $wikitype_attr = array();
        if (!empty($this->_instance)) {
            $wikitype_attr['disabled'] = 'disabled';
        }
        $mform->addElement('select', 'wikimode', "Wiki mode", $wikimodeoptions, $wikitype_attr);
        //$mform->setHelpButton('wikimode', 'wikimode', 'wikicode');

        $formats = wikicode_get_formats();
        $editoroptions = array();
        foreach ($formats as $format) {
            $editoroptions[$format] = get_string($format, 'wikicode');
        }
        $mform->addElement('select', 'defaultformat', get_string('defaultformat', 'wikicode'), $editoroptions);
        //$mform->setHelpButton('defaultformat', 'defaultformat', 'wikicode');
        $mform->addElement('checkbox', 'forceformat', get_string('forceformat', 'wikicode'));
        //$mform->setHelpButton('forceformat', 'forceformat', 'wikicode');
	
        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();

    }
}
