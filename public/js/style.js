let listBtns = document.querySelectorAll(".app-main__sidebar .sidebar-menu li > a");
for (i = 0; i < listBtns.length; i++) {
    listBtns[i].addEventListener("click", function (e) {
        let list = document.querySelectorAll('.app-main__sidebar .sidebar-menu li');
        for (j = 0; j < list.length; j++) {
            list[j].classList.remove("active")
        }
        this.parentElement.classList.add("active")
    })
}
