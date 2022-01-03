@extends($folder.'layouts.layout')
@section('title')
    New Media
@endsection('title')

@include($folder.'layouts.sections')
@include($folder.'notifications')
@include($folder.'tools.uploads-library')


@section('content')

    @yield('header')

    @yield('notifications')

    <style>
.uploader-inline{position:relative;top:auto;right:auto;left:auto;bottom:auto;
    border:5px dashed #b4b9be;}
     </style>

    <div class="row">

        <div>

        </div>
    </div>

    <style>
        #drop-area {
            border: 2px dashed #ccc;
        }
        #drop-area.highlight {
            border-color: purple;
        }
        p {
            margin-top: 0;
        }
        .my-form {
            margin-bottom: 10px;
        }
        #gallery {
            margin-top: 10px;
        }
        #gallery img {
            width: 150px;
            margin-bottom: 10px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .button {
            display: inline-block;
            padding: 10px;
            background: #ccc;
            cursor: pointer;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .button:hover {
            background: #ddd;
        }
        #fileElem {
            display: none;
        }

    </style>

    <script>
$(document).ready(function (){

    let dropArea = document.getElementById('drop-area');

    ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false)
    })

    function preventDefaults (e) {
        e.preventDefault()
        e.stopPropagation()
    }
    ;['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false)
    })

    ;['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false)
    })

    function highlight(e) {
        dropArea.classList.add('highlight')
    }

    function unhighlight(e) {
        dropArea.classList.remove('highlight')
    }


    dropArea.addEventListener('drop', handleDrop, false)

    function handleDrop(e) {
        let dt = e.dataTransfer
        let files = dt.files

        handleFiles(files)
    }


})

function handleFiles(files) {
    files = [...files]
    files.forEach(uploadFile)
    files.forEach(previewFile)
}

function uploadFile(file) {
    // var url = 'http://localhost/tutorials/public/dashboard/media?action=upload&auto_login=1&a=23'
    // var xhr = new XMLHttpRequest()
    // var formData = new FormData()
    // xhr.open('POST', url, true)
    //
    // xhr.addEventListener('readystatechange', function(e) {
    //     if (xhr.readyState == 4 && xhr.status == 200) {
    //         // Done. Inform the user
    //         // alert('success');
    //     }
    //     else if (xhr.readyState == 4 && xhr.status != 200) {
    //         // Error. Inform the user
    //         alert('fail');
    //     }
    // })

    // fetch(url, {
    //     method: 'POST',
    //     body: $('#the_form').serialize()
    // })

    // formData.append('file', file)
    // formData.append('file', file)
   // xhr.send($('form').serialize())
}

function previewFile(file) {
    let reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onloadend = function() {
        let img = document.createElement('img')
        img.src = reader.result
        document.getElementById('gallery').appendChild(img)
    }
}
    </script>

    <div class="row justify-content-center">
        <div id="drop-area" class="rounded-lg w-auto m-4 p-3">
            <form id="the_form" class="my-form" method="post" enctype="multipart/form-data">
                @csrf
                <p>Upload multiple files with the file dialog or by dragging and dropping images onto the dashed region</p>
                <input name="image[]" type="file" id="fileElem" multiple accept="*" onchange="handleFiles(this.files)">
                <div class="row justify-content-center">
                    <label class="button" for="fileElem">Select some files</label>
                </div>
                <div class="row justify-content-center mt-5">
                    <button class="btn btn-outline-primary btn-sm" type="submit">Save the selection</button>
                </div>
                <input type="hidden" value="upload" name="action">
            </form>
        </div>
    </div>


    <div class="row justify-content-center">
        <div id="gallery"></div>

    </div>
    <div class="row d-none">
        <div class="col-12">
            @yield('notifications')
<div class="m-3">
    <div class="row uploader-inline p-5 bg-light mt-2 pt-0">
        <div class="d-flex w-100 justify-content-center">

            <div>
                <div class="row my-3">
                    <div class="d-flex w-100 justify-content-center">
                        Drop files here
                    </div>
                </div>

                <div class="row my-3">
                    <div class="d-flex w-100 justify-content-center"><small>or</small></div>

                </div>

                <div class="row my-3">
                    <div class="d-flex w-100 justify-content-center">
                        <input type="hidden" name="action" value="create">
                <input type="file" name="files[]" class="btn btn-outline-primary" value="Select files" multiple>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
            @yield('uploads')
        </div>
    </div>

@endsection('content')
