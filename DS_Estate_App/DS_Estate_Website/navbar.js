//function to toggle the visibility of the menu
function toggleMenu() {

    //get the DOM element with the ID 'myLinks'
    var x = document.getElementById("myLinks");
    
    //check the current display style of the element
    if (x.style.display === "block") {
        x.style.display = "none"; //if the display style is 'block', set it to 'none' to hide the element
    } else {
        x.style.display = "block"; //if the display style is not 'block', set it to 'block' to show the element
    }
    
}
