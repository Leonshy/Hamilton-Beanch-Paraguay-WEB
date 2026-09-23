@extends('layouts.app')

@php
    $seoDescription = $page->meta_description
        ?: \Illuminate\Support\Str::limit(strip_tags($page->subtitle ?: $page->content ?: ''), 160);
    $seoImage = $page->og_image ?: $page->featuredImage?->url;
@endphp

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $seoDescription)
@section('canonical', route('frontend.pages.show', $page->slug))
@section('robots', $page->no_index ? 'noindex, nofollow' : 'index, follow')
@section('og_title', $page->og_title ?: $page->title)
@section('og_description', $page->og_description ?: $seoDescription)
@section('og_image', $seoImage ?: '')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $page->title }}</h1>

    @if($page->subtitle)
        <p class="text-lg text-gray-500 mb-8">{{ $page->subtitle }}</p>
    @endif

    @if($page->content)
    <div class="prose prose-gray max-w-none
                prose-headings:font-bold prose-headings:text-gray-900
                prose-h2:text-xl prose-h2:mt-8 prose-h2:mb-3
                prose-h3:text-base prose-h3:mt-6 prose-h3:mb-2
                prose-p:text-gray-600 prose-p:leading-relaxed prose-p:mb-4
                prose-ul:text-gray-600 prose-ul:space-y-1 prose-ul:pl-5 prose-ul:list-disc
                prose-ol:text-gray-600 prose-ol:space-y-1 prose-ol:pl-5 prose-ol:list-decimal
                prose-li:leading-relaxed
                prose-strong:text-gray-900 prose-strong:font-semibold
                prose-a:text-brand prose-a:underline hover:prose-a:text-brand-dark">
        {!! $page->content !!}
    </div>
    @endif

</div>
@endsection
