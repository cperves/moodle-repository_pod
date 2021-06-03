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
 * lang file for repository pod
 *
 * @package repository pod
 * @copyright  2020 unistra  {@link http://unistra.fr}
 * @author     Pascal Mathelin <pascal.mathelin@unistra.fr>
 * @author     Celine Perves <cperves@unistra.fr>
 * @author     Claude Yahou <claude.yahou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname']   = 'Pod';
$string['configplugin'] = 'Pod';
$string['pod_url'] = 'Pod base URL';
$string['pod_url_help'] = 'e.g : https://pod-ws-test.u-strasbg.fr';
$string['pod_api_key'] = 'Pod API key';
$string['pod_api_key_help'] = 'Pod web service API key';
$string['page_size'] = 'Pod page size';
$string['page_size_help'] = 'Number of items per page in file picker';
$string['pod:view'] = 'View Pod repository';
$string['missingpodid'] = 'Not existing resource found in pod. Maybe your pod resource has been deleted';
$string['servernotreponding'] = 'Pod server is not responding, please try later.';
$string['servererror']='Pod server is not responding, please try later';
$string['podlicenceinformationunavailable']='-';
$string['privacy:metadata:repository_pod:pod_server:id']='pod resource id';
$string['privacy:metadata:repository_pod:pod_server:owner_username']='owner username coresponding to current username. In some moodle installation the username can be a transformation or an other id that username (uid for example)';
$string['privacy:metadata:repository_pod:pod_server:ownerlist']='pod owner list for a given resource';
$string['privacy:metadata:repository_pod:pod_server:video']='pod video relative path';
$string['privacy:metadata:repository_pod:pod_server:encodingtype']='pod internal encoding type code';
$string['privacy:metadata:repository_pod:pod_server:title']='pod resource title';
$string['privacy:metadata:repository_pod:pod_server']='The repository pod retrieve pod datas to store resources and show them.';
$string['privacy:metadata:core_files']='The repository pod moodle store pod files informations as moodle file.';
$string['qualitymode'] = 'Encoding file quality';
$string['qualitymode_help'] = 'Encoding file quality choice to read in moodle player';
$string['lowquality'] = 'Lower video quality';
$string['bestquality'] = 'Best video quality';
$string['adaptativequality'] = 'Best video quality';
$string['extensions'] = 'Accepted file extensions';
$string['extensions_help'] = 'Choose the file extension accpeted for endoded pod videos or audio. Separated by commas.';
$string['lowerquality'] = 'Lower quality';
$string['bestquality'] = 'Best quality';
$string['https'] = 'Https protocol';
$string['thumbnail'] = 'Default : No, display Thumbnails / Yes, display Extensions';
$string['thumbnail_desc'] = 'Extension mode';
$string['usernamehook'] = 'Enable username hook. moodle user username will be replaced thanks to a function defined in a hooklib.php file, locate din repositpry/pod directory.';