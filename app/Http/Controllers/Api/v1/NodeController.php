<?php namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Node;

class NodeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Node::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return Node::create(
            (array)$request->all()
        );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Node::findOrFail($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $node = Node::findOrFail($id);
        $properties = $node->getFillable();
        $updated = false;
        foreach ($properties as $property) {
            if ($request->has($property)) {
                $node->$property = $request->input($property);
                $updated = true;
            }
        }
        if ($updated)$node->save();
        return $node;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Node::findOrFail($id)->delete();
    }

}
