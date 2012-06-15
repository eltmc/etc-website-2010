
REMOTE_URL = -e 'ssh -p 1122' edinburg@uniform.unisonplatform.com:i/sites/old/replica-of-clearlybrilliant-site/

# Allow the above to be overridden in this file
-include config.local

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
