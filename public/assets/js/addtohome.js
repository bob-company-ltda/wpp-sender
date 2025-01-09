self.addEventListener('beforeinstallprompt', (event) => {
    // Prevent the default installation prompt
    event.preventDefault();
    
    // Store the event for later use
    const deferredPrompt = event;
  
    // Show the custom installation button
    const installButton = document.getElementById('installButton');
    installButton.style.display = 'block';
  
    // Handle the custom installation button click
    installButton.addEventListener('click', () => {
      // Show the installation prompt
      deferredPrompt.prompt();
      
      // Wait for the user to respond to the prompt
      deferredPrompt.userChoice
        .then((choiceResult) => {
          if (choiceResult.outcome === 'accepted') {
            console.log('User accepted the installation');
          } else {
            console.log('User dismissed the installation');
          }
  
          // Reset the deferred prompt
          deferredPrompt = null;
  
          // Hide the custom installation button
          installButton.style.display = 'none';
        });
    });
  });
  