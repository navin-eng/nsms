// data-aos-duration="3000"
let lincolnCourseWrapper = document.querySelectorAll('.lincolnCourseWrapper');
lincolnCourseWrapper.forEach(function (item,index){
    item.setAttribute('data-aos','fade-up');
});


// Sidebar
let sideBarWrapper = document.querySelector('#sideBarWrapper');
let hamBurger = document.querySelector('.hamBurger');
let sideClose = document.querySelector('.sideClose');
hamBurger.addEventListener("click", ()=>{
    sideBarWrapper.classList.toggle('sidbarActive');
});
sideClose.addEventListener("click",()=>{
    sideBarWrapper.classList.remove('sidbarActive');
});


function Whatsapp()
{
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var phone = document.getElementById('phone').value;
    var address = document.getElementById('address').value;
    var desc = document.getElementById('textArea').value;
    var whatsAppUrl = 'https://wa.me/9812355717?text='
    + "Customer Name: " + name + "%0a" +
    "Number: " + phone + "%0a"+
    "Email: " + email + "%0a"
    + "Address: " + address + "%0a"
    + "More Info: " + desc;
    window.open(whatsAppUrl,"_blank").focus();
    name.value ="";
    email.value ="";
    phone.value ="";
    address.value ="";
    desc.value ="";
}
