<div class="row mw-center" style="margin-top: 5%; padding: 0 6%;">
    <h3>สถานะปัจจุบัน</h3>
    <div class="pearls pearls-lg row">
        <div class="pearl col-xs-3 current">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-dropbox" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((!connoteModel.job) ? 'current' : '')">
                    <small>จัดเตรียมพัสดุ</small>
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
                    <small>รออนุมัติ</small>
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
                    <small>กำลังไปส่ง</small>
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
                    <small>ส่งของเรียบร้อย</small>
                </span>
            </a>
        </div>
    </div>
</div>