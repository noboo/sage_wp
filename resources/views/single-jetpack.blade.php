{{--
  Template Name: Custom post
  Template Post Type: jetpack-portfolio
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.jetpack-single-'.get_post_type())
  @endwhile
@endsection
