<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Term;
use App\Models\TermTaxonomy;
use Illuminate\Http\Request;

class TagController
{
    private static function parameters(Request $request)
    {
        $action = $request->action;

        if (method_exists(self::class, $action)) {
            $methodName = $action;
            return self::$methodName($request);
        } else {
            abort(404);
        }

    }

    public static function index(Request $request) {

        $action = $request->action;
        if ($action) {
            return self::parameters($request);
        }

       $items = Term::all();
    return view($this->folder.'tags')->with('items', $items);
    }

    public static function create(Request $request) {
        $name = $request->name;
        $slug = str_slug($request->category_slug ?: $name);
        $description = $request->description;
        $taxonomy = 'category';
        $parent = 0;

        if (@$_POST['action'] == 'create' && strlen($name) > 0) {

//            Check if term name exists
            if (!Term::where([['name', $name]])->first()) {

//            Check if term slug exists
                if (!Term::where([['slug', $slug]])->first()) {

                    //            Save Data

                    if (Term::create(['name' => $name, 'slug' => $slug])) {

                        $id = Term::where([['name', $name], ['slug', $slug]])->first()->id;

                        TermTaxonomy::create(['term_id' => $id, 'taxonomy' => $taxonomy, 'description' => $description, 'parent' => $parent]);

                        return redirect()->back()->with('success', 'Successfully Saved.');
                    } else {
                        return redirect()->back()->with('error', 'Oops! We are encountering a problem performing the action.');
                    }

                } else {
                    return redirect()->back()->with('error', 'Oop! Similar Term Slug exists.');
                }

            } else {
                return redirect()->back()->with('error', 'Oop! Similar Term Name exists.');
            }
        }

        return view($this->folder.'tag-create-edit');

    }

    public static function edit(Request $request)
    {
        $tag_id = $request->tag;

        $name = $request->name;
        $slug = str_slug($request->category_slug ?: $name);
        $description = $request->description;
        $taxonomy = 'category';
        $parent = 0;

        $items = Term::where('id', $tag_id)->first();

        if (@$_POST['action'] == 'edit') {

            if (Term::where('id', $tag_id)->update(['name' => $name, 'slug' => $slug])){

                TermTaxonomy::where('term_id', $tag_id)->update(['taxonomy' => $taxonomy, 'description' => $description, 'parent' => $parent]);
                $l = url('').dash_uri().'/tags?tag=category';
                $link = '<br><a href="'.$l.'" class="text-dark btn btn-link"><span class="fa fa-chevron-left"></span>Back to categories</a>';
                return redirect()->back()->with('success', 'Tag edited.'.$link);

            }else{
                return redirect()->back()->with('warning', 'Oops! We could not save changes.');
            }

        }

        return view($this->folder.'tag-create-edit')->with('items', $items);
    }

    public static function delete(Request $request)
    {
        $tag_id = $request->tag;
        Term::where('id', $tag_id)->delete();

        TermTaxonomy::where('term_id', $tag_id)->delete();


            return redirect()->back()->with('warning', '1 Tag deleted.');
    }

    }
