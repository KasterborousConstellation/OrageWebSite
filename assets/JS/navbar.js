console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
function hamburger(elem){
    if(elem.classList.contains("expand")){
        elem.classList.remove("expand");
    }else{
        elem.classList.add("expand");
    }
}
function profileDropdown(elem){
    if(document.body.clientWidth > 850) return;
    if(elem.classList.contains("button-profile-active")){
        elem.classList.remove("button-profile-active");
    }else{
        elem.classList.add("button-profile-active");
    }
}
const elem = document.getElementById("navbar");
document.getElementById("nav-hamburger").onclick = () => hamburger(elem);
const elem2 = document.getElementsByClassName("button-profile");
for(let i=0; i<elem2.length; i++){
    var button = elem2[i];
    button.onclick = () => profileDropdown(button.parentElement);
}