# repository pod : moodle repository for POD
repository pod is a moodle repository plugin that enable to create file resource linked by reference to an external POD server
* See https://github.com/EsupPortail/pod/wiki for POD server informations

## Features
* Enable to create video or audio file reference with a POD server filtering on current username
* search enabled
* paging enabled for listing and searching in filepicker
* overrride of rendrer to check resource existence on POD server
## Download
from moodle plugin repository

## Moodle Installation
### guzzle http
FIXME
allow_url_fopen in php.ini

### Repository installation
Install repository/pod in your own repository directory

#### Repository setting
fill plugin setting field :
* pod_url : POD webservice base url
* pod_api_key : POD webservice API key
* page_size : page size :used for pod webservice and then enabling paging on filepicker
* usernamehook : will only be available if a hookfile.php is located in repository/pod of your moodle installation
  * check this if you have to apply username hook
  * username hook enable you to map moodle username with pod uid
  * in hooklib.php define a repository_pod_moodle_uid_to_pod_uid function that will make a trnaformation from moodle user object to obtain corresponding pod uid
## filepicker paging patch for search
* apply patch included
```
patch -p1 /moodle_dirroot/repository/filepicker.js  < patch/repository_filepicker_js.patch
```

## Install patch to take external repository in charge
* by default moodle does not restore external repository in course to prevent file breaking
  * but you can enable this by installing following patch on your moodle version
```shell
patch -p1 /moodle_path/backup/moodle2/restore_stepslib.php < /moodlepath/patch/backup_moodle2_restore_stepslib.patch
```

## Contributions
Contributions of any form are welcome. Github pull requests are preferred.
Fill free to commit any bugs, improvements, or feature requiests in our [issue tracker][issues].

## Authors
* Pascal Mathelin
* Celine Perves
* Claude Yahou