<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\{
    Checklist
};

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('checklist.index');
    }

    /**
     * Get all checklist
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return Checklist::orderBy('id','asc')->get()->groupBy('batch');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
        $request->validate([
            'name' => 'required',
            "checklistAdds.*.requirement" => 'required',
            "checklistAdds.*.description" => 'required',
        ]);
            
        DB::beginTransaction();
        try {
            $batch = 1;
            $searchBatch = Checklist::orderBy('batch', 'desc')->first();
            if($searchBatch){
                $batch = $searchBatch->batch + 1;
            }
            foreach($request->checklistAdds as $checklistAdd){
                $checklist = Checklist::create(['requirement'=> $checklistAdd['requirement'], 'description' => $checklistAdd['description'], 'batch' => $batch, 'name' => $request->name]);
            }
            DB::commit();
            return Checklist::orderBy('id','asc')->get()->groupBy('batch');

        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'default_checklists_id' => 'required',
            'remained_id' => 'required',
            "checklist_copieds.*.requirement" => 'required',
            "checklist_copieds.*.description" => 'required',
        ]);
            
        DB::beginTransaction();
        try {
            foreach($request->checklist_copieds as $checklist_copied){
                if(array_key_exists('id', $checklist_copied)){
                    $checklist = Checklist::where('id',$checklist_copied['id'])->first();
                    $checklist->update(['name' => $request->name] + $checklist_copied);
                }else{
                     $checklist = Checklist::create(['requirement'=> $checklist_copied['requirement'], 'description' => $checklist_copied['description'], 'batch' => $id, 'name' => $request->name]);
                }
            }
            $array_differ = array_diff($request->default_checklists_id, $request->remained_id);

            Checklist::whereIn('id',$array_differ)->delete();
            
            DB::commit();
            return $checklist;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($batchId)
    {
        DB::beginTransaction();
        try {
            $ids = Checklist::where('batch',$batchId)->get()->pluck('id');

            $checklist = Checklist::whereIn('id',$ids)->delete();

            DB::commit();
            return $checklist;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     *  Get checklist base on category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getChecklistPerCategory($categoryName){
        
        return Checklist::when($categoryName == 'Support', function($q){
            $q->whereIn('name', ['Support', 'Perimeter']);
        })->when($categoryName == 'Operations', function ($q){
            $q->where('name', 'Operations');
        })->when($categoryName == 'Offices', function ($q){
            $q->where('name', 'Offices');
        })->orderBy('id','asc')->get()->groupBy('batch');
    }
}
