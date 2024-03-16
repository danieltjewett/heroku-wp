The forked repo is old, and isn't making updates.  See https://github.com/xyu/heroku-wp for origin notes.  Below are our notes.

## Installation

Just use this button below:

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/danieltjewett/heroku-wp/tree/major-updates)

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
* [S3-Uploads](https://github.com/humanmade/S3-Uploads) version 2, which the original repo is currently at, as well as other forks, does not currently work.  Version 3 also doesn't work.
  * For Version 3, which seems to have overhauled its namespaces, we tried messing with the `installer-paths` in `composer.json`, for example, `"public.built/wp-content/plugins/{$name}/": ["humanmade/s3-uploads"]`.  But we couldn't get it working.  To get version 3 working from composer, this route is probably the solution, but we don't know enough about advanced composer to make this work.
  * The solution for now is to use [manual-install.zip](https://github.com/humanmade/S3-Uploads/releases/download/3.0.7/manual-install.zip) from version 3 as a zip in this repo.
    * This is stored as a zip in `support/s3-uploads@3.0.7.zip`, and `./support/app_slug_compile.sh` extracts and installs into `tmp/public.building/wp-content/plugins/s3-uploads`, which gets copied to the correct place in the remainder part of `./support/app_slug_compile.sh`.
    * The problem with this approach, is tha it is cumbersome (i.e. not managing dependencies through `composer.json`, and that [humanmade will no longer be supporting the manual install method](https://github.com/humanmade/S3-Uploads/issues/644).
* [automattic/batcache](https://github.com/Automattic/batcache) has been switched back to the original, not the fork from https://github.com/xyu/batcache.
  * We also changed from `predis/predis` to `phpredis` (`ext-redis`).
* If something seems to be outdated / broken, double check other forks to see if anyone has made any changes worth combing through.
  * We can search https://wpackagist.org/search to search for plugins.
* Heroku boots Word Press with `sh support/app_boot.sh`, located in the `Procfile`.  This seems to setup the mysql certs and servers `public.built` with `vendor/bin/heroku-php-nginx`.

## MySQL

Every so often, we'll need to update the cert.  See https://github.com/xyu/heroku-wp/issues/170 for more details.