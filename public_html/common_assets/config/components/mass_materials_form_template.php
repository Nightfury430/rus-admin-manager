<div>
    <div class="ibox ">
        <div class="ibox-content">

            <div class="row mb-3">
                <div class="col-2">

                </div>
                <div class="col-5 align-self-center">Параметр</div>
                <div class="col-5 align-self-center">
                    Значение
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.roughness" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('roughness')}}</div>
                <div class="col-5">
                    <input :disabled="enabled.roughness == 0" class="form-control form-control-sm" v-model="value.roughness" type="number" min="0" step="0.01">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.metalness" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('metalness')}}</div>
                <div class="col-5">
                    <input :disabled="enabled.metalness == 0" class="form-control form-control-sm" v-model="value.metalness" type="number" min="0" step="0.01">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.transparent" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('transparent')}}</div>
                <div class="col-5">
                    <select :disabled="enabled.transparent == 0" v-model="value.transparent" class="form-control form-control-sm">
                        <option value="0">{{lang('no')}}</option>
                        <option value="1">{{lang('yes')}}</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0" v-model="enabled.opacity" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('opacity')}}</div>
                <div class="col-5">
                    <input :disabled="enabled.opacity == 0" class="form-control form-control-sm" v-model="value.opacity" type="number" min="0" step="0.01">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.rotation" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('material_rotation')}}</div>
                <div class="col-5">
                    <select :disabled="enabled.rotation == 0" v-model="value.rotation" class="form-control form-control-sm">
                        <option value="normal">{{lang('default')}}</option>
                        <option value="rotated">Повернуто</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.real_width" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('texture_real_width')}}</div>
                <div class="col-5">
                    <input :disabled="enabled.real_width == 0" class="form-control form-control-sm" v-model="value.real_width" type="number" min="0" step="0.01">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.real_height" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('texture_real_heght')}}</div>
                <div class="col-5">
                    <input :disabled="enabled.real_height == 0" class="form-control form-control-sm" v-model="value.real_height" type="number" min="0" step="0.01">
                </div>
            </div>



            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.stretch_width" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('stretch_width')}}</div>
                <div class="col-5">
                    <select :disabled="enabled.stretch_width == 0" v-model="value.stretch_width" class="form-control form-control-sm">
                        <option value="0">{{lang('no')}}</option>
                        <option value="1">{{lang('yes')}}</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.stretch_height" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('stretch_height')}}</div>
                <div class="col-5">
                    <select :disabled="enabled.stretch_height == 0" v-model="value.stretch_height" class="form-control form-control-sm">
                        <option value="0">{{lang('no')}}</option>
                        <option value="1">{{lang('yes')}}</option>
                    </select>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-2">
                    <label style="scale: 0.8" class="switch">
                        <input :true-value="1" :false-value="0"  v-model="enabled.wrapping" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-5 align-self-center">{{lang('wrapping_type')}}</div>
                <div class="col-5">
                    <select :disabled="enabled.wrapping == 0" v-model="value.wrapping" class="form-control form-control-sm">
                        <option value="mirror">{{lang('wrapping_type_mirror')}}</option>
                        <option value="repeat">{{lang('wrapping_type_repeat')}}</option>
                    </select>
                </div>
            </div>


        </div>
    </div>

</div>