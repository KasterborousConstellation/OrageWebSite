console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
function hamburger(){
    const elem = document.getElementById("navbar");
    if(elem.classList.contains("expand")){
        elem.classList.remove("expand");
    }else{
        elem.classList.add("expand");
    }
}
document.getElementById("nav-hamburger").onclick = hamburger;