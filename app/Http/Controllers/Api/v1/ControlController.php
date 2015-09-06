<?php namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Node;
use App\Models\Control;

class ControlController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($node_id)
    {
        return Node::findOrFail($node_id)->controls;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $node_id)
    {
        $node_id = Node::findOrFail($node_id)->id;
        $newControl = Control::create(
            (array)$request->all()
        );
        $newControl->node_id = $node_id;
        $newControl->save();
        return $newControl;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $node_id, $control_id)
    {
        $node = Node::findOrFail($node_id);
        $control = Control::findOrFail($control_id);
        $properties = $control->getFillable();
        $updated = false;
        foreach ($properties as $property) {
            if ($request->has($property)) {
                $control->$property = $request->input($property);
                $updated = true;
            }
        }
        if ($updated) $control->save();
        return $control;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($node_id, $control_id)
    {
        Node::findOrFail($node_id);
        Control::findOrFail($control_id)->delete();
    }
    
}
