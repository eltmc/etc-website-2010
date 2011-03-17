
REMOTE_URL = nick@udon.noodlefactory.co.uk:public_html/etc/

SRC := $(shell find src \( -path .git -o -path .gitignore -o -path tt -o -path messageboard -o -path html \) -prune -o -type f -print)

list:
	@for f in ${SRC}; do echo $$f; done

html: ttree.cfg ${SRC}
	ttree -f ttree.cfg


test: html
	prove t

upload: test
	rsync -arlv --delete html/ ${REMOTE_URL}


clean:
	rm -rf html