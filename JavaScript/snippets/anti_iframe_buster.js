    // iframes are a necessary evil for many of us. Putting a third party site 
    // in an iframe is always a risk because they can break out of that iframe 
    // and redirect the visitor. This code acts as a defense and prevents them 
    // from being able to bust out of the iframe.
    
    var prevent_bust = 0  
    window.onbeforeunload = function() { prevent_bust++ }  
    setInterval(function() {  
      if (prevent_bust > 0) {  
        prevent_bust -= 2  
        window.top.location = 'http://www.website.com' 
      }  
    }, 1) 
