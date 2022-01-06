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
use App\Models\WorkflowState;
use App\Models\ApplicationStatus;
use App\Models\Category;
use App\Models\WorkflowNavigation;
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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use Carbon\Carbon;

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
                $query->where('TexCod', 188);
                if ($request->search) {

                    $query->where(function ($query) use ($request) {
                        $query->where('NroExpPer', $request->search)
                              ->orWhere('NroExp', $request->search);
                    })->where(function ($query) {
                        $query->where('NroExpS', 'A');
                        $query->where('TexCod', 188);
                    });
                    //return 'funciona';

                //$query->Where('NroExpsol', 'like', '%' . $request->search . '%');
                    //$query->Where('NroExpPer', $request->search)
                    //->OrWhere('NroExp', $request->search);
                    //
                    //$query->OrWhere('NroExp', $request->search);

                }
                //return 'No Funciona';
            })
            //->paginate(15)
            ->get(['NroExp', 'NroExpsol', 'NroExpFch', 'NroExpPer', 'NUsuCod', 'NUsuNombre']);

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

    public function transition(Task $task, WorkflowState $workflowState)
    {
        //return $workflowState;
        $user = Auth::user()->id;
        return view('admin.applications.transition', compact('task', 'workflowState', 'user'));
    }

    public function history(ApplicationStatus $id)
    {
        //return "edit history";
        //  return $id;
        //$user = Auth::user()->id;
        $user = Auth::user()->id;
        return view('admin.applications.transitionedit', compact('id','user'));
    }

    public function show(Application $application)
    {
        //return $application;
        $sol = Task::where('NroExp', $application->NroExp)->first();
        if ($sol) {
            $historial = ApplicationStatus::where('task_id', $sol->id)->orderBy('created_at')->get();
            $navegacion = WorkflowNavigation::where('workflow_state_id', $sol->status->status->id)->get();
        } else {
            $historial = [];
            $navegacion = [];
        }

        //return $sol;

        //return $navegacion;
        //->where('NroExpS', 'A');
        return view('admin.applications.show', compact('application', 'sol', 'historial', 'navegacion'));
    }

    public function create(Application $application)
    {
        //$this->authorize('admin.task.create');
        $nodep = [18, 19, 20, 999];
        $state = State::whereNotIn('DptoId', $nodep)->orderBy('DptoNom')->get();
        $city = City::all();
        $workflow = Workflow::all();
        $category = Category::all();
        return view('admin.task.create', compact('application', 'workflow', 'application', 'state', 'city', 'category'));
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

    public function getPdf(Task $task)
    {

        //$date = Carbon::now();
        //return $date->formatLocalized('%B'); //nombre del mes actual
        setlocale(LC_ALL,'es_ES.UTF-8');
        setlocale(LC_TIME,'es_ES');
        \Carbon\Carbon::setLocale('es_ES');
        $codigoQr = QrCode::size(150)->generate(env('APP_URL') . '/' . $task->certificate_pin);
        $pdf = PDF::loadView(
            'vista_pdf',
            [
                'valor' => $codigoQr,
                'task' => $task
            ]
        );
        return $pdf->download('constancia.pdf');
    }

    public function store(StoreTask $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['state_id'] = $request->getStateId();
        $sanitized['city_id'] = $request->getCityId();
        $sanitized['workflow_id'] = $request->getWorkFlowId();
        $sanitized['category_id'] = $request->getGetCategoryId();

        $key = str_random(25);
        while (Task::where('certificate_pin', $key)->exists()) {
            $key = str_random(25);
        }
        $sanitized['certificate_pin'] = $key;



        //return $sanitized;
        // Store the Task
        $task = Task::create($sanitized);

        $status = new ApplicationStatus;
        $status->task_id = $task->id;
        $status->status_id = 1;
        $status->user_id = Auth::user()->id;
        $status->description = 'Creación de Solicitud';
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
        $category = Category::all();

        return view('admin.task.edit', [
            'application' => $application,
            'task' => $task,
            'state' => $state,
            'workflow' => $workflow,
            'city' => $city,
            'category' => $category
        ]);
    }

    public function update(UpdateTask $request, Task $task)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['state_id'] = $request->getStateId();
        $sanitized['city_id'] = $request->getCityId();
        $sanitized['workflow_state_id'] = $request->getWorkFlowId();
        $sanitized['category_id'] = $request->getGetCategoryId();
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
