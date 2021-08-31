@extends('layouts.home')
@section('styles')


@endsection

@section('content')
<!-- Page Title -->
	<div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
		<nav aria-label="breadcrumb" class="page_title_inner_wrap">
			<div class="box-size">	
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
			    <li class="breadcrumb-item active" aria-current="page"><a href="#">Site Map</a></li>
			  </ol>
			</div>
		</nav>
	</div>

<!-- Contact Us -->
	<div class="container-fluid pl-0 pr-0">
		<div class="box-size">
			<div class="site_map_menu_wrap">
				<ul class="nav nav-pills">
				  <li class="active">
				  	<a data-toggle="pill" href="#popular_map" class="active">Most Popular</a>
				  </li>
				  <li>
				  	<a data-toggle="pill" href="#category_map">Categories</a>
				  </li>
				  <li>
				  	<a data-toggle="pill" href="#states_map">States</a>
				  </li>
				  <li>
				  	<a data-toggle="pill" href="#cities_map">Cities</a>
				  </li>
				</ul>
			</div>
			<div class="site_map_tab_content tab-content">
			  <div id="popular_map" class="tab-pane fade in active show">
				    <div class="sitemap_tile_wrap">
				    	<h3>Categories</h3>
				    	<p>Browser through some of our most popular categories</p>
				    </div>
				    <div class="categories_list_site_map">
				    	<ul>
				    		@if(!empty($popularcategories))
                                @foreach($popularcategories as $category)
                                <li>
                                    <div class="cat_icon_img">
                                        <img class ="selectcategory" src="{{url('public/uploads/categories/')}}/{{$category->image}}" data-category_id="{{$category->id}}">
                                        <!-- <i class="selectcategory fa {{$category->icon}}" aria-hidden="true"></i> -->
                                    </div>
                                    <h4>{{$category->name}}</h4>
                                </li>
                                @endforeach
                            @endif
						</ul>
				    </div>
				    <div class="sitemap_tile_wrap">
				    	<h3>Locations</h3>
				    	<p>Browser through some of our most popular Locations.</p>
				    </div>
				    <div class="sitemap_location_list">
				    	@if(!empty($popularcity))
                            @foreach($popularcity as $city)
				    			<a href="javascript:void(0);">{{ ucwords(get_cityname($city->cities,80)) }}</a>
				    		@endforeach
                        @endif
				    </div>
			  </div>
			  <div id="category_map" class="tab-pane fade">
			    <div class="sitemap_tile_wrap">
			    	<h3>Categories</h3>
			    </div>
			    <div class="categories_tab_outer_wrap">
			    	@foreach($parentcate as $key =>$parenttree)
				    	<div class="categories_tab_single_wrap">
				    		<a href="javascript:void(0);" class="categories_tab_title_wrap">{{ucwords($parenttree['name'])}}</a>
				    		<div class="categories_tab_content_outer_wrap">
				    			<div class="categories_tab_content_inner_wrap">
					    			<ul>
					    				@foreach($subcate as $subs)
	                                        @if($parenttree['id'] == $subs['parent_id'])
		                                        <li>
		                                        	<a href="javascript:void(0);">{{ucwords($subs['name'])}}</a>
		                                        </li>
	                                        @endif
                                    	@endforeach
					    			</ul>
					    		</div>
				    		</div>
				    	</div>
			    	@endforeach 
			    </div>
			  </div>

			  <div id="states_map" class="tab-pane fade">
			    <div class="sitemap_tile_wrap">
			    	<h3>Categories</h3>
			    </div>
			    <div class="categories_tab_outer_wrap">
			    	@foreach($sitemapstatecomplete as $key =>$stitemapstate)
			    	<div class="categories_tab_single_wrap">
			    		<a href="#" class="categories_tab_title_wrap">{{ ucwords($key) }}</a>
			    		<div class="categories_tab_content_outer_wrap change_row_categories_tab__outer_wrap">
			    			
			    			<div class="categories_tab_content_inner_wrap">
			    				
				    			<a href="#" class="categories_tab_content_title_wrap"></a>
				    			<ul>
				    				@foreach($stitemapstate as $key =>$stitemapstates)
				    				<li>
				    					<a href="javascript:void(0);">{{$stitemapstates->parent_name}}</a>
				    				</li>
				    				@endforeach
				    			</ul>
				    		</div>
				    		
				    	</div>
			    	</div>
			    	@endforeach 
			    </div>
			  </div>
			  <div id="cities_map" class="tab-pane fade">
			    <div class="sitemap_tile_wrap">
			    	<h3>Cities</h3>
			    </div>
			    <div class="categories_tab_outer_wrap">
			    	
			    	@foreach($sitemapcitycomplete as $key =>$stitemapcity)
			    	<div class="categories_tab_single_wrap">
			    		<a href="#" class="categories_tab_title_wrap">{{ ucwords(get_cityname($key)) }}</a>
			    		<div class="categories_tab_content_outer_wrap change_row_categories_tab__outer_wrap">
			    			
			    			<div class="categories_tab_content_inner_wrap">
			    				
				    			<a href="#" class="categories_tab_content_title_wrap"></a>
				    			<ul>
				    				@foreach($stitemapcity as $key =>$stitemapciy)
				    				<li>
				    					<a href="javascript:void(0);">{{$stitemapciy->parent_name}}</a>
				    				</li>
				    				@endforeach
				    			</ul>
				    		</div>
				    		
				    	</div>
			    	</div>
			    	@endforeach 

			   </div>
			</div>
		</div>
	</div>
@endsection 


@section('scripts')
@endsection