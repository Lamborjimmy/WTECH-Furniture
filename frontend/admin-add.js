const imageIds = [
  { input: "mainImageInput", preview: "mainImagePreview" },
  { input: "detailImageInput1", preview: "detailImagePreview1" },
  { input: "detailImageInput2", preview: "detailImagePreview2" },
  { input: "detailImageInput3", preview: "detailImagePreview3" },
];

imageIds.forEach((imageId) => {
  document.getElementById(imageId.input).addEventListener("change", (event) => {
    const files = event.target.files;

    const previewContainer = document.getElementById(imageId.preview);
    previewContainer.innerHTML = "";

    if (files.length === 0) return;

    const reader = new FileReader();
    reader.onload = (e) => {
      const container = document.createElement("div");
      container.classList.add(
        "admin-image-container",
        "border",
        "border-secondary",
        "mt-3",
      );

      const deleteButton = document.createElement("button");
      deleteButton.innerHTML = "<i class='bi bi-trash'></i>";
      deleteButton.classList.add(
        "btn",
        "btn-sm",
        "btn-danger",
        "admin-delete-button",
      );

      deleteButton.onclick = () => {
        container.remove();
        event.target.value = "";
      };

      const img = document.createElement("img");
      img.classList.add("img-fluid");
      img.src = e.target.result;

      container.appendChild(deleteButton);
      container.appendChild(img);
      previewContainer.appendChild(container);
    };

    reader.readAsDataURL(files[0]);
  });
});
