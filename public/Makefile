LESSC=node_modules/.bin/lessc
TSC=node_modules/.bin/tsc
UGLIFY=node_modules/.bin/uglifyjs
NODE=node

production: less js bundle

less:
	$(LESSC) app/style.less out/style.css

js:
	$(TSC)

bundle:
	$(NODE) bundle.js > out/outfile.js

