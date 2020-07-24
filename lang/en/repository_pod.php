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

$string['pluginname']   = 'Pod';
$string['configplugin'] = 'Pod';
$string['spore_description_file_url'] = 'Pod description file URL';
$string['spore_description_file_url_help'] = 'e.g. https://pod-ws-test.u-strasbg.fr/site_media/description.json';
$string['spore_base_url'] = 'Pod base URL';
$string['spore_base_url_help'] = 'e.g : https://pod-ws-test.u-strasbg.fr';
$string['spore_token'] = 'Pod Token';
$string['spore_token_help'] = 'Pod web service token';
$string['media_server_url'] = 'pod media server Url';
$string['media_server_url_help'] = 'e.g https://podcast-test.u-strasbg.fr/media/';
$string['page_size'] = 'Pod page size';
$string['page_size_help'] = 'Number of items per page in file picker';
$string['pod:view'] = 'View Pod repository';
$string['errorphpsporeclient'] = 'Can\'t get PHP spore client for pod';
$string['missingpodid'] = 'Not existing resource found in pod. Maybe your pod resource has been deleted';
$string['servernotreponding'] = 'Pod server is not responding, please try later.';
$string['poderrorphpsporeclient'] = 'pod error : spore client error';
$string['servererror']='Pod server is not responding, please try later';
$string['podlicenceinformationunavailable']='-';
$string['privacy:metadata:repository_pod:pod_server:id']='pod resource id';
$string['privacy:metadata:repository_pod:pod_server:owner_username']='owner username coresponding to current username';
$string['privacy:metadata:repository_pod:pod_server:ownerlist']='pod owner list for a given resource';
$string['privacy:metadata:repository_pod:pod_server:video']='pod video relative path';
$string['privacy:metadata:repository_pod:pod_server:encodingtype']='pod internal encoding type code';
$string['privacy:metadata:repository_pod:pod_server:title']='pod resource title';
$string['privacy:metadata:repository_pod:pod_server']='The repository pod retrieve pod datas to store resources and show them.';
$string['privacy:metadata:core_files']='The repository pod moodle store pod files informations as moodle file.';