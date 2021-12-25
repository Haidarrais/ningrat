<div>
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area text-center">
        <div class="container">
            <h1>{{$banner->name}}</h1>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$banner->name}}</li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Breadcrumb Area End -->
    <!-- Post Area Start -->
    <div class="post-area blog-area pt-110 pb-95 post-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="single-post-item text-center pb-70">
                        <h3 class="single-post-title"><a href="blog-details.html">{{$banner->description}}</a></h3>
                        <div class="single-post-meta">
                            <span>Posts by : admin</span>
                            <span>{{$banner->created_at}}</span>
                        </div>
                        <div class="single-post-img">
                            <img src="{{ asset("uploads/contents/$banner->image") }}" alt="">
                        </div>
                        <div class="single-post-info-text text-left">
                            {!!$article->articles!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
