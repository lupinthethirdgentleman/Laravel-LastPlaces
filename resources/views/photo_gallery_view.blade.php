@extends('layouts.inner')
@section('content')

{{ HTML::style('css/photogallery.css') }}
{{ HTML::script('js/photogallery.js') }}
<style>
a.list-group-item, button.list-group-item {

    color: #555;
    background: #fbe9ca;

}

.thumbnail-img {
    max-height: 135px;
    overflow: hidden;
}
.thumbnail-img img.img-responsive {
    margin: auto;
}

.gallery
{
    width:100%;
}
</style>

<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">

                <h1> {{ trans('messages.'.$slug)}}   </h1>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-3">
                <div class="list-group">
       @foreach($destinationCountry as $country)
        <a href="{{Request::url().'?country=' . $country->destinationCountryDescription[0]->parent_id}}" class="list-group-item">
            <i class="fa fa-image"></i> {{$country->destinationCountryDescription[0]->source_col_description}} <span class="badge">{{$country->destinationCountryDescription[0]->total_images}}</span>
        </a>
        @endforeach
       
        
    </div>
        </div>

         <div class="col-sm-9">
              <div class='list-group gallery '>

                @foreach($photoObj as $listPhotos)

                <div class='col-sm-4 col-xs-6 col-md-4'>
                    <div class="image_hwe">
                    <a class="thumbnail fancybox" title="{{ $listPhotos->title}}" rel="ligthbox" href="<?php echo WEBSITE_URL .'image.php?width=750px&height=450px&image=' . PHOTOGALLERY_IMAGE_URL.$listPhotos->image;?>">
                        <div class="thumbnail-img">
                        <img class="img-responsive" alt="{{$listPhotos->image}}" src="<?php echo WEBSITE_URL .'image.php?width=300px&height=155px&image=' . PHOTOGALLERY_IMAGE_URL.$listPhotos->image;?>" />
                    </div>
                        
                    </a>
                </div>
                    <h6 class="image-title" style="text-align: center;font-size: 18px;">{{ $listPhotos->title}}</h6>

                </div> <!-- col-6 / end -->
            @endforeach
          
         
          
           
        </div> <!-- list-group / end -->
<?php
$link_limit = 6; 
?>
        @if ($photoObj->lastPage() > 1)
            <div class="row">
                <div class="col-sm-12">
                    <nav aria-label="Page navigation" class="blog-pagination">
                      <ul class="pagination">
                        <li class="page-item {{ ($photoObj->currentPage() == 1) ? ' disabled' : '' }}">
                          <a class="page-link" href="{{ ($photoObj->currentPage() == 1) ? 'javascript:void(0)' : $photoObj->url($photoObj->currentPage()-1) }}@if(request('country'))&country={{request('country')}} @endif" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                          </a>
                        </li>

                        @for ($i = 1; $i <= $photoObj->lastPage(); $i++)
                            <?php
                            $half_total_links = floor($link_limit / 2);
                            $from = $photoObj->currentPage() - $half_total_links;
                            $to = $photoObj->currentPage() + $half_total_links;
                            if ($photoObj->currentPage() < $half_total_links) {
                               $to += $half_total_links - $photoObj->currentPage();
                            }
                            if ($photoObj->lastPage() - $photoObj->currentPage() < $half_total_links) {
                                $from -= $half_total_links - ($photoObj->lastPage() - $photoObj->currentPage()) - 1;
                            }
                            ?>

                             @if ($from < $i && $i < $to)
                            <li class="page-item {{ ($photoObj->currentPage() == $i) ? ' active' : '' }}">

                <a class="page-link" href='{{ str_replace('/?','?',$photoObj->url("$i")) }}@if(request('country'))&country={{request('country')}} @endif'>{{ $i }}</a>

                            </li>

                             @endif

                        @endfor

                        <li class="page-item {{ ($photoObj->currentPage() == $photoObj->lastPage()) ? ' disabled' : '' }}">
                          <a class="page-link" href="{{ ($photoObj->currentPage() == $photoObj->lastPage()) ? 'javascript:void(0)' : str_replace('/?','?',$photoObj->url($photoObj->currentPage()+1))   }}@if(request('country'))&country={{request('country')}} @endif" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                          </a>
                        </li>
                      </ul>
                    </nav>
                </div>
            </div>
            @endif
        </div>
</div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
@include('layouts.main.footer_top')
@stop