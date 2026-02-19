/**
 * This script initializes a lightbox gallery for any anchor with data-lightbox as an attribute.
 * @Author Garet C. Green
 */
(function () {
  if (SimpleLightbox === undefined) {
    console.warn("Tried to initialize page lightbox feature but SimpleLightbox is not yet loaded. Did you forget to include the lightbox source code?");
  } else {
    // Fetch all anchor elements that have data-lightbox on them
    var lightboxAnchors = document.querySelectorAll("a[data-lightbox]");

    // Gather all anchors categorized by their gallery name from the data-lightbox attribute
    var initializedGalleriesByName = {};
    for (var _i = 0, _Array$from = Array.from(lightboxAnchors); _i < _Array$from.length; _i++) {
      var anchor = _Array$from[_i];
      var galleryName = anchor.getAttribute("data-lightbox");
      if (!(galleryName in initializedGalleriesByName)) {
        initializedGalleriesByName[galleryName] = [];
      }
      initializedGalleriesByName[galleryName].push(anchor);
    }

    // Load SimpleLightbox for each gallery collection
    for (var _galleryName in initializedGalleriesByName) {
      new SimpleLightbox(initializedGalleriesByName[_galleryName], {
        uniqueImages: false
      });
    }
  }
})();