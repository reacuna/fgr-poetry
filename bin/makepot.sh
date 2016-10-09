#!/bin/bash

export PATH=/usr/local/Cellar/gettext/0.19.8.1/bin/:$PATH 

php ~/Sites/wordpress-dev/tools/i18n/makepot.php wp-plugin ~/Projects/fgr-poetry ~/Projects/fgr-poetry/languages/fgr-poetry.pot
