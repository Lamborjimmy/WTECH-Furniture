document.addEventListener("DOMContentLoaded", function () {
  const thumbnails = document.querySelectorAll(".thumbnail");
  const modalImage = document.getElementById("modalImage");

  thumbnails.forEach((thumbnail) => {
    thumbnail.addEventListener("click", function () {
      const largeSrc = this.getAttribute("data-large");
      modalImage.src = largeSrc;
    });
  });
});
