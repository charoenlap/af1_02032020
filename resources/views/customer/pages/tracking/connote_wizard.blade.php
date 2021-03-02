<div class="row mw-center" style="margin-top: 5%; padding: 0 6%;">
    <h3>งานส่งของ</h3>
    <div class="pearls pearls-lg row">
        <div class="pearl col-xs-3"
        v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status != 'cancel') ? 'current' : '') : ((connoteModel.status != 'cancel') ? 'current' : ''))">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-dropbox" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!connoteModel.job) ? ((connoteModel.status != 'cancel') ? 'current' : '') : '')">
                    <small>จัดเตรียมพัสดุ
                        <p class="mw-mg-0"><small>@{{ connoteModel.pending_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-3"
        v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'new' || connoteModel.job.status == 'inprogress' || connoteModel.job.status == 'complete') ? 'current' : '') : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-thumbs-o-up" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'new') ? 'current' : '') : '')">
                    <small>รออนุมัติ
                        <p class="mw-mg-0" v-if="!!connoteModel.job"><small>@{{ connoteModel.job.new_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-3"
        v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'inprogress' || connoteModel.job.status == 'complete') ? 'current' : '') : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-motorcycle" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'inprogress') ? 'current' : '') : '')">
                    <small>กำลังไปส่ง
                        <p class="mw-mg-0" v-if="!!connoteModel.job"><small>@{{ connoteModel.job.inprogress_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-3"
        v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'complete') ? 'current' : '') : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-check" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'complete') ? 'current' : '') : '')">
                    <small>ส่งของเรียบร้อย
                        <p class="mw-mg-0" v-if="!!connoteModel.job"><small>@{{ connoteModel.job.complete_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
    </div>
</div>