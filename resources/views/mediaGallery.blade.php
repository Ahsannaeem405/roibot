@extends('layout.mainlayout')
<link rel="stylesheet" href="{{asset('css/gallery.css')}}">
@section('content')
    <!-- Media Gallery Section -->

    <section class="mediaGallery pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3 text-center">
                    <h2 class="color">Media Gallery</h2>
                </div>
                <div class="col-12 pt-5 pb-5 text-right">
                    <button class="btn btn-primary" style="font-weight: bold">Add New</button>
                    <button class="btn btn-primary ml-2" style="font-weight: bold">Select</button>
                            <button class="btn btn-danger ml-2">Delete</button>
                </div>
                {{-- <div class="col-12 mt-3">
                    <div class="row">
                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="col-12 my-3">
                    <div class="row">
                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div> --}}
            </div>
            <section id="lightbox_gallery" class="container">
                <div class="row">
                <div class=" col-12 col-md-4 col-lg-3 p-3">
                <div class="lightbox-enabled"
                style="background-image: url('{{ asset('images/img_avatar.png')}}')"

                data-imgsrc="{{asset('images/img_avatar.png')}}">

                </div>
                </div>
                <div class=" col-12 col-md-4 col-lg-3 p-3">
                <div class="lightbox-enabled"
                style="background-image:url('{{ asset('images/img_avatar.png')}}')"
                data-imgsrc="{{asset('images/img_avatar.png')}}">
                </div>
                </div>
                <div class=" col-12 col-md-4 col-lg-3 p-3">
                <div class="lightbox-enabled"
                style="background-image:url('{{ asset('images/img_avatar.png')}}')"
                data-imgsrc="{{asset('images/img_avatar.png')}}">
                </div>
                </div>
                <div class=" col-12 col-md-4 col-lg-3 p-3">
                <div class="lightbox-enabled"
                style="background-image:url('{{ asset('images/img_avatar.png')}}')"
                data-imgsrc="{{asset('images/img_avatar.png')}}">
                </div>
                </div>
                <div class=" col-12 col-md-4 col-lg-3 p-3">
                <div class="lightbox-enabled"
                style="background-image:url('{{ asset('images/img_avatar.png')}}')"
                data-imgsrc="{{asset('images/img_avatar.png')}}">
                </div>
                </div>
                <div class=" col-12 col-md-4 col-lg-3 p-3">
                <div class="lightbox-enabled"
                style="background-image:url('{{ asset('images/img_avatar.png')}}')"
                data-imgsrc="{{asset('images/img_avatar.png')}}">
                </div>
                </div>



                </div>
                </section>
                <section class="lightbox-container">
                <span class="material-icons-outlined lightbox-btn left" id="left">
                    <i class="fa fa-angle-left"></i>
                </span>
                <span class="material-icons-outlined lightbox-btn right" id="right">
                    <i class="fas fa-angle-right"></i>
                </span>
                  <span class="close" id="close"><i class="fa fa-times"></i></span>
                  <div class="lightbox-image-wrapper">
                  <img alt="lightboximage" class="lightbox-image">


                  </div>
                </section>

        </div>

    </section>
    <!-- Media Gallery Section -->

<script>

// Much of this code is not from me. I got a good chunk of the functionality from a tutorial I can't remember. I added the animations cause I'm tired of easy-to-implement galleries always looking dull. Thanks for looking! If you end up making any upgrades to the code, please let me know and I'll implement them here. Thanks!
// query selectors
const lightboxEnabled = document.querySelectorAll('.lightbox-enabled');
const lightboxArray = Array.from(lightboxEnabled);
const lastImage = lightboxArray.length-1;
const lightboxContainer = document.querySelector('.lightbox-container');
const lightboxImage = document.querySelector('.lightbox-image');
const lightboxBtns = document.querySelectorAll('.lightbox-btn');
const lightboxBtnRight = document.querySelector('#right');
const lightboxBtnLeft = document.querySelector('#left');
const close = document.querySelector('#close');
let activeImage;
// Functions
const showLightBox = () => {lightboxContainer.classList.add('active')}

const hideLightBox = () => {lightboxContainer.classList.remove('active')}

const setActiveImage = (image) => {
lightboxImage.src = image.dataset.imgsrc;
activeImage= lightboxArray.indexOf(image);
}

const transitionSlidesLeft = () => {
  lightboxBtnLeft.focus();
  $('.lightbox-image').addClass('slideright');
   setTimeout(function() {
  activeImage === 0 ? setActiveImage(lightboxArray[lastImage]) : setActiveImage(lightboxArray[activeImage-1]);
}, 250);


  setTimeout(function() {
    $('.lightbox-image').removeClass('slideright');
}, 500);
}

const transitionSlidesRight = () => {
 lightboxBtnRight.focus();
$('.lightbox-image').addClass('slideleft');
  setTimeout(function() {
   activeImage === lastImage ? setActiveImage(lightboxArray[0]) : setActiveImage(lightboxArray[activeImage+1]);
}, 250);
  setTimeout(function() {
    $('.lightbox-image').removeClass('slideleft');
}, 500);
}

const transitionSlideHandler = (moveItem) => {
  moveItem.includes('left') ? transitionSlidesLeft() : transitionSlidesRight();
}

// Event Listeners
lightboxEnabled.forEach(image => {
   image.addEventListener('click', (e) => {
    showLightBox();
    setActiveImage(image);
    })
    })
lightboxContainer.addEventListener('click', () => {hideLightBox()})
close.addEventListener('click', () => {hideLightBox()})
lightboxBtns.forEach(btn => {
btn.addEventListener('click', (e) => {
e.stopPropagation();
  transitionSlideHandler(e.currentTarget.id);
})
})

lightboxImage.addEventListener('click', (e) => {
e.stopPropagation();

})



</script>
   @endsection
