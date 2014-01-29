<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/lib/formslib.php');

class mod_wikicode_log_form extends moodleform {

    function definition() {
        global $CFG, $USER, $DB;

        $mform =& $this->_form;

        $version = $this->_customdata['version'];
        $format  = $this->_customdata['format'];
        $tags    = !isset($this->_customdata['tags'])?"":$this->_customdata['tags'];

        if ($format != 'html') {
            $contextid  = $this->_customdata['contextid'];
            $filearea   = $this->_customdata['filearea'];
            $fileitemid = $this->_customdata['fileitemid'];
        }

        if (isset($this->_customdata['pagetitle'])) {
            $pagetitle = get_string('logpage', 'wikicode', $this->_customdata['pagetitle']);
        } else {
            $pagetitle = get_string('loging', 'wikicode');
        }
		
		//Time
		$time = $this->_customdata['page']->timeendedit - $this->_customdata['page']->timestartedit;
		$seconds = $time % 60;
		$time = ($time - $seconds) / 60;
		$minutes = $time % 60;
		$hours = ($time - $minutes) / 60;		
		
		//Stats
		$attr = array('size' => '75', 'readonly' => 1);
		$mform->addElement('header','stats', 'Stats');
		
		$time = $this->_customdata['page']->timer;
		$seconds = $time % 60;
		$time = ($time - $seconds) / 60;
		$minutes = $time % 60;
		$hours = ($time - $minutes) / 60;	
		$attr['value'] = $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds";
		$mform->addElement('text', 'timeedit', 'Edit Time', $attr);
		
		$time = $this->_customdata['page']->timeendedit - $this->_customdata['page']->timestartedit;
		$seconds = $time % 60;
		$time = ($time - $seconds) / 60;
		$minutes = $time % 60;
		$hours = ($time - $minutes) / 60;	
		$attr['value'] = $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds";
		$mform->addElement('text', 'timetotal', 'Total Time', $attr);

		$attr['value'] = $this->_customdata['page']->errorcompile;
		$mform->addElement('text', 'errorscompilation', 'Compilation Errors', $attr);


        $mform->addElement('hidden', 'contentformat');
        $mform->setDefault('contentformat', $format);
		
		$mform->addElement('hidden', 'insert');
		$mform->setDefault('insert', 1);

    }

}