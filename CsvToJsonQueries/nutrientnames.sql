select json_arrayagg(json_object(
		'NutrientID', NutrientID,
		'NutrientSymbol', NutrientSymbol,
		'NutrientUnit', NutrientUnit,
		'NutrientName', NutrientName
	))
from nutrientnames
INTO OUTFILE '/usr/local/exports/nutrientnames.json'
FIELDS ESCAPED BY ''
;

SELECT 'NutrientID', 'NutrientSymbol', 'NutrientUnit', 'NutrientName'
UNION ALL
select 
		NutrientID,
		NutrientSymbol,
		NutrientUnit,
		NutrientName
from nutrientnames
INTO OUTFILE '/usr/local/exports/nutrientnames.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;