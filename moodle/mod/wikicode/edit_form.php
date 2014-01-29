<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot . '/mod/wikicode/editors/wikieditor.php');
require_once($CFG->dirroot . '/mod/wikicode/chat/wikichat.php');
require_once($CFG->dirroot . '/lib/formslib.php');

class mod_wikicode_edit_form extends moodleform {

    function definition() {
		global $CFG, $USER;
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
            $pagetitle = get_string('editingpage', 'wikicode', $this->_customdata['pagetitle']);
        } else {
            $pagetitle = get_string('editing', 'wikicode');
        }

        //editor
        $mform->addElement('header', 'general', $pagetitle);

        $fieldname = get_string('format' . $format, 'wikicode');
        if ($format != 'html') {
            // Use wiki editor
			$buttoncommands=array();
			$buttoncommands[] =& $mform->createElement('button','editoption','Unlock', array('id' => 'btnunlock', 'class' => 'btnunlock'));
			$buttoncommands[] =& $mform->createElement('button','editoption','Refresh', array('id' => 'btnref', 'class' => 'btnref'));
			$buttoncommands[] =& $mform->createElement('submit', 'editoption', 'Save', array('id' => 'save'));
			$mform->addGroup($buttoncommands, 'editoption', 'Actions', '', true);
			$mform->setHelpButton('editoption', array('helpeditor', 'Actions'));
            $mform->addElement('wikicodeeditor', 'newcontent', $fieldname, array('cols' => 150, 'rows' => 30, 'Wiki_format' => $format, 'files'=>$files));
        } else {
            $mform->addElement('editor', 'newcontent_editor', $fieldname, null, page_wikicode_edit::$attachmentoptions);
        }
		
		//chat
		$mform->addElement('header','chat','Chat');
		$mform->addElement('wikicodechat', 'wikicodechat', null, array('itemid'=>$fileitemid));
		
		//compiler
		$mform->addElement('header','compiler', 'Compiler');
		$mform->addElement('textarea', 'textCompiler', '', 'wrap="virtual" rows="3" cols="100" readonly="readonly" ');
		
		$buttonarray=array();
		$buttonarray[] =& $mform->createElement('submit','editoption','Compile', array('id' => 'compile'));
		$buttonarray[] =& $mform->createElement('submit','editoption','Download EXE', array('id' => 'compile'));
		$mform->addGroup($buttonarray, 'editoption', 'Options:', '', true);

        //hiddens
        if ($version >= 0) {
            $mform->addElement('hidden', 'version');
            $mform->setDefault('version', $version);
        }

        $mform->addElement('hidden', 'contentformat');
        $mform->setDefault('contentformat', $format);
		
		$mform->addElement('hidden', 'insert');
		$mform->setDefault('insert', 1);
		

    }

}

