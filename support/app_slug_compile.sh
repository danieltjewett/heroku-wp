#!/bin/bash

# Cleanup dirs
rm -rf tmp/public.building tmp/public.old
mkdir -p tmp/public.building

# Recursively copy files build final web dir
cp -R vendor/wordpress/wordpress/* tmp/public.building
cp -R public/* tmp/public.building

# Deal with humanmade/s3-uploads nonsense
unzip support/s3-uploads@3.0.7.zip -d tmp/public.building/wp-content/plugins/s3-uploads

# Move built web dir into place
mkdir -p public.built
mv public.built tmp/public.old && mv tmp/public.building public.built
rm -rf tmp/public.old

# Remove files to slim down slug if we're on Heroku
if [ ! -e .sluglocal ]
then
	rm -rf vendor/wordpress
	rm -rf public
	rm support/s3-uploads@3.0.7.zip
fi

# Write some info about our slug
NOW=$( date )
cat <<EOT > public.built/.heroku-wp
Powered by HerokuWP
https://github.com/xyu/heroku-wp
=============================================

Slug Compiled : $NOW
EOT
