<?php

class ActuatorController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $actuators = Actuator::orderBy('created_at');

        if (Request::get('from')) {
            $actuators->where('updated_at', '>', new DateTime(Request::get('from')));
        }

        if (Request::get('to')) {
            $actuators->where('updated_at', '<', new DateTime(Request::get('to')));
        }

        return Response::json(array(
            'error' => false,
            'actuators' => $actuators->get()
        ), 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $actuator = new Actuator();
        $actuator->name = Input::get('name');
        $actuator->description = Input::get('description');

        // Validation and Filtering is sorely needed!!
        // Seriously, I'm a bad person for leaving that out.

        $actuator->save();

        return Response::json(array(
            'error' => false,
            'actuators' => $actuator->toArray()),
            200
        );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = "";
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    public function power()
    {
        libxml_use_internal_errors(true);
        $url = "http://www.servisinfo.com/cena-struje";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);

        $DOM = new DOMDocument;
        $DOM->loadHTML($output);

//get all H1
        $items = $DOM->getElementsByTagName('tr');

//display all H1 text
        for ($i = 0; $i < $items->length; $i++) {
            echo $items->item($i)->nodeValue . "|   <br/>";
        }

//        var_dump(DOMDocument::saveHTML($doc->getElementsByTagName('tbody')));
//        $xpath = new DOMXPath($doc);
//
//        $query = "//div[@class='statusup']";
//
//        $entries = $xpath->query($query);
//        var_dump($entries->item(0)->textContent);
    }


}
