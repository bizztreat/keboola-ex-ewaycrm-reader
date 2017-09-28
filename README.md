# keboola-ex-ewaycrm-reader
This app serves as an extractor. Calls an API endpoint of the eWay CRM systems and gets the data back.

# Checklist

0.   bizztreat
1.   eWay CRM reader
2.   extractor
3.   App provide features to read data from eWay API
4.   
5.   TODO icon
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
  "webServiceAddress": "web-service-endpoint",
  "username": "your-account",
  "#password": "your-secret-password",
  "passwordAlreadyEncrypted": [true/false],
  "dieOnItemConflict": [true/false],
  "apiFunction": [getCompanies, getProjects]
}
```
[Details here](https://github.com/bizztreat/keboola-ex-ewaycrm-reader/blob/master/MANUAL.md)