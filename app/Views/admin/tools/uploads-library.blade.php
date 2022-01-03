@section('uploads')


<div class="container">
    <?php

    $items = \App\Models\Upload::where('id', '>', 0)->orderBy('id', 'desc')->get();
    ?>
        <div class="border shadow-sm card">
            @if($items)

                <hr class="my-3">

                <h3>Recent Image Uploads</h3>
                <div class="row">
                    @foreach($items as $item)
                        <?php

                       $img_url = url('/').$item->guid;
                        ?>
                        <div class="col-3">
                            <div class="card">
                                <img class="img-responsive card-img-top" src="{{ $img_url }}" height="200" width="200">

                                <div class="card-body">
                                    <h6 class="card-title"><div class="overflow-auto text-nowrap"><input type="text" value="{{ asset("$item->guid") }}" disabled></div></h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>



            @else
        Media Library is empty. Uploaded media Lists here
    @endif
        </div>

</div>
@endsection('uploads')
