<?php

namespace App\Http\Controllers\Conversionfactor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateConversionfactorRequest;
use App\Models\Conversionfactor;
use App\Models\Nutrientamount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(UpdateConversionfactorRequest $request, Conversionfactor $conversionfactor)
    {
        if(isset($request->FoodDescription))
        {
            $conversionfactor->foodname->update([
                'FoodDescription' => $request->FoodDescription,
            ]);
        }
        if(isset($request->MeasureDescription))
        {
            $conversionfactor->measurename->update([
                'MeasureDescription' => $request->MeasureDescription,
            ]);
        }
        if(isset($request->nutrients))
        {
            collect($request->nutrients)->each(function ($nutrient) use($conversionfactor) {
                $newValue = $nutrient['NutrientAmount'];
                $nutrientValue = round($newValue / $conversionfactor->ConversionFactorValue);
               DB::table('nutrientamounts')
                ->where('FoodID', $conversionfactor->foodname->FoodID)
                ->where('NutrientID', $nutrient['NutrientID'])
                ->update([
                    'NutrientValue' => $nutrientValue,
                ]);
            });
        }

        return redirect()->back();
    }
}
