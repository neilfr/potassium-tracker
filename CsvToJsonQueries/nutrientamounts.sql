select json_arrayagg(json_object(
		'FoodID', FoodID,
		'NutrientID', NutrientID,
		'NutrientValue', NutrientValue
	))
from nutrientamounts
/* where FoodID IN (2, 4) */
INTO OUTFILE '/usr/local/exports/nutrientamounts.json'
FIELDS ESCAPED BY ''
;

SELECT 'FoodID', 'NutrientID', 'NutrientValue'
UNION ALL
select 
		FoodID,
		NutrientID,
		NutrientValue
from nutrientamounts
INTO OUTFILE '/usr/local/exports/nutrientamounts.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;
