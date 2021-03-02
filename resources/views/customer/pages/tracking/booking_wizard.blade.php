<div class="row mw-center" style="margin-top: 5%; padding: 0 6%;">
    <h3>งานรับของ</h3>
    <div class="pearls pearls-lg row">
        <div class="pearl col-xs-3"
        v-bind:class="((bookingModel.status == 'pending' || bookingModel.status == 'new' || bookingModel.status == 'inprogress' || bookingModel.status == 'complete') ? 'current' : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-child" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((bookingModel.status == 'pending') ? 'current' : '')">
                    <small>รออนุมัติ
                        <p class="mw-mg-0"><small>@{{ bookingModel.pending_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-3"
        v-bind:class="((bookingModel.status == 'new' || bookingModel.status == 'inprogress' || bookingModel.status == 'complete') ? 'current' : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-thumbs-o-up" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((bookingModel.status == 'new') ? 'current' : '')">
                    <small>เริ่มงานรับของ
                        <p class="mw-mg-0"><small>@{{ bookingModel.new_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-3"
        v-bind:class="((bookingModel.status == 'inprogress' || bookingModel.status == 'complete') ? 'current' : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-motorcycle" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((bookingModel.status == 'inprogress') ? 'current' : '')">
                    <small>กำลังไปรับ
                        <p class="mw-mg-0"><small>@{{ bookingModel.inprogress_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
        <div class="pearl col-xs-3"
        v-bind:class="((bookingModel.status == 'complete') ? 'current' : '')">
            <a href="#">
                <div class="pearl-icon">
                    <i class="icon fa-check" aria-hidden="true"></i>
                </div>
                <span class="hidden-xs pearl-title"
                v-bind:class="((bookingModel.status == 'complete') ? 'current' : '')">
                    <small>รับของเรียบร้อย
                        <p class="mw-mg-0"><small>@{{ bookingModel.complete_datetime_label }}</small></p>
                    </small>
                </span>
            </a>
        </div>
    </div>
</div>