select json_arrayagg(json_object(
		'FoodGroupID', FoodGroupID,
		'FoodGroupName', FoodGroupName
	))
from foodgroups
INTO OUTFILE '/usr/local/exports/foodgroups.json'
FIELDS ESCAPED BY ''
;

SELECT 'FoodGroupID', 'FoodGroupName'
UNION ALL
select 
		FoodGroupID,
		FoodGroupName, 
from foodgroups
INTO OUTFILE '/usr/local/exports/foodgroups.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;