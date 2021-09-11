select json_arrayagg(json_object(
		'FoodID', FoodID,
		'MeasureID', MeasureID,
		'ConversionFactorValue', ConversionFactorValue
	))
from conversionfactors
INTO OUTFILE '/usr/local/exports/conversionfactors.json'
FIELDS ESCAPED BY ''
;

SELECT 'FoodID', 'MeasureID', 'ConversionFactorValue'
UNION ALL
select 
		FoodID,
		MeasureID, 
		ConversionFactorValue,
from conversionfactors
INTO OUTFILE '/usr/local/exports/conversionfactors.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;