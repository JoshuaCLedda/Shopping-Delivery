document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.sidebar-dropdown').forEach(function(link) {
      link.addEventListener('click', function(event) {
        // Prevent default link behavior
        event.preventDefault();

        // Find the submenu that is a sibling and a direct child of the same parent
        const submenu = this.nextElementSibling;

        // Check if the submenu is of class 'nav-content'
        if (submenu && submenu.classList.contains('nav-content')) {
          // Toggle the 'show' class to open or close the submenu
          submenu.classList.toggle('show');

          // Toggle the rotation of the arrow icon
          const icon = this.querySelector('.nav-small-cap-icon');
          if (icon) {
            icon.classList.toggle('rotate');
          }
        }
      });
    });
  });