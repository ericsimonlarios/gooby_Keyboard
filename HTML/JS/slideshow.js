var slideIndex = 1;
var slide=0;
var imgArray=["../RES/awesome-bg-kb.jpg","../RES/categories-sw.jpg", "../RES/kb-categories.jpg"];
var timeout;
imgLoop();

function moveImg(n){  
    showImg(slideIndex += n)
   if(n<0){
       slide-=2;
       clearTimeout(timeout)
   }else{
       clearTimeout(timeout)   
   }
   imgLoop();
   
}
function changeImg(n){
    showImg(slideIndex = n)
    slide=n;
}
function showImg(n){
    var dots = document.getElementsByClassName("flickity-dots");
    if(n > dots.length){
        slideIndex = 1;
        n = slideIndex;
    }
    if (n <= 0){
        slideIndex = 3;
        n = slideIndex;
    }
    for(var i=0; i < dots.length; ++i){
        dots[i].className = dots[i].className.replace("current-img", " ");    
    }
    for (var j=0; j<=dots.length; j++){
        if(j == n-1){
            dots[j].classList.add("current-img");
            document.getElementById("img-slide-show").src=imgArray[j];
            break;
        }
    }
    
}
function imgLoop(){
    var dots = document.getElementsByClassName("flickity-dots");
   
    if(slide < 0){
        slide = 2;
    }
    if(slide > dots.length){
        slide = dots.length;
    }
    if(slide == dots.length){
        slide = 0;
    }
    for(var i=0; i < dots.length; ++i){
        dots[i].className = dots[i].className.replace("current-img", " ");    
    }
    for (var j=0; j<=dots.length; j++){
        if(j == slide){
            dots[j].classList.add("current-img");
            document.getElementById("img-slide-show").src=imgArray[j];
            break;
        }
    }
    ++slide; 
    timeout = setTimeout(imgLoop, 2000);
}