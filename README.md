# TT-RSS on Heroku

> It is possible and it works, even with free dynos!

Thanks [Tiny Tiny RSS](http://tt-rss.org) for your existence, and thanks to [Reuben Castelino](https://projectdelphai.github.io/blog/2013/03/15/replacing-google-reader-with-tt-rss-on-heroku/) and [Art Chaidarun](https://chaidarun.com/ttrss-heroku) for pioneering the deploy on Heroku.

If you have issues, feel free to bug report/submit pull request. Depending on spare time I'll look into it.


## Quick start

Supposing you have already a Heroku account and you have the toolbelt installed and configured in your environment:

```sh
# clone this repository
$ git clone https://github.com/serl/ttrss-heroku.git && cd ttrss-heroku

# create the application (names are unique on the platform)
$ heroku create my-fancy-ttrss

# we'll need a database
$ heroku addons:create heroku-postgresql:hobby-dev

# everything is ready, push! (this will take time)
$ git push heroku master

# and enjoy (credentials are admin:password, so go to change the password)
$ heroku open
```


## Update the feeds

As you'll quickly discover, the feeds are not going to update by themselves.

### Solution #1 *(recommended)*

Use the scheduler addon:

```sh
$ heroku addons:create scheduler:standard
$ heroku addons:open scheduler
```

Then on the web interface that appears, add a new hourly job. The command to run is `update`.

NOTE: in order to comply the 10k row limit of the free tier, after the update I'm truncating the `ttrss_tags` table, as I'm not personally using this feature (and it uses a lot of rows).

### Solution #2

You could fire worker dyno with the `update-daemon` command (and let it eat a lot of free dyno hours).


## Feed icons are disappearing!

I've a solution for you. Create an account on Amazon Web Services, a bucket on S3 (names are unique on the platform) and credentials to access to it from IAM. When you have all this, set these variables on the application (change where needed):

```sh
$ heroku config:set \
  AWS_REGION=eu-central-1 \
  AWS_S3_BUCKET_NAME=my-fancy-ttrss \
  AWS_ACCESS_KEY_ID=youraccesskeyid \
  AWS_SECRET_ACCESS_KEY=yoursecretaccesskey
```

Then, if you want the icons to appear, you should force the application to reload them (**don't do this if you're updating with *Solution #2*!**):

```sh
$ heroku run update-icons
$ heroku restart
```


## Update TT-RSS version

Either you update the submodule in `tt-rss`, or you wait me to pick the latest commit (and then pull my changes), then update your Heroku application (`git push heroku master`).


## Tips to spare dyno hours

* Prefer scheduler over daemon for updates (*Solution #1*)... Maybe less than once per hour?
* Put wisely the update interval for each feed (where *wisely* = *as loose as possible*).
* Let the web dyno go to sleep when it's tired (don't keep that tab always open / use The Great Suspender on Chrome).
* *(unrelated to dyno hours, but still important)* As we're in the free tier for the database, we're limited to 10k rows. Check from time to time if you're compliant (Heroku web interface is friendly). If not, consider deleting some feeds.


## TODOs

* Persist sessions (memcached)?
* Email digest support
* *You name it*
