var slideIndex = 1;
var slide=0;
var imgArray=["../RES/awesome-bg-kb.jpg","../RES/categories-sw.jpg", "../RES/kb-categories.jpg"];
var timeout;
imgLoop();
function menuClose(value){
    if(value=="menu-container"){
        document.getElementById(value).style.left="-100%";
        document.getElementById('menu-content-leftover').style.removeProperty('right');
    }else if (value=="cart-content"){  
        document.getElementById(value).style.right="-100%";
        document.getElementById('cart-container').style.display='none';
        document.getElementsByTagName("body")[0].style.overflow='auto';
       
    }
    // document.getElementById(value).style.display="none";
   
}
function menuOpen(value){
    if(value=="menu-container"){
        left = window.getComputedStyle( document.getElementById(value),null).getPropertyValue("left");   
                   
            document.getElementById(value).style.left = "0%";
            document.getElementById('menu-content-leftover').style.right='0';
    }else if(value=="cart-content"){    
        document.getElementById(value).style.right = "0%";
        document.getElementById('cart-body').style.overflow="auto";
        document.getElementById('cart-container').style.display='block';
        document.getElementsByTagName("body")[0].style.overflow='hidden';
    }

}

function shopRedirect(){
    document.location.href="categories.php"
}
function redirect(value){
    window.location.href=value.toString() + ".php";
}
function hideRegister(){
    document.getElementById('menu-register').style.display='none';
}
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
function showArrow(){
    if(document.getElementById("user-settings-container").style.display=="none"){
        document.getElementById("user-settings-container").style.display="block";
        window.addEventListener("click", function(event) {
            if (event.target != document.getElementById('down-arrow')) {
                document.getElementById("user-settings-container").style.display = "none";
            }
        })
    }
    else{
        document.getElementById("user-settings-container").style.display="none";
    }
}
function showBilling(){
    document.getElementById('billing-info-content-container').style.display="flex";
    document.getElementById('no-billing-info-container').style.display="none";
}
function showDetails(mode){
    if(mode=="show"){
        document.getElementById('pay-via-visa-details').style.display="block";
    }else{
        document.getElementById('pay-via-visa-details').style.display="none";
    }
}
  