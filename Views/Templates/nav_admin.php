    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= media(); ?>/images/man.png" alt="User Image">
        <div>
          <p class="app-sidebar__user-name"><?= $_SESSION['userData']['nombres']; ?> <?= $_SESSION['userData']['apellidos']; ?></p> <!-- esto se coloca para que en elnav valla el nobre y apellido que viene en el array  -->
          <p class="app-sidebar__user-designation"> <?= $_SESSION['userData']['nombre_rol']; ?></p>
          <!--esto se coloca para colocar en el nav el nombre del rol correspondiente  -->
        </div>
      </div>
      <ul class="app-menu">
        <li><a class="app-menu__item" href="<?= base_url(); ?>dashboard"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>

        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Usuarios</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>roles"><i class="icon fa fa-circle-o"></i> Roles</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>permisos"><i class="icon fa fa-circle-o"></i> Permisos</a></li>
          </ul>
        </li>

        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Clientes</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>roles"><i class="icon fa fa-circle-o"></i> Roles</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>permisos"><i class="icon fa fa-circle-o"></i> Permisos</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-archive"></i>
            <span class="app-menu__label">Produtos</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>

          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>roles"><i class="icon fa fa-circle-o"></i> Roles</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>permisos"><i class="icon fa fa-circle-o"></i> Permisos</a></li>
          </ul>
        </li>

        <li><a class="app-menu__item" href="<?= base_url(); ?>pedidos">
            <i class="app-menu__icon fa fa-shopping-cart"></i>
            <span class="app-menu__label">Pedidos</span></a>
        </li>

        <li><a class="app-menu__item" href="<?= base_url(); ?>logout">
            <i class="app-menu__icon fa fa-sign-out"></i>
            <span class="app-menu__label">Logout</span></a>
        </li>
      </ul>

    </aside>