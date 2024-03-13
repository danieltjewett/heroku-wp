The forked repo is old, and isn't making updates.  See https://github.com/xyu/heroku-wp for origin notes.  Below are our notes.

## Installation

Just use this button below:

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/danieltjewett/heroku-wp/tree/master)

There are a couple of gotchas:

https://github.com/xyu/heroku-wp/issues/68
https://github.com/xyu/heroku-wp/issues/130

The TL;DR is that `It's the secure-db-connection plugin trying to install itself before WordPress has installed. `  The intent of the author was to have the MariaDB addon provision before the plugin runs.  This isn't happening anymore.

The solution is to remove `"wpackagist-plugin/secure-db-connection": "~1",` in the `composer.json` file, temporarily.  Run `composer update --ignore-platform-reqs`.  Push the changes and click on the above button (make sure to update the deploy link to the correct branch).

We should be able to install WordPress, albeit insecure DB connection.  Simply add `"wpackagist-plugin/secure-db-connection": "~1",` back into `composer.json` and run `composer update --ignore-platform-reqs`.  Be sure to deploy.

## Updates

The intention of this repo is to make updates with `composer.json`, then run the updater so that the lock as the right versions.

1. Make the required version updates in `composer.json`
2. Run `composer update --ignore-platform-reqs`
3. Deploy changes to Heroku
4. If it is a major update, run `wp core update-db`

On Heroku, there is a hook found in the `composer.json` file that runs `./support/app_slug_compile.sh`.  This creates a `public.built` directory, in which our files within `public` get copied overtop of.  We can take advantage of this by writing over any files necessary.

## Notes

* [SendGrid](http://wordpress.org/plugins/sendgrid-email-delivery-simplified/), from the original repo, is no longer supported.
  * The replacement we went with was `wpackagist-plugin/smtp-mailer`.  We went the `SMTP Relay` route with SendGrid.
* [S3-Uploads](https://github.com/humanmade/S3-Uploads) this was installed manually into the `public/wp-content/plugins/s3-uploads` directory.
  * We need to figure out how to make composer map its files / classes / namespace not to the vendor directory, but to `public.built/wp-content/plugins/s3-uploads`.  If we can't figure it out, this is probably good enough.  The problem is [they will no longer be supporting the manual install method](https://github.com/humanmade/S3-Uploads/issues/644).
* If something seems to be outdated / broken, double check other forks to see if anyone has made any changes worth combing through.
  * We can search https://wpackagist.org/search to search for plugins.
* Heroku boots Word Press with `sh support/app_boot.sh`, located in the `Procfile`.  This seems to setup the mysql certs and servers `public.built` with `vendor/bin/heroku-php-nginx`.

## MySQL

Every so often, we'll need to update the cert.  See https://github.com/xyu/heroku-wp/issues/170 for more details.