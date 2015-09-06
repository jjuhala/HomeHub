<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use app\Models\Node as Node;
use app\Models\Control as Control;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call('NodeControlSeeder');
        Model::reguard();
    }
}



class NodeControlSeeder extends Seeder {

    // todo: seed actions
    // todo: seed trigers

    public function run() {
        DB::table('nodes')->delete();
        DB::table('controls')->delete();

        $node1 = Node::create([
            'name'  => 'ExampleNode1',
            'ip'    => '127.0.0.1',
            'notes' => 'Example note one.'
        ]);

        $node2 = Node::create([
            'name'  => 'ExampleNode2',
            'ip'    => '127.0.0.1',
            'notes' => 'Example note two.'
        ]);

        Control::create([
            'name'  => 'ExampleControl1',
            'show_on_ui' => true,
            'type'  => 'sensor',
            'node_id' => $node1->id
        ]);

        Control::create([
            'name'  => 'ExampleControl2',
            'show_on_ui' => true,
            'type'  => 'trigger',
            'node_id' => $node1->id
        ]);

        Control::create([
            'name'  => 'ExampleControl3',
            'show_on_ui' => false,
            'type'  => 'switch',
            'node_id' => $node2->id
        ]);
    }

}
