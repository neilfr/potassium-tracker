select json_arrayagg(json_object(
		'FoodID', FoodID, 
		'FoodCode', FoodCode, 
		'FoodDescription', FoodDescription,
		'FoodGroupID', FoodGroupID
	))
from foodnames
/* where FoodID=2676 */
INTO OUTFILE '/usr/local/exports/arg.json'
FIELDS ESCAPED BY ''
;

SELECT 'FoodID', 'FoodCode', 'FoodDescription', 'FoodGroupID'
UNION ALL
select 
		FoodID,
		FoodCode, 
		FoodDescription,
		FoodGroupID
from foodnames
/* where FoodID IN (2, 4) */
INTO OUTFILE '/usr/local/exports/foodnames.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;