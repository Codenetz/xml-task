### Google authentication

Create service account for your project in Google Cloud Console. 

Download `.json` file and place it in `var/` folder.

Example:
```
$ cp var/dist.googleServiceAccount.json var/googleServiceAccount.json
```

### Build docker image
```
$ ./setup
```

### Run task
```
$ ./run data/coffee_feed.xml
```