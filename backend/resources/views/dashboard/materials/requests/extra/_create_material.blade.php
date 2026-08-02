<div class="modal fade" id="create_visitor_modal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard.add_materials') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 100vh;">
                <div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-8">
                                    <label for="">{{ __('dashboard.selectmaterials') }}</label>
                                    <select class="form-control  select2" id="selectmaterials" name="materials[]" multiple>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <a href="#" id="addNewMaterial"
                                       class="btn btn-primary mt-6" >
                                        <i class="flaticon2-plus" style="font-size: 1rem;"></i>{{ __('dashboard.add_new') }}
                                    </a>
                                    <a href="#" id="saveMaterials"
                                       class="btn btn-success mt-6">{{ __('dashboard.save') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="materialNewForm" style="display:none;">
                            <div class="card">
                                <div class="card-body" style="padding: 0.25rem !important;">
                                    <form id="material_form" class="form">
                                        <div class="card-body">
                                            <h3 class="font-size-lg text-dark font-weight-bold mb-6">1. {{__('dashboard.materialInfo')}}:</h3>
                                            <div class="mb-15">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{ __('dashboard.materialName') }}: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <input type="text" name="name" class="form-control" placeholder="{{ __('dashboard.materialName') }}" required/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{ __('dashboard.materialDescription') }}: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <textarea name="description" class="form-control" id="material_description" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{ __('dashboard.materialQty') }}: <span class="text-danger">*</span></label>
                                                    <div class="col-lg-9">
                                                        <input type="number"
                                                               oninput="javascript: if (this.value.length > 1) this.value = this.value.slice(0, 1);"
                                                               name="quantity" class="form-control" placeholder="{{ __('dashboard.materialQty') }}" required/>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-lg-3"></div>
                                                    <div class="col-lg-6">
                                                        <a id="storeMaterialForm" type="button" class="btn btn-success mr-2">{{ __('dashboard.save') }}</a>
                                                        <button type="reset" class="btn btn-secondary">{{ __('dashboard.cancel') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
