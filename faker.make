; Drush make file for the faker module.  When this module is included from a
; project's make file, this will take care of automatically downloading the
; Faker php library from github.

core = 7.x
api = 2

libraries[faker][download][type] = get
libraries[faker][download][url] = https://github.com/fzaninotto/Faker/archive/v1.1.0.tar.gz
