/* eslint-disable indent */
import "@theme/front/init.scss";
import "bootstrap/js/dist/dropdown";
import "bootstrap/js/dist/collapse";
import "./lazysizes";
import "nette.ajax.js/nette.ajax";
import Nette from "@/front/netteForms";
import naja from "naja";
import "lightbox2/dist/css/lightbox.css";
// eslint-disable-next-line no-unused-vars
import lightbox from "lightbox2/dist/js/lightbox";

// seznam map
const $map = $("#map");
let mapInitialized = false;
window.addEventListener("scroll",() => {
    if (window.scrollY > window.innerHeight && !mapInitialized) {
        $map.attr("src", $map.data("src"));
        mapInitialized = true;
    }
});

document.addEventListener("DOMContentLoaded", () => {
    naja.initialize();
    Nette.initOnLoad();
    window.Nette = Nette;

    const $nav = document.querySelector("#navbar");
	const $scrollTopBtn = document.querySelector("#scrollTopBtn");
	const $navbarCollapse = document.querySelector("#navbarToggler");
    const $navbarLinks = document.querySelectorAll("#navbarToggler .nav-link");

//close nav
    $navbarLinks.forEach(item => {
        item.addEventListener("click", function() {
            $navbarCollapse.classList.remove("show");
        });
    });

    var prevScrollpos = window.scrollY;
    window.onscroll = function() {
        if (window.innerWidth > 1100) {
            var currentScrollPos = window.scrollY;
            if (prevScrollpos > currentScrollPos) {
                $nav.style.top = "0";
            } else {
                if (!$navbarCollapse.classList.contains("show") && window.scrollY > 50) {
                    $nav.style.top = "-130px";
                }
            }
        }
        prevScrollpos = currentScrollPos;

        if (currentScrollPos > window.innerHeight) {
            $scrollTopBtn.style.display = "block";
        } else {
            $scrollTopBtn.style.display = "none";
        }

    };

    const $footer = document.querySelector("footer");
    const $body = document.querySelector("body");
    window.addEventListener("load", function() {
        if ($body.offsetHeight < screen.height - 100) {
            $footer.style.position = "absolute";
            $footer.style.bottom = "0";
        }

    });

    $(document).ready(function(){

        // smooth scroll
        // Add smooth scrolling to all links
        $(".scroll").on("click", function(event) {

            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Store hash
                var hash = this.hash;

                // Using jQuery's animate() method to add smooth page scroll
                // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                $("html, body").animate({
                    scrollTop: $(hash).offset().top
                }, 800, function(){

                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.hash = hash;
                });
            } // End if
        });
    });
});
