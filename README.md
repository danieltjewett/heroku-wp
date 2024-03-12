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
* [S3-Uploads](https://github.com/humanmade/S3-Uploads) doesn't seem to work with the latest version.  It seems to be version 2 and 3 have a conflict with GuzzleHTTP that we haven't figured out why, so we stuck with version 1.1, as seen [here](https://github.com/humanmade/S3-Uploads/tree/9cb765284e7407d7ad2309a64dd8e5e13db258b2).
  * 3/12/24 1:30PM - Uploads don't actually work :(
* [Contact Form 7](https://github.com/rocklobster-in/contact-form-7) has a conflict, however, with S3-Uploads@1.1.
  * When the `wpcf7_cleanup_upload_files` [hook runs](https://github.com/rocklobster-in/contact-form-7/blob/dev/5.8/includes/file.php#L346), it's trying to delete attachments in the contact form.  The S3-Uploads plugin overwrites all files and interjects it with a url from s3.  Contact Form 7 doesn't know how to handle that and crashes (`359` is the crash, `349-353` is the check that should catch this).  This as a consequence was causing Jetpack to have conflicts and not work as well.
  * The temporary solution was to overwrite `public/wp-content/plugins/contact-form-7/includes/file.php` with a `return` during the `wpcf7_cleanup_upload_files` function, since we are currently NOT using file uploads in our contact us form.  We make use of the fact that when `./support/app_slug_compile.sh` on deployment copies overtop `public.built` the contents of `public`.  Therefore, we just copy the version of `file.php` from [Contact Form 7](https://github.com/rocklobster-in/contact-form-7).
* If something seems to be outdated / broken, double check other forks to see if anyone has made any changes worth combing through.
* Heroku boots Word Press with `sh support/app_boot.sh`, located in the `Procfile`.  This seems to setup the mysql certs and servers `public.built` with `vendor/bin/heroku-php-nginx`.

## MySQL

Every so often, we'll need to update the cert.  See https://github.com/xyu/heroku-wp/issues/170 for more details.