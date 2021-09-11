select json_arrayagg(json_object(
		'MeasureID', MeasureID,
		'MeasureDescription', MeasureDescription
	))
from measurenames
INTO OUTFILE '/usr/local/exports/measurenames.json'
FIELDS ESCAPED BY ''
;

SELECT 'MeasureID', 'MeasureDescription'
UNION ALL
select 
		MeasureID,
		MeasureDescription
from measurenames
INTO OUTFILE '/usr/local/exports/measurenames.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;