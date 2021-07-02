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


class repository_pod_tools {

    public static function moodle_username_to_pod_uid($isusernamehookactivated) {
        global $USER, $CFG;
        // User id hook : check if hookfile exists.
        if ($isusernamehookactivated && file_exists($CFG->dirroot.'/repository/pod/hooklib.php')) {
            require_once($CFG->dirroot.'/repository/pod/hooklib.php');
            return repository_pod_moodle_uid_to_pod_uid();
        }
        return $USER->username;
    }

}