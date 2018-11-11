var grayColor = "#e7e7e7";

function onImageClicked(event) {
    var currentImage = event.currentTarget;
    var index = Array.prototype.slice.call(document.getElementsByClassName("staff-image"), 0).indexOf(currentImage);
    var staffDescriptions = document.getElementsByClassName("staff-description");
    var staffSections = document.getElementsByClassName("staff-section");
    for (var i = 0; i < staffDescriptions.length; i++) {
        var currentIndex = (index == i);
        if (currentIndex) {
            if (staffDescriptions[i].style.display == "block") {
                staffDescriptions[i].style.display = "none";
                staffSections[i].style.backgroundColor = "";
            } else {
                staffDescriptions[i].style.display = "block";
                staffSections[i].style.backgroundColor = grayColor;
            }
        } else {
            staffDescriptions[i].style.display = "none";
            staffSections[i].style.backgroundColor = "";
        }
    }
}