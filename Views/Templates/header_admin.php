<!DOCTYPE html>
<html lang="en">

<head>

  <!-- todos los significados de las metas esta en el one note "clases backend - en apuntes para el SEO" -->
  <meta charset="utf-8">
  <meta name="description" content="tienda virtual">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="riveros">
  <meta name="theme-color" content=" #009688">
  <!-- icono en el navegador -->
  <link rel="shortcut icon" href="<?= media(); ?>/images/shopping-cart.png">
  <title><?= $data['page_tag']; ?></title>
  <!-- estilos para el plugin de select un buscador -->
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/bootstrap-select.min.css">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css">
  <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">

</head>

<body class="app sidebar-mini">
  <!-- Navbar-->
  <header class="app-header"><a class="app-header__logo" href="<?= base_url(); ?>dashboard">Tienda Virtual</a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">

      <!-- User Menu-->
      <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right">
          <li><a class="dropdown-item" href="<?= base_url(); ?>/Opciones"><i class="fa fa-cog fa-lg"></i> Opciones</a></li>
          <li><a class="dropdown-item" href="<?= base_url(); ?>/Perfil"><i class="fa fa-user fa-lg"></i> Perfil</a></li>
          <li><a class="dropdown-item" href="<?= base_url(); ?>/Logout"><i class="fa fa-sign-out fa-lg"></i> Cerrar</a></li>
        </ul>
      </li>
    </ul>
  </header>