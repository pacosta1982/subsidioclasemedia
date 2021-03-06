<div class="form-group row align-items-center" :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.category.columns.name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}" id="name" name="name" placeholder="{{ trans('admin.category.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('percentage'), 'has-success': fields.percentage && fields.percentage.valid }">
    <label for="percentage" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.category.columns.percentage') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.percentage" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('percentage'), 'form-control-success': fields.percentage && fields.percentage.valid}" id="percentage" name="percentage" placeholder="{{ trans('admin.category.columns.percentage') }}">
        <div v-if="errors.has('percentage')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('percentage') }}</div>
    </div>
</div>


