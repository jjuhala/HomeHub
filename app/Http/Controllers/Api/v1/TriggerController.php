<?php namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Trigger;
use App\Models\Action;

class TriggerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($action_id)
    {
        return Action::findOrFail($action_id)->triggers;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $action_id)
    {
        $action_id = Action::findOrFail($action_id)->id;
        $newTrigger = Trigger::create(
            (array)$request->all()
        );
        $newTrigger->action_id = $action_id;
        $newTrigger->save();
        return $newTrigger;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $action_id, $trigger_id)
    {
        $action = Action::findOrFail($action_id);
        $trigger = Trigger::findOrFail($trigger_id);
        $properties = $trigger->getFillable();
        $updated = false;
        foreach ($properties as $property) {
            if ($request->has($property)) {
                $trigger->$property = $request->input($property);
                $updated = true;
            }
        }
        if ($updated) $trigger->save();
        return $trigger;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($action_id, $trigger_id)
    {
        Action::findOrFail($action_id);
        Trigger::findOrFail($trigger_id)->delete();
    }

}
