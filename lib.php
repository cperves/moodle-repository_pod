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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * lib for repository pod
 *
 * @package
 * @subpackage
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author     Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use repository_pod\manager\repository_pod_api_manager;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/repository/lib.php');
require_once($CFG->dirroot . '/repository/pod/locallib.php');


class repository_pod extends repository {

    public $cachelimit = 1;
    /**
     * @var pod     The instance of pod client.
     */
    protected $pod;

    public function get_listing($path = '', $page = 0) {
            return $this->get_listing_details($path, $page);
    }

    public function search($query, $page = 0) {
        return $this->get_listing_details('', $page, $query);
    }

    public function supported_filetypes() {
        return '*';
    }

    public function supported_returntypes() {
        return FILE_REFERENCE;
    }

    /**
     * Add Plugin settings input to Moodle form
     * @param object $mform
     */
    public static function instance_config_form($mform, $classname = 'repository') {
        global $CFG;
        parent::type_config_form($mform);

        $strrequired = get_string('required');

        $mform->addElement('checkbox', 'https', get_string('https', 'repository_pod'));

        $mform->addElement('text', 'pod_url', get_string('pod_url', 'repository_pod'),
                array('size' => '100'));
        $mform->addElement('static', null, '', get_string('pod_url_help', 'repository_pod'));
        $mform->setType('pod_url', PARAM_RAW_TRIMMED);

        $mform->addElement('text', 'pod_api_key', get_string('pod_api_key', 'repository_pod'),
                array('size' => '100'));
        $mform->setType('pod_api_key', PARAM_RAW_TRIMMED);
        $mform->addElement('static', null, '', get_string('pod_api_key_help', 'repository_pod'));

        $mform->addElement('text', 'extensions', get_string('extensions', 'repository_pod'));
        $mform->setType('extensions', PARAM_RAW_TRIMMED);
        $mform->addElement('static', null, '', get_string('extensions', 'repository_pod'));

        $mform->addElement('text', 'page_size', get_string('page_size', 'repository_pod'),
                array('size' => '200'));
        $mform->setType('page_size', PARAM_INT);
        $mform->addElement('static', null, '', get_string('page_size_help', 'repository_pod'));
        $qualitymodes = array(
            'lower' => get_string('lowerquality', 'repository_pod'),
            'best' => get_string('bestquality', 'repository_pod'),
            /* Will taken in charge in a future version
            'adaptative' => get_string('adaptativequality', 'repository_pod')*/
        );
        $mform->addElement('select', 'qualitymode', get_string('qualitymode', 'repository_pod'), $qualitymodes);
        $mform->addElement('static', null, '', get_string('qualitymode_help', 'repository_pod'));
        $mform->setType('qualitymode', PARAM_RAW_TRIMMED);
        $mform->addElement('checkbox', 'thumbnail',
            get_string('thumbnail_desc', 'repository_pod'),
            get_string('thumbnail', 'repository_pod')
        );
        $mform->setDefault('thumbnail', 0);
        // User id hook : check if hookfile exists.
        if (file_exists($CFG->dirroot.'/repository/pod/hooklib.php')) {
            $mform->addElement('checkbox', 'usernamehook', get_string('usernamehook', 'repository_pod'));
            $mform->setDefault('usernamehook', 0);
        }
        $mform->addRule('pod_url', $strrequired, 'required', null, 'client');
        $mform->addRule('pod_api_key', $strrequired, 'required', null, 'client');
        $mform->addRule('page_size', $strrequired, 'required', null, 'client');
        $mform->addRule('extensions', $strrequired, 'required', null, 'client');

    }

    public static function get_instance_option_names() {
        return array('pod_url', 'pod_api_key', 'page_size', 'https', 'extensions', 'qualitymode', 'thumbnail', 'usernamehook');
    }

    public function send_file($storedfile, $lifetime=86400 , $filter=0, $forcedownload=true, array $options = null) {
        global $CFG;
        require_once($CFG->dirroot.'/mod/resource/locallib.php');
        $podrestapimanager = new \repository_pod\manager\repository_pod_api_manager($this->options);
        $qualitymode = $this->options['qualitymode'];
        $podresourceid = $storedfile->get_reference();
        $mimetype = $storedfile->get_mimetype();
        $mediatype = explode('/', $mimetype)[0];
        $params = array(
            "format" => "json",
            "video" => $podresourceid,
            "extension" => explode('.', $storedfile->get_filename())[1]
        );
        $videourl = null;
        $results = $podrestapimanager->execute_request('/rest/encodings_'.$mediatype.'/'.$mediatype.'_encodedfiles/?', $params);
        if ($results) {
            if ($qualitymode == 'best') {
                $result = array_pop($results);
                $videourl = $result->source_file;
            } else {
                $result = array_shift($results);
                $videourl = $result->source_file;
            }
        }
        /*
         * Display results
         */
        if (!empty($videourl)) {
            header('Location: '.$videourl);
        } else {
            throw new repository_exception('podfilenotfound', 'repository', '', get_string('podfilenotfound', 'repository_pod'));
        }
    }

    public function get_listing_details($path, $page, $request='') {
        global $USER;
        if (isset($USER)) {
            $username = repository_pod_tools::moodle_username_to_pod_uid($this->options['usernamehook']);
        } else {
            require_login();
        }
        $params = array(
            "username" => $username,
            "format" => "json",
            "encoding_in_progress" => "False",
        );
        if (!empty($this->options['extensions'])) {
            $params['extensions'] = $this->options['extensions'];
        }
        if ($request) {
            $params["search__title"] = $request;
        }
        if (isset($page) && !empty($page)) {
            $params["page"] = $page;
        }
        $podrestapimanager = new repository_pod_api_manager($this->options);
        $result = $podrestapimanager->execute_request("/rest/videos/user_videos/?", $params);
        if (!$result) {
            throw new \moodle_exception(get_string("servererror", "repository_pod"));
        }
        if (is_array($result) && (count($result) == 4) && array_key_exists('results', $result) && count($result['results']) > 0) {
            $list = $podrestapimanager->get_all_encoded_files($result);
            if (!empty($request)) {
                $list['searchtext'] = $request;
            }
        } else {
            // No video found for current request.
            $list = array(
                'list' => array()
            );
        }
        return $list;
    }
}


function repository_pod_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $OUTPUT, $CFG;
    // Allowed filearea is either thumb or icon - size of the thumbnail.
    if ($filearea !== 'thumb' && $filearea !== 'icon') {
        return false;
    }

    // As itemid we pass repository instance id.
    $itemid = array_shift($args);
    // Filename is some token that we can ignore (used only to make sure browser does not serve cached copy when file is changed).
    array_pop($args);
    // As filepath we use full filepath (dir+name) of the file in this instance of filesystem repository.
    $filepath = implode('/', $args);

    // Make sure file exists in the repository and is accessible.
    $repo = repository::get_repository_by_id($itemid, $context);
    $repo->check_capability();
    // Find stored or generated thumbnail.
    if (!($file = $repo->get_thumbnail($filepath, $filearea))) {
        // Generation failed, redirect to default icon for file extension.
        redirect($OUTPUT->pix_icon(file_extension_icon($file, 90)));
    }
    // The thumbnails should not be changing much, but maybe the default lifetime is too long.
    $lifetime = $CFG->filelifetime;
    if ($lifetime > 60 * 10) {
        $lifetime = 60 * 10;
    }
    send_stored_file($file, $lifetime, 0, $forcedownload, $options);
}
