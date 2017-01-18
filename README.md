# TT-RSS on Heroku

It is possible and it works. This readme needs anyway to be updated.

Add the database:
```sh
$ heroku addons:create heroku-postgresql:hobby-dev
```

In order to persist the feed icons on S3, you need to set these: `AWS_REGION`, `AWS_S3_BUCKET_NAME`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`.
