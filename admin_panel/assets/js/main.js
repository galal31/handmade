// add hovered class to selected list item
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};
const menuItems = document.querySelectorAll(".navigation li");
const sections = document.querySelectorAll(".content-section");

document.getElementById('products-tab').addEventListener('click', function() {
  showContent('products');
});
document.getElementById('sellers-tab').addEventListener('click', function() {
  showContent('sellers');
});
document.getElementById('customers-tab').addEventListener('click', function() {
  showContent('customers');
});
document.getElementById('orders-tab').addEventListener('click', function() {
  showContent('orders');
});

function showContent(section) {
  // Hide all sections
  document.getElementById('products-content').style.display = 'none';
  document.getElementById('sellers-content').style.display = 'none';
  document.getElementById('customers-content').style.display = 'none';
  document.getElementById('orders-content').style.display = 'none';

  // Show the clicked section
  document.getElementById(section + '-content').style.display = 'block';
}
// وظيفة لإظهار القسم المختار وإخفاء الأقسام الأخرى
// menuItems.forEach((item, index) => {
//     item.addEventListener("click", () => {
//         sections.forEach((section) => {
//             section.style.display = "none"; // إخفاء جميع الأقسام
//         });
//         sections[index].style.display = "block"; // إظهار القسم المختار
//     });
// });

