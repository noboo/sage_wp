<footer class="container mt-5">
  <div class="border-top pt-5">
    @php dynamic_sidebar('sidebar-footer') @endphp
    <hr class="mt-5"/><small> ©
      <?php echo date('Y');?>
      <a class="brand"
        href="<?= esc_url(home_url('/')); ?>">
        <?php bloginfo('name'); ?>
      </a>. design by
      <a class="デジタルバス" href="//www.digitalbath.jp/">
        DigitalBath
      </a>.</small>
</div>
</footer>
<span class="page-top display-4">△</span>
@if (wp_is_mobile())
@if (has_nav_menu('mobile_navigation'))
        {!! wp_nav_menu([
          'menu' => 'mobile_navigation',
          'theme_location'  => 'mobile_navigation',
          'menu_class'      => 'm-0 bg-light list-unstyled d-flex justify-content-around fixed-bottom',
          'menu_id'         => 'bottomenu',
          'before'          => '<div class="m-2">',
          'after'           => '</div>',
          'link_before'     => '<span class="text-primary col">',
          'link_after'      => '</span>'  
          ]) !!}
          @endif
          @endif
<script
    	src="<?php echo get_template_directory_uri(); ?>/views/jq/jq.js">
    </script>