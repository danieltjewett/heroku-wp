The forked repo is old, and isn't making updates.  See https://github.com/xyu/heroku-wp for origin notes.  Below are our notes.

## Installation

Just use this button below:

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/danieltjewett/heroku-wp/tree/major-updates)

There are a couple of gotchas:

https://github.com/xyu/heroku-wp/issues/68
https://github.com/xyu/heroku-wp/issues/130

The TL;DR is that `It's the secure-db-connection plugin trying to install itself before WordPress has installed. `  The intent of the author was to have the MariaDB addon provision before the plugin runs.  This isn't happening anymore.

The solution is to remove `"wpackagist-plugin/secure-db-connection": "~1",` in the `composer.json` file, temporarily.  Run `composer update --ignore-platform-reqs`.  Push the changes and click on the above button (make sure to update the deploy link to the correct branch).

We should be able to install WordPress, albeit insecure DB connection.  Simply add `"wpackagist-plugin/secure-db-connection": "~1",` back into `composer.json` and run `composer update --ignore-platform-reqs`.  Be sure to deploy

## Updates

1. Make the required version updates in `composer.json`
2. Run `composer update --ignore-platform-reqs`
3. Deploy changes to Heroku
4. If it is a major update, run `wp core update-db`

## Notes

These plugins, from the original repo, are no longer support
* [SendGrid](http://wordpress.org/plugins/sendgrid-email-delivery-simplified/)

The replacement we went with was `wpackagist-plugin/smtp-mailer`.  We went the `SMTP Relay` route with SendGrid.

Media Uploads still seem to work well to do this day.

If something seems to be outdated, double check other forks to see if anyone has made any changes worth combing through.

## MySQL

Every so often, we'll need to update the cert.  See https://github.com/xyu/heroku-wp/issues/170 for more details.