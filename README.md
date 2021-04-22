## Import XML to Google Spreadsheet

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

### Run tests
```
$ ./test
```

### Additional options

It will delete Spreadsheet from Google drive after the import.

_(used when tests are running)_
```
--deleteSpreadsheet=true
```

```
$ import:xml [--deleteSpreadsheet [DELETESPREADSHEET]] [--] <fileLocation>
```