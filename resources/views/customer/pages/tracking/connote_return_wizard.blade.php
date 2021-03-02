<div class="row mw-center" style="margin-top: 5%; padding: 0 6%;">
    <h3>งานส่งของ <small>(ส่งกลับ)</small></h3>
    <div class="pearls pearls-lg row">
        <div class="pearl col-xs-4"
        v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'new' || connoteModel.job.status == 'inprogress' || connoteModel.job.status == 'complete') ? 'current' : '') : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-thumbs-o-up" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'new') ? 'current' : '') : '')">
                    <small>รออนุมัติ
                        <p class="mw-mg-0" v-if="!!connoteModel.job"><small>@{{ connoteModel.job.new_datetime_return }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-4"
        v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'inprogress' || connoteModel.job.status == 'complete') ? 'current' : '') : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-motorcycle" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'inprogress') ? 'current' : '') : '')">
                    <small>กำลังส่งกลับ
                        <p class="mw-mg-0" v-if="!!connoteModel.job"><small>@{{ connoteModel.job.inprogress_datetime_return }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-4"
        v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'complete') ? 'current' : '') : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-check" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!!connoteModel.job) ? ((connoteModel.job.status == 'complete') ? 'current' : '') : '')">
                    <small>ส่งกลับของเรียบร้อย
                        <p class="mw-mg-0" v-if="!!connoteModel.job"><small>@{{ connoteModel.job.complete_datetime_return }}</small></p>
                    </small>
                </span>
            </a>
        </div>
    </div>
</div>