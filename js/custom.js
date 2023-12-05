document.addEventListener('DOMContentLoaded', (event) => {
    var button = document.createElement("a");
    bbutton.setAttribute("class", "btn btn-donate btn-donate-mobile-nav");
    button.setAttribute("href", "https://donate.act.greenpeace.org.nz/p4-donate?source=nav");
    button.setAttribute("data-ga-category", "Menu Navigation");
    button.setAttribute("data-ga-action", "Donate");
    button.setAttribute("data-ga-label", "Homepage");
    button.innerHTML = "Donate";
   
    var logo = document.querySelector('.site-logo');
    logo.insertAdjacentElement('afterend', button);
   });