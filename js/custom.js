document.addEventListener("DOMContentLoaded", (event) => {
  var button = document.createElement("a");
  button.setAttribute("class", "btn btn-donate btn-donate-mobile-nav");
  button.setAttribute(
    "href",
    "https://action.greenpeace.org.nz/appeal/donate?utm_source=web&utm_medium=nav&utm_campaign=greenpeace"
  );
  button.setAttribute("data-ga-category", "Menu Navigation");
  button.setAttribute("data-ga-action", "Donate");
  button.setAttribute("data-ga-label", "Homepage");
  button.innerHTML = "Donate";

  var logo = document.querySelector(".site-logo");
  logo.insertAdjacentElement("afterend", button);
});
