<?php $user = Yii::$app->user->identity?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=$user->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <ul class="sidebar-menu" data-widget="tree">
            <?php foreach (Yii::$app->params['menu']['sidebar'] as $item):?>
                <?php $subHtml = ''?>
                <?php $sign = false?>
                <?php foreach ($item['sub'] as $value):?>
                    <?php $url = ('/' . $this->context->id . '/' . $this->context->action->id)?>
                    <?php if ($url == $value['url']){
                        $sign = true;
                    }?>
                    <?php $subHtml .= '<li class="'. ($url == $value['url'] ? 'active' : '') .'"><a href="' . $value['url'] .'">' . $value['label'] . '</a></li>'; ?>
                <?php endforeach;?>
                <li class="treeview <?=$sign ? 'active' : ''?>">
                    <a href="javascritp:;">
                        <i class="fa <?=$item['icon']?>"></i>
                        <span><?=$item['label']?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <?=$subHtml?>
                    </ul>
                </li>
            <?php endforeach;?>
        </ul>

    </section>

</aside>
