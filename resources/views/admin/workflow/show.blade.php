@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.workflow-state.actions.flow'))

@section('body')

<div>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-pencil"></i> {{ trans('admin.workflow.actions.show') }} >> {{$workflow->name}}
            <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/workflow-states/create/'.$workflow->id) }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.workflow-state.actions.create') }}</a>
        </div>
        <div class="card-body">
    <workflow-state-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/workflow-states') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <table class="table table-hover table-listing">
                    <thead>
                        <tr>
                            <!--<th class="bulk-checkbox">
                                <input class="form-check-input" id="enabled" type="checkbox" v-model="isClickedAll" v-validate="''" data-vv-name="enabled"  name="enabled_fake_element" @click="onBulkItemsClickedAllWithPagination()">
                                <label class="form-check-label" for="enabled">
                                    #
                                </label>
                            </th>-->
                            <!--<th is='sortable' :column="'id'">{{ trans('admin.workflow-state.columns.id') }}</th>-->
                            <th>Color</th>
                            <th is='sortable' :column="'name'">{{ trans('admin.workflow-state.columns.name') }}</th>
                            <th is='sortable' :column="'isactive'">{{ trans('admin.workflow-state.columns.isactive') }}</th>
                            <th></th>
                        </tr>
                        <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                            <td class="bg-bulk-info d-table-cell text-center" colspan="6">
                                <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('/admin/workflow-states')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span class="text-primary">|</span> <a
                                            href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>
                                <span class="pull-right pr-2">
                                    <button class="btn btn-sm btn-danger pr-3 pl-3" @click="bulkDelete('/admin/workflow-states/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                </span>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                            <!--<td class="bulk-checkbox">
                                <input class="form-check-input" :id="'enabled' + item.id" type="checkbox" v-model="bulkItems[item.id]" v-validate="''" :data-vv-name="'enabled' + item.id"  :name="'enabled' + item.id + '_fake_element'" @click="onBulkItemClicked(item.id)" :disabled="bulkCheckingAllLoader">
                                <label class="form-check-label" :for="'enabled' + item.id">
                                </label>
                            </td>-->
                            <!--<td>@{{ item.id }}</td>-->
                            <td v-bind:style="{ backgroundColor: item.color }" ></td>
                            <td>@{{ item.name }}</td>
                            <td>@{{ item.isactive }}</td>

                            <td>
                                <div class="row no-gutters">
                                    <div class="col-auto">
                                        <a class="btn btn-sm btn-spinner btn-warning" :href="item.resource_url + '/show/' + {{ $workflow->id }}" title="{{ trans('brackets/admin-ui::admin.btn.navigation') }}" role="button"><i class="fa fa-exchange"></i></a>
                                    </div>
                                    <div class="col-auto">
                                        <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="{{ trans('brackets/admin-ui::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                    </div>
                                    <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row" v-if="pagination.state.total > 0">
                    <div class="col-sm">
                        <span class="pagination-caption">{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                    </div>
                    <div class="col-sm-auto">
                        <pagination></pagination>
                    </div>
                </div>

                <div class="no-items-found" v-if="!collection.length > 0">
                    <i class="icon-magnifier"></i>
                    <h3>{{ trans('brackets/admin-ui::admin.index.no_items') }}</h3>
                    <p>{{ trans('brackets/admin-ui::admin.index.try_changing_items') }}</p>
                    <a class="btn btn-primary btn-spinner" href="{{ url('admin/workflow-states/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.workflow-state.actions.create') }}</a>
                </div>
            </div>
            <div class="col">
                <diagram ref="diag"
                :datos="{{$graph->toJson()}}"
                :datatransitions="{{json_encode($datatransitions)}}"
             style="border: solid 1px black; width:100%; height:400px">
            </diagram>
            </div>
        </div>
    </workflow-state-listing>

        </div>
        <div class="card-footer">

        </div>



    </div>

</div>

@endsection

