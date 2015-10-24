<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\TemplateRequest;
use App\Http\Controllers\Controller;

class TemplatesController extends Controller
{
    public function redirect($templateCategory)
    {
        $siteId = \Auth::user()->currentSiteId();

        $templateCategory = strtolower($templateCategory);

        return redirect("/sites/$siteId/templates/$templateCategory");
    }

    public function changeSite(Request $request, $site, $templateCategory)
    {
        $input = $request->all();
        if (isset($input['siteId']) and $input['siteId'] > 0) {
            $siteId = $input['siteId'];

            if (\Auth::user()->setCurrentSiteId($siteId)) {
                return redirect("/sites/$siteId/templates/$templateCategory");
            }
        }
        return redirect("/sites/templates/$templateCategory");
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($site, $templateCategory)
    {
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
        $templates = $site->templates()->ofCategory($templateCategory)->get();

        return view('template.list', [
            'templates' => $templates,
            'site' => $site,
            'templateCategory' => $templateCategory
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($site, $templateCategory)
    {
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
        $newTemplate = new \App\Template;

        $newTemplate->category = $templateCategory;
        $newTemplate->site_id = $site->id;

        $templateTypes = $newTemplate->getAllTypes();

        return view('template.create', [
            'template' => $newTemplate,
            'site' => $site,
            'templateTypes' => $templateTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store($site, TemplateRequest $request)
    {
        $input = $request->all();
        $newTemplate = new \App\Template;
        $newTemplate->fill($input);
        $newTemplate->site_id = $site->id;
        $newTemplate->save();
        return $newTemplate;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($site, $template)
    {
        return $this->edit($site, $template);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($site, $template)
    {
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
        $templateTypes = $template->getAllTypes();

        return view('template.edit', ['site' => $site, 'template' => $template, 'templateTypes' => $templateTypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($site, $template, TemplateRequest $request)
    {
        $input = $request->all();

        $template->update($input);

        return $template;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($site, $template)
    {
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
        $infocost->delete();
        return redirect('sites/'. $site->id. '/templates/'.$template->category);
    }

    public function get($site, $template) {
        $template->content = $template->content;

        return $template;
    }
}
