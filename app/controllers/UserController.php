<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $users = User::paginate(5);

        return View::make('users.index', compact('users'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('users.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::all();
        $validation = Validator::make($input, User::$rules);
        $file = Input::file('photo');

        if ($validation->passes())
        {
            $post = User::create($input);

            if (Input::hasFile('photo'))
            {
                if (Input::file('photo')->isValid())
                {
                    $name = Input::file('photo')->getClientOriginalName();
                    $extension = Input::file('photo')->getClientOriginalExtension();
                    Input::file('photo')->move('public/upload', $name);

                    $post->photo=$name;
                    $post->save();
                }
            }
            else
            {
                $message = "you must provide a valid photo";
            }

            $message = "you must provide a valid photo";

            return Redirect::route('users.index')->with('message', 'User Created Successfully');

        }

        return Redirect::route('users.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $user = User::find($id);
        if (is_null($user))
        {
            return Redirect::route('users.index');
        }
        return View::make('users.edit', compact('user'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $input = Input::all();

        $validation = Validator::make($input, User::$rules);

        $file = Input::file('photo');


        if ($validation->passes())
        {
            $user = User::find($id);

            $post=$user->update($input);

//            if (Input::hasFile('photo'))
//            {
//                if (Input::file('photo')->isValid())
//                {
//                    $name = Input::file('photo')->getClientOriginalName();
//                    $extension = Input::file('photo')->getClientOriginalExtension();
//                    Input::file('photo')->move('public/upload', $name);
//
//                    $post->photo=$name;
//                    $post->save();
//                }
//            }
          return Redirect::route('users.index');
        }
        return Redirect::route('users.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        User::find($id)->delete();
        return Redirect::route('users.index');
	}


}
