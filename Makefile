
#REMOTE_URL = nick@udon.noodlefactory.co.uk:public_html/etc/
REMOTE_URL = twinsadmin@edinburghtwins.co.uk:www/html/

SRC := $(shell find src \( -path .git -o -path .gitignore -o -path tt -o -path messageboard -o -path html \) -prune -o -type f -print)

list:
	@for f in ${SRC}; do echo $$f; done

html: ttree.cfg ${SRC}
	ttree -f ttree.cfg
	ln -sfn ../messageboard html/messageboard 
	ln -sfn ../public.old html/not-so-old-website
	ln -sfn ../oldwebsite html/oldwebsite


test: html
	prove t

xtest: html
	prove xt

force_upload: html
	rsync -arlv --delete html/ ${REMOTE_URL}

upload: test force_upload





clean:
	rm -rf html
