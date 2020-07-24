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
 * Folder plugin version information
 *
 * @package
 * @subpackage
 * @copyright  2017 unistra  {@link http://unistra.fr}
 * @author      Pascal Mathelin <pascal.mathelin@unistra.fr>
 * @author      Celine Perves <cperves@unistra.fr>
 * @author      Claude Yahou <claude.yahou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/repository/lib.php');


class repository_pod extends repository {

    public $cachelimit = 1;
    /**
     * @var pod     The instance of pod client.
     */
    protected $pod;

    public function __construct($repositoryid, $context = SYSCONTEXTID, $options = array()) {
        global $SESSION, $CFG;
        $this->pod = new repository_pod\pod();
        parent::__construct($repositoryid, $context, $options);
    }

    public function get_listing($path = '', $page = 0) {
            return $this->pod->get_listing($path, $page);
    }

    public function search($query, $page = 0) {
        return $this->pod->get_listing('', $page, $query);
    }

    public function supported_filetypes() {
        return '*';
    }

    public function max_cache_bytes() {
        return $this->cachelimit;
    }

    public function supported_returntypes() {
        return FILE_REFERENCE;
    }

    public function send_file($storedfile, $lifetime=86400 , $filter=0, $forcedownload=true, array $options = null) {
        global $PAGE, $DB, $CFG;
        require_once($CFG->dirroot.'/mod/resource/locallib.php');

        /*
         * Get spore results
         */
        $repositorypodtools = new repository_pod_tools();

        $podresourceid = $storedfile->get_reference();
        $params = array(
            "id" => $podresourceid,
            "format" => "json",
            "to_encode" => "False",
            "encoding_in_progress" => "False"
        );
        try {
            $client = $repositorypodtools->get_client();
        } catch (Exception $e) {
            throw new \moodle_exception(get_string("poderrorphpsporeclient", "repository_pod"));
        }
        $result = $repositorypodtools->execute_request($client, "get_pods", $params);
        $list = $repositorypodtools->get_all_encoded_files($result);
        if (count($list['list']) == 0) {
            print_error('missingpodid', 'repository_pod');
        }
        /*
         * Display results
         */
        $encodingfile = $list['list'][0]['encodingfile'];
        $url = get_config('pod', 'media_server_url').$encodingfile;
        $moodleurl = new moodle_url($url);
        $modname = $PAGE->cm->modname;
        header('Location: '.$url);
    }
    /**
     *
     * @param array $options
     * @return mixed
     */
    public function set_option($options = array()) {
        if (!empty($options['spore_description_file_url'])) {
            set_config('spore_description_file_url', trim($options['spore_description_file_url']), 'pod');
        }
        if (!empty($options['spore_base_url'])) {
            set_config('spore_base_url', trim($options['spore_base_url']), 'pod');
        }
        if (!empty($options['spore_token'])) {
            set_config('spore_token', trim($options['spore_token']), 'pod');
        }
        if (!empty($options['media_server_url'])) {
            set_config('media_server_url', trim($options['media_server_url']), 'pod');
        }
        if (!empty($options['page_size'])) {
            set_config('page_size', trim($options['page_size']), 'pod');
        }
        unset($options['spore_description_file_url']);
        unset($options['spore_base_url']);
        unset($options['spore_token']);
        unset($options['media_server_url']);
        unset($options['page_size']);
        $ret = parent::set_option($options);
        return $ret;
    }

    /**
     *
     * @param string $config
     * @return mixed
     */
    public function get_option($config = '') {
        if ($config === 'spore_description_file_url') {
            return trim(get_config('pod', 'spore_description_file_url'));
        } else if ($config === 'spore_base_url') {
            return trim(get_config('pod', 'spore_base_url'));
        } else if ($config === 'spore_token') {
            return trim(get_config('pod', 'spore_token'));
        } else if ($config === 'media_server_url') {
            return trim(get_config('pod', 'media_server_url'));
        } else if ($config === 'page_size') {
            return trim(get_config('pod', 'page_size'));
        } else {
            $options['spore_description_file_url'] = trim(get_config('pod', 'spore_description_file_url'));
            $options['spore_base_url']             = trim(get_config('pod', 'spore_base_url'));
            $options['spore_token']                = trim(get_config('pod', 'spore_token'));
            $options['media_server_url']           = trim(get_config('pod', 'media_server_url'));
            $options['page_size']                  = trim(get_config('pod', 'page_size'));
        }
        $options = parent::get_option($config);
        return $options;
    }
    /**
     * Add Plugin settings input to Moodle form
     * @param object $mform
     */
    public static function type_config_form($mform, $classname = 'repository') {
        $sporedescriptionfileurl = get_config('pod', 'spore_description_file_url');
        $sporebaseurl = get_config('pod', 'spore_base_url');
        $sporetoken = get_config('pod', 'spore_token');
        $mediaserverurl = get_config('pod', 'media_server_url');
        $pagesize = get_config('pod', 'page_size');

        if (empty($sporedescriptionfileurl)) {
            $sporedescriptionfileurl = '';
        }
        if (empty($sporebaseurl)) {
            $sporebaseurl = '';
        }
        if (empty($sporetoken)) {
            $sporetoken = '';
        }
        if (empty($mediaserverurl)) {
            $mediaserverurl = '';
        }
        if (empty($pagesize)) {
            $pagesize = '';
        }

        parent::type_config_form($mform);

        $strrequired = get_string('required');

        $mform->addElement('text', 'spore_description_file_url', get_string('spore_description_file_url', 'repository_pod'),
                array('value' => $sporedescriptionfileurl, 'size' => '100'));
        $mform->addElement('static', null, '', get_string('spore_description_file_url_help', 'repository_pod'));

        $mform->setType('spore_description_file_url', PARAM_RAW_TRIMMED);
        $mform->addElement('text', 'spore_base_url', get_string('spore_base_url', 'repository_pod'),
                array('value' => $sporebaseurl, 'size' => '100'));
        $mform->addElement('static', null, '', get_string('spore_base_url_help', 'repository_pod'));
        $mform->setType('spore_base_url', PARAM_RAW_TRIMMED);

        $mform->addElement('text', 'spore_token', get_string('spore_token', 'repository_pod'),
                array('value' => $sporetoken, 'size' => '100'));
        $mform->setType('spore_token', PARAM_RAW_TRIMMED);
        $mform->addElement('static', null, '', get_string('spore_token_help', 'repository_pod'));

        $mform->addElement('text', 'media_server_url', get_string('media_server_url', 'repository_pod'),
                array('value' => $mediaserverurl, 'size' => '200'));
        $mform->setType('media_server_url', PARAM_RAW_TRIMMED);
        $mform->addElement('static', null, '', get_string('media_server_url_help', 'repository_pod'));

        $mform->addElement('text', 'page_size', get_string('page_size', 'repository_pod'),
                array('value' => $pagesize, 'size' => '200'));
        $mform->setType('page_size', PARAM_INT);
        $mform->addElement('static', null, '', get_string('page_size_help', 'repository_pod'));

        $mform->addRule('spore_description_file_url', $strrequired, 'required', null, 'client');
        $mform->addRule('spore_base_url', $strrequired, 'required', null, 'client');
        $mform->addRule('spore_token', $strrequired, 'required', null, 'client');
        $mform->addRule('media_server_url', $strrequired, 'required', null, 'client');
        $mform->addRule('page_size', $strrequired, 'required', null, 'client');
    }
    /**
     * Names of the plugin settings
     * @return array
     */
    public static function get_type_option_names() {
        return array('spore_description_file_url', 'spore_base_url', 'spore_token', 'media_server_url', 'page_size');
    }
}




class repository_pod_tools {
    const POD_NOTPOD = -2;
    const POD_EXISTS = 1;
    const POD_NOTEXISTS = 0;
    const POD_SERVERKO = -1;

    const MEDIATYPE_AUDIO = "audio";
    const MEDIATYPE_VIDEO = "video";

    static public function get_client() {
        global $CFG;
        require_once($CFG->dirroot.'/local/spore/src/MyMiddleware.php');
        require_once($CFG->dirroot.'/local/spore/src/Spore.php');

        $sporedescriptionfileurl = get_config('pod', 'spore_description_file_url');
        $sporebaseurl = get_config('pod', 'spore_base_url');
        $sporetoken = get_config('pod', 'spore_token');
        try {

            $client = new \Spore($sporedescriptionfileurl);
        } catch (Exception $e) {
            return false;
        }

        $client->setBaseUrl($sporebaseurl);
        $client->enable("Spore_Middleware_Authentication", array("authorization" => "Token $sporetoken"));
        return $client;
    }
    static public function execute_request($client, $func, $params) {
        /*
        * Needed for Php spore Http query string construction, because Moodle has modified default value in setup.php
        */
        try {
            $oldqueryseparator = ini_get('arg_separator.output');
            ini_set('arg_separator.output', '&');
            $result = self::execute_raw_request($client, $func, $params);
            ini_set('arg_separator.output', $oldqueryseparator);
        } catch (Exception $e) {
            ini_set('arg_separator.output', $oldqueryseparator);
            throw $e;
        }
        return $result;
    }
    static public function execute_raw_request($client, $func, $params) {
        global $CFG;
        require_once($CFG->dirroot.'/local/spore/src/MyMiddleware.php');
        require_once($CFG->dirroot.'/local/spore/src/Spore.php');
        if (!is_array($params)) {
            return false;
        }
        $res = array();

        $result = $client->$func($params);

        if (!isset($result->status) || $result->status == "404") {
            return false;
        }

        if (isset($result->body->results)) {
            foreach ($result->body->results as $data) {
                array_push($res, $data);
            }
            $page = isset($params['page']) ? $params['page'] : 1;
            return array('page' => $page, 'results' => $res, 'pages' => $result->body->num_pages, 'total' => $result->body->count);
        } else {
            return false;
        }
    }
    static public function get_all_encoded_files($resultarray) {
        global $OUTPUT;
        // Get all encoded files for one username.
        $mediaserverurl = get_config('pod', 'media_server_url');
        $list = array();
        $list['total'] = $resultarray['total'];
        $list['pages'] = $resultarray['pages'];
        $list['perpage'] = get_config('pod', 'page_size');
        $list['page'] = $resultarray['page'];
        $list['norefresh'] = true;
        $result = $resultarray['results'];
        if (count($result) > 0) {
            $list['list'] = array();
            for ($i = 0; $i < count($result); $i++) {
                $podsencodingpodsset = $result[$i]->podsencodingpods_set;
                if (count($podsencodingpodsset) == 0) {
                    // Video with missing encoding.
                    continue;
                } else if (count($podsencodingpodsset) == 1) {
                    // Videos with only one encoding.
                    $encodingfile = $podsencodingpodsset[0]->encodingfile;
                    $mediatype = $podsencodingpodsset[0]->encodingtype->mediatype;
                    if (! in_array($mediatype, array(self::MEDIATYPE_AUDIO, self::MEDIATYPE_VIDEO))) {
                        continue;
                    }
                } else {
                    // Videos with many encoding (select only highest resolution).
                    $outputheightmax = null;
                    $outputheightmaxindex = null;
                    $mediatypevideofound = false;
                    for ($j = 0; $j < count($podsencodingpodsset); $j++) {
                        $mediatype = $podsencodingpodsset[$j]->encodingtype->mediatype;
                        $outputheight = $podsencodingpodsset[$j]->encodingtype->output_height;
                        if ($mediatype == self::MEDIATYPE_VIDEO) {
                            // Video encoding.
                            if (($outputheight == null) || ($outputheight > $outputheightmax)) {
                                $outputheightmax = $outputheight;
                                $outputheightmaxindex = $j;
                            }
                            $mediatypevideofound = true;
                        } else if ($mediatype == self::MEDIATYPE_AUDIO) {
                            // Audio encoding.
                            if (!$mediatypevideofound) {
                                $outputheightmaxindex = $j;
                            }
                        } else {
                            continue;
                        }
                    }
                    $encodingfile = $podsencodingpodsset[$outputheightmaxindex]->encodingfile;
                    $mediatype = $podsencodingpodsset[$outputheightmaxindex]->encodingtype->mediatype;
                }
                $podcourseid = $result[$i]->id;
                $title = $result[$i]->title;
                $url    = $mediaserverurl.$encodingfile;
                $source = $podcourseid;
                $author = $result[$i]->owner->username;
                $datemodified = strtotime($result[$i]->date_added);
                $datecreated = $result[$i]->date_evt;
                if ($datecreated === null) {
                    $datecreated = $datemodified;
                } else {
                    $datecreated = strtotime($datecreated);
                }
                $duration = $result[$i]->duration;
                if ($mediatype == self::MEDIATYPE_VIDEO) {
                    $extension = '.mp4';
                } else if ($mediatype == self::MEDIATYPE_AUDIO) {
                    $extension = '.mp3';
                }
                $license = get_string("podlicenceinformationunavailable", "repository_pod");
                $list['list'][$i] = array(
                    'title' => $title.$extension,
                    'url' => $url,
                    'source' => $source,
                    'encodingfile' => $encodingfile,
                    'datecreated' => $datecreated,
                    'datemodified' => $datemodified,
                    'size' => null,
                    'author' => $author,
                    'license' => $license
                );
                try {
                    if (property_exists($result[$i],"thumbnail")) {
                        $thumbnailfile = $result[$i]->thumbnail->file_ptr->file;
                        $list["list"][$i]["thumbnail"] = $mediaserverurl.$thumbnailfile;
                    } else {
                        $list["list"][$i]["thumbnail"] = $OUTPUT->image_url(file_extension_icon($url, 24))->out(false);
                    }
                } catch (Exception $e) {
                    $list["list"][$i]["thumbnail"] = $OUTPUT->image_url(file_extension_icon($url, 24))->out(false);
                }
            }
            
        } else {
            $list['list'] = array();
        }
        return $list;
    }
    /**
     * check if the current resource is pod type and exists on pod
     * @param $ctxid module contextid
     * @throws \moodle_exception
     * @return boolean 2 if not a pod resource,if pod and exist on pod return 1, 0 if pod and not exist on pod ,-1 pod not respond
     */
    public static function check_resource_exists_from_contextid($ctxid) {
        global $DB, $CFG;
        $sql = "SELECT r.type,f.source
                FROM mdl_files f INNER JOIN {files_reference} fr ON fr.id=f.referencefileid
                INNER JOIN {repository_instances} ri  on fr.repositoryid=ri.id INNER JOIN {repository} r on r.id=ri.typeid 
                WHERE f.contextid=:ctxid  AND r.type=:type AND f.component=:component AND f.filearea=:filearea";
        $record = $DB->get_record_sql($sql,
                array('ctxid' => $ctxid, 'type' => 'pod', 'component' => 'mod_resource', 'filearea' => 'content'));
        if ($record && $record->type == 'pod') {
            // Check pod resource exists.
            $repositorypodtools = new repository_pod_tools();
            $podresourceid = $record->source;
            $params = array(
                    "id" => $podresourceid,
                    "format" => "json",
                    "to_encode" => "False",
                    "encoding_in_progress" => "False"
            );
            try {
                $client = $repositorypodtools->get_client();
            } catch (Exception $e) {
                // Pod communication cut return false.
                return self::POD_SERVERKO;
            }
            $result = $repositorypodtools->execute_request($client, "get_pods", $params);
            if (! $result) {
                return self::POD_SERVERKO;
            }
            $list = $repositorypodtools->get_all_encoded_files($result);
            if (count($list['list']) > 0) {
                return self::POD_EXISTS;
            } else {
                return self::POD_NOTEXISTS;
            }
        }
        return self::POD_NOTPOD;
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
