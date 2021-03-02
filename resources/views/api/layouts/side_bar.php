<div class="collapse navbar-collapse">
    <ul class="nav navbar-nav side-nav" id="side-menu">
        <?php foreach ($models as $model): ?>
        <li>
            <a href="#"><i class="fa fa-folder-open fa-fw"></i><?php echo ' '.$model['class'] ?><span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <?php foreach ($model['method'] as $method): ?>
                <li class="li-apidoc-sidebar" data-value="<?php echo strtolower($model['class']).'-'.strtolower($method['verb']) ?>" >
                    <a href="#"><?php echo $method['verb'] ?> </a>
                </li>
                <?php endforeach ?>
            </ul>
        </li>
        <?php endforeach ?>
        <li class="li-apidoc-sidebar" data-value="fail-case">
            <a href="#"><i class="fa fa-warning fa-fw"></i> Fail Case</a>
        </li>
    </ul>
</div>