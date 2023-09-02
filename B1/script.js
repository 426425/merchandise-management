
function toggleSubMenu(event) {
    var subMenu = document.getElementById("subMenu");
    subMenu.classList.toggle("active");
    event.stopPropagation();
}

function changePage(event, url) {
    event.preventDefault(); // 阻止默认跳转行为
    var rightContent = document.getElementById("rightContent");
    rightContent.src = url;
    var subMenu = document.getElementById("subMenu");
    var buttons = subMenu.getElementsByClassName("Left-button");
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("active");
    }
    event.target.classList.add("active");
    subMenu.classList.remove("active");
}

window.addEventListener("click", function(event) {
    var subMenu = document.getElementById("subMenu");
    if (!subMenu.contains(event.target)) {
        subMenu.classList.remove("active");
    }
});

window.onload = function() {
    var rightContent = document.getElementById("rightContent");
    rightContent.src = initialPage;
};

