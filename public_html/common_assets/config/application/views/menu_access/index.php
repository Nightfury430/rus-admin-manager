<div class="col-lg-12 col-md-12 mb-3 ">
    <div class="card h-10">
        <div class="card-header" style="padding: 0.8rem !important">
            <h2 style="margin-bottom : 0rem"><?php echo $lang_arr['user_menu_access_control']?></h2>
        </div>
    </div>
</div>
<div class="alert alert-danger alert-dismissible" role="alert">
    Сначала вам нужно выбрать пользователя.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<div id="menu_access_container">
    <div class="card">
        <div class="card-header header-elements">
            <h5 class="mb-0 me-2"><?php echo $lang_arr['user_menu_access_control'] ?></h5>
            <div class="card-header-elements ms-auto">
                <button type="button" class="btn btn-primary" id="user_menu_access_control_save">
                    <i class="fa-solid fa-arrow-down-from-arc"></i>
                    <?php echo $lang_arr['save']?>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-0 p-3">
                <div class="col-lg-6 mb-6 mb-xl-0">
                    <small class="text-light fw-medium">Users</small>
                    <div class="demo-inline-spacing mt-4">
                        <div class="list-group list-group-flush">
                            <?php 
                                foreach($users as $key=>$user){
                                    echo '<a onClick="MenuAccess.selectUser('.$user->id.')" class="list-group-item list-group-item-action visited-user"
                            >'.$user->first_name.' '. $user->middle_name .' '. $user->last_name .'</a>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div id="jstree"></div>
                </div>
            </div>
        </div>
        <input type="hidden" id="node_id" value="0"></input>
    </div>
</div>