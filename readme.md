## eSMS Extended

### To setup

1. Clone this repository.
2. Create `/.env.production.php`.
```
	<?php

	return array(
		'DBHOST' => 'your_host',
		'DBNAME' => 'your_dbname',
		'DBUSER' => 'your_dbuser',
		'DBPASS' => 'your_password'
	);
```
3. Run `composer install`.


### For frontend development

The `/frontend/` folder, contains the ember-cli for frontend development. 
Make sure you have nodejs installed on your machine and run `npm install`.

Notice that when you run `ember build --environment=production`, the `/frontend/dist/` folder
contains the production files. You need to copy them to `/public/`.

1. # cp -R /frontend/dist/assets /public


## Using
- Laravel
- Ember.js
- Postgresql
- Toastr
- Papa-Parse
- Moment.js
- Laravel-Excel
- Selectize.js
- Bootstrap 3
- Accounting.js
- ExcelExport - Battatech