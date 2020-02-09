<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function  sortJson()
    {
        $jsonString = file_get_contents(base_path('resources/phonebook.json'));
        $data = json_decode($jsonString, true);
        sort($data);
        file_put_contents('/resources/phonebook.json', json_encode($data));
    }

    public function getAll()
    {
        $this->sortJson();
        $jsonString = file_get_contents(base_path('resources/phonebook.json'));
        $data = json_decode($jsonString, true);


        return response()->json($data);
    }

    public function createNew(Request $request)
    {
        $file = 'resources/phonebook.json';
        $jsonString = file_get_contents(base_path($file));
        $data = json_decode($jsonString, true);


        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'number' => 'required'
        ]);

        if ($data != null) {

            array_push($data, array('id' => $this->getLastId() + 1, 'name' => $request->name, 'type' => $request->type, 'number' => $request->number));
            file_put_contents('/resources/phonebook.json', json_encode($data));
        } else {

            $data = json_encode(array(0 => array('id' => 1, 'name' => $request->name, 'type' => $request->type, 'number' => $request->number)));
            file_put_contents('/resources/phonebook.json', $data);
        }
        $this->sortJson();
        return response()->json(json_decode($jsonString, true));
    }




    public function editPhone(Request $request)
    {

        $file = 'resources/phonebook.json';
        $jsonString = file_get_contents(base_path($file));
        $data = json_decode($jsonString, true);

        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'type' => 'required',
            'number' => 'required'
        ]);

        foreach ($data as $key => $value) {
            if ($value['id'] == $request->id) {
                $data[$key]['name'] = $request->name;
                $data[$key]['type'] = $request->type;
                $data[$key]['number'] = $request->number;
            }
        }
        $this->sortJson();
        file_put_contents('/resources/phonebook.json', json_encode($data));

        return response()->json(json_decode($jsonString, true));
    }

    public function deletePhone($id)
    {

        $file = 'resources/phonebook.json';
        $jsonString = file_get_contents(base_path($file));
        $data = json_decode($jsonString, true);

        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                unset($data[$key]);
            }
        }
        file_put_contents('/resources/phonebook.json', json_encode($data));
        $this->sortJson();
        return response()->json(json_decode($jsonString, true));
    }

    public function getLastId()
    {
        $jsonString = file_get_contents(base_path('resources/phonebook.json'));
        $data = json_decode($jsonString, true);
        $max = max($data);
        return $max['id'];
    }
}
