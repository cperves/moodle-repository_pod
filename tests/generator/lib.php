<?php


/**
 * repository_pod data generator
 *
 * @package    repository_pod
 * @subpackage
 * @copyright  2017 Unistra {@link http://nistra.fr}
 * @author     Pascal Mathelin <pascal.mathelin@unistra.fr>
 * @author     Celine Perves <cperves@unistra.fr>
 * @author     Claude Yahou <claude.yahou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later *
 */


defined('MOODLE_INTERNAL') || die();

/**
 * repository_pod data generator class.
 *
 * @package    repository_pod
 * @category   test
 */
class repository_pod_generator extends testing_data_generator {

     public function create_pod_instance($pod, $fields,$course){
          //retrieve plugin to add instance
          $plugin = repository_get_plugin($pod);
          return $plugin->add_instance($course, $fields);
     }

}
