

let deleteWrap = document.querySelectorAll('.delete-wrap');
let alertBox = document.querySelector('.alertBox');
let deleteAlert = document.querySelector('.delete-alert');
let cancel = document.querySelector('.cancel');
let DeletNow = document.querySelector('.DeletNow');
deleteWrap.forEach(function (item) {
    item.addEventListener("click", () => {
        let a = item.getAttribute('data-route');
        alertBox.style.display = "flex";
        DeletNow.setAttribute('href', a);
    });
});

cancel.addEventListener("click", function () {
    alertBox.style.display = "none";
});
