<div class="loader-bg">
  <!-- loding -->
  <h1>
      <?php if (get_header_image()): ?>
      <img src="<?php header_image(); ?>"
          alt="<?php bloginfo('name'); ?>"
          class="animated fadeIn">
      <?php else: ?>
      <?php bloginfo('name'); ?>
      <?php endif; ?>
  </h1>
</div>
@php dynamic_sidebar('sidebar-header') @endphp
<header class="banner">
    {{--
      <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
      --}}
    <nav class="nav-primary navbar navbar-expand-lg navbar-light bg-white rounded">
      <div class="container">
      @if ( get_header_image() && wp_is_mobile())
    <a class="brand navbar-brand" href="{{ home_url('/') }}"><img src="@php header_image() @endphp" width="30" height="30" alt="{{ get_bloginfo('name', 'display') }}"></a>
    @endif
      <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#bs4navbar" aria-controls="bs4navbar" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'menu'            => 'primary_navigation',
          'theme_location'  => 'primary_navigation',
          'container'       => 'div',
          'container_id'    => 'bs4navbar',
          'container_class' => 'collapse navbar-collapse',
          'menu_id'         => false,
          'menu_class'      => 'm-0 navbar-nav mr-auto text-md-center nav-justified w-100',
          'depth'           => 2,
          'fallback_cb'     => 'bs4navwalker::fallback',
          'walker'          => new bs4navwalker()
          ]) !!}
      @endif
    </nav>
  </div>
</header>
