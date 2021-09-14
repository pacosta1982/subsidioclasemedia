<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Visit\BulkDestroyVisit;
use App\Http\Requests\Admin\Visit\DestroyVisit;
use App\Http\Requests\Admin\Application\IndexApplication;
use App\Http\Requests\Admin\Visit\IndexVisit;
use App\Http\Requests\Admin\Visit\StoreVisit;
use App\Http\Requests\Admin\Visit\UpdateVisit;
use App\Http\Requests\Admin\Task\StoreTask;
use App\Http\Requests\Admin\Task\UpdateTask;
use App\Models\Application;
use App\Models\Task;
use App\Models\Workflow;
use App\Models\State;
use App\Models\City;
use App\Models\ApplicationStatus;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ApplicationsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexVisit $request
     * @return array|Factory|View
     */
    public function index(IndexApplication $request)
    {
        $data = AdminListing::create(Application::class)
            //->attachOrdering('id')
            ->attachPagination($request->currentPage)

            ->modifyQuery(function ($query) use ($request) {

                $query->where('NroExpS', 'A');
                $query->where('TexCod', 187);
                if ($request->search) {
                    //return 'funciona';

                    //$query->where('NroExpsol', 'like', '%' . $request->search . '%');
                    $query->Where('NroExpPer', $request->search);
                }
                //return 'No Funciona';
            })
            //->paginate(15)
            ->get(['NroExp', 'NroExpsol', 'NroExpFch', 'NroExpPer']);

        //  return $request;

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('SEOBId')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.applications.index', ['data' => $data]);
    }

    public function show(Application $application)
    {
        //return $application;
        $sol = Task::where('NroExp', $application->NroExp)->first();
        //return $sol;
        //->where('NroExpS', 'A');
        return view('admin.applications.show', compact('application', 'sol'));
    }

    public function create(Application $application)
    {
        //$this->authorize('admin.task.create');
        $nodep = [18, 19, 20, 999];
        $state = State::whereNotIn('DptoId', $nodep)->orderBy('DptoNom')->get();
        $city = City::all();
        $workflow = Workflow::all();
        return view('admin.task.create', compact('application', 'workflow', 'application', 'state', 'city'));
    }

    public function cities($dptoid)
    {
        //$nodep = [18, 19, 20, 999];
        //$state = State::whereNotIn('DptoId', $nodep)->orderBy('DptoNom')->get();
        //return $state;
        $city = City::where('CiuDptoID', $dptoid)
            ->whereNotIn('CiuId', [998, 999])
            ->get(); //->sortBy("CiuNom"); //->pluck("CiuNom", "CiuId");
        return $city;
        //return json_encode($city, JSON_FORCE_OBJECT);
        //return json_encode($city, JSON_UNESCAPED_UNICODE);
    }

    public function store(StoreTask $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['state_id'] = $request->getStateId();
        $sanitized['city_id'] = $request->getCityId();
        $sanitized['workflow_state_id'] = $request->getWorkFlowId();



        //return $sanitized;
        // Store the Task
        $task = Task::create($sanitized);

        $status = new ApplicationStatus;
        $status->task_id = $task->id;
        $status->status_id = 1;
        $status->user = Auth::user()->id;
        //$status->user_model = 'App\Models\User';
        $status->save();

        if ($request->ajax()) {
            return ['redirect' => url('admin/applications/' . $sanitized['NroExp'] . '/show'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/tasks');
    }

    public function edit(Application $application, Task $task)
    {
        //$this->authorize('admin.resume.edit', $resume);
        //dd($resume);
        //return $application;
        $nodep = [18, 19, 20, 999];
        $state = State::whereNotIn('DptoId', $nodep)->orderBy('DptoNom')->get();
        $workflow = Workflow::all();
        $city = City::all();

        return view('admin.task.edit', [
            'application' => $application,
            'task' => $task,
            'state' => $state,
            'workflow' => $workflow,
            'city' => $city
        ]);
    }

    public function update(UpdateTask $request, Task $task)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['state_id'] = $request->getStateId();
        $sanitized['city_id'] = $request->getCityId();
        $sanitized['workflow_state_id'] = $request->getWorkFlowId();
        //return $sanitized;
        // Update changed values Task
        $task->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/applications/' . $sanitized['NroExp'] . '/show'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/tasks');
    }
}
