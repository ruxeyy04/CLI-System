<div class="gap-2 d-flex align-items-center gap-lg-3">
    <!--begin::Filter menu-->
    @if (Route::currentRouteName() == 'dashboard')
    <div class="d-flex">
        <select name="campaign-type" data-control="select2" data-hide-search="true"
            class="form-select form-select-sm bg-body border-body w-175px">
            <option value="Twitter" selected="selected">HF-304</option>
        </select>
    </div>
    @endif
    <!--end::Filter menu-->
</div>
