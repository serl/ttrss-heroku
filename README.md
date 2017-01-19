# TT-RSS on Heroku

> It is possible and it works, even with free dynos!

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


## TODOs

* Email digest support
* *You name it*
