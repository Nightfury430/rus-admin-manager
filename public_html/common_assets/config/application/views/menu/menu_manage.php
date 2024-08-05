<div class="col-lg-12 col-md-12 mb-3 ">
    <div class="card h-10">
        <div class="card-header" style="padding: 0.8rem !important">
            <h2 style="margin-bottom : 0rem"><?php echo $lang_arr['menu_manage']?></h2>
        </div>
    </div>
</div>

<div id="menu_container">
    <div class="card">
        <div class="card-header header-elements">
            <h5 class="mb-0 me-2"><?php echo $lang_arr['menu_manage'] ?></h5>
        </div>
        <div class="card-body">
            <div class="row row-bordered g-0 p-3">
                <div class="col-lg-6">
                    <div id="jstree"></div>
                </div>
                <div class="col-lg-6 p-3 ">
                    <form id="menu_insert_form" class="needs-validation" novalidate>
                        <div class="mb-6">
                            <label class="form-label" for="title">Title</label>
                            <input
                            type="text"
                            class="form-control"
                            id="title"
                            name="title"
                            required />
                            <div class="invalid-feedback">Please enter title</div>
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="page_url">Url</label>
                            <input
                            type="text"
                            class="form-control"
                            id="page_url"
                            name="page_url"
                            required />
                            <div class="invalid-feedback">Please enter Url</div>
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="icon_name">Icon</label>
                            <input
                            type="text"
                            class="form-control"
                            id="icon_name"
                            name="icon_name"
                            required
                            />
                            <div class="invalid-feedback">Please enter icon</div>
                        </div>
                        <div class="row">
                            <div class="col-12 flex" style="display: flex; justify-content: space-evenly;" >
                                <button id="form_delete" class="btn rounded-pill btn-danger waves-effect waves-light">Delete</button>
                                <button type="submit" id="form_update" class="btn rounded-pill btn-success waves-effect waves-light">Update</button>
                                <button type="submit" id="form_submit" class="btn rounded-pill btn-primary waves-effect waves-light">Add</button>
                            </div>
                        </div>
                        <input type="hidden" id="node_id" name="node_id" value="0" />
                        <input type="hidden" id="selected_id" name="selected_id" value="0" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>