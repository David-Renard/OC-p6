let seeMedia = document.getElementById("seeMedia");
let media = document.getElementById("media");
function displayButton(breakpoint) {
    if (breakpoint.matches) { // If media query matches
        // Do both in case user navigates between both viewports
        seeMedia.classList.add("d-block");
        seeMedia.classList.remove("d-none");
        media.classList.add("collapse");

        // Collapse
        seeMedia.addEventListener("click", function () {
            Object.assign(seeMedia, {
                "data-bs-toggle": "collapse",
                "data-bs-target": "#media",
                "aria-controls": "media",
                "aria-expanded": "false",
                "aria-label": "collapse",
            })
            seeMedia.classList.add("d-none");
            media.classList.remove("collapse");
        });
    } else {
        seeMedia.classList.add("d-none");
        seeMedia.classList.remove("d-block");
        media.classList.remove("collapse");
    }
}

// Create a MediaQueryList object
const breakpoint = window.matchMedia("(max-width: 768px)")



// Call listener function at run time
displayButton(breakpoint);

// Attach listener function on state changes
breakpoint.addEventListener("change", function() {
    displayButton(breakpoint);
});