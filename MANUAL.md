# eWay CRM extractor

## What It Does

This helps you to extract allowed data objects from eWay CRM using their API. 

### Allowed data objects to extract

- Companies
- Projects
- Carts (Invoices)

All the objects are mapped to output CSV file. See schema below:

#### Companies

['ItemGUID', 'ItemVersion', 'IdentificationNumber', 'CompanyName', 'MRPID']

- ItemGUID: eWay unique identifier
- ItemVersion: eWay numeric increment
- IdentificationNumber: company ID
- CompanyName: company NAME data
- MRPDI: custom ID field

#### Projects

['ItemGUID', 'ItemVersion', 'ProjectName', 'OrderNumber', 'MRPID']

- ItemGUID: eWay unique identifier
- ItemVersion: eWay numeric increment
- OrderNumber: project ID
- MRPDI: custom ID field

#### Carts

['ItemGUID', 'ItemVersion', 'InvoiceNumber', 'MRPID']

- ItemGUID: eWay unique identifier
- ItemVersion: eWay numeric increment
- InvoiceNumber: invoice ID
- MRPDI: custom ID field


*Note: If you are looking for any other data object extract, do not hesitate and contact our support and 
we will help you with enhancements. Custom fields will be always different in your case.* 


## Configuration

### Output mapping

Use path `/data/out/tables/destination.csv` as File in output mapping.
For destination bucket use according your needs.


### Parameters

<pre>
{
  "webServiceAddress": <web-service-endpoint>,
  "username": <your-account>,
  "#password": <your-secret-password>,
  "passwordAlreadyEncrypted": [true/false], DEPRECATED - Password is encrypted on KBC side so it is not necessary to use this param
  "dieOnItemConflict": [true/false],
  "apiFunction": [getCompanies, getProjects]
}
</pre>


- `webServiceAddress` url endpoint   
- `username` is name of your account  
- `#password` is your secret password
- `passwordAlreadyEncrypted` is flag if you provide already encrypted password (use always FALSE) pasword is encrypted inside KBC  
- `dieOnItemConflict` is flag to stop process when data conflicts
- `apiFunction` is one of provided fuctions for getting a data from CRM  


## Contact

BizzTreat, s.r.o
Bubenská 1477/1
Prague

If you have any question contact support@bizztreat.com

Cheers!