# Main Installation:

 - `cd {webroot}`
 - `git pull origin master`
 - `composer update`
 - `cp .htaccess.sample .htaccess`
 - check that you have an `_ss_environment.php` file
 - set up image styles as per below
 - run a `/dev/build/`


# Image Styles (REQUIRED)

 - go to `imagewithstyle/_config/imagestyledefauls.yml.example` to review example
 - enter your desired styles here:  `mysite/_config/imagestyledefaults.yml`, we have entered some examples by default.
 - review: `perfect_cms_images/_config/perfect_cms_images.yml.example`
 - enter your desired image styles here: `mysite/_config/perfect_cms_images.yml`
 - *NB the `ClassNameForCSS` should match the `Name` in the perfect CMS Image.*
 - make sure you follow yml syntax or you will get weird errors


# Front-End Setup

- copy `themes/webpack-variables.example.js` to `themes/webpack-variables.js`
- browse to `/themes/sswebpack_engine_only`
- type `npm install`
- type `npm run watch`
- to publish press `q` and then type `npm run build`


# Review Default Data

- review `mysite/data/defaultpages.yml`
- when you run a `dev/build` in `dev` mode (NOT on test server) then you will automagically get some pages. The file above shows what pages are included. You can add your own.


# ready to go

 - login to cms
 - create pages


# when you edit the site ...

*Every time* you _start_ work on the site, you ...

 - review notes: https://docs.google.com/document/d/1FUeMPI435t0GqYOtuciknC22me0-KfXD99mecRAPzLU/edit#
 - `git pull origin master`
 - `composer update`

*Every time* When you _finish_ your work, you

 - `git add . -A && git commit . -m "description here" && git pull origin master`
 - resolve conflicts - if any ...
 - `git push origin master`


# updating the test / live site ....


 - `# _go to local root dir_:`
 - `cd /var/www/mysite.co.nz.localhost/`

 - `# _update modules_:`
 - `composer update`

 - `# _create front-end files_:`
 - `cd ./themes/sswebpack_engine_only && npm run build && cd ../../`

 - `# _git commit_:`
 - `git add . -A && git commit . -m "PATCH: new release" && git pull origin master && git push origin master`

 - `# _review tags_:`
 - `# review the last tag numbers`
 - `# create a new one, bug-fixes are third number (z), enhancements are second number (y) and first number (x) is for API breaking changes.`
 - `git tag`

 - `# _review tags to find best new number (x,y.z)_ `
 - `git tag x.y.z && git push --tags`

 - `# load new tag on test / live site ...`
 - `# assumes you have set up an SSH alias myalias in ~/.ssh/config, if you have not you can use: myalias@223.165.66.192 instead of myalias`
 - `ssh myalias "php /container/application/silverstripe-server-management/src/Git_Tag_Change_Composer_Update.php"`

 - Browse to 'http://test.mysite.co.nz/dev/build/?flush=all' to check it is all working

# Update the test site

 - NB. the above will load the latest tag
 - Make sure you read any error messages carefully.
 - If there are changes on the server then `git stash` them.
 `ssh myalias "php /container/application/silverstripe-server-management/src/Git_Tag_Change_Composer_Update.php 0.10.0"`

# Default Data

To replace the database with default data, define add `define('SSU_DEV', true);` to your `_ss_environment.php` file. **Careful, this will replace all your data.**


# Getting DB + Assets from Test / Live to local

 - **Run below from local command line**,
 - Assumes you have set up an SSH alias `myalias` in ~/.ssh/config),
 - If you have not set up an alias you can use: `username@111.222.333.444` instead of `myalias`

 - `# go to local root dir`
 - `cd /var/www/mysite.co.nz.localhost/`

 - `# dump db on site`
 - `ssh myalias "php /container/application/silverstripe-server-management/src/Dump_Silverstripe_Database.php"`

 - `# copy dababase`
 - `scp myalias:/container/application/mydb.sql /var/www/`

 - `# import database`
 - `mysql -u root -p mydb < ../mydb.sql`

 - `# get assets from live`
 - `rsync -chavzP --stats myalias:/container/application/public/assets/* /var/www/mysite.co.nz.localhost/assets/`
