<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ImportantLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportantLinkController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $links = ImportantLink::orderBy('id', 'desc')->get();
        return view('backend.pages.important_links.index', compact('links'));
    }

    public function create()
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        return view('backend.pages.important_links.create');
    }

    public function store(Request $request)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url|max:2048',
        ]);

        try {
            $link = ImportantLink::create($request->all());
            return ['status' => 'success', 'data' => $link, 'msg' => 'Important Link has been Created !!'];
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Creating Important Link !!'], 500);
        }
    }

    public function edit($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $link = ImportantLink::findOrFail($id);
        return view('backend.pages.important_links.create', compact('link'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url|max:2048',
        ]);

        $link = ImportantLink::findOrFail($id);

        try {
            $link->update($request->all());
            return ['status' => 'success', 'data' => $link, 'msg' => 'Important Link has been Updated !!'];
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'msg' => 'Failed Updating Important Link !!'], 500);
        }
    }

    public function destroy($id)
    {
        if (is_null($this->user)) {
            abort(403, 'Unauthorized Access!');
        }

        $link = ImportantLink::find($id);

        if (!is_null($link)) {
            $link->delete();
            session()->flash('success', 'Important Link has been Deleted!!');
        } else {
            session()->flash('failed', 'Important Link could not be deleted!!');
        }
        return back();
    }
}
