# repository pod : moodle repository for POD
repository pod is a moodle repository plugin that enable to create file resource linked by reference to an external POD server
* See https://github.com/EsupPortail/pod/wiki for POD server informations
* We use University of Strasbourg version https://github.com/unistra/pod of POD server
* The moodle plugin requires use of POD webservices devlopped by University of Strasbourg https://github.com/unistra/pod-ws

## Features
* Enable to create video or audio file reference with a POD server
* search enabled
* paging enabled for listing and searching in filepicker
* overrride of rendrer to check resource existence on POD server
* use of SPORE specification (see https://spore.github.io/)
## Download
from moodle plugin repository

## Pod Moodle requirements installation
* You'll need to install POD-WS according to given installation on https://github.com/unistra/pod-ws
* When installed, connect yourself to django pod-ws admin interface 
* Go to the administration page ("/admin")
* Create a user "moodle" with all "views" permissions
* Create an authorization token for this user
* Load fields permissions for the moodle user with the following command :
```
python manage.py fine_permissions_load -u moodle moodle.json
```

you'll find moodle.json file into the pod-ws directory of the pod repository plugin directory

## Moodle Installation
### local spore plugin
* since repository pod use spore you'll need it's client implementation as a moodle local plugin
* install local/spore on your own local directory

### Repository installation
Install repository/pod in your own repository directory

#### Repository setting
fill plugin setting field :
* pod/spore_description_file_url : spore description file 
* pod/spore_base_url : POD webservice base url
* pod/spore_token : POD webservice token
* pod/media_server_url : POD media url
* pod/page_size : page size :used for pod webservice and then enabling paging on filepicker

## filepicker paging patch for search
* apply patch included
```
patch -p1 /moodle_dirroot/repository/filepicker.js  < patch/repository_filepicker_js.patch
```

## Contributions
Contributions of any form are welcome. Github pull requests are preferred.
Fill any bugs, improvements, or feature requiests in our [issue tracker][issues].

## Authors
* Pascal Mathelin
* Celine Perves
* Claude Yahou

Special Thanks to Morgan Bohn for POD-ws development and help


## License
* http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
[]: 
[issues]: 