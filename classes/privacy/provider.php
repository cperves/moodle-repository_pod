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
 * Privacy Subsystem implementation for repository_flickr.
 *
 * @package    repository_flickr
 * @copyright  2018 Zig Tan <zig@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace repository_pod\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\context;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem for repository_pod implementing metadata, plugin, and user_preference providers.
 *
 * @copyright  2019 University of Strasbourg
 * @author Céline Pervès <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider
{

    /**
     * Returns meta data about this system.
     *
     * @param   collection $collection The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_external_location_link(
            'pod.server',
            [
                'id' => 'privacy:metadata:repository_pod:pod_server:id',
                'username'=> 'privacy:metadata:repository_pod:pod_server:owner_username',
                'owner'=> 'privacy:metadata:repository_pod:pod_server:ownerlist',
                'video'=> 'privacy:metadata:repository_pod:pod_server:video',
                'mediatype' => 'privacy:metadata:repository_pod:pod_server:encodingtype',
                'title' => 'privacy:metadata:repository_pod:pod_server:title',
            ],
            'privacy:metadata:repository_pod:pod_server'
        );
        $collection->add_subsystem_link('core_files', [], 'privacy:metadata:core_files');


        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param   int $userid The user to search.
     * @return  contextlist   $contextlist  The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        //module context but also user context because of draft et private
        $contextlist =  new contextlist();
        $sql = 'select f.contextid from {files} f inner join {files_reference} fr on fr.id=f.referencefileid inner join {repository_instances} ri on ri.id=fr.repositoryid inner join {repository} r on r.id=ri.typeid inner join {context} ctx on ctx.id=f.contextid where r.type=\'pod\' and f.userid=:userid';
        $params = [
                'userid' => $userid,
        ];
        $contextlist->add_from_sql($sql,$params);
        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param   userlist    $userlist   The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();
        if($context instanceof \context_module || $context instanceof \context_user){
            $sql = 'select f.userid from {files} f inner join {files_reference} fr on fr.id=f.referencefileid inner join {repository_instances} ri on ri.id=fr.repositoryid inner join {repository} r on r.id=ri.typeid where r.type=\'pod\' and f.contextid=:contextid';
            $params = [
                    'contextid' => $context->id
            ];
            $userlist->add_from_sql('userid', $sql, $params);
        }
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        //file entry with pod referenceid?

    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param   context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        //it is linked to a resource in a course
        //no seems to be necessary
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param   approved_userlist       $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
    }
}
