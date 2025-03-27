document.addEventListener("DOMContentLoaded", function () {
  const thumbnails = document.querySelectorAll(".thumbnail");
  const mainImage = document.getElementById("mainImage");

  thumbnails.forEach((thumbnail) => {
    thumbnail.addEventListener("click", function () {
      const newSrc = this.getAttribute("data-large");
      mainImage.src = newSrc;
    });
  });
});
