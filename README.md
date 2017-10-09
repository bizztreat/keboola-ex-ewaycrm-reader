# keboola-ex-ewaycrm-reader
This app serves as an extractor. Calls an API endpoint of the eWay CRM systems and gets the data back.

# Docker image instructions

AWS ECR
registry: 147946154733.dkr.ecr.us-east-1.amazonaws.com
repository: developer-portal-v2/bizztreat.ex-eway-crm-reader

You may need to login first:
```
docker login -u AWS -p <pwd> https://<registry>
```

Password you will get from API call here:
https://apps-api.keboola.com/vendors/bizztreat/apps/bizztreat.ex-eway-crm-reader/repository
but you need to have a auth token from here:
https://apps-api.keboola.com/auth/login

more info here:
https://developers.keboola.com/extend/docker/tutorial/automated-build/


After any change of code run to build a new image:
```
docker build --tag=<AWS-registry>/<repository> --no-cache .
```

Then to push a new version of image to AWS ECR repository:
```
docker push <AWS-registry>/<repository>
```


Run locally and debug volume:
```
docker run --volume=/Users/.../:/data/ -i -t --entrypoint=/bin/bash <image>
```

# Checklist

0.   bizztreat
1.   eWay CRM reader
2.   extractor
3.   App provide features to read data from eWay API
4.   
5.   ./eway-logo.png
6.   Bizztreat s.r.o.
7.   Bubenská 1477/1, Praha, CZ
8.   david.chobotsky@bizztreat.com
9.   https://github.com/bizztreat/keboola-ex-ewaycrm-reader/blob/master/LICENSE.md
10.  https://hub.docker.com/r/bizztreat/docker-keboola-eway-connector/
11.  latest
12.  (default)
13.  (default)
16.  true
17.  false
18.  in
19.  false
20.  https://github.com/bizztreat/keboola-ex-ewaycrm-reader/blob/master/MANUAL.md
21.  tableOutput
22.  see below ...
23.  see below ...
24.  see below ...
25.  dataIn
26.  run
27.  false
28.  none
29.  standard
30.  local

###Test configuration
```
{
    "storage": {
        "output": {
            "tables": [
                {
                    "source": "destination.csv",
                    "destination": "out.c-eway.test",
                    "incremental": false,
                    "primary_key": [],
                    "columns": [],
                    "delete_where_values": [],
                    "delete_where_operator": "eq",
                    "delimiter": ",",
                    "enclosure": "\"",
                    "metadata": [],
                    "column_metadata": []
                }
            ],
            "files": []
        }
    },
    "parameters": {
        "webServiceAddress": "https:\/\/trial.eway-crm.com\/<id>\/WcfService\/Service.svc\/",
        "username": "<user>",
        "#password": "<pwd>",
        "passwordAlreadyEncrypted": false,
        "dieOnItemConflict": false,
        "apiFunction": "getCompanies"
    },
    "processors": {
        "before": [],
        "after": []
    },
    "image_parameters": [],
    "action": "run"
}
```

### Configuration schema
```
{
  "configurationSchema": {
    "type": "object",
    "title": "Parameters",
    "required": [
      "webServiceAddress",
      "username",
      "#password",
      "apiFunction"
    ],
    "properties": {
      "webServiceAddress": {
        "type": "string",
        "title": "API endpoint",
        "default": "",
        "minLength": 1
      },
      "username": {
        "type": "string",
        "title": "Username",
        "default": "",
        "minLength": 1
      },
      "#password": {
        "type": "string",
        "title": "Password",
        "format": "password",
        "default": "",
        "minLength": 4
      },
      "apiFunction": {
        "type": "string",
        "title": "API Function",
        "default": "",
        "minLength": 1
      },
      "passwordAlreadyEncrypted": {
        "type": "boolean",
        "title": "Password Already Encrypted",
        "format": "checkbox",
        "default": "false"
      },
      "dieOnItemConflict": {
        "type": "boolean",
        "title": "Die On Item Conflict",
        "format": "checkbox",
        "default": "false"
       }
    }
  }
}
```
[Details here](https://github.com/bizztreat/keboola-ex-ewaycrm-reader/blob/master/MANUAL.md)