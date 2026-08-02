const menuBurger  = document.getElementById('menuBurger');
const menuOverlay = document.getElementById('menuOverlay');
const navbarMenu  = document.getElementById('navbarMenu');

// toggleMenu
export const toggleMenu = () => {
  menuBurger.classList.toggle('active');
  menuOverlay.classList.toggle('active');
  navbarMenu.classList.toggle('active');

  document.querySelector('body').classList.toggle('overflow-hidden');
}

// closeMenu
export const closeMenu = () => {
  menuBurger.classList.remove('active');
  menuOverlay.classList.remove('active');
  navbarMenu.classList.remove('active');

  document.querySelector('body').classList.remove('overflow-hidden');
}