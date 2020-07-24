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
 * @package  Pod API
 * @subpackage
 * @copyright  2017 unistra  {@link http://unistra.fr}
 * @author      Pascal Mathelin <pascal.mathelin@unistra.fr>
 * @author      Celine Perves <cperves@unistra.fr>
 * @author      Claude Yahou <claude.yahou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace repository_pod;

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/repository/pod/lib.php');

class pod {
    protected $username = null;

    public function __construct() {
        global $USER;
        if (isset($USER)) {
            $this->username = $USER->username;
        } else {
            require_login();
        }
    }

    public function get_listing($path, $page, $request='') {
        global $CFG;

        $params = array(
            "owner__username" => $this->username,
            "format" => "json",
            "to_encode" => "False",
            "encoding_in_progress" => "False",
            "page_size" => get_config('pod', 'page_size')
        );
        if ($request) {
            $params["title__icontains"] = $request;
        }
        if (isset($page) && !empty($page)) {
            $params["page"] = $page;
        }
        $repositorypodtools = new \repository_pod_tools();
        try {
            $client = $repositorypodtools->get_client();
        } catch (Exception $e) {
            throw new \moodle_exception(get_string("poderrorphpsporeclient", "repository_pod"));
        }
        $result = $repositorypodtools->execute_request($client, "get_pods", $params);
        if (!$result) {
            throw new \moodle_exception(get_string("servererror", "repository_pod"));
        }
        if (is_array($result) && (count($result) == 4) && array_key_exists('results', $result) && count($result['results']) > 0) {
            $list = $repositorypodtools->get_all_encoded_files($result);
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