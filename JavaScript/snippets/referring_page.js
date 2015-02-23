/*
 * This script makes sure that your visitor can only reach a given 
 * page from the page that you specify. Paste the following before 
 * the ending head tag on the page:
 */

var allowedreferrer = "http://www.yoursite.com/pagename.html"; 

if (document.referrer.indexOf(allowedreferrer) == -1) {
  alert ("You can only access this page from " + allowedreferrer);
  window.location = allowedreferrer;
}

