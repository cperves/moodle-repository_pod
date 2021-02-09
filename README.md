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
* pod/pod_url : POD webservice base url
* pod/pod_api_key : POD webservice API key
* pod/page_size : page size :used for pod webservice and then enabling paging on filepicker

## filepicker paging patch for search
* apply patch included
```
patch -p1 /moodle_dirroot/repository/filepicker.js  < patch/repository_filepicker_js.patch
```

## Contributions
Contributions of any form are welcome. Github pull requests are preferred.
Fill free to commit any bugs, improvements, or feature requiests in our [issue tracker][issues].

## Authors
* Pascal Mathelin
* Celine Perves
* Claude Yahou