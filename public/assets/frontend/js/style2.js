document.addEventListener('DOMContentLoaded', function() {
        // Get all buttons, tab list items, and content elements
        var buttons = document.querySelectorAll('.btn');
        var tabs = document.querySelectorAll('.tab');
        var contents = document.querySelectorAll('.tab-pane');
        var blk = document.querySelectorAll('.tab-pane-ar');

        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Remove active class from all buttons, tabs, and contents
                buttons.forEach(function(btn) { btn.classList.remove('active'); });
                tabs.forEach(function(tab) { tab.classList.remove('active'); });
                contents.forEach(function(content) { content.classList.remove('active'); });
                blk.forEach(function(content) { content.classList.remove('active'); });

                // Add active class to the clicked button, its parent tab, and corresponding content
                button.classList.add('active');
                button.parentElement.classList.add('active');
                var target = document.querySelector(button.getAttribute('data-target'));
                var ar = document.querySelector('.tab-pane-ar');
                if (target) {
                    target.classList.add('active','in');
                    
                }
                
            });
        });
    });
    
    
    
  document.addEventListener('DOMContentLoaded', () => {
  const slider = document.querySelector('.slide-track');
  
  // Ensure the element exists
  if (slider) {
    const slides = Array.from(slider.children);

    // Clone each slide and append to the slider
    slides.forEach(slide => {
      const clone = slide.cloneNode(true);
      slider.appendChild(clone);
    });
  } else {
    console.log('Slider element not found');
  }
});



document.addEventListener('DOMContentLoaded', function() {
  var toggleButton = document.getElementById('toggleButton');
  var wacloudMenu = document.getElementById('showc');
  var wacloudbtn = document.getElementById('showb');
  
  toggleButton.addEventListener('click', function() {
    wacloudMenu.classList.toggle('showOptionBs');
    wacloudbtn.classList.toggle('showOptionBs');
  });
});