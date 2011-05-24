
#REMOTE_URL = nick@udon.noodlefactory.co.uk:public_html/etc/
REMOTE_URL = twinsadmin@edinburghtwins.co.uk:www/html/

DEST = html

SRC := $(shell find src \( -path .git -o -path .gitignore -o -path tt -o -path messageboard -o -path html \) -prune -o -type f -print)

.PHONY: html list test xtest force_upload upload clean

list:
	@for f in ${SRC}; do echo $$f; done

html: ttree.cfg ${SRC}
	ttree -f ttree.cfg --dest='${DEST}'
	ln -sfn ../messageboard ${DEST}/messageboard 
	ln -sfn ../public.old ${DEST}/not-so-old-website
	ln -sfn ../oldwebsite ${DEST}/oldwebsite



test: html
	prove t

xtest: html
	prove xt

force_upload: html
	rsync -arlv --delete ${DEST}/ ${REMOTE_URL}

upload: test force_upload





clean:
	rm -rf ${DEST}
