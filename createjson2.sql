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