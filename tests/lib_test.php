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
 *  Pod tests.
 *
 * @package    repository_pod
 * @category   phpunit
 * @copyright  2017 Unistra {@link http://nistra.fr}
 * @author     Pascal Mathelin <pascal.mathelin@unistra.fr>
 * @author     Celine Perves <cperves@unistra.fr>
 * @author     Claude Yahou <claude.yahou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class repository_pod_lib_testcase extends advanced_testcase
{
    public function setUp()
    {
        set_config('page_size', '20', 'pod');
        // First video with only one encoding size
        $encodingtype1 = new stdClass();
        $encodingtype1->mediatype = "video";
        $encodingtype1->name = "480";
        $encodingtype1->output_height = 480;

        $podsencodingpod1 = new StdClass();
        $podsencodingpod1->encodingfile = "videos/test_user/2274baeed68844b520f46a40d1b2e621d71e7e4cbe173ede4efe6b9d8149f1a0/18936/video_18936_480.mp4";
        $podsencodingpod1->encodingformat = "video/mp4";
        $podsencodingpod1->encodingtype = $encodingtype1;
        $podsencodingpod1->video = 18936;

        $owner1 = new StdClass();
        $owner1->id = 570;
        $owner1->username = "test_user";

        $type1 = new StdClass();
        $type1->slug = "avc-video";
        $type1->title = "AVC video";

        $result1 = new StdClass();
        $result1->encoding_in_progress = false;
        $result1->encoding_status = "DONE at Mon Jun 12 11:50:13 2017";
        $result1->id = 18936;
        $result1->owner = $owner1;
        $result1->slug = "18936-test-video1";
        $result1->title = "Test pod Video1";
        $result1->to_encode = false;
        $result1->type = $type1;
        $result1->video = "videos/test_user/65d43d6cd56d90802bf5a688a5563cf0abc9fdf0a4251061ed539e9855586705/Video1.mp4";
        $result1->pod_media_url = "https://podcast-test.u-strasbg.fr/media/";
        $result1->podscontributorpods_set = array();
        $result1->podsencodingpods_set = array($podsencodingpod1);
        $result1->date_added = "2017-01-23";
        $result1->date_evt = "2017-01-23";
        $result1->duration = 10;

        // Second video with two encoding sizes
        $encodingtype21 = new stdClass();
        $encodingtype21->mediatype = "video";
        $encodingtype21->name = "720";
        $encodingtype21->output_height = 720;

        $podsencodingpod21 = new StdClass();
        $podsencodingpod21->encodingfile = "videos/test_user/a28bab64c520b0b8043030d6bf797b1b3d5d87609c67c8154840ceb5734b3246/18957/video_18957_720.mp4";
        $podsencodingpod21->encodingformat = "video/mp4";
        $podsencodingpod21->encodingtype = $encodingtype21;
        $podsencodingpod21->video = 18957;

        $encodingtype22 = new stdClass();
        $encodingtype22->mediatype = "video";
        $encodingtype22->name = "480";
        $encodingtype22->output_height = 480;

        $podsencodingpod22 = new StdClass();
        $podsencodingpod22->encodingfile = "videos/test_user/a28bab64c520b0b8043030d6bf797b1b3d5d87609c67c8154840ceb5734b3246/18957/video_18957_480.mp4";
        $podsencodingpod22->encodingformat = "video/mp4";
        $podsencodingpod22->encodingtype = $encodingtype22;
        $podsencodingpod22->video = 18957;

        $owner2 = new StdClass();
        $owner2->id = 570;
        $owner2->username = "test_user";

        $type2 = new StdClass();
        $type2->slug = "avc-video";
        $type2->title = "AVC video";

        $result2 = new StdClass();
        $result2->encoding_in_progress = false;
        $result2->encoding_status = "DONE at Mon Jun 19 15:03:50 2017";
        $result2->id = 18957;
        $result2->owner = $owner2;
        $result2->slug = "18957-test-video2";
        $result2->title = "Test pod Video2";
        $result2->to_encode = false;
        $result2->type = $type2;
        $result2->video = "videos/test_user/65d43d6cd56d90802bf5a688a5563cf0abc9fdf0a4251061ed539e9855586705/Video2.mp4";
        $result2->pod_media_url = "https://podcast-test.u-strasbg.fr/media/";
        $result2->podscontributorpods_set = array();
        $result2->podsencodingpods_set = array($podsencodingpod21, $podsencodingpod22);
        $result2->date_added = "2017-01-23";
        $result2->date_evt = "2017-01-23";
        $result2->duration = 20;
        
        // Third video for further test of pagination
        $encodingtype3 = new stdClass();
        $encodingtype3->mediatype = "video";
        $encodingtype3->name = "240";
        $encodingtype3->output_height = 240;

        $podsencodingpod3 = new StdClass();
        $podsencodingpod3->encodingfile = "videos/test_user/5b4b22f0bf0753a360b8b56f71b494bc08aa968abe2978ccf1854cb80a2c3dec/18937/video_18937_240.mp4";
        $podsencodingpod3->encodingformat = "video/mp4";
        $podsencodingpod3->encodingtype = $encodingtype3;
        $podsencodingpod3->video = 18937;

        $owner3 = new StdClass();
        $owner3->id = 570;
        $owner3->username = "test_user";

        $type3 = new StdClass();
        $type3->slug = "avc-video";
        $type3->title = "AVC video";

        $result3 = new StdClass();
        $result3->encoding_in_progress = false;
        $result3->encoding_status = "DONE at Mon Jul  3 16:22:37 2017";
        $result3->id = 18937;
        $result3->owner = $owner3;
        $result3->slug = "18937-test-video3";
        $result3->title = "Test pod Video3";
        $result3->to_encode = false;
        $result3->type = $type3;
        $result3->video = "videos/test_user/65d43d6cd56d90802bf5a688a5563cf0abc9fdf0a4251061ed539e9855586705/Video3.mp4";
        $result3->pod_media_url = "https://podcast-test.u-strasbg.fr/media/";
        $result3->podscontributorpods_set = array();
        $result3->podsencodingpods_set = array($podsencodingpod3);
        $result3->date_added = "2017-01-23";
        $result3->date_evt = "2017-01-23";
        $result3->duration = 30;

        $this->resultarray = array(
            "page" => 1,
            "results" => array($result1, $result2, $result3),
            "pages" => 1,
            "total" => 3
        );


        $this->emptyresultarray = array(
            "page" => 1,
            "results" => array(),
            "pages" => 1,
            "total" => 0
        );

        /*
         * Exemple of data returned by get_listing
        $resultdatafrompod = <<<'EOT'
{"page":1,
 "results":[            {"encoding_in_progress":false,
             "encoding_status": "DONE at Mon Jun 12 11:50:13 2017",
             "id":18936,
             "owner":{"id":570,"username":"yahou"},
             "slug":"18936-test-video1",
             "title":"Test pod Video1",
             "to_encode":false,
             "type":{"slug":"avc-video","title":"AVC video"},
             "video":"videos\/yahou\/65d43d6cd56d90802bf5a688a5563cf0abc9fdf0a4251061ed539e9855586705\/Video1.mp4",
             "pod_media_url":"https:\/\/podcast-test.u-strasbg.fr\/media\/",
             "podscontributorpods_set":[],
             "podsencodingpods_set":[{"encodingfile":"videos\/yahou\/2274baeed68844b520f46a40d1b2e621d71e7e4cbe173ede4efe6b9d8149f1a0\/18936\/video_18936_480.mp4",
                                      "encodingformat":"video\/mp4",
                                      "encodingtype":{"mediatype":"video",
                                                      "name":"480",
                                                      "output_height":480},
                                      "video":18936
                                     }
                                     ]
            },
            {"encoding_in_progress":false,
             "encoding_status":"DONE at Mon Jun 19 15:03:50 2017",
             "id":18957,"owner":{"id":570,"username":"yahou"},
             "slug":"18957-test-video2",
             "title":"Test pod Video2",
             "to_encode":false,
             "type":{"slug":"avc-video","title":"AVC video"},
             "video":"videos\/yahou\/65d43d6cd56d90802bf5a688a5563cf0abc9fdf0a4251061ed539e9855586705\/Video2.mp4",
             "pod_media_url":"https:\/\/podcast-test.u-strasbg.fr\/media\/",
             "podscontributorpods_set":[],
             "podsencodingpods_set":[{"encodingfile":"videos\/yahou\/a28bab64c520b0b8043030d6bf797b1b3d5d87609c67c8154840ceb5734b3246\/18957\/video_18957_720.mp4",
                                      "encodingformat":"video\/mp4",
                                      "encodingtype":{"mediatype":"video","name":"720","output_height":720},
                                      "video":18957
                                     },
                                     {"encodingfile":"videos\/yahou\/a28bab64c520b0b8043030d6bf797b1b3d5d87609c67c8154840ceb5734b3246\/18957\/video_18957_480.mp4",
                                      "encodingformat":"video\/mp4",
                                      "encodingtype":{"mediatype":"video","name":"480","output_height":480},
                                      "video":18957
                                     }
                                    ]
             },
            {"encoding_in_progress":false,
              "encoding_status":"DONE at Mon Jul  3 16:22:37 2017",
              "id":18937,"owner":{"id":570,"username":"yahou"},
              "slug":"18937-test-video3",
              "title":"Test pod Video3",
              "to_encode":false,
              "type":{"slug":"avc-video","title":"AVC video"},
              "video":"videos\/yahou\/65d43d6cd56d90802bf5a688a5563cf0abc9fdf0a4251061ed539e9855586705\/Video3.mp4",
              "pod_media_url":"https:\/\/podcast-test.u-strasbg.fr\/media\/",
              "podscontributorpods_set":[],
              "podsencodingpods_set":[{"encodingfile":"videos\/yahou\/5b4b22f0bf0753a360b8b56f71b494bc08aa968abe2978ccf1854cb80a2c3dec\/18937\/video_18937_240.mp4",
                                       "encodingformat":"video\/mp4",
                                       "encodingtype":{"mediatype":"video","name":"240","output_height":240},
                                       "video":18937
                                       }
                                      ]
             }
             ],
 "pages":1,
 "total":3
}
EOT;
        */
    }

    public function tearDown()
    {
    }

    //TODO à finir bonne idée
    public function test_pod_parameters_filled()
    {
        $this->resetAfterTest();
        /*
        print("  >>>> test_pod_parameters_filled() -- start\n");
        $this->resetAfterTest(false);
        $this->assertNotNull(get_config("pod", 'spore_description_file_url'), 'spore description file url setting null');
        $this->assertNotEmpty(get_config("pod", 'spore_description_file_url'), 'spore description file url setting empty');
        $this->assertNotNull(get_config("pod", 'spore_base_url'), 'spore description file url setting null');
        $this->assertNotEmpty(get_config("pod", 'spore_base_url'), 'spore description file url setting empty');
        $this->assertNotNull(get_config("pod", 'spore_token'), 'spore description file url setting null');
        $this->assertNotEmpty(get_config("pod", 'spore_token'), 'spore description file url setting empty');
        $this->assertNotNull(get_config("pod", 'media_server_url'), 'spore description file url setting null');
        $this->assertNotEmpty(get_config("pod", 'media_server_url'), 'spore description file url setting empty');
        $this->assertNotNull(get_config("pod", 'page_size'), 'spore description file url setting null');
        $this->assertNotEmpty(get_config("pod", 'page_size'), 'spore description file url setting empty');
        print("  <<<< test_pod_parameters_end() -- start\n");
        */
    }

    /**
     * TODO doc
     */
    public function test_function_get_all_encoded_files_with_normal_result()
    {
        global $CFG, $OUTPUT;

        $this->resetAfterTest();
        require_once($CFG->dirroot . '/repository/pod/lib.php');

        $encodedfiles = repository_pod_tools::get_all_encoded_files($this->resultarray);
        $this->assertEquals($encodedfiles,
            array(
                'total' => 3,
                'pages' => 1,
                'perpage' => "20",
                'page' => 1,
                'norefresh' => true,
                'list' => array(
                    array(
                        'title' => "Test pod Video1.mp4",
                        'url' => "videos/test_user/2274baeed68844b520f46a40d1b2e621d71e7e4cbe173ede4efe6b9d8149f1a0/18936/video_18936_480.mp4",
                        'source' => 18936,
                        'encodingfile' => "videos/test_user/2274baeed68844b520f46a40d1b2e621d71e7e4cbe173ede4efe6b9d8149f1a0/18936/video_18936_480.mp4",
                        'datecreated' => strtotime("2017-01-23"),
                        'datemodified' => strtotime("2017-01-23"),
                        'size' => null,
                        'author' => 'test_user',
                        'license' => '-',
                        'thumbnail' => $OUTPUT->image_url(file_extension_icon("videos/test_user/2274baeed68844b520f46a40d1b2e621d71e7e4cbe173ede4efe6b9d8149f1a0/18936/video_18936_480.mp4", 24),'')->out(false)
                    ),
                    array(
                        'title' => "Test pod Video2.mp4",
                        'url' => "videos/test_user/a28bab64c520b0b8043030d6bf797b1b3d5d87609c67c8154840ceb5734b3246/18957/video_18957_720.mp4",
                        'source' => 18957,
                        'encodingfile' => "videos/test_user/a28bab64c520b0b8043030d6bf797b1b3d5d87609c67c8154840ceb5734b3246/18957/video_18957_720.mp4",
                        'datecreated' => strtotime("2017-01-23"),
                        'datemodified' => strtotime("2017-01-23"),
                        'size' => null,
                        'author' => 'test_user',
                        'license' => '-',
                        'thumbnail' => $OUTPUT->image_url(file_extension_icon("videos/test_user/a28bab64c520b0b8043030d6bf797b1b3d5d87609c67c8154840ceb5734b3246/18957/video_18957_720.mp4", 24),'')->out(false)
                    ),
                    array(
                        'title' => "Test pod Video3.mp4",
                        'url' => "videos/test_user/5b4b22f0bf0753a360b8b56f71b494bc08aa968abe2978ccf1854cb80a2c3dec/18937/video_18937_240.mp4",
                        'source' => 18937,
                        'encodingfile' => "videos/test_user/5b4b22f0bf0753a360b8b56f71b494bc08aa968abe2978ccf1854cb80a2c3dec/18937/video_18937_240.mp4",
                        'datecreated' => strtotime("2017-01-23"),
                        'datemodified' => strtotime("2017-01-23"),
                        'size' => null,
                        'author' => 'test_user',
                        'license' => '-',
                        'thumbnail' => $OUTPUT->image_url(file_extension_icon("videos/test_user/5b4b22f0bf0753a360b8b56f71b494bc08aa968abe2978ccf1854cb80a2c3dec/18937/video_18937_240.mp4", 24),'')->out(false)
                    ),
                )
            )
        );
    }

    /**
     * 
     */
    public function test_function_get_all_encoded_files_with_no_result_params()
    {
        global $CFG;

        $this->resetAfterTest();

        $encodedfiles = repository_pod_tools::get_all_encoded_files($this->emptyresultarray);
        $this->assertEquals($encodedfiles,
            array(
                'total' => 0,
                'pages' => 1,
                'perpage' => "20",
                'page' => 1,
                'norefresh' => true,
                'list' => array()
            )
        );
    }
    /**
     * result with no encodingset is evinced
     */
    public function test_function_get_all_encoded_files_with_missing_encodingpodset()
    {
        global $CFG;

        $this->resetAfterTest();
        $this->resultarray["results"][1]->podsencodingpods_set = array();
        $results = repository_pod_tools::get_all_encoded_files($this->resultarray);
        $this->assertArrayNotHasKey(1, $results);
    }

    /**
     * result with no mediatype recognize is evinced
     */
    public function test_function_get_all_encoded_files_with_unknown_mediatype()
    {
        global $CFG;

        $this->resetAfterTest();
        $this->resultarray["results"][1]->podsencodingpods_set[1]->encodingtype->mediatype = "other";
        $results = repository_pod_tools::get_all_encoded_files($this->resultarray);
        $this->assertArrayNotHasKey(1, $results);
    }
}

